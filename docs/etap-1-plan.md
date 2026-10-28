# Aktualizacja wdrożenia

Wygląd został przeniesiony do lekkiego motywu `wordpress/biblioteka-ranizow/` ze standardowym edytorem blokowym treści. Wydarzenia, książki i ogłoszenia działają jako zwykłe wpisy z panelem Treść biblioteki; dodatkowa wtyczka kalendarza nie jest wymagana. [Instalacja motywu](wordpress-instalacja-motywu.md). Poniżej zachowano wcześniejszy plan projektowy, którego propozycje narzędzi nie są listą wymaganych zależności obecnego motywu.

# Etap 1 — plan strony biblioteki w Raniżowie

**Status: propozycja do oceny, po poprawce hierarchii informacji.** Nie jest to wdrożony WordPress. Zgodnie z briefem następny etap rozpocznie się po „dalej”. Makieta HTML pokazuje kierunek wizualny; opisy poniżej określają docelowe podstrony i działanie panelu.

## 1. Założenia i dane do potwierdzenia

| Obszar | Przyjęty wariant |
| --- | --- |
| Platforma | WordPress.org, jedna instalacja i jeden hosting dla biblioteki oraz wszystkich filii |
| Charakter projektu | Lekki, własny motyw blokowy, edytor WordPressa; bez Elementora i innych page-builderów |
| Logo | Biblioteka nie ma logo. W makiecie jest autorski znak roboczy do oceny, nie oficjalne logo |
| Dane kontaktowe i godziny | Uzupełnione według wiadomości użytkownika: ul. Wolska 35, 36-130 Raniżów; 17 22 77 432; gbp@ranizow.pl; pon. 9:00–17:00, wt.–pt. 8:00–16:00. Szczegóły i filie: [dane biblioteki](dane-biblioteki.md) |
| Filie | Dwie: w Woli Raniżowskiej oraz w Mazurach. Oficjalne nazwy, adresy, godziny i kontakty: [DO UZUPEŁNIENIA] |
| Katalog i BIP | Katalog na szukamksiążki.pl — link gminny oraz osobne linki Raniżowa i Mazur przekazane przez użytkownika; patrz dane-biblioteki.md. BIP wskazany przez użytkownika: https://bip.ranizow.pl/6742,6744/6744/ — zakres informacji o bibliotece do potwierdzenia |
| Domena i hosting | Na testy InfiniteFree i bezpłatny adres. Docelowa domena i hosting jeszcze niekupione; orientacyjny budżet około 300 zł rocznie |
| Historia i zdjęcia | Brak materiałów historycznych na dziś; użytkownik może przygotować własne zdjęcia. Prawa, zgody i podpisy: [DO UZUPEŁNIENIA] |
| Publikowanie | Głównie kierowniczka; częstotliwość nieustalona. Robocza propozycja: 2 wpisy miesięcznie, do oceny później |
| Formularz | Nie będzie formularza kontaktowego. Kontakt telefoniczny i przez e-mail |
| Statystyki | Zgodnie z decyzją użytkownika bez statystyk i analityki; bez marketingu i zewnętrznych zasobów ładowanych automatycznie |
| Materiały demonstracyjne | Tylko w makiecie, jawnie oznaczone; nie publikować ich jako faktów ani ogłoszeń |

Układ korzysta z ogólnych wzorców stron instytucji: kontakt, aktualności, katalog, informacje publiczne. Nie wykorzystuje tekstów, zdjęć ani grafiki biblioteki w Dzikowcu. Nie przeprowadzono audytu jej aktualnej strony.

## 2. Docelowa mapa serwisu

Menu na komputerze: **Start · Aktualności · Dla czytelników · O bibliotece · Filie · Kontakt**, obok wyróżniony **Katalog online** (linki są już przekazane). Wyszukiwarka dostępna przy menu. Na telefonie: przycisk „Menu” z opisanymi rozwijanymi grupami. Menu główne ma być krótkie; pozostałe działy są w podmenu, odpowiednich sekcjach strony głównej i stopce.

Makieta ma rozwijane menu, skróty do sekcji i osobne strony HTML artykułów oraz dokumentów. Poniższe adresy opisują docelowy WordPress, a nie nazwy plików makiety. Docelowe adresy:

