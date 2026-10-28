<?php
/** Testy wymagają własnego, izolowanego kontenera utworzonego przez dev/wordpress.py. */
if (!getenv('BR_TEST_ADMIN_PASSWORD')) { throw new RuntimeException('Brak lokalnego kontekstu testowego.'); }
$_SERVER['HTTP_HOST'] = '127.0.0.1:8081'; $_SERVER['SERVER_PORT'] = '8081'; $_SERVER['REQUEST_METHOD'] = 'GET';
require '/var/www/html/wp-load.php';
if (wp_get_environment_type() !== 'local' || get_stylesheet() !== 'biblioteka-ranizow') { throw new RuntimeException('Niewłaściwe środowisko testowe.'); }
$checks = 0;
set_exception_handler(function ($error) { fwrite(STDERR, $error->getMessage() . PHP_EOL); exit(1); });
function verify($condition, $message) { global $checks; if (!$condition) { throw new RuntimeException($message); } $checks++; }
function fixture($data) {
    $id = wp_insert_post(array_merge(array('post_type' => 'post', 'post_status' => 'publish', 'post_content' => '<p>Treść testowa — tylko lokalna baza sprawdzająca motyw.</p>'), $data), true);
    if (is_wp_error($id)) { throw new RuntimeException($id->get_error_message()); }
    $owned = get_option('br_test_owned_ids', array()); $owned[] = $id; update_option('br_test_owned_ids', $owned);
    return $id;
}
function save_kind($id, $kind, $fields = array()) {
    $_POST = array_merge(array('br_content_nonce' => wp_create_nonce('br_content'), 'br_kind' => $kind), $fields);
    wp_update_post(array('ID' => $id, 'post_excerpt' => 'Opis testowy wpisu biblioteki.'));
    $_POST = array();
}
function render_part($part) { ob_start(); get_template_part('template-parts/' . $part); return ob_get_clean(); }

$admin = get_user_by('login', 'biblioteka_test_admin'); wp_set_current_user($admin->ID);
foreach (get_option('br_test_owned_ids', array()) as $id) { wp_delete_post((int) $id, true); }
update_option('br_test_owned_ids', array());
$old_menu = (int) get_option('br_test_owned_menu'); if ($old_menu) { wp_delete_nav_menu($old_menu); }
delete_option('br_menu_id'); delete_option('br_pages'); delete_option('br_setup_done');
set_theme_mod('nav_menu_locations', array());
update_option('br_library_data', br_defaults());
verify(!wp_get_theme()->errors(), 'WordPress nie rozpoznaje motywu.');

