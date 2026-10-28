<?php
/** Integracja z prawdziwym API W3TC; bez aktywowania wtyczki i zmiany jej ustawień. */
if (!getenv('BR_TEST_ADMIN_PASSWORD')) { throw new RuntimeException('Brak izolowanego środowiska testowego.'); }
$_SERVER['HTTP_HOST'] = '127.0.0.1:8081'; $_SERVER['SERVER_PORT'] = '8081'; $_SERVER['REQUEST_METHOD'] = 'GET';
require '/var/www/html/wp-load.php';
if (wp_get_environment_type() !== 'local' || get_stylesheet() !== 'biblioteka-ranizow') { throw new RuntimeException('Niewłaściwe środowisko testowe.'); }
set_exception_handler(function ($error) { fwrite(STDERR, $error->getMessage() . PHP_EOL); exit(1); });
$scenario = isset($argv[1]) ? $argv[1] : 'publish';
if (!in_array($scenario, array('publish','rest','withdraw','delete','draft'), true)) { throw new RuntimeException('Nieznany scenariusz.'); }
$admin = get_user_by('login', 'biblioteka_test_admin'); wp_set_current_user($admin->ID);
$id = wp_insert_post(array('post_type'=>'post','post_status'=>in_array($scenario,array('withdraw','delete'),true)?'publish':'draft','post_title'=>'W3TC_TEST_'.$scenario),true);
if (is_wp_error($id)) { throw new RuntimeException($id->get_error_message()); }
try {
    // Pliki pochodzą z oficjalnego repozytorium BoldGrid, przypiętego w dokumentacji.
    require '/tmp/biblioteka-w3tc-test/w3-total-cache-api.php';
    $config = W3TC\Dispatcher::config();
    $config->set('pgcache.enabled',true);
    $config->set('pgcache.purge.front_page',false);
    $config->set('pgcache.purge.home',true);
    (new W3TC\PgCache_Plugin())->run();
    $urls = array(); $meta_at_flush = array();
    add_action('w3tc_flush_url', function ($url) use (&$urls,&$meta_at_flush,$id) {
        $urls[]=$url;
        $meta_at_flush=array(get_post_meta($id,'_br_kind',true),(int)get_post_meta($id,'_br_event_start',true),(int)get_post_meta($id,'_br_event_until',true));
    });
    if ($scenario === 'rest') {
        $request = new WP_REST_Request('POST','/wp/v2/posts/'.$id);
        $request->set_param('status','publish');
        $request->set_param('meta',array('_br_kind'=>'event','_br_event_start'=>time()+DAY_IN_SECONDS,'_br_event_until'=>0));
        $response=rest_do_request($request);
        if ($response->get_status()!==200) { throw new RuntimeException('REST nie opublikował testowego wydarzenia.'); }
    } elseif ($scenario === 'delete') { wp_delete_post($id,true); }
    else { wp_update_post(array('ID'=>$id,'post_status'=>$scenario==='publish'?'publish':'draft','post_title'=>'W3TC_TEST_ZMIANA_'.$scenario)); }
    $home_flushed = in_array(home_url('/'),$urls,true);
    if ($home_flushed !== ($scenario !== 'draft')) { throw new RuntimeException('W3TC: błędne odświeżanie statycznej strony głównej — '.$scenario); }
    if ($scenario==='rest' && ($meta_at_flush[0]!=='event' || !$meta_at_flush[1] || $meta_at_flush[2]<$meta_at_flush[1])) { throw new RuntimeException('W3TC otrzymał odświeżenie przed zapisaniem rodzaju i terminu.'); }
    echo 'W3TC API: '.$scenario.' — OK'.PHP_EOL;
} finally { wp_delete_post($id,true); }
