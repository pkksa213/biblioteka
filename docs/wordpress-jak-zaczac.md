# Aktualizacja: gotowy motyw WordPressa

Motyw jest już przygotowany. Jeśli masz zainstalowanego WordPressa, przejdź do [instrukcji instalacji motywu](wordpress-instalacja-motywu.md). Poniższa instrukcja dotyczy głównie instalacji samego WordPressa. Zachowana makieta HTML to osobna paczka.

# WordPress — instalacja i dodawanie treści

## Co mamy teraz, a co daje WordPress

Obecny ZIP to makieta HTML. Pokazuje wygląd i odnośniki, ale nie ma bazy ani panelu redakcyjnego. Zmiana plików HTML wymaga obecnie edycji plików i ponownego wgrania.

WordPress działa na hostingu z PHP i bazą danych. Udostępnia panel logowania, edytor, konta użytkowników i bibliotekę mediów. Motyw określa wygląd; treści zapisuje się w bazie. Po prawidłowym wdrożeniu motywu kierowniczka będzie dodawać treści bez edycji HTML i bez GitHuba.

**Samo zainstalowanie WordPressa nie przenosi wyglądu makiety.** Teraz dostępny jest gotowy motyw w paczce `biblioteka-ranizow-wordpress.zip`; zainstaluj go zgodnie z osobną instrukcją. Paczka HTML pozostaje makietą. Instalację na koncie użytkownika wykonuje użytkownik w panelu administratora.

## Jak zainstalować wersję testową na InfiniteFree

Nazwy elementów panelu mogą zależeć od jego wersji. Poniższa instrukcja opisuje standardową instalację; możliwości aktualnego planu i dostępną wersję PHP/bazy trzeba sprawdzić w panelu. Nie sprawdzono ich przez konto użytkownika.