// Symulacja stron i kategorii już przygotowanych przez użytkownika.
foreach (br_category_definitions() as $definition) { if (!get_term_by('name', $definition['name'], 'category')) { wp_insert_term($definition['name'], 'category', array('slug' => $definition['slug'])); } }
$category_count = wp_count_terms(array('taxonomy' => 'category', 'hide_empty' => false));
$front = fixture(array('post_type' => 'page', 'post_title' => 'Strona główna', 'post_name' => 'strona-glowna', 'post_content' => ''));
$blog = fixture(array('post_type' => 'page', 'post_title' => 'Aktualności', 'post_name' => 'aktualnosci', 'post_content' => ''));
$reader = fixture(array('post_type' => 'page', 'post_status' => 'draft', 'post_title' => 'Dla czytelników', 'post_name' => 'dla-czytelnikow', 'post_content' => ''));
$about = fixture(array('post_type' => 'page', 'post_title' => 'O bibliotece', 'post_name' => 'o-bibliotece', 'post_content' => '<p>Zachowana treść właściciela.</p>'));
$rules = fixture(array('post_type' => 'page', 'post_status' => 'draft', 'post_title' => 'Regulamin', 'post_name' => 'regulamin', 'post_content' => '<p>Własny szkic regulaminu.</p>'));
$gallery_content='<figure><img src="/zdjecie-zastane.jpg" alt="Zastane zdjęcie"></figure>';
$existing_gallery=fixture(array('post_type'=>'page','post_title'=>'Galeria','post_name'=>'galeria','post_content'=>$gallery_content));
update_option('page_on_front', $front); update_option('page_for_posts', $blog);
$fixture_pages = br_prepare_site(); verify(!is_wp_error($fixture_pages), 'Konfiguracja zwróciła błąd.');
update_option('br_test_owned_ids', array_values(array_unique(array_merge(get_option('br_test_owned_ids'), array_values($fixture_pages)))));
update_option('br_test_owned_menu', get_option('br_menu_id'));
verify($fixture_pages['front'] === $front && $fixture_pages['posts'] === $blog && $fixture_pages['reader'] === $reader, 'Powstały duplikaty istniejących stron.');
verify(wp_count_terms(array('taxonomy' => 'category', 'hide_empty' => false)) === $category_count, 'Powstały duplikaty kategorii.');
verify(get_post_status($reader) === 'publish' && get_page_template_slug($reader) === 'template-reader.php', 'Pusta strona czytelnika nie otrzymała szablonu.');
verify(get_post_field('post_content', $about) === '<p>Zachowana treść właściciela.</p>', 'Nadpisana treść użytkownika.');
verify(get_post_field('post_content',$existing_gallery)===$gallery_content,'Nadpisano stronę z samym zdjęciem.');
verify(get_post_status($rules) === 'draft' && get_post_field('post_content', $rules) === '<p>Własny szkic regulaminu.</p>', 'Zmieniono własny dokument prawny.');
foreach (array('rules','accessibility','privacy','statute','children_safety') as $key) { verify(get_post_status($fixture_pages[$key]) === 'draft', 'Dokument został opublikowany automatycznie.'); }
$before_count = count(get_posts(array('post_type' => 'page', 'post_status' => 'any', 'numberposts' => -1)));
$menu_before = count(wp_get_nav_menu_items(get_option('br_menu_id')));
wp_update_post(array('ID' => $fixture_pages['gallery'], 'post_status' => 'draft'));
verify(!is_wp_error(br_prepare_site()), 'Powtórne przygotowanie nie powiodło się.');
verify(get_post_status($fixture_pages['gallery']) === 'draft', 'Powtórne przygotowanie opublikowało wycofaną stronę.');
verify(count(get_posts(array('post_type'=>'page','post_status'=>'any','numberposts'=>-1))) === $before_count, 'Powtórne przygotowanie zduplikowało strony.');
verify(count(wp_get_nav_menu_items(get_option('br_menu_id'))) === $menu_before, 'Powtórne przygotowanie zduplikowało menu.');
verify(get_option('show_on_front') === 'page' && (int) get_option('page_for_posts') === $blog, 'Nie ustawiono strony głównej i wpisów.');

// Dane, URL-e i role — wejścia nie mogą wprowadzać skryptów ani rozszerzać uprawnień.
$sanitized = br_sanitize_data(array('phone' => '<script>alert(1)</script>123', 'catalog_main' => 'javascript:alert(1)', 'email'=>'wrong', 'hours'=>array('mon'=>'<b>10:00–18:00</b>'), 'injected'=>'value'));
verify(strpos($sanitized['phone'], '<') === false && $sanitized['catalog_main'] === '', 'Niesanitowane dane kontaktowe lub URL.');
verify($sanitized['email'] === '' && !isset($sanitized['injected']), 'Nieprawidłowe pola ustawień.');
verify($sanitized['hours']['mon'] === '10:00–18:00' && $sanitized['street'] === br_defaults()['street'], 'Nie zachowano danych pominiętych w zmianie.');
$editor = get_user_by('login', 'biblioteka_test_editor');
if (!$editor) { $id = wp_insert_user(array('user_login'=>'biblioteka_test_editor','user_pass'=>getenv('BR_TEST_ADMIN_PASSWORD'),'user_email'=>'test-editor@example.invalid','role'=>'editor')); $editor=get_user_by('id',$id); }
$subscriber = get_user_by('login', 'biblioteka_test_subscriber');
if (!$subscriber) { $id = wp_insert_user(array('user_login'=>'biblioteka_test_subscriber','user_pass'=>getenv('BR_TEST_ADMIN_PASSWORD'),'user_email'=>'test-subscriber@example.invalid','role'=>'subscriber')); $subscriber=get_user_by('id',$id); }
wp_set_current_user($editor->ID);
verify(current_user_can('edit_pages') && !current_user_can('manage_options'), 'Nieprawidłowe uprawnienia Redaktora.');
verify(is_wp_error(br_prepare_site()), 'Redaktor może uruchomić konfigurację administratora.');
wp_set_current_user($subscriber->ID); verify(!current_user_can('edit_pages'), 'Czytelnik ma dostęp do danych biblioteki.');
wp_set_current_user($admin->ID);

