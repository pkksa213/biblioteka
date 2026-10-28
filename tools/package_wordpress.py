#!/usr/bin/env python3
"""Buduje instalacyjny ZIP motywu; nie zawiera WordPressa, bazy ani danych logowania."""
from pathlib import Path
from zipfile import ZipFile, ZipInfo, ZIP_DEFLATED

ROOT = Path(__file__).resolve().parents[1]
SOURCE = ROOT / 'wordpress/biblioteka-ranizow'
OUTPUT = ROOT / 'do-pobrania/biblioteka-ranizow-wordpress.zip'
required = ['style.css', 'index.php', 'functions.php', 'header.php', 'footer.php', 'front-page.php', 'assets/site.css', 'assets/site.js', 'assets/block-editor.js', 'LICENSE.txt']
for name in required:
    if not (SOURCE / name).is_file(): raise SystemExit('Brak wymaganego pliku: ' + name)
files = sorted(p for p in SOURCE.rglob('*') if p.is_file())
for path in files:
    if path.is_symlink() or path.name in ('.env', 'wp-config.php') or path.name.startswith('.'):
        raise SystemExit('Nieoczekiwany plik w katalogu motywu: ' + str(path.relative_to(SOURCE)))
OUTPUT.parent.mkdir(exist_ok=True)
with ZipFile(OUTPUT, 'w', ZIP_DEFLATED, compresslevel=9) as archive:
    for path in files:
        info = ZipInfo('biblioteka-ranizow/' + path.relative_to(SOURCE).as_posix())
        info.compress_type = ZIP_DEFLATED
        info.external_attr = 0o100644 << 16
        archive.writestr(info, path.read_bytes())
with ZipFile(OUTPUT) as archive:
    if archive.testzip() is not None: raise SystemExit('Uszkodzona paczka ZIP.')
    for path in files:
        if archive.read('biblioteka-ranizow/' + path.relative_to(SOURCE).as_posix()) != path.read_bytes():
            raise SystemExit('Niezgodna zawartość paczki.')
print('Motyw WordPress: ' + str(OUTPUT.relative_to(ROOT)) + '; ' + str(len(files)) + ' plików; ' + str(OUTPUT.stat().st_size) + ' bajtów.')
