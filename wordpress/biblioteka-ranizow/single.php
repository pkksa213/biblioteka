<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main id="tresc" tabindex="-1" class="wrap document-main"><p class="breadcrumbs"><a href="<?php echo esc_url(home_url('/')); ?>">Strona główna</a> / <a href="<?php echo esc_url(br_blog_url()); ?>">Artykuły</a></p>
<?php while (have_posts()) { the_post(); $kind = get_post_meta(get_the_ID(), '_br_kind', true); ?>
<article <?php post_class('article-detail'); ?>><h1><?php the_title(); ?></h1><p class="article-meta">Opublikowano <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time></p>
<?php if ($kind === 'book') { $author = get_post_meta(get_the_ID(), '_br_book_author', true); $catalog = get_post_meta(get_the_ID(), '_br_book_catalog', true); if ($author) { ?><p class="article-lead">Autor: <?php echo esc_html($author); ?></p><?php } if ($catalog) { ?><p><a class="button" href="<?php echo esc_url($catalog); ?>">Sprawdź książkę w katalogu</a></p><?php } } ?>
<?php if ($kind === 'event') { $start = (int) get_post_meta(get_the_ID(), '_br_event_start', true); $place = get_post_meta(get_the_ID(), '_br_event_place', true); ?><div class="event-details"><?php if ($start) { ?><p><strong>Termin:</strong> <time datetime="<?php echo esc_attr(wp_date(DATE_W3C, $start)); ?>"><?php echo esc_html(wp_date('j F Y, H:i', $start)); ?></time></p><?php } if ($place) { ?><p><strong>Miejsce:</strong> <?php echo esc_html($place); ?></p><?php } ?></div><?php } ?>
<?php if (has_post_thumbnail()) { ?><figure class="entry-thumbnail"><?php the_post_thumbnail('large'); ?></figure><?php } ?>
<div class="entry-content"><?php the_content(); wp_link_pages(array('before' => '<nav class="page-links" aria-label="Strony artykułu">', 'after' => '</nav>')); ?></div>
<p><a href="<?php echo esc_url(br_blog_url()); ?>">Wróć do artykułów</a></p></article>
<?php } ?></main><?php get_footer(); ?>
