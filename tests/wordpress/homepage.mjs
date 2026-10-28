// Publikacja przyciskami edytora: wpisy widoczne w archiwum muszą trafić też na start.
import fs from 'node:fs';
import { createRequire } from 'node:module';
import { execFileSync } from 'node:child_process';
const require = createRequire(import.meta.url);
const { chromium } = require(process.env.PLAYWRIGHT_MODULE || '/opt/codex/cua_node/lib/node_modules/playwright');
const base = 'http://127.0.0.1:8081';
const password = fs.readFileSync('/workspace/.biblioteka-runtime/wp.env', 'utf8').match(/^BR_TEST_ADMIN_PASSWORD=(.+)$/m)?.[1];
if (!password) throw new Error('Brak izolowanego środowiska testowego.');
const commandEnv = { ...process.env };
for (const key of ['DOCKER_HOST', 'DOCKER_CONTEXT', 'DOCKER_TLS', 'DOCKER_TLS_VERIFY', 'DOCKER_CERT_PATH']) delete commandEnv[key];
const manifest = JSON.parse(execFileSync('docker', ['--host=unix:///var/run/docker.sock', 'exec', 'biblioteka-wp-test', 'cat', '/tmp/br-test-manifest.json'], { env: commandEnv, encoding: 'utf8' }));
const owned = [];
let checks = 0;
function check(condition, message) { if (!condition) throw new Error(message); checks++; }
const browser = await chromium.launch({ executablePath: '/usr/bin/chromium', args: ['--no-sandbox', '--disable-dev-shm-usage'] });
try {
  const visitor = await browser.newPage();
  // Pierwsza wizyta odbywa się przed dodaniem wpisów, tak jak na stronie użytkownika.
  await visitor.goto(base + '/');
  const editor = await browser.newPage();
  await editor.goto(base + '/wp-login.php');
  await editor.locator('#user_login').fill('biblioteka_test_editor');
  await editor.locator('#user_pass').fill(password);
  await Promise.all([editor.waitForURL(/\/wp-admin\//), editor.locator('#wp-submit').click()]);
  for (const [kind, title, section] of [
    ['article', 'REGRESJA: artykuł opublikowany przyciskiem', '#aktualnosci'],
    ['event', 'REGRESJA: wydarzenie opublikowane przyciskiem', '#wydarzenia'],
    ['book', 'REGRESJA: książka opublikowana przyciskiem', '#nowosci'],
  ]) {
    await editor.goto(base + '/wp-admin/post-new.php');
    await editor.waitForFunction(() => window.wp?.data?.select('core/editor')?.getCurrentPostId());
    owned.push(await editor.evaluate(() => window.wp.data.select('core/editor').getCurrentPostId()));
    const welcome = editor.locator('.components-modal__header button[aria-label="Close dialog"], .components-modal__header button[aria-label="Close"]');
    if (await welcome.count()) await welcome.first().click();
    await editor.evaluate(title => window.wp.data.dispatch('core/editor').editPost({ title, content: '<!-- wp:paragraph --><p>Sprawdzenie publikowania na stronie głównej.</p><!-- /wp:paragraph -->' }), title);
    if (!await editor.locator('#br-kind').isVisible()) await editor.getByRole('button', { name: 'Treść biblioteki', exact: true }).click();
    await editor.locator('#br-kind').selectOption(kind);
    if (kind === 'event') {
      const start = await editor.evaluate(() => window.wp.date.date('Y-m-d\\TH:i', Date.now() + 86400000));
      await editor.locator('#br_event_start').fill(start);
      await editor.locator('#br_event_place').fill('Lokalne miejsce testowe');
    }
    if (kind === 'book') await editor.locator('#br_book_author').fill('Autor testowy');
    // Bez ustawiania statusu i savePost z poziomu JS: oba etapy publikacji w interfejsie.
    await editor.locator('.editor-post-publish-panel__toggle').click();
    await editor.locator('button.editor-post-publish-button').click();
    await editor.waitForFunction(() => window.wp.data.select('core/editor').isCurrentPostPublished() && !window.wp.data.select('core/editor').isSavingPost());
    const state = await editor.evaluate(() => ({ status: window.wp.data.select('core/editor').getCurrentPost().status, meta: window.wp.data.select('core/editor').getCurrentPost().meta }));
    check(state.status === 'publish', 'Przycisk nie opublikował wpisu: ' + kind);
    if (kind !== 'article') check(state.meta._br_kind === kind, 'Nie zapisano rodzaju: ' + kind);
    if (kind === 'event') check(state.meta._br_event_until >= state.meta._br_event_start, 'Nie zapisano końca wydarzenia.');
    const id = owned.at(-1);
    const detail = await visitor.goto(base + '/?p=' + id);
    check(detail.status() === 200 && (await visitor.locator('main').innerText()).includes(title), 'Brak wpisu na podstronie: ' + kind);
    await visitor.goto(base + '/?page_id=' + manifest.pages.posts);
    check((await visitor.locator('.archive-grid').innerText()).includes(title), 'Brak wpisu na liście: ' + kind);
    await visitor.goto(base + '/');
    check((await visitor.locator(section).innerText()).includes(title), 'Wpis jest na podstronie, ale nie na głównej: ' + kind);
  }
  console.log('Sprawdzenia rzeczywistej publikacji i strony głównej: ' + checks);
} finally {
  await browser.close();
  const cleanup = 'if(!getenv("BR_TEST_ADMIN_PASSWORD")){exit(1);}require "/var/www/html/wp-load.php";if(wp_get_environment_type()!=="local"){exit(1);}foreach(array_slice($argv,1) as $id){$post=get_post((int)$id);if($post && strpos($post->post_title,"REGRESJA:")===0){wp_delete_post($post->ID,true);}}';
  execFileSync('docker', ['--host=unix:///var/run/docker.sock', 'exec', 'biblioteka-wp-test', 'php', '-r', cleanup, ...owned.map(String)], { env: commandEnv, stdio: 'pipe' });
}
