#!/usr/bin/env python3
"""Izolowany WordPress do sprawdzania motywu. Hasła powstają poza repozytorium."""
from pathlib import Path
import json
import os
import secrets
import subprocess
import sys
import time

ROOT = Path(__file__).resolve().parents[1]
RUNTIME = Path('/workspace/.biblioteka-runtime')
WP = 'biblioteka-wp-test'
DB = 'biblioteka-db-test'
ENV = os.environ.copy()
for selector in ('DOCKER_HOST', 'DOCKER_CONTEXT', 'DOCKER_TLS', 'DOCKER_TLS_VERIFY', 'DOCKER_CERT_PATH'):
    ENV.pop(selector, None)
DOCKER = ['docker', '--host=unix:///var/run/docker.sock']
WP_IMAGE = 'wordpress@sha256:8901759bfbb7ebc567dbc44a244c0d96d720d0a67c17bc4f42ec355ddba2e863'
DB_IMAGE = 'mariadb@sha256:d1891ede5a559f1086f55053bb8cc1569b0298338c4d72d71725a7f74f6ba620'

def run(args, capture=False, check=True):
    return subprocess.run(DOCKER + args, env=ENV, text=True, check=check, stdout=subprocess.PIPE if capture else None, stderr=subprocess.PIPE if capture else None)

def exists(kind, name):
    return run([kind, 'inspect', name], capture=True, check=False).returncode == 0

def prepare():
    RUNTIME.mkdir(mode=0o700, exist_ok=True)
    if not (RUNTIME / 'db.env').exists():
        db_password = secrets.token_urlsafe(32)
        (RUNTIME / 'db.env').write_text('MARIADB_DATABASE=biblioteka\nMARIADB_USER=biblioteka\nMARIADB_PASSWORD='+db_password+'\nMARIADB_ROOT_PASSWORD='+secrets.token_urlsafe(32)+'\n')
        (RUNTIME / 'wp.env').write_text('WORDPRESS_DB_HOST='+DB+':3306\nWORDPRESS_DB_NAME=biblioteka\nWORDPRESS_DB_USER=biblioteka\nWORDPRESS_DB_PASSWORD='+db_password+'\nBR_TEST_ADMIN_PASSWORD='+secrets.token_urlsafe(32)+'\nWORDPRESS_DEBUG=1\nWORDPRESS_CONFIG_EXTRA=define("WP_ENVIRONMENT_TYPE", "local"); define("WP_HTTP_BLOCK_EXTERNAL", true); define("DISABLE_WP_CRON", true); define("WP_DEBUG_LOG", true); define("WP_DEBUG_DISPLAY", false);\n')
    if not (RUNTIME / 'wp.env').exists():
        raise SystemExit('Brak lokalnego pliku wp.env; nie zastępuję istniejących danych bazy.')
    for name in ('db.env', 'wp.env'):
        (RUNTIME / name).chmod(0o600)
    (RUNTIME / 'ca.ini').write_text('curl.cainfo=/run/system-ca.pem\nopenssl.cafile=/run/system-ca.pem\n')
    (RUNTIME / 'ca.ini').chmod(0o644)
    for path in (ROOT / 'wordpress/biblioteka-ranizow').rglob('*'):
        path.chmod(0o755 if path.is_dir() else 0o644)
    (ROOT / 'wordpress/biblioteka-ranizow').chmod(0o755)

def start():
    prepare()
    run(['info', '--format', '{{.ServerVersion}}'])
    for image in (DB_IMAGE, WP_IMAGE):
        if not exists('image', image): run(['pull', image])
    if not exists('network', 'biblioteka-local'): run(['network', 'create', 'biblioteka-local'])
    for volume in ('biblioteka-test-db', 'biblioteka-test-core'):
        if not exists('volume', volume): run(['volume', 'create', volume])
    definitions = {
        DB: ['--env-file', str(RUNTIME / 'db.env'), '--mount', 'type=volume,src=biblioteka-test-db,dst=/var/lib/mysql', DB_IMAGE],
        WP: ['--publish', '127.0.0.1:8081:80', '--env-file', str(RUNTIME / 'wp.env'), '--mount', 'type=volume,src=biblioteka-test-core,dst=/var/www/html', '--mount', 'type=bind,src='+str(ROOT / 'wordpress/biblioteka-ranizow')+',dst=/var/www/html/wp-content/themes/biblioteka-ranizow,readonly', '--mount', 'type=bind,src='+str(ROOT)+',dst=/project,readonly', '--mount', 'type=bind,src=/etc/ssl/certs/ca-certificates.crt,dst=/run/system-ca.pem,readonly', '--mount', 'type=bind,src='+str(RUNTIME / 'ca.ini')+',dst=/usr/local/etc/php/conf.d/session-ca.ini,readonly', WP_IMAGE],
    }
    for name, args in definitions.items():
        if exists('container', name):
            label = run(['inspect', '--format', '{{index .Config.Labels "biblioteka.project"}}', name], capture=True).stdout.strip()
            if label != str(ROOT): raise SystemExit('Istnieje kontener o tej nazwie należący do innego projektu: '+name)
            run(['start', name])
        else:
            run(['run', '-d', '--name', name, '--label', 'biblioteka.project='+str(ROOT), '--network', 'biblioteka-local'] + args)
    for _ in range(60):
        db = run(['exec', DB, 'healthcheck.sh', '--connect', '--innodb_initialized'], capture=True, check=False)
        core = run(['exec', WP, 'php', '-r', 'exit(file_exists("/var/www/html/wp-load.php")?0:1);'], capture=True, check=False)
        if db.returncode == 0 and core.returncode == 0:
            run(['exec', WP, 'php', '/project/tests/wordpress/bootstrap.php'])
            return
        time.sleep(1)
    raise SystemExit('WordPress lub baza nie osiągnęły gotowości w ciągu 60 sekund.')

if __name__ == '__main__':
    action = sys.argv[1] if len(sys.argv) > 1 else 'start'
    if action == 'start': start()
    elif action == 'test': run(['exec', WP, 'php', '/project/tests/wordpress/integration.php'])
    elif action == 'lint': run(['exec', WP, 'sh', '-c', 'find /var/www/html/wp-content/themes/biblioteka-ranizow -name "*.php" -print0 | xargs -0 -n1 php -l'])
    elif action == 'recreate':
        if exists('container', WP):
            label = run(['inspect', '--format', '{{index .Config.Labels "biblioteka.project"}}', WP], capture=True).stdout.strip()
            if label != str(ROOT): raise SystemExit('Kontener należy do innego projektu.')
            run(['rm', '-f', WP])
        start()
    elif action == 'stop':
        for name in (WP, DB):
            if exists('container', name):
                label = run(['inspect', '--format', '{{index .Config.Labels "biblioteka.project"}}', name], capture=True).stdout.strip()
                if label != str(ROOT): raise SystemExit('Kontener należy do innego projektu.')
                run(['stop', name])
    else: raise SystemExit('Dostępne polecenia: start, lint, test, recreate, stop')
