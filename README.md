# Gminna Biblioteka Publiczna w Raniżowie

**Gotowy motyw WordPressa** przenoszący uzgodnioną makietę do działającego panelu redakcyjnego. Godziny, adres i kontakt są na początku; katalogi oraz ogłoszenia są wyróżnione. Bez recenzji, historii, regionaliów, bocznej sekcji Na skróty, oferty usług i cennika.

## Zainstaluj na swoim WordPressie

1. **[Pobierz ZIP motywu](do-pobrania/biblioteka-ranizow-wordpress.zip)** — na stronie pliku kliknij „Download raw file”.
2. WordPress: **Wygląd → Motywy → Dodaj nowy → Wyślij motyw na serwer**. Wybierz ZIP, zainstaluj i włącz.
3. **Wygląd → Uruchom bibliotekę → Przygotuj stronę biblioteki**.
4. W **Dane biblioteki** sprawdź telefony, adresy, godziny i katalogi.

[Dokładna instrukcja instalacji, krok po kroku](docs/wordpress-instalacja-motywu.md).

**Aktualizacja 1.0.1:** wgraj nowy ZIP tą samą drogą i wybierz **Zastąp obecny motyw przesłanym**. Wpisy pozostają w bazie. Kalendarz obsługuje również zapisany początek wydarzenia bez metadanych opcjonalnego końca. Po pełnym zapisie publicznego wpisu motyw zgłasza odświeżenie strony głównej do mechanizmu cache WordPressa. W **Wygląd → Sprawdź wpisy** administrator może sprawdzić pobierane wpisy, terminy i świeży podgląd. Brak zwykłych artykułów na zewnętrznym hostingu nadal wymaga potwierdzenia raportem; nie ustalono, czy wynika z cache lub innej konfiguracji.

## Dodawanie treści

W **Wpisy → Dodaj nowy**, w prawej zakładce **Wpis → Treść biblioteki**, wybierz **Artykuł, Nowa książka, Wydarzenie albo Ważne ogłoszenie**. Opublikowane treści pojawiają się w odpowiednich sekcjach. W klasycznym edytorze panel jest pod treścią. Katalog biblioteczny pozostaje zewnętrznym systemem; motyw nie importuje z niego automatycznie książek.

Redaktor może dodawać treści i zmieniać dane biblioteki. Konfiguracja początkowa jest dostępna dla Administratora. Wykorzystuje istniejące strony i kategorie, zachowuje niepuste treści i nie publikuje samodzielnie dokumentów prawnych. Nie tworzy kont ani przykładowych ogłoszeń. Puste strony informacyjne są przygotowywane po kliknięciu odpowiedniego przycisku.

Galeria korzysta z natywnego bloku WordPressa. Nie ma zależności od page-buildera ani wymaganej wtyczki wydarzeń. Aktualności, książki i wydarzenia pozostają zwykłymi wpisami w bazie po zmianie motywu.

## Pliki i dokumentacja

- `wordpress/biblioteka-ranizow/` — źródła motywu.
- `do-pobrania/biblioteka-ranizow-wordpress.zip` — paczka do instalacji przez panel.
- `makieta/` i `do-pobrania/biblioteka-na-hosting.zip` — zachowana wcześniejsza makieta HTML.
- [Podgląd działającego WordPressa](docs/wordpress-strona.png), [telefon](docs/wordpress-telefon.png), [strefa czytelnika](docs/wordpress-strefa.png).
- [Panel pól wpisu](docs/wordpress-pola-wpisu.png).
- [Testy i uruchomienie lokalne](docs/wordpress-testy.md).
- [Dane biblioteki przekazane do projektu](docs/dane-biblioteki.md).
- [Prawo i dostępność — analiza robocza](docs/prawo-i-dostepnosc.md).
- [Wynik prób odczytania dokumentów i katalogów](docs/sprawdzenie-dokumentow.md).

## Weryfikacja

Motyw uruchomiono na WordPressie 7.1.3 z PHP 8.3.36 i MariaDB 11.4. Sprawdzono konfigurację, zachowanie treści, uprawnienia, formularz danych, natywny edytor, publikowanie książki i wydarzenia, daty i wygasanie ogłoszeń, galerię oraz responsywność dziewięciu typów stron z tekstem 100% i 200%. Aktualne uruchomienie zakończyło 58 sprawdzeń integracyjnych, 145 przeglądarkowych i 15 sprawdzeń publikacji przyciskami edytora. WordPress rozpakował ZIP i rozpoznał poprawny motyw 1.0.1. Dokumentacja zawiera odtwarzalne polecenia.

Paczka motywu jest przygotowana i sprawdzona lokalnie. Nie zainstalowano jej zdalnie na koncie hostingowym użytkownika. W WordPressie użytkownika potrzebne jest wgranie, aktywacja oraz uruchomienie konfiguracji.

## Przed uruchomieniem publicznej strony instytucji

Dane kontaktowe pochodzą od użytkownika. Biblioteka musi potwierdzić ich aktualność, zatwierdzić dokumenty i prawa do treści oraz przeprowadzić ocenę dostępności. Motyw nie jest certyfikatem WCAG. Dokumenty prawne są początkowo szkicami; dopiero ich publikacja dodaje odnośniki w strefie dokumentów i stopce.

Nie odczytano na żywo źródeł urzędowych i Dzikowca: proxy środowiska blokowało te połączenia. Nie skopiowano cudzych dokumentów, tekstów ani zdjęć. Podglądy lokalnego WordPressa zawierają jawnie nazwane dane testowe, których nie ma w paczce instalacyjnej.
