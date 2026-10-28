# Co zrobić z projektem — prosta instrukcja

## Co jest gotowe teraz

To **makieta HTML**, czyli działający przykład wyglądu strony. Ma menu, godziny, adres i kontakt, artykuły z osobnymi stronami, filie oraz oznaczone szkice dokumentów. Nie jest jeszcze motywem WordPressa. Nie ma panelu dodawania wpisów.

GitHub przechowuje pliki i historię zmian. Hosting udostępnia stronę odwiedzającym. Wysłanie plików do GitHuba nie instaluje WordPressa i nie uruchamia automatycznie strony na InfiniteFree.

## 1. Otwórz projekt z GitHuba

1. Wejdź do repozytorium `pkksa213/biblioteka`, na gałąź wskazaną w podsumowaniu w czacie.
2. Wybierz **Code → Download ZIP** i rozpakuj plik na swoim komputerze.
3. Otwórz folder `makieta`, a następnie plik `index.html` w przeglądarce. Menu, linki do artykułów i podstrony działają także lokalnie. Filtry artykułów używają JavaScriptu; gdy przeglądarka ogranicza działanie przy otwieraniu plików lokalnych, użyj serwera testowego.
4. Dokumenty `.md` w folderze `docs` możesz wygodnie czytać bezpośrednio na GitHubie.

Jeżeli wysłanie do GitHuba okaże się zablokowane, skorzystaj z paczki makiety udostępnionej w czacie. Nie musisz samodzielnie wpisywać komend Git, aby obejrzeć pliki.

## 2. Zobacz makietę na InfiniteFree

1. Rozpakuj paczkę makiety na komputerze.
2. Zaloguj się do swojego panelu InfiniteFree i otwórz menedżer plików albo skonfigurowany przez dostawcę dostęp do plików.
3. Otwórz folder `htdocs` właściwej domeny testowej. **Nie nadpisuj istniejącego WordPressa ani innych plików strony.**
4. Utwórz pusty podfolder o nazwie `makieta`.
5. Wgraj do niego **zawartość** naszego folderu `makieta`: wszystkie pliki `.html`, `styles.css` i `script.js`. `index.html` ma być bezpośrednio w `htdocs/makieta/`.
6. Otwórz swój adres testowy z dopiskiem `/makieta/`. Jeżeli HTTPS jest już poprawnie skonfigurowany, użyj go. Konfigurację certyfikatu wykonuje się w panelu dostawcy; sama zmiana adresu z HTTP na HTTPS nie wydaje certyfikatu.
7. Sprawdź telefon i komputer: menu, godziny, kliknięcie telefonu i e-maila, pełny artykuł, filtry kategorii, filie i katalog.

Nie wgrywaj folderu `.git`, dokumentów z danymi prywatnymi, haseł ani pliku `wp-config.php` do repozytorium. Pliki dokumentacji nie muszą znajdować się na hostingu. Paczka makiety zawiera wyłącznie pliki do oglądania strony.

Wszystkie podstrony mają `noindex`, ale to **nie jest ochrona dostępu**. Jeżeli dostępny jest mechanizm hasła dla katalogu testowego, warto go użyć. Nie umieszczaj w testach danych czytelników ani niezatwierdzonych zdjęć. Baner „makieta” i oznaczenia szkiców muszą pozostać do czasu zatwierdzenia.

## 3. Docelowo przejdziemy do WordPressa

1. Po ocenie poprawionego wyglądu przygotujemy instalację testową WordPressa i wymagania dla wybranego hostingu.
2. Zbudujemy lekki motyw i wzorce na podstawie makiety. Samo wgranie obecnego `index.html` do motywów WordPressa nie zadziała.
3. Utworzymy strony i kategorie. Kierowniczka będzie dodawać artykuły w **Wpisy → Dodaj nowy**, z tytułem, treścią, kategorią, zdjęciem i datą publikacji.
4. Wdrożymy wydarzenia, kopie, role, 2FA i potrzebne wtyczki. Nie dokładamy formularza ani statystyk, zgodnie z decyzją użytkownika.
5. Biblioteka sprawdzi treści, prawa do zdjęć, dokumenty i dane; wykonamy badanie dostępności oraz uzupełnimy deklarację na podstawie wyników.
6. Dopiero po tych krokach przeniesiemy stronę na docelowy hosting i domenę, skonfigurujemy HTTPS, przekierowania i SEO oraz dopuścimy indeksowanie.

InfiniteFree służy tu do prób. Zanim publiczna instytucja użyje danego hostingu produkcyjnie, trzeba sprawdzić jego warunki, powierzenie danych, kopie, lokalizację przetwarzania i wsparcie. Nie zakładamy dostępności wszystkich funkcji bez testów.

## 4. Jak udostępnić internet temu środowisku

W ustawieniach środowiska chmurowego znajdź ustawienia dostępu do internetu. Zapisano propozycję domen potrzebnych do sprawdzenia Dzikowca, BIP i oficjalnych źródeł prawnych. Przejrzyj i zapisz ustawienia. Jeżeli interfejs wymaga publikacji konfiguracji, opublikuj środowisko zgodnie z jego komunikatem. Sam zapis projektu konfiguracji przez agenta nie zastosował tych zmian do działającej sesji.

Nie potrzebuję Twojego hasła do GitHuba ani tokenu w czacie. Dostęp do hostingu jest osobną sprawą i nie wynika z zezwolenia na przeglądanie internetu. Po zastosowaniu zmian agent powinien ponowić zwykły odczyt stron i potwierdzić faktyczny wynik, zamiast zakładać, że dostęp już działa.