// Rzeczywiste wpisy i normalny mechanizm zapisu metadanych.
$article = fixture(array('post_title'=>'Testowy artykuł biblioteki', 'post_name'=>'testowy-artykul-biblioteki'));
save_kind($article,'article');
$book = fixture(array('post_title'=>'Testowa książka — przykład', 'post_name'=>'testowa-ksiazka'));
save_kind($book,'book',array('br_book_author'=>'Autorka testowa','br_book_catalog'=>br_defaults()['catalog_main']));
verify(get_post_meta($book,'_br_kind',true)==='book' && has_category(br_term_id('adults'),$book), 'Książka nie otrzymała typu lub kategorii.');
verify(strpos(render_part('books'),'Testowa książka')!==false, 'Opublikowana książka nie jest widoczna.');
$draft = fixture(array('post_status'=>'draft','post_title'=>'TAJNY_SZKIC_TESTOWY'));
save_kind($draft,'book',array('br_book_author'=>'Ukryty autor'));
verify(strpos(render_part('books'),'TAJNY_SZKIC_TESTOWY')===false, 'Szkic książki wyciekł na stronę.');
$scheduled = fixture(array('post_status'=>'future','post_title'=>'ZAPLANOWANA_KSIAZKA_TEST','post_date'=>wp_date('Y-m-d H:i:s',time()+7*DAY_IN_SECONDS)));
save_kind($scheduled,'book'); verify(strpos(render_part('books'),'ZAPLANOWANA_KSIAZKA_TEST')===false,'Zaplanowany wpis wyświetla się przed publikacją.');
$_POST=array('br_content_nonce'=>'bad','br_kind'=>'notice');wp_update_post(array('ID'=>$book));$_POST=array();
verify(get_post_meta($book,'_br_kind',true)==='book','Nieprawidłowy nonce pozwolił zmienić typ.');
wp_set_current_user($subscriber->ID);save_kind($book,'notice');verify(get_post_meta($book,'_br_kind',true)==='book','Brak kontroli uprawnień zapisu metadanych.');wp_set_current_user($admin->ID);
$invalid = fixture(array('post_status'=>'draft','post_title'=>'Test sanitacji'));
save_kind($invalid,'book',array('br_book_author'=>'<script>x</script>Autor','br_book_catalog'=>'javascript:alert(1)'));
verify(get_post_meta($invalid,'_br_book_catalog',true)==='' && strpos(get_post_meta($invalid,'_br_book_author',true),'<')===false,'Niebezpieczna treść książki.');

$event = fixture(array('post_title'=>'Testowe spotkanie — przykład'));
$start = wp_date('Y-m-d\TH:i',time()+3*DAY_IN_SECONDS);
save_kind($event,'event',array('br_event_start'=>$start,'br_event_place'=>'Biblioteka — miejsce testowe'));
verify((int)get_post_meta($event,'_br_event_start',true)===br_parse_datetime($start),'Błędna strefa czasowa wydarzenia.');
verify(strpos(render_part('events'),'Testowe spotkanie')!==false,'Nadchodzące wydarzenie niewidoczne.');
$past = fixture(array('post_title'=>'MINIONE_WYDARZENIE_TEST'));
save_kind($past,'event',array('br_event_start'=>wp_date('Y-m-d\TH:i',time()-4*DAY_IN_SECONDS)));
verify(strpos(render_part('events'),'MINIONE_WYDARZENIE_TEST')===false,'Minione wydarzenie widoczne jako nadchodzące.');
$invalid_event = fixture(array('post_status'=>'draft','post_title'=>'Błędny termin'));
save_kind($invalid_event,'event',array('br_event_start'=>'2026-02-30T12:00'));
verify(!get_post_meta($invalid_event,'_br_event_start',true),'Nieprawidłowa data została przyjęta.');
save_kind($invalid_event,'event',array('br_event_start'=>$start,'br_event_end'=>wp_date('Y-m-d\TH:i',time()+DAY_IN_SECONDS)));
verify(!get_post_meta($invalid_event,'_br_event_until',true),'Przyjęto zakończenie przed rozpoczęciem.');
wp_update_post(array('ID'=>$invalid_event,'post_status'=>'publish'));
verify(get_post_status($invalid_event)==='draft','Szybka edycja publikuje wydarzenie bez terminu.');
$request=new WP_REST_Request('POST','/wp/v2/posts/'.$invalid_event);
$request->set_param('status','publish');
$response=rest_do_request($request);
verify($response->get_status()===400 && get_post_status($invalid_event)==='draft','REST publikuje istniejący szkic wydarzenia bez terminu.');

