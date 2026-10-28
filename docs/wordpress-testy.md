# Sprawdzenie wdrożenia WordPressa

Motyw sprawdzono na lokalnej instalacji **WordPress 7.1.3, PHP 8.3.36 i MariaDB 11.4**, z oficjalnych obrazów przypiętych digestami w `dev/wordpress.py`. Nie logowano się do hostingu użytkownika i nie wykonywano instalacji na jego koncie.

## Zakres

- WordPress rozpoznaje i aktywuje motyw.
- Konfiguracja używa istniejących stron i kategorii, zachowuje niepuste treści oraz dokumenty robocze. Powtórzenie nie dodaje duplikatów ani nie publikuje wycofanej strony.
- Dokumenty prawne pozostają szkicami. Publiczna strona szkicu zwraca 404 dla odwiedzającego; po publikacji dokument jest dostępny w strefie i stopce.
- Artykuły, książki, wydarzenia i ogłoszenia są zwykłymi wpisami. Szkice i zaplanowane wpisy nie trafiają do publicznych sekcji przed publikacją.
- Daty wydarzeń respektują strefę WordPressa; terminy niepoprawne i zakończenie przed rozpoczęciem są odrzucane. Minione wydarzenia i wygasłe ogłoszenia znikają z aktywnych bloków.
- Weryfikowane są uprawnienia, nonce i sanitacja wejścia w klasycznym zapisie, REST oraz ustawieniach biblioteki. Redaktor zapisuje kontakt, nie może uruchomić konfiguracji administratora; Subskrybent nie ma dostępu do formularza danych.
- Zmiana telefonu przez prawdziwy formularz Redaktora jest widoczna równocześnie w kontakcie i stopce.
- Książkę i wydarzenie utworzono w natywnym edytorze blokowym, zapisano przez mechanizm WordPressa i sprawdzono na publicznej stronie.
- Galeria odczytuje zdjęcia z natywnego bloku, respektuje opisy alternatywne.
- Sprawdzono dziewięć typów stron, szerokości 320, 375, 768, 1024 i 1440 px oraz tekst 100% i 200%, link do treści, klawiaturę, Escape, menu mobilne, wyszukiwanie i przełączniki czytelności.
- Podczas sprawdzonego przeglądania niezalogowanej strony motyw nie wykonywał zewnętrznych żądań. Nie dodaje analityki, cookies ani zewnętrznych fontów.

Końcowe uruchomienie zaliczyło **58 sprawdzeń integracyjnych, 145 przeglądarkowych, 15 sprawdzeń rzeczywistej publikacji i pięć scenariuszy API W3TC**. Osobny test `homepage.mjs` publikuje artykuł, wydarzenie i książkę prawdziwymi przyciskami edytora po wcześniejszym odwiedzeniu strony głównej; sprawdza status, metadane, podstronę, archiwum i sekcję na stronie głównej. Test przeglądarkowy weryfikuje także dostępność raportu tylko dla administratora oraz nagłówki bez cache i indeksowania w podglądzie. WordPress rozpakował ZIP i rozpoznał poprawny motyw Biblioteka Raniżów **1.0.2**. Nie są to certyfikat dostępności, audyt całej przyszłej treści ani gwarancja zgodności z każdym hostingiem i zestawem wtyczek.

### Regresja kalendarza i ograniczenia diagnozy

Przed poprawką 1.0.1 test opublikowanego wydarzenia z przyszłym początkiem i końcem `0` kończył się błędem: **„Wydarzenie bez opcjonalnego zakończenia znika z kalendarza”**. To samo dotyczyło brakujących lub pustych metadanych zakończenia. Po poprawce zapytanie wyświetla je do końca dnia rozpoczęcia w strefie WordPressa. Test obejmuje też bieżący dzień, wydarzenie minione i brak początku.

Sprawdzono sygnał odświeżenia strony głównej po publikacji, wycofaniu i trwałym usunięciu wpisu. Zmiana niepublicznego szkicu go nie wywołuje. W 1.0.2 standardowe `clean_post_cache` uzupełniono o publiczne `w3tc_flush_url(home_url('/'))`, jeśli funkcja jest dostępna.