```text
/
├── /aktualnosci/
│   ├── /aktualnosci/wydarzenia/                 kategoria zapowiedzi i relacji
│   ├── /aktualnosci/nowosci-dla-doroslych/
│   ├── /aktualnosci/nowosci-dla-dzieci/
│   ├── /aktualnosci/ogloszenia/
│   └── /aktualnosci/tytul-wpisu/                pojedynczy wpis
├── /wydarzenia/                                lista nadchodzących wydarzeń
│   └── /wydarzenia/nazwa-wydarzenia/            szczegóły wydarzenia
├── /dla-czytelnikow/
│   ├── /dla-czytelnikow/godziny-otwarcia/
│   ├── /dla-czytelnikow/jak-sie-zapisac/
│   ├── /dla-czytelnikow/regulamin/
│   ├── /dla-czytelnikow/dzieci-i-mlodziez/       jeśli jest odrębna oferta
│   └── /dla-czytelnikow/seniorzy/               jeśli jest odrębna oferta
├── /o-bibliotece/
│   ├── /o-bibliotece/struktura-i-pracownicy/
│   ├── /o-bibliotece/statut/
│   └── /o-bibliotece/projekty-i-dofinansowania/
├── /filie/                                    wszystkie filie jako bloki
├── /galeria/                                  albumy z podpisami
│   └── /galeria/nazwa-albumu/
├── /kontakt/
├── /deklaracja-dostepnosci/
├── /polityka-prywatnosci/                      obejmuje informację o cookies
├── /ochrona-dzieci/                            zatwierdzone standardy i dokumenty
├── /regulamin-serwisu/
└── /mapa-strony/                               mapa HTML dla użytkowników
```

**Linki zewnętrzne:** Katalog online działa w serwisie szukamksiążki.pl; adresy przekazał użytkownik i dodano je do makiety. Katalog oraz BIP opisujemy nazwą i informacją o przejściu do zewnętrznego serwisu. Bez wymuszania nowej karty. Nie tworzymy własnej kopii BIP. Kategoria „Wydarzenia” zawiera wpisy redakcyjne, a moduł wydarzeń daty, lokalizacje i archiwum; między nimi umieszczamy linki, zamiast powielać cały opis.

**Co to jest BIP?** Biuletyn Informacji Publicznej to urzędowy serwis udostępniający informacje publiczne, np. statut, strukturę, dane kierownictwa i zasady działania instytucji. Nie jest katalogiem książek ani działem aktualności. Z biblioteką lub gminą należy ustalić, gdzie publikowane są jej wymagane informacje i jaki adres podlinkować. Sam odnośnik do strony gminy nie potwierdza spełnienia obowiązków biblioteki.