1. Zaloguj się do swojego konta hostingowego. Wybierz właściwą domenę lub subdomenę testową. Sprawdź, czy działa HTTPS i jakie są wymagania aktualnego WordPressa.
2. Zachowaj makietę w `htdocs/makieta/`. Do prób z WordPressem użyj oddzielnego pustego katalogu, np. `htdocs/wordpress-test/`. Nie nadpisuj istniejących stron ani `wp-config.php`.
3. Jeżeli panel udostępnia instalator WordPressa, wybierz WordPress, odpowiednią domenę i katalog `wordpress-test`. Zwróć uwagę, żeby instalator nie wyczyścił innego katalogu. Jeśli instalatora nie ma, użyj kroków ręcznych poniżej.
4. W instalacji ręcznej: w panelu hostingu utwórz bazę MySQL i zapisz jej nazwę, nazwę użytkownika, hasło oraz **adres serwera bazy podany przez hosting**. Nie zakładaj, że jest to `localhost`. Haseł nie wysyłaj do czatu i nie zapisuj w GitHubie.
5. Pobierz WordPress z [oficjalnej polskiej strony](https://pl.wordpress.org/download/). Rozpakuj na komputerze i prześlij **zawartość** folderu `wordpress` do pustego `htdocs/wordpress-test/`, korzystając z mechanizmu przesyłania plików obsługiwanego przez hosting. Sprawdź limity plików i zasobów planu.
6. Otwórz w przeglądarce swój adres testowy z dopiskiem `/wordpress-test/`. Instalator poprosi o dane bazy, tytuł strony, konto administratora i e-mail. Ustaw silne hasło oraz własną nazwę użytkownika. Dla testów zaznacz ograniczenie indeksowania przez wyszukiwarki; samo `noindex` nie chroni przed dostępem osób postronnych.
7. Po instalacji otwórz adres z dopiskiem `/wordpress-test/wp-admin/` i zaloguj się. W instalacji przez aplikację panelu dane administratora ustawia się w odpowiednim kroku instalatora.
8. W **Ustawienia → Ogólne** wybierz język polski i strefę **Europe/Warsaw**, aby WordPress uwzględniał zmianę czasu. W **Ustawienia → Bezpośrednie odnośniki** wybierz czytelny schemat adresów, np. „Nazwa wpisu”.
9. Usuń przykładowe wpisy, wyłącz komentarze, jeśli nie będą potrzebne, i pozostaw testową stronę bez indeksowania. Utwórz konto Redaktora dla kierowniczki; konta Administratora używaj do obsługi technicznej. Wdróż 2FA i kopie oraz sprawdź odtworzenie.
10. Zainstaluj `biblioteka-ranizow-wordpress.zip`, włącz motyw i wybierz **Wygląd → Uruchom bibliotekę → Przygotuj stronę biblioteki**. Ta czynność ustawia stronę główną i listę wpisów, wykorzystując istniejące strony.

Na docelową domenę przenosimy dopiero sprawdzone wdrożenie. Przed produkcją trzeba ocenić hosting, HTTPS, umowy, kopie i aktualizacje oraz dokumenty i dostępność strony instytucji. Nie zmieniamy adresu działającego WordPressa w ustawieniach bez zaplanowanego przeniesienia plików i bazy.

## Czy WordPress sam dodaje nowe książki?

**Nie pobiera ich automatycznie z katalogu bibliotecznego.** Proponowany prosty sposób redakcji:

1. Kierowniczka otwiera **Wpisy → Dodaj nowy**.
2. W zakładce **Wpis → Treść biblioteki** wybiera **Nowa książka**.
3. Wpisuje autora, opis, opcjonalny link do katalogu i dodaje okładkę jako zdjęcie wyróżniające. Wybiera grupę odbiorców w kategoriach.
4. Kliknięcie **Opublikuj** dodaje książkę do sekcji nowości. Szkic czeka na publikację. Planowanie wymaga działającego harmonogramu WordPressa na hostingu.

Wariant „jedna książka = jeden wpis” jest prosty i pozwala użyć zdjęcia wyróżniającego. Można też publikować artykuły z kilkoma nowościami, jeśli taki układ wybierze redakcja. To decyzja o treści i szablonie podczas wdrożenia.

Automatyczne pobieranie nowości z zewnętrznego katalogu wymaga potwierdzonego API albo innego dozwolonego źródła oraz osobnej integracji. **Nie jest to funkcja dodana do tej makiety ani zapewniona samą instalacją WordPressa.** Nie kopiujemy bazy i okładek przez przypadkowy scraper.

## Artykuły, ogłoszenia, wydarzenia i pozostałe informacje

| Treść | Jak będzie dodawana po wdrożeniu | Co może zrobić strona automatycznie |
| --- | --- | --- |
| Artykuł / relacja | Wpisy → Dodaj nowy; kategoria, zdjęcie i treść | Pokazać najnowsze opublikowane wpisy w ustalonych sekcjach |
| Nowa książka | Wpis z kategorią „Nowości książkowe”, opisem, okładką i linkiem do katalogu | Pokazać ostatnie wpisy książkowe na stronie głównej |
| Ważne ogłoszenie | Wpis z kategorią „Ogłoszenia”; rodzaj Ważne ogłoszenie i opcjonalny termin wygaśnięcia | Wyświetlić aktywne ogłoszenie w żółtym bloku, w czasie aktywności wpisu |
| Wydarzenie | Moduł wydarzeń z datą, godziną, miejscem i opisem | Pokazać nadchodzące wydarzenia i przenieść minione do archiwum |
| Godziny, kontakt, regulamin | Strony / przygotowany wspólny blok danych, dostępne dla Redaktora | Powtarzać te same zatwierdzone dane w nagłówku, treści i stopce |
| Katalog książek i dostępność zbiorów | Dane utrzymuje biblioteka w swoim systemie katalogowym; strona prowadzi do katalogu | Sam link przekierowuje użytkownika; WordPress nie zmienia stanów wypożyczeń |

Informacje o akcjach czy zajęciach można publikować jako artykuły i wydarzenia. Zgodnie z decyzją użytkownika nie będzie odrębnej sekcji „Oferta i usługi” ani cennika. WordPress nie tworzy oferty i treści sam — redakcja je wpisuje, a przygotowane szablony odpowiadają za wyświetlanie.

## Krótka instrukcja dla kierowniczki

Po przygotowaniu motywu i kategorii typowa praca będzie wyglądała tak: zaloguj się → dodaj wpis → wpisz tekst i kategorię → dodaj zdjęcie z opisem alternatywnym → sprawdź podgląd → opublikuj lub zaplanuj. Nie trzeba wtedy wgrywać ZIP-ów na hosting przy każdym artykule. Pełna instrukcja z obrazkami powstanie na działającym panelu, żeby nie pokazywać nieistniejących przycisków.
