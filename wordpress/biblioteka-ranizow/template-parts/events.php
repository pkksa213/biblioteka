<?php
if (!defined('ABSPATH')) { exit; }
$events = br_home_query('events');
?>
<section class="section" id="wydarzenia" aria-labelledby="events-title"><div class="section-heading"><div><p class="eyebrow">Spotkajmy się</p><h2 id="events-title">W kalendarzu biblioteki</h2></div></div>
<?php if ($events->have_posts()) { while ($events->have_posts()) { $events->the_post(); $start = (int) get_post_meta(get_the_ID(), '_br_event_start', true); $place = get_post_meta(get_the_ID(), '_br_event_place', true); ?><article class="event"><div class="event-date" aria-hidden="true"><strong><?php echo esc_html(wp_date('d', $start)); ?></strong><?php echo esc_html(wp_date('M Y', $start)); ?></div><div><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><time datetime="<?php echo esc_attr(wp_date(DATE_W3C, $start)); ?>"><?php echo esc_html(wp_date('j F Y, H:i', $start)); ?></time><?php if ($place) { echo ' · ' . esc_html($place); } ?></p></div></article><?php } wp_reset_postdata(); } else { ?><p class="empty-state">Obecnie nie ma opublikowanych nadchodzących wydarzeń.</p><?php } ?>
</section>