$notice = fixture(array('post_title'=>'Testowe ważne ogłoszenie'));
save_kind($notice,'notice'); verify(strpos(render_part('notices'),'Testowe ważne ogłoszenie')!==false,'Aktywne ogłoszenie niewidoczne.');
$expired = fixture(array('post_title'=>'WYGASLE_OGLOSZENIE_TEST'));
save_kind($expired,'notice',array('br_notice_until'=>wp_date('Y-m-d\TH:i',time()-DAY_IN_SECONDS)));
verify(strpos(render_part('notices'),'WYGASLE_OGLOSZENIE_TEST')===false,'Wygasłe ogłoszenie widoczne w ważnych.');

// Zmiana danych trafia jednocześnie do strony głównej i stopki.
$data=br_data();$data['phone']='17 11 22 333';$data['street']='Adres testowy 12';update_option('br_library_data',$data);
verify(strpos(render_part('visit'),'17 11 22 333')!==false,'Dane kontaktowe nie trafiają na stronę.');
verify(strpos(render_part('catalogs'),'Adres testowy 12')!==false,'Katalog wyświetla nieaktualny adres.');
// Stopkę i zapis ustawień sprawdza test przeglądarkowy w pełnym żądaniu HTTP.
update_option('br_library_data',br_defaults());

// Natywna galeria i publikacja dokumentu bez kopiowania szkiców na stronę publiczną.
wp_update_post(array('ID'=>$fixture_pages['gallery'],'post_status'=>'publish'));
require_once ABSPATH.'wp-admin/includes/file.php'; require_once ABSPATH.'wp-admin/includes/image.php';
$upload=wp_upload_bits('biblioteka-test.png',null,base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+j9ioAAAAASUVORK5CYII='));
verify(empty($upload['error']),'Nie można utworzyć pliku galerii.');
$attachment=wp_insert_attachment(array('post_mime_type'=>'image/png','post_title'=>'Zdjęcie testowe','post_status'=>'inherit'),$upload['file'],$fixture_pages['gallery']);
$owned=get_option('br_test_owned_ids');$owned[]=$attachment;update_option('br_test_owned_ids',$owned);
wp_update_attachment_metadata($attachment,wp_generate_attachment_metadata($attachment,$upload['file']));
update_post_meta($attachment,'_wp_attachment_image_alt','Opis testowego zdjęcia');
wp_update_post(array('ID'=>$fixture_pages['gallery'],'post_content'=>'<!-- wp:gallery --><figure class="wp-block-gallery"><!-- wp:image {"id":'.$attachment.'} --><figure class="wp-block-image"><img src="'.esc_url($upload['url']).'" alt="Opis testowego zdjęcia" class="wp-image-'.$attachment.'"/></figure><!-- /wp:image --></figure><!-- /wp:gallery -->'));
verify(br_gallery_ids()===array($attachment),'Galeria nie odczytuje bloku obrazka.');
verify(strpos(render_part('gallery'),'Opis testowego zdjęcia')!==false,'Galeria pomija tekst alternatywny.');
verify(!br_published_page('rules'),'Szkic regulaminu traktowany jako publiczny.');
wp_update_post(array('ID'=>$rules,'post_status'=>'publish'));verify(br_published_page('rules')===$rules,'Opublikowany dokument niewidoczny.');wp_update_post(array('ID'=>$rules,'post_status'=>'draft'));

flush_rewrite_rules(true);
$manifest=array('pages'=>$fixture_pages,'article'=>$article,'book'=>$book,'event'=>$event,'notice'=>$notice,'editor'=>$editor->ID,'subscriber'=>$subscriber->ID,'attachment'=>$attachment,'checks'=>$checks,'version'=>get_bloginfo('version'));
file_put_contents('/tmp/br-test-manifest.json',wp_json_encode($manifest));
echo 'Zaliczone sprawdzenia integracyjne: '.$checks.PHP_EOL;
