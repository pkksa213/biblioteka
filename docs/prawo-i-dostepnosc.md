# Prawo i dostępność — analiza robocza dla biblioteki

Ten dokument pomaga przygotować stronę publicznej biblioteki. **Nie jest poradą prawną, audytem ani zapewnieniem zgodności.** Dokumenty instytucji zatwierdza biblioteka z IOD i osobą odpowiedzialną za dostępność. Nie wpisujemy deklaracji „strona zgodna z WCAG” bez badania.

## 1. Źródła i granice sprawdzenia

Próby odczytania `bibliotekadzikowiec.pl` oraz portalu gov.pl zostały zablokowane przez proxy środowiska (403 przy zestawianiu połączenia). Także po informacji użytkownika o zmianie ustawień bieżące środowisko nadal zgłaszało poprzednią politykę. **Nie przeprowadzono analizy aktualnej treści strony w Dzikowcu ani nie zweryfikowano na żywo aktualnego brzmienia przepisów.** Poniżej jest analiza wstępna oraz lista oficjalnych źródeł do sprawdzenia po uzyskaniu dostępu.

- [Ustawa o dostępności cyfrowej z 4 kwietnia 2019 r.](https://eli.gov.pl/eli/DU/2019/848/ogl).
- [Ustawa o zapewnianiu dostępności osobom ze szczególnymi potrzebami](https://eli.gov.pl/eli/DU/2019/1696/ogl).
- [Ministerstwo Cyfryzacji — dostępność cyfrowa, wzór i warunki techniczne deklaracji](https://www.gov.pl/web/dostepnosc-cyfrowa).
- [RODO — oficjalny tekst unijny](https://eur-lex.europa.eu/eli/reg/2016/679/oj?locale=pl) i [Urząd Ochrony Danych Osobowych](https://uodo.gov.pl/).
- [Prawo komunikacji elektronicznej](https://eli.gov.pl/eli/DU/2024/1221/ogl) — w szczególności przepisy dotyczące zapisu i dostępu do informacji w urządzeniu użytkownika.
- [Ustawa o dostępie do informacji publicznej](https://eli.gov.pl/eli/DU/2001/1198/ogl).
- [Ustawa o prawie autorskim i prawach pokrewnych](https://eli.gov.pl/eli/DU/1994/83/ogl), w szczególności art. 81 o rozpowszechnianiu wizerunku.

Linki ELI prowadzą do opublikowanych aktów; przed wdrożeniem trzeba sprawdzić wersję obowiązującą, nowelizacje i aktualne wytyczne. Nie traktować tych linków jako dowodu, że wszystkie szczegóły zostały zweryfikowane na dzień publikacji strony.

## 2. Co bierzemy ze wzorca w Dzikowcu

Na podstawie **opisu przekazanego przez użytkownika**, a nie bieżącego audytu serwisu:

| Element | Decyzja w projekcie Raniżowa |
| --- | --- |
| Menu O bibliotece / Aktualności / Dla czytelników | Rozbudowane, rozwijane grupy z czytelnymi nazwami; dodatkowo Filie, Regionalia, Galeria i Kontakt |
| Godziny i kontakt | Jeszcze wyraźniej: trzy duże bloki na początku strony, przed artykułami |
| Wpisy i nowości | Osobna lista artykułów, kategorie i pełne strony przykładowych wpisów |
| Link do BIP | Wskazany przez użytkownika adres; zakres informacji o bibliotece wymaga sprawdzenia |
| Deklaracja dostępności | Stały link w stopce każdej strony, własna deklaracja dla Raniżowa po badaniu |
| Dokumenty i RODO | Własne dane, procesy i kontakty. Bez kopiowania dokumentów innej instytucji |
| Narzędzia A+ i kontrast | Dodatkowa pomoc; nie zastępują dostępności strony, treści, PDF-ów i formularzy |

Nie kopiujemy ich tekstów, logo, zdjęć, danych kontaktowych, dat audytów ani opisów budynków. Obecność deklaracji na cudzej stronie nie dowodzi zgodności tej strony ani nie nadaje jej treści do użycia w Raniżowie.

## 3. Deklaracja dostępności — co jest potrzebne

Gminna biblioteka publiczna powinna potraktować dostępność cyfrową jako obowiązek podmiotu publicznego, a nie opcjonalną funkcję motywu. Podstawą oceny są właściwe wymagania ustawy i jej załącznika, praktycznie wiązane z WCAG 2.1 na poziomie AA w ustawowym zakresie. Wdrożenie należy sprawdzić według aktualnych wytycznych; stosowanie nowszych dobrych praktyk nie zastępuje sprawdzenia obowiązków ustawowych.

Deklaracja powinna zawierać co najmniej:

1. Nazwę podmiotu i adres strony, której dotyczy.
2. Rzeczywistą datę publikacji strony oraz ostatniej istotnej aktualizacji.
3. Stan zgodności wynikający z badania — bez automatycznego zaznaczenia „zgodna”.
4. Konkretną listę problemów, niezgodności i prawidłowo uzasadnionych wyłączeń, jeśli występują. Sam brak budżetu nie jest uniwersalnym uzasadnieniem wyłączenia.
5. Datę i metodę sporządzenia, podstawę oceny, dane o przeglądzie.
6. Kontakt do osoby lub komórki obsługującej zgłoszenia oraz sposób zgłoszenia problemu i żądania dostępu alternatywnego.
7. Procedurę żądań i skarg, z właściwymi danymi adresata.
8. Rzetelny opis dostępności architektonicznej biblioteki głównej i obu filii oraz informacji komunikacyjnych wymaganych przez aktualny wzór.

Deklarację należy przeglądać i aktualizować **co roku do 31 marca**, a także po zmianach wpływających na dostępność. Nie zmieniamy daty mechanicznie bez faktycznego przeglądu. Wskazane terminy i wszystkie elementy należy potwierdzić w aktualnym brzmieniu przepisów przed uruchomieniem publicznego serwisu.

**Żądania dostępności:** co do zasady bez zbędnej zwłoki, nie później niż w ciągu 7 dni; gdy to niemożliwe, informacja o przyczynie i nowym terminie, nie dłuższym niż 2 miesiące od żądania. Procedura musi obejmować właściwą reakcję na niemożność zapewnienia dostępności, dostęp alternatywny oraz skargę. Biblioteka musi rzeczywiście zapewnić obsługę zgłoszeń — sam akapit na stronie nie wystarcza.

**Wdrożenie techniczne:** końcową deklarację przygotować według aktualnego wzoru i warunków Ministerstwa Cyfryzacji, z wymaganymi identyfikatorami HTML i datami w odpowiednim formacie. Nie odwzorowujemy starszego dokumentu innej biblioteki. Obecna strona `makieta/deklaracja-dostepnosci.html` to celowo oznaczony szkic, bez twierdzenia o spełnieniu końcowej specyfikacji.

## 4. Szkic deklaracji do dalszego uzupełnienia

> **NIE PUBLIKOWAĆ BEZ UZUPEŁNIENIA I BADANIA**
>
> Gminna Biblioteka Publiczna w Raniżowie zobowiązuje się zapewnić dostępność cyfrową swojej strony internetowej zgodnie z właściwymi przepisami o dostępności cyfrowej podmiotów publicznych.
>
> Deklaracja dotyczy strony: **[docelowy adres]**.
>
> Data publikacji: **[rzeczywista data]**. Data ostatniej istotnej aktualizacji: **[rzeczywista data]**.
>
> **Stan dostępności cyfrowej:** [wybrać i uzasadnić na podstawie badania].
>
> **Niedostępne treści:** [lista konkretnych niezgodności, wyłączeń i uzasadnień albo potwierdzona informacja o ich braku].
>
> **Przygotowanie deklaracji:** [data, metoda, wykonawca / samoocena, zakres badania]. **Ostatni przegląd:** [data i zakres].
>
> **Kontakt:** [osoba / komórka]. Kontakt ogólny biblioteki: **17 22 77 432**, **gbp@ranizow.pl**; potwierdzić, że te kanały przyjmują także zgłoszenia dostępności.
>
> Zgłoszenie powinno określać stronę lub treść, opis problemu, sposób kontaktu oraz ewentualnie preferowany sposób dostępu alternatywnego.
>
> **Żądania i skargi:** [kompletna procedura zgodna z aktualnymi przepisami, terminy, osoba / organ właściwy i adres; opracować z biblioteką].
>
> **Dostępność architektoniczna:** biblioteka główna — ul. Wolska 35, 36-130 Raniżów; filia w Mazurach — Mazury 225, 36-130 Raniżów; filia w Woli Raniżowskiej — Wola Raniżowska 317, 36-125 Wola Raniżowska, w budynku Zespołu Szkół. Dla każdej placówki: [sprawdzony opis dojścia, wejścia, schodów, wnętrza, toalet, parkingu, oznaczeń, psa asystującego i kontaktu w sprawie pomocy].
>
> **Dostępność komunikacyjno-informacyjna:** [rzeczywiste kanały i możliwości wsparcia, PJM jeśli zapewniono; sposób zamówienia pomocy].

Adresy i kontakt pochodzą z informacji użytkownika, nie z wizji lokalnej. Nie zakładamy istnienia windy, podjazdu, pętli indukcyjnej czy tłumacza.

## 5. RODO i polityka prywatności bez formularza

Brak formularza i statystyk ogranicza zakres, ale **nie oznacza braku przetwarzania danych**. Mogą występować logi z IP, konta redakcyjne, korespondencja, kopie zapasowe oraz dane w publikowanych wpisach i zdjęciach.

Z IOD trzeba ustalić:

- Pełną nazwę i adres administratora danych, dane IOD i kanały realizacji praw.
- Rzeczywiste cele oraz odrębne podstawy przetwarzania. Nie wpisywać uniwersalnej zgody na wszystko. Dla organów publicznych nie stosować automatycznie „prawnie uzasadnionego interesu” do wykonywania zadań publicznych.
- Dostawców hostingu, poczty i kopii, umowy powierzenia, podwykonawców, lokalizacje i ewentualne transfery poza EOG wraz z podstawą.
- Konkretne okresy lub zasady retencji logów, korespondencji i kopii. Nie wymyślać „30 dni” bez znajomości konfiguracji i obowiązków archiwalnych.
- Zakres praw wynikający z poszczególnych podstaw oraz informację o skardze do Prezesa UODO.
- Zakres danych personelu i zdjęć, zasady dostępu do panelu oraz procedurę incydentów.

Szkic strony `prywatnosc.html` porządkuje te punkty; nie jest gotową polityką. Wariant testowy na InfiniteFree nie może być automatycznie uznany za odpowiedni hosting publicznej instytucji. Przed produkcją sprawdzić umowę, miejsce przetwarzania, retencję, kopie, zabezpieczenia i wsparcie.

## 6. Cookies — czy musi być baner?

Nie dodajemy banera wyłącznie dlatego, że strona korzysta z WordPressa. Trzeba najpierw sprawdzić działanie hostingu, WordPressa, motywu i wszystkich wtyczek, także u niezalogowanego użytkownika.

- Obecna makieta nie ustawia cookies ani localStorage; ustawienia A+ i kontrastu trwają tylko w otwartej stronie.
- Cookies ściśle niezbędne mogą korzystać z ustawowego wyjątku od uzyskania zgody; nadal potrzebna jest właściwa informacja.
- Dla niekoniecznych technologii wymagających zgody: blokowanie przed zgodą, czytelne cele, równie łatwa odmowa i wycofanie, brak domyślnych zgód.
- Zewnętrzna mapa, film, font, widget społecznościowy lub CAPTCHA mogą przesyłać dane niezależnie od tego, czy wykonawca widzi cookie. Dlatego domyślnie ich nie osadzamy.
- Sam link do zewnętrznego katalogu nie osadza jego zasobów u odwiedzającego. Po kliknięciu obowiązują zasady odrębnego serwisu.

Nie obiecujemy „strona bez cookies” przed zbadaniem **rzeczywistego hostingu**. Obecna makieta i przyszły WordPress to różne środowiska.

## 7. Zdjęcia, wizerunek i dokumenty

- Potrzebne są prawa do fotografii i osobno właściwa podstawa publikacji wizerunku oraz danych. Zgoda fotografa nie zastępuje rozstrzygnięcia praw osoby na zdjęciu.
- Wizerunek dziecka wymaga szczególnej ostrożności. Udział w wydarzeniu nie oznacza automatycznej zgody na publikację zdjęcia. Wyjątków dotyczących szczegółu większej całości nie stosować mechanicznie do portretów dzieci.
- Jeśli podstawą jest zgoda, ma być dobrowolna, konkretna, rozdzielona według kanałów (strona, media społecznościowe itp.) i z informacją o wycofaniu. Nie uzależniać zwykłego udziału w zajęciach od zgody promocyjnej bez właściwej podstawy.
- Wzór zgody musi zawierać instytucję, określone wydarzenie / zakres, cel, kanały, okres / zasady publikacji, sposób wycofania oraz odpowiednią informację RODO. Uzgodnić reprezentację dziecka z IOD; nie zbierać skanów dokumentów „na zapas”.
- Nie kopiować okładek, fotografii i dokumentów z Dzikowca. Sprawdzać licencję konkretnego zasobu i warunki podpisu.
- Regulamin i cennik mają odpowiadać rzeczywistym uchwałom / dokumentom biblioteki. Statut, standardy ochrony małoletnich i inne wymagane dokumenty muszą być zatwierdzone przez właściwy podmiot. Informacje wymagane prawem powinny być dostępne także w czytelnym HTML, a nie wyłącznie jako skan PDF.

## 8. BIP i dokumenty na stronie

Użytkownik wskazał `https://bip.ranizow.pl/6742,6744/6744/`. Trzeba sprawdzić, czy to aktualny, właściwy dział biblioteki i czy zawiera wymagane informacje. Nie tworzymy fałszywego „BIP” przez samo nazwanie zwykłej podstrony tym skrótem. Organizację publikacji ustalamy z biblioteką i gminą.

W stopce każdej podstrony przewidujemy:

**Deklaracja dostępności · Prywatność i cookies · Statut · Regulamin i cennik · Standardy ochrony małoletnich · BIP · Kontakt**.

Nie każdy regulamin jest obowiązkowy dlatego, że istnieje strona internetowa; dokumenty mają odpowiadać faktycznej działalności. Nie dodajemy zbędnego regulaminu sklepu ani zgód marketingowych do informacyjnej strony biblioteki.

## 9. Kontrola przed publikacją

- [ ] Biblioteka zatwierdziła godziny, adresy, kontakt, role personelu i katalogi.
- [ ] Audyt obejmuje menu, klawiaturę, fokus, czytnik ekranu, skalowanie i reflow, kontrast, teksty alternatywne, tabele, dokumenty oraz wszystkie istotne szablony WordPressa.
- [ ] Godziny i ważne informacje są tekstem, nie tylko plakatem.
- [ ] Deklaracja ma faktyczne wyniki badania, daty, wymagane pola i aktualną strukturę techniczną.
- [ ] Sprawdzono rzeczywisty stan dostępności wszystkich trzech lokalizacji; jest osoba odpowiedzialna za zgłoszenia.
- [ ] IOD sprawdził politykę i procesy; hosting, poczta i kopie są zaakceptowane.
- [ ] Sprawdzono zasoby i technologie zewnętrzne na docelowym hostingu, mechanizm zgody jeśli potrzebny oraz konta redakcyjne i 2FA.
- [ ] Publikowane zdjęcia i teksty mają potwierdzone prawa i podstawy publikacji; dokumenty nie są samymi skanami.
- [ ] Działa właściwy BIP, katalogi i kontakt. Nikt nie opublikował placeholderów, demonstracyjnych wydarzeń ani szkicu jako oficjalnej deklaracji.

Podstawowe testy makiety nie spełniają automatycznie tej listy. Badanie docelowego WordPressa jest odrębnym krokiem przed jego publikacją.
