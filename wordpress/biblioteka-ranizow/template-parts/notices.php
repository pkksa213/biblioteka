<?php
if (!defined('ABSPATH')) { exit; }
$notices = br_home_query('notices');
if (!$notices->have_posts()) { return; }
?>
<section class="important-announcements" id="ogloszenia" aria-labelledby="important-title"><div class="important-symbol" aria-hidden="true">!</div><div class="important-copy"><p class="eyebrow">Warto wiedzieć przed wizytą</p><h2 id="important-title">Ważne ogłoszenia</h2><ul class="important-list"><?php while ($notices->have_posts()) { $notices->the_post(); ?><li><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p></li><?php } wp_reset_postdata(); ?></ul></div><a class="button" href="<?php echo esc_url(br_category_url('notices')); ?>">Zobacz ogłoszenia</a></section>
