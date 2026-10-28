<?php
if (!defined('ABSPATH')) { exit; }

function br_sanitize_data($input) {
    $input = is_array($input) ? $input : array();
    $old = br_data();
    foreach (br_defaults() as $key => $default) {
        if (!array_key_exists($key, $input)) { continue; }
        if (is_array($default)) {
            $raw = is_array($input[$key]) ? $input[$key] : array();
            foreach (br_days() as $day => $label) { $old[$key][$day] = isset($raw[$day]) && is_scalar($raw[$day]) ? sanitize_text_field($raw[$day]) : ''; }
        } elseif (is_scalar($input[$key])) {
            if (strpos($key, 'catalog_') === 0 || $key === 'bip') { $old[$key] = esc_url_raw($input[$key], array('http', 'https')); }
            elseif (strpos($key, 'email') !== false) { $old[$key] = sanitize_email($input[$key]); }
            else { $old[$key] = sanitize_text_field($input[$key]); }
        }
    }
    return $old;
}
add_action('admin_init', function () { register_setting('br_library', 'br_library_data', array('type' => 'array', 'sanitize_callback' => 'br_sanitize_data')); });
add_filter('option_page_capability_br_library', function () { return 'edit_pages'; });
add_action('admin_menu', function () {
    add_menu_page('Dane biblioteki', 'Dane biblioteki', 'edit_pages', 'br-library', 'br_settings_page', 'dashicons-book-alt', 26);
    if (current_user_can('edit_pages') && !current_user_can('manage_options')) { remove_menu_page('tools.php'); remove_menu_page('edit-comments.php'); }
});
function br_settings_page() {
    if (!current_user_can('edit_pages')) { wp_die('Brak uprawnień.'); }
    $data = br_data();
    $groups = array(
        'Biblioteka główna' => array('street' => 'Ulica i numer', 'postal' => 'Kod pocztowy', 'phone' => 'Telefon', 'email' => 'E-mail', 'manager' => 'Kierownik', 'hours_note' => 'Informacja pod godzinami'),
        'Katalogi i BIP' => array('catalog_all' => 'Katalog całej gminy', 'catalog_main' => 'Katalog Raniżowa', 'catalog_mazury' => 'Katalog Mazur', 'catalog_wola' => 'Osobny katalog Woli Raniżowskiej (opcjonalnie)', 'bip' => 'Adres BIP'),
        'Filia w Woli Raniżowskiej' => array('wola_address' => 'Adres', 'wola_note' => 'Wskazówki dojazdu / lokalizacji', 'wola_person' => 'Bibliotekarka', 'wola_phone' => 'Telefon filii', 'wola_email' => 'E-mail filii'),
        'Filia w Mazurach' => array('mazury_address' => 'Adres', 'mazury_person' => 'Bibliotekarka', 'mazury_phone' => 'Telefon filii', 'mazury_email' => 'E-mail filii'),
    );
    echo '<div class="wrap"><h1>Dane biblioteki</h1><p>Te dane są wspólne dla strony głównej, filii i stopki. Puste godziny oznaczają brak informacji; wpisz „Nieczynne” tylko po potwierdzeniu.</p><form method="post" action="options.php">';
    settings_fields('br_library');
    foreach ($groups as $title => $fields) {
        echo '<h2>' . esc_html($title) . '</h2><table class="form-table"><tbody>';
        foreach ($fields as $key => $label) {
            $type = strpos($key, 'catalog_') === 0 || $key === 'bip' ? 'url' : (strpos($key, 'email') !== false ? 'email' : 'text');
            echo '<tr><th scope="row"><label for="br-' . esc_attr($key) . '">' . esc_html($label) . '</label></th><td><input class="regular-text" id="br-' . esc_attr($key) . '" name="br_library_data[' . esc_attr($key) . ']" type="' . esc_attr($type) . '" value="' . esc_attr($data[$key]) . '"></td></tr>';
        }
        echo '</tbody></table>';
    }
    foreach (array('hours' => 'Godziny — Raniżów', 'wola_hours' => 'Godziny — Wola Raniżowska', 'mazury_hours' => 'Godziny — Mazury') as $key => $title) {
        echo '<h2>' . esc_html($title) . '</h2><table class="form-table"><tbody>';
        foreach (br_days() as $day => $label) { echo '<tr><th scope="row"><label for="br-' . esc_attr($key . '-' . $day) . '">' . esc_html($label) . '</label></th><td><input id="br-' . esc_attr($key . '-' . $day) . '" name="br_library_data[' . esc_attr($key) . '][' . esc_attr($day) . ']" value="' . esc_attr($data[$key][$day]) . '" placeholder="np. 9:00–17:00"></td></tr>'; }
        echo '</tbody></table>';
    }
    submit_button('Zapisz dane biblioteki');
    echo '</form></div>';
}
