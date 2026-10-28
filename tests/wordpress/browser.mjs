import fs from 'node:fs';
import { createRequire } from 'node:module';
import { execFileSync } from 'node:child_process';
const require = createRequire(import.meta.url);
const { chromium } = require(process.env.PLAYWRIGHT_MODULE || '/opt/codex/cua_node/lib/node_modules/playwright');
const secretFile = fs.readFileSync('/workspace/.biblioteka-runtime/wp.env', 'utf8');
const password = secretFile.match(/^BR_TEST_ADMIN_PASSWORD=(.+)$/m)?.[1];
if (!password) throw new Error('Brak lokalnego kontekstu testowego.');
const commandEnv = { ...process.env };
for (const key of ['DOCKER_HOST', 'DOCKER_CONTEXT', 'DOCKER_TLS', 'DOCKER_TLS_VERIFY', 'DOCKER_CERT_PATH']) delete commandEnv[key];
const manifest = JSON.parse(execFileSync('docker', ['--host=unix:///var/run/docker.sock', 'exec', 'biblioteka-wp-test', 'cat', '/tmp/br-test-manifest.json'], { env: commandEnv, encoding: 'utf8' }));
const base = 'http://127.0.0.1:8081';
let assertions = 0;
function check(value, message) { if (!value) throw new Error(message); assertions++; }
function trackPost(id) {
  const code = 'require "/var/www/html/wp-load.php"; $ids=get_option("br_test_owned_ids",array());$ids[]=(int)$argv[1];update_option("br_test_owned_ids",array_values(array_unique($ids)));';
  execFileSync('docker', ['--host=unix:///var/run/docker.sock','exec','biblioteka-wp-test','php','-r',code,String(id)], { env: commandEnv, stdio: 'pipe' });
}
async function openFields(page) {
  if (!await page.locator('#br-kind').isVisible()) await page.getByRole('button', { name: 'Treść biblioteki', exact: true }).click();
  await page.locator('#br-kind').waitFor({ state: 'visible', timeout: 10000 });
}
async function login(page, user) {
  await page.goto(base + '/wp-login.php');
  await page.locator('#user_login').fill(user);
  await page.locator('#user_pass').fill(password);
  await Promise.all([page.waitForURL(/\/wp-admin\//), page.locator('#wp-submit').click()]);
}

const browser = await chromium.launch({ executablePath: '/usr/bin/chromium', args: ['--no-sandbox', '--disable-dev-shm-usage'] });
try {
  const guest = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
  const page = await guest.newPage();
  const errors = [], remote = [];
  page.on('pageerror', error => errors.push(error.message));
  page.on('request', request => { if (!request.url().startsWith(base) && !request.url().startsWith('data:') && !request.url().startsWith('blob:')) remote.push(request.url()); });
  const routes = ['/', '/?page_id=' + manifest.pages.reader, '/?page_id=' + manifest.pages.contact, '/?page_id=' + manifest.pages.branches, '/?page_id=' + manifest.pages.gallery, '/?p=' + manifest.article, '/?p=' + manifest.book, '/?p=' + manifest.event, '/?page_id=' + manifest.pages.posts];
  for (const route of routes) {
    const response = await page.goto(base + route);
    check(response.status() === 200, 'Błąd HTTP: ' + route);
    check(await page.locator('h1').count() === 1, 'Liczba H1: ' + route);
    for (const width of [320, 375, 768, 1024, 1440]) {
      await page.setViewportSize({ width, height: 900 });
      for (const scale of ['1', '2']) {
        await page.evaluate(value => document.documentElement.style.setProperty('--font-scale', value), scale);
        const dimensions = await page.evaluate(() => [document.documentElement.scrollWidth, innerWidth]);
        check(dimensions[0] <= dimensions[1], 'Przewijanie poziome: ' + route + ' / ' + width + ' / ' + scale + ' / ' + dimensions);
      }
    }
    await page.evaluate(() => document.documentElement.style.setProperty('--font-scale', '1'));
  }
  await page.goto(base + '/');
  check((await page.locator('#nowosci').innerText()).includes('Testowa książka'), 'Brak książki na stronie głównej.');
  check(!(await page.locator('#nowosci').innerText()).includes('TAJNY_SZKIC'), 'Szkic na stronie publicznej.');
  check(!(await page.locator('#ogloszenia').innerText()).includes('WYGASLE_OGLOSZENIE'), 'Wygasłe ogłoszenie nadal ważne.');
  check((await page.locator('#wydarzenia').innerText()).includes('Testowe spotkanie'), 'Brak wydarzenia.');
  check(!(await page.locator('#wydarzenia').innerText()).includes('MINIONE_WYDARZENIE'), 'Minione wydarzenie w kalendarzu.');
  check(await page.locator('.footer-grid a').filter({ hasText: 'Regulamin' }).count() === 0, 'Szkic regulaminu w publicznej stopce.');
  check(await page.locator('a[href*="recenzje"], a[href*="regionalia"], a[href*="historia"]').count() === 0, 'Usunięte sekcje wróciły do menu.');
  const broken = await page.locator('a[href*="#"]').evaluateAll(links => links.filter(link => link.origin === location.origin && link.pathname === location.pathname && !document.getElementById(decodeURIComponent(link.hash.slice(1)))).map(link => link.href));
  check(broken.length === 0, 'Nieprawidłowe kotwice: ' + broken.join(', '));
  await page.setViewportSize({ width: 1440, height: 1000 });
  await page.keyboard.press('Tab'); check(await page.locator('.skip-link').evaluate(element => element === document.activeElement), 'Pierwszy fokus poza linkiem do treści.');
  await page.keyboard.press('Enter'); check(await page.locator('#tresc').evaluate(element => element === document.activeElement), 'Brak fokusu treści.');
  const menuGroup = page.locator('.nav-group').first();
  await menuGroup.locator('summary').focus(); await page.keyboard.press('Enter'); check(await menuGroup.evaluate(element => element.open), 'Podmenu nie otwiera się klawiaturą.');
  await page.keyboard.press('Escape'); check(!await menuGroup.evaluate(element => element.open), 'Escape nie zamyka podmenu.');
  await page.locator('#font-size').click(); check(await page.locator('#font-size').getAttribute('aria-pressed') === 'true', 'Powiększenie tekstu.'); await page.locator('#font-size').click();
  await page.locator('#contrast').click(); check(await page.evaluate(() => getComputedStyle(document.body).backgroundColor) === 'rgb(0, 0, 0)', 'Kontrast.'); await page.locator('#contrast').click();
  await page.setViewportSize({ width: 375, height: 900 });
  await page.locator('.menu-toggle').click(); await page.locator('.br-catalog-menu a').click();
  check(!await page.locator('#menu').isVisible(), 'Menu mobilne pozostało otwarte.');
  check(await page.locator('#katalogi').evaluate(element => element === document.activeElement), 'Brak fokusu katalogów po kliknięciu w menu.');
  const privacy = await guest.request.get(base + '/?page_id=' + manifest.pages.privacy);
  check(privacy.status() === 404, 'Dokument roboczy jest publiczny.');
  const search = await guest.request.get(base + '/?s=Testowy+artykuł'); check(search.status() === 200 && (await search.text()).includes('Testowy artykuł'), 'Wyszukiwarka nie znajduje artykułu.');
  check(errors.length === 0, 'Błędy JavaScript: ' + errors.join('; '));
  check(remote.length === 0, 'Zewnętrzne żądania strony: ' + remote.join('; '));

  const adminContext = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
  const admin = await adminContext.newPage();
  await login(admin, 'biblioteka_test_admin');
  await admin.goto(base + '/wp-admin/themes.php?page=br-start');
  await Promise.all([admin.waitForURL(/prepared=1/), admin.getByRole('button', { name: 'Przygotuj stronę biblioteki', exact: true }).click()]);
  check(await admin.getByText('Strona przygotowana.', { exact: false }).count() > 0, 'Przycisk konfiguracji nie zadziałał.');
  await admin.goto(base + '/wp-admin/themes.php?page=br-posts');
  const diagnostic = await admin.locator('#br-home-report').inputValue();
  check(diagnostic.includes('Kalendarz — aktualnie pobierane ID: ' + manifest.event), 'Raport nie używa faktycznego zapytania strony głównej.');
  check(diagnostic.includes('Testowe spotkanie') && diagnostic.includes('Początek:'), 'Raport nie wyświetla zapisanych terminów.');
  const previewLink = await admin.getByRole('link', { name: 'Otwórz świeży podgląd strony głównej', exact: true }).getAttribute('href');
  const preview = await admin.goto(previewLink);
  check(preview.status() === 200 && (await admin.locator('body').innerText()).includes('Podgląd administratora — motyw 1.0.1'), 'Podgląd nie uruchamia obecnej wersji motywu.');
  check((await preview.headerValue('cache-control')).includes('no-cache') && (await preview.headerValue('x-robots-tag')).includes('noindex'), 'Podgląd diagnostyczny może być zapisany w cache lub indeksie.');
  const guestPreview = await guest.request.get(previewLink);
  check(guestPreview.status() === 403, 'Niezalogowany czytelnik ma dostęp do podglądu diagnostycznego.');

  const editorContext = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
  const editor = await editorContext.newPage();
  await login(editor, 'biblioteka_test_editor');
  await editor.goto(base + '/wp-admin/admin.php?page=br-library');
  await editor.locator('#br-phone').fill('17 98 76 543');
  await Promise.all([editor.waitForURL(/settings-updated=true/), editor.getByRole('button', { name: 'Zapisz dane biblioteki', exact: true }).click()]);
  await page.goto(base + '/');
  check((await page.locator('#kontakt').innerText()).includes('17 98 76 543'), 'Redaktor nie zapisał kontaktu.');
  check((await page.locator('footer').innerText()).includes('17 98 76 543'), 'Stopka nie odczytała zapisu Redaktora.');
  await editor.locator('#br-phone').fill('17 22 77 432');
  await Promise.all([editor.waitForURL(/settings-updated=true/), editor.getByRole('button', { name: 'Zapisz dane biblioteki', exact: true }).click()]);
  const denied = await editorContext.request.post(base + '/wp-admin/admin-post.php', { form: { action: 'br_prepare', _wpnonce: 'invalid' } });
  check(denied.status() === 403, 'Redaktor może wywołać konfigurację administratora.');
  const subscriberContext = await browser.newContext(); const subscriber = await subscriberContext.newPage();
  await login(subscriber, 'biblioteka_test_subscriber');
  const forbiddenSettings = await subscriberContext.request.get(base + '/wp-admin/admin.php?page=br-library');
  check(forbiddenSettings.status() === 403, 'Czytelnik ma dostęp do danych biblioteki.');
  const forbiddenReport = await subscriberContext.request.get(base + '/wp-admin/themes.php?page=br-posts');
  check(forbiddenReport.status() === 403 && !(await forbiddenReport.text()).includes('Testowe spotkanie'), 'Czytelnik ma dostęp do raportu wpisów.');

  await editor.goto(base + '/wp-admin/post-new.php');
  await editor.waitForFunction(() => window.wp?.data?.select('core/editor'));
  // Natywny magazyn edytora ustawia treść; zapis i metadane przechodzą przez rzeczywisty edytor.
  await editor.evaluate(() => window.wp.data.dispatch('core/editor').editPost({ title: 'Książka dodana przez panel', content: '<!-- wp:paragraph --><p>Wpis utworzony przez edytor WordPressa.</p><!-- /wp:paragraph -->' }));
  const welcome = editor.locator('.components-modal__header button[aria-label="Close dialog"], .components-modal__header button[aria-label="Close"]');
  if (await welcome.count()) await welcome.first().click();
  await openFields(editor);
  await editor.locator('#br-kind').selectOption('book');
  await editor.locator('#br_book_author').fill('Autor z panelu');
  await editor.locator('#br_book_catalog').fill('https://xn--szukamksiki-4kb16m.pl/SkNewWeb/bstart/272');
  await editor.locator('.br-native-panel').screenshot({ path: 'docs/wordpress-pola-wpisu.png' });
  await editor.evaluate(() => window.wp.data.dispatch('core/editor').editPost({ status: 'publish' }));
  await editor.evaluate(() => window.wp.data.dispatch('core/editor').savePost());
  await editor.waitForFunction(() => !window.wp.data.select('core/editor').isSavingPost());
  const newPostId = await editor.evaluate(() => window.wp.data.select('core/editor').getCurrentPostId());
  check(Number.isInteger(newPostId) && newPostId > 0, 'Edytor nie utworzył wpisu.');
  trackPost(newPostId);
  await page.goto(base + '/');
  check((await page.locator('#nowosci').innerText()).includes('Książka dodana przez panel'), 'Książka z edytora nie pojawia się w nowościach.');
  check((await page.locator('#nowosci').innerText()).includes('Autor z panelu'), 'Edytor nie zapisał autora książki.');

  const invalidEvent = await editor.evaluate(async () => {
    try { await window.wp.apiFetch({ path: '/wp/v2/posts', method: 'POST', data: { title: 'Niepoprawny termin testowy', status: 'publish', meta: { _br_kind: 'event', _br_event_start: 0, _br_event_until: 0 } } }); return null; }
    catch (error) { return { code: error.code, status: error.data?.status }; }
  });
  check(invalidEvent?.code === 'br_event_date' && invalidEvent.status === 400, 'Edytor pozwala opublikować wydarzenie bez terminu.');
  await editor.goto(base + '/wp-admin/post-new.php');
  await editor.waitForFunction(() => window.wp?.data?.select('core/editor'));
  await editor.evaluate(() => window.wp.data.dispatch('core/editor').editPost({ title: 'Spotkanie dodane w panelu', content: '<!-- wp:paragraph --><p>Opis wydarzenia z edytora.</p><!-- /wp:paragraph -->' }));
  await openFields(editor);
  await editor.locator('#br-kind').selectOption('event');
  const localDate = await editor.evaluate(() => window.wp.date.date('Y-m-d\\TH:i', Date.now() + 5 * 86400000));
  await editor.locator('#br_event_start').fill(localDate);
  await editor.locator('#br_event_place').fill('Miejsce z panelu');
  const dateRoundtrip = await editor.evaluate(() => {
    const timestamp = window.wp.data.select('core/editor').getEditedPostAttribute('meta')._br_event_start;
    return window.wp.date.date('Y-m-d\\TH:i', timestamp * 1000);
  });
  check(dateRoundtrip === localDate, 'Nieprawidłowe przeliczenie czasu wydarzenia w edytorze.');
  await editor.evaluate(() => window.wp.data.dispatch('core/editor').editPost({ status: 'publish' }));
  await editor.evaluate(() => window.wp.data.dispatch('core/editor').savePost());
  await editor.waitForFunction(() => !window.wp.data.select('core/editor').isSavingPost());
  trackPost(await editor.evaluate(() => window.wp.data.select('core/editor').getCurrentPostId()));
  await page.goto(base + '/');
  check((await page.locator('#wydarzenia').innerText()).includes('Spotkanie dodane w panelu'), 'Wydarzenie dodane w panelu nie pojawiło się w kalendarzu.');

  await page.setViewportSize({ width: 1440, height: 1000 }); await page.goto(base + '/'); await page.screenshot({ path: 'docs/wordpress-strona.png', fullPage: true });
  await page.setViewportSize({ width: 375, height: 900 }); await page.screenshot({ path: 'docs/wordpress-telefon.png', fullPage: true });
  await page.setViewportSize({ width: 1440, height: 1000 }); await page.goto(base + '/?page_id=' + manifest.pages.reader); await page.screenshot({ path: 'docs/wordpress-strefa.png', fullPage: true });
  await page.setViewportSize({ width: 1200, height: 900 }); await page.goto(base + '/'); await page.screenshot({ path: 'wordpress/biblioteka-ranizow/screenshot.png' });
  console.log('Zaliczone sprawdzenia przeglądarkowe: ' + assertions);
} finally { await browser.close(); }
