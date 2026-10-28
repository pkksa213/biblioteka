<?php
if (!getenv('BR_TEST_ADMIN_PASSWORD')) { throw new RuntimeException('Brak lokalnego kontekstu testowego.'); }
$_SERVER['HTTP_HOST']='127.0.0.1:8081'; $_SERVER['REQUEST_METHOD']='GET';
define('FS_METHOD','direct');
require '/var/www/html/wp-load.php';
set_exception_handler(function ($error) { fwrite(STDERR,$error->getMessage().PHP_EOL);exit(1); });
require_once ABSPATH.'wp-admin/includes/file.php';
WP_Filesystem();
global $wp_filesystem;
$destination=WP_CONTENT_DIR.'/upgrade/biblioteka-package-test';
if ($wp_filesystem->exists($destination)) { $wp_filesystem->delete($destination,true); }
$result=unzip_file('/project/do-pobrania/biblioteka-ranizow-wordpress.zip',$destination);
if (is_wp_error($result)) { throw new RuntimeException($result->get_error_message()); }
$package_theme=wp_get_theme('biblioteka-ranizow',$destination);
if (!$package_theme->exists() || $package_theme->errors() || $package_theme->get('Name')!=='Biblioteka Raniżów') { throw new RuntimeException('WordPress odrzuca strukturę paczki motywu.'); }
echo 'WordPress rozpakował ZIP i rozpoznał instalacyjny motyw '.$package_theme->get('Name').' '.$package_theme->get('Version').'.'.PHP_EOL;