Raport z instalacji użytkownika potwierdził, że publiczne wpisy są pobierane do sekcji, lecz odwiedzający w inkognito widzą starszą kopię; aktywny jest W3 Total Cache. Oficjalny kod W3TC domyślnie ustawia `pgcache.purge.front_page=false`, a przy statycznej stronie głównej `pgcache.purge.home=true` dotyczy osobnej listy wpisów. W3TC nie nasłuchuje `clean_post_cache` używanego przez motyw 1.0.1. To odtworzony problem zgodności z jego polityką cache. Stan konkretnej opcji użytkownika oraz wynik czyszczenia na hostingu wymagają sprawdzenia w jego panelu.

### Integracja z prawdziwym API W3 Total Cache

Użyto kodu W3TC **2.11.0** z [oficjalnego repozytorium BoldGrid, commit d71b0c7](https://github.com/BoldGrid/w3-total-cache/tree/d71b0c7e9ecd143e07d25e667daf64759d453bf4). Przed poprawką `tests/wordpress/w3tc.php publish` kończył się błędem **„W3TC: błędne odświeżanie statycznej strony głównej — publish”**. Po poprawce zaliczono pięć scenariuszy: publikacja, publikacja wydarzenia przez REST, wycofanie, usunięcie i zmiana szkicu. W REST sygnał występuje po zapisaniu rodzaju i pełnego terminu. Szkic nie powoduje sygnału.

Test ładuje rzeczywiste API i komponent strony cache z plików w `/tmp/biblioteka-w3tc-test` kontenera. Ustawienia wtyczki zmienia wyłącznie w pamięci procesu, bez ich zapisu i bez aktywowania wtyczki w testowej witrynie. Weryfikuje jej rzeczywiste powiadomienie `w3tc_flush_url`; nie jest testem odczytu gotowego pliku HTML przez serwer cache ani CDN.

Źródła testowe można odtworzyć poza repozytorium przez pobranie archiwum wskazanego commitu z `https://codeload.github.com/BoldGrid/w3-total-cache/tar.gz/d71b0c7e9ecd143e07d25e667daf64759d453bf4` z zachowaniem TLS, rozpakowanie i `docker cp` do wskazanej ścieżki kontenera. Następnie uruchom we własnym kontenerze `php /project/tests/wordpress/w3tc.php SCENARIUSZ`, osobno dla `publish`, `rest`, `withdraw`, `delete` i `draft`. Skrypt wymaga lokalnego środowiska projektu i wygenerowanych kont testowych. Pliki W3TC nie są dołączane do ZIP-a motywu.

## Powtórzenie w środowisku projektu

```sh
cd /workspace/biblioteka
python3 dev/wordpress.py start
python3 dev/wordpress.py lint
python3 dev/wordpress.py test
node tests/wordpress/browser.mjs
node tests/wordpress/homepage.mjs
python3 tools/package_wordpress.py
```

Wymagane są działający lokalny Docker oraz Python 3; test przeglądarkowy używa dostępnego w tym środowisku Playwrighta i Chromium. Dla innego miejsca instalacji Playwrighta ustaw `PLAYWRIGHT_MODULE`. Projekt korzysta z istniejącego checkoutu, bez tworzenia Git worktree.

Lokalne dane i losowe hasła testowe znajdują się **poza repozytorium**, w `/workspace/.biblioteka-runtime/`, a WordPress i baza w osobnych kontenerach i wolumenach. Przeglądarka testowa nie wypisuje haseł. Testy zarządzają tylko własnymi oznaczonymi rekordami w izolowanej bazie testowej; nie należy kierować ich do witryny produkcyjnej.

Serwer testowy słucha na porcie 8081 tylko na adresie loopback. Jego proces nie jest częścią paczki motywu. Polecenie `python3 dev/wordpress.py stop` zatrzymuje wyłącznie kontenery tego projektu, zachowując dane. `recreate` odtwarza własny kontener aplikacji z zachowaniem wolumenów, np. po zmianie lokalnej konfiguracji środowiska. Nie wyłączano weryfikacji TLS, podpisów ani sum obrazów.

Pierwsze uruchomienia wykryły kolizję nazwy zmiennej testowej z globalną zmienną WordPressa oraz niedostępny zasób edytora w lokalnym bind mount. Poprawiono runner i uprawnienia zasobów. Nowszy edytor ukrywał też klasyczne pola; docelowy panel jest natywnym panelem „Treść biblioteki”, z klasycznym formularzem jako obsługą starszego edytora.
