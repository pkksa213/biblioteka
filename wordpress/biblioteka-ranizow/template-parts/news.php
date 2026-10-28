<?php
if (!defined('ABSPATH')) { exit; }
$news = br_home_query('news');
?>
<div class="editorial-layout"><section class="section articles-home" id="aktualnosci" aria-labelledby="news-title"><div class="section-heading"><div><p class="eyebrow">Z życia biblioteki</p><h2 id="news-title">Artykuły i aktualności</h2></div><a class="button button-small" href="<?php echo esc_url(br_blog_url()); ?>">Wszystkie artykuły</a></div>
<?php if ($news->have_posts()) { ?><div class="news-grid"><?php while ($news->have_posts()) { $news->the_post(); get_template_part('template-parts/card', null, array('heading' => 'h3')); } wp_reset_postdata(); ?></div><?php } else { ?><p class="empty-state">Aktualności pojawią się tutaj po opublikowaniu pierwszego wpisu.</p><?php } ?>
</section></div>
