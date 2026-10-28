# Dokumenty i katalogi — wynik sprawdzenia

## Co sprawdzono

Przy tej poprawce ponowiono zwykłe odczyty HTTPS przez skonfigurowane proxy:

| Źródło | Cel | Wynik |
| --- | --- | --- |
| `bip.ranizow.pl/6742,6744/6744/` | Odszukanie statutu, regulaminu i standardów biblioteki | Proxy odrzuciło zestawienie połączenia: CONNECT 403; treści nie odczytano |
| `biblioteka.kolbuszowa.pl` | Sprawdzenie danych i dokumentów biblioteki | CONNECT 403; treści nie odczytano |
| `www.gov.pl/web/dostepnosc-cyfrowa` | Sprawdzenie aktualnych wytycznych deklaracji | CONNECT 403; treści nie odczytano |
| Katalog gminy w `szukamksiążki.pl` | Sprawdzenie działania adresu katalogu | CONNECT 403; działania serwisu nie potwierdzono |

Uruchomione środowisko nadal zgłaszało ograniczoną politykę sieciową ze starą listą domen. Wcześniejszy zapis nowszych ustawień nie jest potwierdzeniem ich zastosowania. Błędy proxy **nie dowodzą niedziałania serwisów u czytelnika**. Nie korzystano z zewnętrznych pośredników w celu obchodzenia blokady.

## Co dodano na stronie

- Strefę dokumentów z odnośnikami do regulaminu, BIP, szkiców deklaracji i prywatności oraz kontaktu w sprawie standardów ochrony małoletnich.
- Osobną stronę regulaminu, która informuje o oczekiwaniu na zatwierdzony dokument i podaje kontakt do biblioteki. Nie zawiera zmyślonych zasad wypożyczeń.
- Statusy „szkic” i „do uzupełnienia” pozostają widoczne. Nie opublikowano deklaracji o zbadanej zgodności WCAG ani nie podano fikcyjnych wyników oceny.

Nie udało się odszukać i zweryfikować właściwych dokumentów w tej sesji. Do ukończenia tej części potrzebny jest działający dostęp do źródeł albo oficjalne dokumenty od biblioteki. Nie zastąpiono ich regulaminem innej instytucji.

## Adresy katalogów użyte w makiecie

Wszystkie poniższe adresy przekazał użytkownik. Sprawdzono zgodność zapisu hosta IDN: `szukamksiążki.pl` odpowiada `xn--szukamksiki-4kb16m.pl`. Nie jest to test odpowiedzi serwera.

- Cała gmina: `https://xn--szukamksiki-4kb16m.pl/SkNewWeb/astart/231`.
- Raniżów: `https://xn--szukamksiki-4kb16m.pl/SkNewWeb/bstart/272`.
- Mazury: `https://xn--szukamksiki-4kb16m.pl/SkNewWeb/bstart/273`.
- Wola Raniżowska: odnośnik do **katalogu wspólnego gminy**, jawnie tak opisany. Nie zgadywano osobnego identyfikatora placówki.

Nowe wyróżnione karty i przycisk w menu korzystają z tych adresów. Dane o aktualnych zbiorach i wypożyczeniach pozostają w systemie katalogowym, niezależnie od przyszłego WordPressa.
