<?php
if (!defined('ABSPATH')) { exit; }

function br_defaults() {
    return array(
        'street' => 'ul. Wolska 35', 'postal' => '36-130', 'phone' => '17 22 77 432', 'email' => 'gbp@ranizow.pl',
        'manager' => 'Anna Jaszcz', 'hours_note' => 'Godziny w dni świąteczne potwierdź telefonicznie.',
        'hours' => array('mon' => '9:00–17:00', 'tue' => '8:00–16:00', 'wed' => '8:00–16:00', 'thu' => '8:00–16:00', 'fri' => '8:00–16:00', 'sat' => '', 'sun' => ''),
        'catalog_all' => 'https://xn--szukamksiki-4kb16m.pl/SkNewWeb/astart/231',
        'catalog_main' => 'https://xn--szukamksiki-4kb16m.pl/SkNewWeb/bstart/272',
        'catalog_mazury' => 'https://xn--szukamksiki-4kb16m.pl/SkNewWeb/bstart/273', 'catalog_wola' => '',
        'bip' => 'https://bip.ranizow.pl/6742,6744/6744/',
        'wola_address' => 'Wola Raniżowska 317, 36-125 Wola Raniżowska', 'wola_note' => 'W budynku Zespołu Szkół w Woli Raniżowskiej.', 'wola_person' => 'Beata Chudzik', 'wola_phone' => '', 'wola_email' => '',
        'wola_hours' => array('mon' => '11:00–16:00', 'tue' => '8:00–16:00', 'wed' => '11:00–16:00', 'thu' => '11:00–16:00', 'fri' => '11:00–16:00', 'sat' => '', 'sun' => ''),
        'mazury_address' => 'Mazury 225, 36-130 Raniżów', 'mazury_person' => 'Teresa Kwiecień', 'mazury_phone' => '', 'mazury_email' => '',
        'mazury_hours' => array('mon' => '9:00–17:00', 'tue' => '', 'wed' => '11:00–19:00', 'thu' => '', 'fri' => '9:00–17:00', 'sat' => '', 'sun' => ''),
    );
}
function br_days() { return array('mon' => 'Poniedziałek', 'tue' => 'Wtorek', 'wed' => 'Środa', 'thu' => 'Czwartek', 'fri' => 'Piątek', 'sat' => 'Sobota', 'sun' => 'Niedziela'); }
function br_data() {
    $defaults = br_defaults();
    $saved = get_option('br_library_data', array());
    $data = array_merge($defaults, is_array($saved) ? array_intersect_key($saved, $defaults) : array());
    foreach (array('hours', 'wola_hours', 'mazury_hours') as $key) { $data[$key] = array_merge($defaults[$key], is_array($data[$key]) ? $data[$key] : array()); }
    return $data;
}
function br_hours_table($hours, $caption) {
    echo '<table><caption class="sr-only">' . esc_html($caption) . '</caption><thead class="sr-only"><tr><th scope="col">Dzień</th><th scope="col">Godziny</th></tr></thead><tbody>';
    foreach (br_days() as $key => $day) {
        if (!empty($hours[$key])) { echo '<tr><th scope="row">' . esc_html($day) . '</th><td>' . esc_html($hours[$key]) . '</td></tr>'; }
    }
    echo '</tbody></table>';
}
function br_category_definitions() {
    return array('events' => array('name' => 'Wydarzenia', 'slug' => 'wydarzenia'), 'adults' => array('name' => 'Nowości książkowe dla dorosłych', 'slug' => 'nowosci-ksiazkowe-dla-doroslych'), 'children' => array('name' => 'Dla dzieci i młodzieży', 'slug' => 'dla-dzieci-i-mlodziezy'), 'notices' => array('name' => 'Ogłoszenia', 'slug' => 'ogloszenia'));
}
function br_menu_definition() {
    $reader = br_page_url('reader', 'dla-czytelnikow');
    return array(
        array('title' => 'Start', 'url' => home_url('/')),
        array('title' => 'O bibliotece', 'children' => array(array('title' => 'O nas', 'url' => br_page_url('about', 'o-bibliotece')), array('title' => 'Struktura i pracownicy', 'url' => br_reader_anchor('pracownicy')), array('title' => 'Dokumenty instytucji', 'url' => br_reader_anchor('dokumenty')))),
        array('title' => 'Artykuły i aktualności', 'children' => array(array('title' => 'Wszystkie artykuły', 'url' => br_blog_url()), array('title' => 'Wydarzenia', 'url' => br_category_url('events')), array('title' => 'Nowości dla dorosłych', 'url' => br_category_url('adults')), array('title' => 'Dla dzieci i młodzieży', 'url' => br_category_url('children')), array('title' => 'Ogłoszenia', 'url' => br_category_url('notices')))),
        array('title' => 'Dla czytelników', 'children' => array(array('title' => 'Strefa czytelnika', 'url' => $reader), array('title' => 'Godziny otwarcia', 'url' => br_anchor('godziny')), array('title' => 'Jak się zapisać', 'url' => br_reader_anchor('zapisy')), array('title' => 'Regulamin biblioteki', 'url' => br_document_url('rules')), array('title' => 'Kalendarz wydarzeń', 'url' => br_anchor('wydarzenia')), array('title' => 'Dokumenty i dostępność', 'url' => br_reader_anchor('dokumenty')))),
        array('title' => 'Filie', 'children' => array(array('title' => 'Wola Raniżowska', 'url' => br_anchor('filia-wola')), array('title' => 'Mazury', 'url' => br_anchor('filia-mazury')))),
        array('title' => 'Galeria', 'url' => br_page_url('gallery', 'galeria')),
        array('title' => 'Kontakt i adres', 'url' => br_anchor('kontakt')),
        array('title' => 'Katalog książek', 'url' => br_anchor('katalogi'), 'catalog' => true),
    );
}
