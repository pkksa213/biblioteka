<?php
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<main id="tresc" tabindex="-1" class="wrap">
    <section class="visit-intro" aria-labelledby="library-title"><p class="eyebrow">Zaplanuj wizytę</p><h1 id="library-title">Twoja biblioteka w Raniżowie</h1><p>Najważniejsze informacje w jednym miejscu: godziny, adres i kontakt.</p></section>
    <?php get_template_part('template-parts/visit'); ?>
    <?php if (is_page() && trim(get_post_field('post_content', get_queried_object_id())) !== '') { ?><section class="front-editor-content entry-content"><?php while (have_posts()) { the_post(); the_content(); } ?></section><?php } ?>
    <?php get_template_part('template-parts/notices'); ?>
    <?php get_template_part('template-parts/catalogs'); ?>
    <?php get_template_part('template-parts/news'); ?>
    <?php get_template_part('template-parts/books'); ?>
    <div class="events-about"><?php get_template_part('template-parts/events'); ?><section class="about" id="dla-czytelnikow"><p class="eyebrow">Dobrze być blisko</p><h2 id="o-bibliotece">Więcej niż<br>miejsce z książkami.</h2><p>Gminna Biblioteka Publiczna w Raniżowie oraz filie w Woli Raniżowskiej i Mazurach — poznaj nasze placówki i zaplanuj wizytę.</p><p>Informacje o zapisach, dokumentach i kontakcie z biblioteką zebraliśmy w strefie czytelnika.</p><a href="<?php echo esc_url(br_page_url('reader')); ?>">Przejdź do strefy czytelnika</a></section></div>
    <?php get_template_part('template-parts/gallery'); ?>
    <?php get_template_part('template-parts/branches'); ?>
</main>
<?php get_footer(); ?>
