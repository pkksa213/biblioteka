<?php
/** Wyłącznie lokalny kontener testowy. Żadne hasło nie trafia do repozytorium ani logu. */
if (getenv('BR_TEST_ADMIN_PASSWORD') === false) { throw new RuntimeException('Brak lokalnego kontekstu testowego.'); }
define('WP_INSTALLING', true);
$_SERVER['HTTP_HOST'] = '127.0.0.1:8081';
$_SERVER['SERVER_NAME'] = '127.0.0.1';
$_SERVER['SERVER_PORT'] = '8081';
$_SERVER['REQUEST_METHOD'] = 'GET';
require '/var/www/html/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
if (!is_blog_installed()) {
    wp_install('Gminna Biblioteka Publiczna w Raniżowie', 'biblioteka_test_admin', 'test-admin@example.invalid', false, '', getenv('BR_TEST_ADMIN_PASSWORD'));
    update_option('home', 'http://127.0.0.1:8081');
    update_option('siteurl', 'http://127.0.0.1:8081');
    update_option('timezone_string', 'Europe/Warsaw');
    update_option('permalink_structure', '/%postname%/');
}
switch_theme('biblioteka-ranizow');
global $wp_version;
echo 'WordPress ' . $wp_version . '; PHP ' . PHP_VERSION . '; motyw aktywny.' . PHP_EOL;