Użytkownik wskazał [ten adres w domenie BIP Raniżowa](https://bip.ranizow.pl/6742,6744/6744/). Dodano go do makiety. Odczyt treści w środowisku roboczym został zablokowany odpowiedzią 403 przy zestawianiu połączenia przez proxy, więc nie potwierdzono zawartości ani tego, czy jest to docelowy dział biblioteki. Nie jest to stwierdzenie, że sama strona nie działa u użytkownika.

**Stopka:** adres, telefon, e-mail i godziny jako tekst; skróty do filii; Katalog; BIP; Statut; Regulamin; Ochrona dzieci; Deklaracja dostępności; Prywatność i cookies; Mapa strony. Pozycje niewypełnione pozostają w szkicu, a nie w publicznej nawigacji. Służbowe dane pracowników publikujemy w zatwierdzonym zakresie.

## 3. Układ strony głównej — aktualna poprawka

```text
[Przejdź do treści]
[Nazwa i znak roboczy | narzędzia czytelności]
[Start | O bibliotece | Artykuły | Dla czytelników | Filie | Galeria | Kontakt | KATALOG KSIĄŻEK]

GODZINY OTWARCIA          ADRES                    TELEFON I E-MAIL
WAŻNE OGŁOSZENIA — szeroki, wyróżniony blok
KATALOG GMINY — główne wejście
[Katalog Raniżowa] [Katalog Mazur] [Wola Raniżowska: katalog wspólny]
ARTYKUŁY I AKTUALNOŚCI — bez bocznej sekcji Na skróty
NOWOŚCI KSIĄŻKOWE
WYDARZENIA I O BIBLIOTECE
GALERIA
FILIE — adresy i godziny
STOPKA — kontakt i dokumenty
```

Godziny, adres i kontakt pozostają na pierwszym planie. Ogłoszenia mają szeroki żółty blok bez fikcyjnego pilnego komunikatu. Katalog jest wyróżniony w menu oraz głównym panelem i kartami placówek. Wola Raniżowska prowadzi jawnie do katalogu gminnego. Nie potwierdzono działania zewnętrznych serwisów, ponieważ proxy odrzuciło odczyt.

Usunięto sekcję Na skróty także ze stopki, historię i regionalia. Strefa czytelnika ma nowy nagłówek, karty pierwszej wizyty, regulaminu i wydarzeń, opis zapisów oraz panel dokumentów. Nie ma oferty usług ani cennika. Pozostały informacje o instytucji, pracownikach i projektach.

Regulamin, statut i standardy wymagają oficjalnych dokumentów biblioteki. W makiecie są wyraźne informacje o ich statusie oraz kontakt, a nie wymyślone zasady. Deklaracja i polityka pozostają oznaczonymi szkicami.

## 4. Układy podstron

### Aktualności — lista

`Okruszki → H1 Aktualności → krótki opis → kategorie i wyszukiwarka → karty wpisów → paginacja → stopka`.

Karta: zdjęcie, kategoria, data, nagłówek będący linkiem, krótki wstęp. Filtrowanie z etykietami i działaniem bez JavaScriptu. Bez nieskończonego przewijania.

### Pojedynczy wpis

`Okruszki → kategoria i data → H1 → wstęp → zdjęcie z podpisem → treść → powiązane wydarzenie → inne wpisy`.

Kolumna tekstu około 65–75 znaków. Hierarchia H2/H3. PDF-y z podanym formatem i rozmiarem, z dostępnym odpowiednikiem HTML dla istotnych informacji. Plakat ma tekstową datę, godzinę, miejsce, warunki udziału i kontakt obok obrazu.

### Standardowa podstrona

`Okruszki → H1 → wprowadzenie → sekcje H2 → aktualizacja / osoba lub komórka do kontaktu → powiązane strony`.

Godziny otwarcia: tabela z nagłówkami kolumn „Dzień” i „Godziny”, filie osobno, wyjątki pod tabelą. Regulamin: zatwierdzony tekst HTML jako podstawowy format, dostępny PDF opcjonalnie. Z aktualnego zakresu usunięto ofertę usług i cennik.

### Wydarzenia

`H1 → lista od najbliższego terminu → filtr miesiąca / grupy → archiwum`.

Szczegóły: nazwa, data, godzina, miejsce, dostępność miejsca, grupa odbiorców, ewentualne zapisy i koszt, opis, plakat. Wszystkie wymagane dane widoczne jako tekst. W Etapie 1 wybieramy listę; siatka kalendarza jest opcjonalna, bo lista jest prostsza na telefonie i w obsłudze klawiaturą.

### Filie

`H1 → krótka lista nazw jako kotwice → blok każdej filii → powrót do początku`.

Blok: nazwa, adres, telefon/e-mail służbowy, godziny, dostępność architektoniczna, własne zdjęcie, link „Wyznacz trasę” i opcjonalna mapa uruchamiana świadomie przez użytkownika. Przy braku filii usuwamy tę pozycję z menu zamiast pozostawiać pustą stronę.

Potwierdzone bloki: **Filia w Woli Raniżowskiej** i **Filia w Mazurach**. Sformułowania są nazwami opisowymi; oficjalne brzmienie do sprawdzenia. Każdy blok ma własne godziny i kontakt, ale wspólny motyw, panel i hosting.

### Galeria

`H1 → lista albumów z datą → album → zdjęcia z podpisami`.

Na początek natywny blok Galeria. Bez lightboxa, jeśli nie został sprawdzony klawiaturą i czytnikiem ekranu. Opisy alternatywne dotyczą treści zdjęcia; dekoracje mają pusty alt. Publikacja dopiero po sprawdzeniu praw i podstawy udostępnienia wizerunku.

### Kontakt

`H1 → adres / telefon / e-mail → godziny → dostępność budynku → link do mapy`.

Bez formularza i osadzonej mapy na start. Kliknięcie adresu e-mail uruchomi aplikację pocztową użytkownika, a numeru telefonu — połączenie na telefonie. Formularz umożliwiałby wysłanie wiadomości bezpośrednio ze strony; użytkownik z niego zrezygnował, więc nie instalujemy wtyczki formularza i nie konfigurujemy wysyłki poczty ze strony. Polityka prywatności nadal opisuje m.in. korespondencję i logi hostingu.

## 5. Paleta i kontrast

Kontrast obliczono wzorem WCAG dla kolorów sRGB. Cel dla zwykłego tekstu: co najmniej **4,5:1**. Dla istotnych obramowań i stanów kontrolek: co najmniej **3:1**. Poniższe wyniki dotyczą dokładnie wskazanych par — nie dowolnych kombinacji palety.

| Zastosowanie | Kolor tekstu / elementu | Tło | Kontrast |
| --- | --- | --- | --- |
| Tekst główny | `#20342B` | krem `#F8F6EF` | 12,23:1 |
| Nagłówki i linki | zieleń `#174C3C` | krem `#F8F6EF` | 9,09:1 |
| Opisy pomocnicze | `#526258` | krem `#F8F6EF` | 5,98:1 |
| Opisy w panelu nowości | `#526258` | szałwia `#E8EEE5` | 5,48:1 |
| Przycisk podstawowy | biały `#FFFFFF` | zieleń `#174C3C` | 9,83:1 |
| Żółty przycisk / etykieta | `#20342B` | żółty `#F2CF66` | 8,75:1 |
| Akcent w zielonej stopce | żółty `#F2CF66` | zieleń `#174C3C` | 6,51:1 |
| Obramowanie kontrolki | `#64766A` | krem `#F8F6EF` | 4,47:1 |

Dodatkowo: biel `#FFFFFF` dla kart, ciemniejsza zieleń `#10382C` dla hover przycisku, `#D8DED4` wyłącznie dla dekoracyjnych separatorów. Żółtego nie używamy jako drobnego tekstu na jasnym tle. Linki w treści podkreślone, a nie wyróżnione samym kolorem. Fokus: zielony obrys na jasnym tle, żółty na zielonym i w trybie wysokiego kontrastu.

Narzędzia A+ i kontrast są dodatkiem. Nie zastępują skalowania przeglądarką ani dostępnej konstrukcji strony. Do weryfikacji wdrożenia: powiększenie 200%, reflow przy szerokości 320 px, klawiatura, czytnik ekranu, formularze i dokumenty. Sama makieta nie stanowi potwierdzenia zgodności WCAG całej przyszłej strony.

## 6. Typografia i elementy graficzne

- **Source Sans 3** — tekst, menu, formularze i panelowe etykiety; wagi 400 i 600; minimum 18 px dla tekstu głównego we wdrożeniu, interlinia około 1,6.
- **Lora** — nagłówki; 400 i 600; bez długich akapitów zapisanych kapitalikami. H1 około 36 px na telefonie i 56–64 px na dużym ekranie.
- Obie rodziny są dostępne w Google Fonts i obsługują polskie znaki. We wdrożeniu: WOFF2 z polskimi znakami, przechowywane lokalnie wraz z licencjami, `font-display: swap`. Bez połączeń do serwerów Google. W Etapie 1 nie pobieramy fontów; makieta używa systemowego bezszeryfowego i Georgii jako przybliżenia.
- Ikony: proste kontury SVG, 1,5–2 px, wspólny styl; zawsze z podpisem albo dostępną nazwą. Elementy dekoracyjne ukryte przed czytnikiem ekranu.
- Logo: lewy górny róg, pełna nazwa obok, w stopce ewentualnie uproszczona wersja. Roboczego znaku nie przedstawiamy jako istniejącej identyfikacji instytucji.
- Grafika makiety jest autorska. Docelowe fotografie: własne lub z udokumentowaną licencją. Unsplash i Pexels — sprawdzić warunki konkretnego wykorzystania; Wikimedia Commons — sprawdzić licencję każdego pliku, podpis autora i ewentualne wymaganie udostępniania na tych samych zasadach. Wolna licencja nie rozstrzyga automatycznie praw do wizerunku.

## 7. WordPress, panel i minimalny zestaw wtyczek

**Motyw:** własny lekki motyw blokowy z `theme.json` i wzorcami „Godziny”, „Filia”, „Kontakt”, „Wyróżniony wpis”. Blokujemy przypadkowe usunięcie istotnych elementów szablonu; redakcja zmienia treści standardowym edytorem. Alternatywa tańsza w utrzymaniu: oficjalny motyw blokowy z własnym stylem i wzorcami. Wybór wersji WordPressa oraz test zgodności wtyczek nastąpi w Etapie 2; żadna wtyczka nie jest teraz zainstalowana.

| Funkcja | Propozycja | Zakres i ograniczenia |
| --- | --- | --- |
| Formularz | **Brak — decyzja użytkownika** | Telefon i e-mail wystarczą. Jeśli potrzeba zmieni się w przyszłości, można rozważyć Contact Form 7 po testach dostępności i doręczania poczty |
| SEO | **The SEO Framework** | Tytuły, opisy i kontrola indeksacji. Jedna wtyczka SEO; bez równoległego Yoasta/Rank Math. Sprawdzić jej mapę XML i nie utrzymywać konkurencyjnych map |
| Wydarzenia | **The Events Calendar — wersja darmowa** | Daty, godziny, miejsca, lista i widok miesiąca. Na start lista. Cykliczność i inne funkcje płatne nie są założeniem projektu. Widoki testujemy pod kątem dostępności |
| Kopie zapasowe | **UpdraftPlus — wersja darmowa** | Baza codziennie, pliki co tydzień i przed aktualizacją; np. 14 kopii bazy i 4 plików, zależnie od miejsca i ustawień darmowej wersji. Kopia poza kontem hostingowym, na zatwierdzonym magazynie. Test odtworzenia. Sprawdzić cron hostingu |
| Logowanie | **Two-Factor** + limity hostingu | 2FA kont administracyjnych i redakcyjnych; kody odzyskiwania poza stroną. Jeśli hosting nie ogranicza prób, rozważyć dodatkowo **Limit Login Attempts Reloaded**; nie instalować drugiego mechanizmu tego samego typu |
| Cache | Najpierw mechanizm hostingu | **LiteSpeed Cache** tylko przy właściwym serwerze lub zgodnej infrastrukturze; inaczej opcjonalnie **WP Super Cache**. Nie uruchamiać kilku cache naraz. Wykluczyć panel i sprawdzić aktualizację treści |
| Cookies | Domyślnie bez wtyczki zgód | Przy braku niekoniecznych technologii nie dodajemy zbędnego banera. Jeśli pojawią się narzędzia wymagające zgody: **Complianz — wersja darmowa**, po ocenie konfiguracji i rzeczywistego blokowania zasobów przed zgodą |
| Galeria | Natywny blok **Galeria** | Bez kolejnej wtyczki i bez automatycznej karuzeli. Albumy jako zwykłe strony lub wpisy z kategorią |
| Dostępność | Semantyka motywu i testy | Nie instalujemy nakładki obiecującej automatyczną zgodność WCAG. Ustawienia czytelności realizujemy w motywie |

Wariant bazowy bez formularza: cztery wtyczki (SEO, wydarzenia, kopie, 2FA). Cache, limit prób i zgody dodajemy tylko wtedy, gdy potrzebne. Przed instalacją sprawdzamy bieżące utrzymanie, licencję, zgodność PHP/WordPressa i zakres wersji darmowej; nazwy są propozycją, nie wynikiem bieżącego audytu bezpieczeństwa.

**Role i pulpit:**

- Administrator — właściciel techniczny, ustawienia, aktualizacje, użytkownicy i kopie.
- Redaktor — kierowniczka; Wpisy, Strony, Media i Wydarzenia oraz profil/wylogowanie. Nie otrzymuje dostępu do instalowania wtyczek i zmian konfiguracji. Ukrywanie menu nie zastępuje kontroli uprawnień.
- Autor filii — na początek zwykłe konto Autora, jeżeli akceptujemy standardowy zakres. WordPress **nie ogranicza domyślnie autora do kategorii filii**. Jeżeli ograniczenie jest wymagane, w Etapie 3 potrzebny jest mały moduł uprawnień z kontrolą po stronie serwera (także REST) i testami. Rozwiązanie prostsze: Współpracownik przygotowuje szkic, Redaktor wybiera kategorię i publikuje. Nie obiecujemy zabezpieczenia przez samo ukrycie kategorii.
- Standardowe wpisy obsługują kategorie, zdjęcie wyróżniające i planowanie publikacji. Jedno źródło godzin otwarcia w WordPressie, wykorzystywane w nagłówku, podstronie i stopce, aby uniknąć rozbieżności.

## 8. SEO, prywatność i utrzymanie — decyzje projektowe

- Czytelne, hierarchiczne adresy jak wyżej; jeden H1; tytuł i opis każdej strony. Pełne propozycje metadanych powstaną w Etapie 4.
- `Library` w danych strukturalnych dopiero z potwierdzonym adresem i godzinami; bez zmyślonych ocen i danych. Każda potwierdzona filia może mieć osobny opis placówki.
- XML i Search Console w Etapie 7. Mapa HTML to osobna pomoc nawigacyjna. Makieta ma `noindex`; środowisko robocze WordPressa także nie powinno być indeksowane.
- Obrazy lokalnie, WebP/AVIF tam, gdzie wspiera je docelowy hosting; odpowiednie wymiary i warianty responsywne. Lazy loading poniżej pierwszego ekranu. HTTPS oraz jedna docelowa wersja domeny.
- Mapa Google domyślnie zastąpiona informacją i przyciskiem. Osadzenie dopiero po odpowiednim świadomym wyborze użytkownika i analizie prywatności; zwykły link do map jest prostszą alternatywą. Sama mapa nie może zastąpić adresu i wskazówek dojazdu w tekście.
- Dokumenty prawne powstaną jako szkice do przeglądu biblioteki/IOD w Etapie 5. To wskazówki projektowe, nie porada prawna ani certyfikat zgodności. Dostępność należy sprawdzić także w PDF-ach, formularzach i treściach redakcyjnych.
- **InfiniteFree wyłącznie do testów**, zgodnie z aktualną decyzją użytkownika. Nie zakładamy dostępności wszystkich funkcji: w Etapie 2 sprawdzimy wymagania PHP/MySQL, HTTPS, zadania okresowe, kopie i ograniczenia planu. Na środowisko próbne nie wgrywamy danych czytelników, prywatnych danych pracowników ani niezatwierdzonych zdjęć dzieci. Bezpłatny adres nie jest wybraną domeną produkcyjną. Przed uruchomieniem publicznej strony instytucji trzeba ocenić m.in. umowę powierzenia, lokalizację przetwarzania, kopie, wsparcie i utrzymanie.
- Nie kupujemy domeny ani hostingu na tym etapie. Budżet docelowy: około **300 zł rocznie** za hosting i domenę — do potwierdzenia z pełną ceną odnowienia i VAT, a nie tylko promocją na pierwszy rok. W Etapie 2 porównamy także SSL, kopie i odtwarzanie, limity zasobów, wsparcie i lokalizację przetwarzania. Nazwy dostawców z briefu nie są rekomendacją opartą na zweryfikowanym cenniku.

## 9. Co ocenić przed „dalej”

1. Spokojny, zielono-żółty styl oraz kolejność sekcji strony głównej.
2. Zakres menu i oferty; uwzględniono już obie potwierdzone filie.
3. Listę wydarzeń i edytor blokowy; brak formularza i analityki jest już ustalony.

Odpowiedzi na pytania z początku rozmowy zastąpią placeholdery. Brak odpowiedzi nie blokuje dalszego planowania na oznaczonych założeniach. Dopiero polecenie **„dalej”** rozpoczyna Etap 2; nie dokonano zakupu, wdrożenia ani publikacji strony.

## Uzupełnienie: analiza prawna i instrukcja

Na wyraźną prośbę użytkownika przygotowano [wstępną analizę prawa i dostępności](prawo-i-dostepnosc.md), szkice podstron deklaracji i prywatności oraz [instrukcję dalszych działań](co-dalej.md). Odczyt Dzikowca i źródeł urzędowych nadal był blokowany przez proxy; nie oznacza to pełnego audytu ani ukończenia całego Etapu 5.

## WordPress i treści

Instrukcja instalacji i wyjaśnienie redakcji: [WordPress — jak zacząć](wordpress-jak-zaczac.md). Nowe książki i artykuły dodaje redakcja; WordPress po wdrożeniu szablonów wyświetla wpisy we właściwych sekcjach. Katalog zewnętrzny nie jest automatycznie synchronizowany. Aktualna paczka nadal jest makietą HTML.
