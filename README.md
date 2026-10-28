# Gminna Biblioteka Publiczna w Raniżowie

**Etap 1 — poprawiona makieta i plan strony WordPress.** To działający przykład HTML, nie gotowy motyw WordPressa ani oficjalny serwis biblioteki.

## Zobacz projekt

- **[Pobierz ZIP do wgrania na hosting](do-pobrania/biblioteka-na-hosting.zip)** — na stronie pliku kliknij „Download raw file”. Rozpakuj i wgraj zawartość do pustego katalogu `htdocs/makieta/` na hostingu testowym. Paczka zawiera wyłącznie 11 plików strony (9 HTML, CSS i JavaScript).
- [Strona główna](makieta/index.html): na początku godziny, adres, telefon i e-mail.
- [Artykuły](makieta/artykuly.html): kategorie i trzy pełne przykładowe wpisy.
- [Podgląd na komputerze](docs/makieta-desktop.png) i [telefonie](docs/makieta-mobile.png).
- [Plan, mapa serwisu, kolory, fonty i wtyczki](docs/etap-1-plan.md).
- [Prawo i dostępność — analiza robocza](docs/prawo-i-dostepnosc.md).
- [Co zrobić dalej — instrukcja dla początkującego](docs/co-dalej.md).
- [Dane biblioteki przekazane do projektu](docs/dane-biblioteki.md).
- [WordPress: instalacja, artykuły i nowe książki](docs/wordpress-jak-zaczac.md).
- [Wynik próby wyszukania dokumentów i sprawdzenia katalogów](docs/sprawdzenie-dokumentow.md).

## Uruchomienie

Nie trzeba instalować zależności. Można otworzyć `makieta/index.html` bezpośrednio w przeglądarce albo uruchomić lokalny serwer z Pythonem 3:

```sh
cd /workspace/biblioteka
python3 -m http.server 8080 --bind 127.0.0.1 --directory makieta
```

Przy samym oglądaniu statycznej makiety nie jest potrzebny PHP, MySQL ani WordPress. Rozbudowane menu, artykuły i ich filtry, A+ i kontrast działają bez bibliotek zewnętrznych. Żaden formularz nie zbiera danych; nie ma analityki, zewnętrznych fontów ani osadzonych map. Przycisk A+ nie zapisuje ustawienia w cookies ani localStorage.

## Co pozostaje do wykonania

Makieta zawiera rzeczywiste dane kontaktowe **przekazane przez użytkownika**, ale ich aktualność przed publikacją musi potwierdzić biblioteka. Treści artykułów, książek i wydarzeń są przykładami. Deklaracja i polityka prywatności są wyraźnie oznaczonymi szkicami.

InfiniteFree jest wybrany do testów. Docelowy hosting i domena pozostają do wyboru, orientacyjny budżet około 300 zł rocznie. Nie skonfigurowano jeszcze hostingu, WordPressa, bazy ani panelu redakcyjnego.

Po akceptacji układu: instalacja testowego WordPressa, wdrożenie motywu i redagowania treści, uzupełnienie dokumentów na podstawie faktycznego stanu, testy oraz publikacja. Zgodnie z briefem kolejne etapy zaczynają się po „dalej”, chyba że użytkownik wyraźnie zleci dany zakres wcześniej, jak analizę prawną w tej poprawce.

## Weryfikacja i źródła

Instrukcję uruchomienia i układ sprawdzamy w lokalnym Chromium, obejmując telefon, komputer, powiększenie tekstu, klawiaturę, przełączniki czytelności, menu, filtry i odnośniki. Nie jest to pełny audyt WCAG ani test przyszłego WordPressa.

W tej sesji odczyt Dzikowca, BIP i gov.pl został zablokowany na połączeniu proxy. Inspiracja strukturą Dzikowca pochodzi z briefu użytkownika, a analiza prawna jest wstępna, ze źródłami do weryfikacji. Nie skopiowano tekstów, zdjęć, ikon ani deklaracji innej biblioteki. Sam zapis domen w projekcie ustawień nie zastosował dostępu do działającego środowiska.

Sprawdzono dziewięć stron w Chromium na szerokościach 320, 375, 768, 1024 i 1440 px z tekstem 100% i 200%: brak przewijania poziomego. Wszystkie lokalne odnośniki i identyfikatory sprawdzono; każda strona odpowiada HTTP 200 i ma jeden H1. Działają menu klawiaturą, Escape, przejście do treści, fokus po przejściu do kontaktu, filtry kategorii i pełne artykuły. Bez błędów JavaScript, cookies, localStorage i żądań do zewnętrznych serwisów podczas sprawdzonego przeglądania. Menu i podmenu działają także bez JavaScriptu.

## Aktualny układ

Godziny, kontakt i adres pozostają pierwsze. Ważne ogłoszenia mają osobny szeroki blok, katalog książek jest wyróżniony w menu i kartach placówek. Usunięto Na skróty, historię i regionalia; strefa czytelnika ma nowy wygląd bez oferty usług i cennika. Nie znaleziono zatwierdzonych dokumentów w tej sesji z powodu blokady źródeł przez proxy; ich status jest jawnie opisany na stronie.
