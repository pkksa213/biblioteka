<?php
/* Template Name: Filie biblioteki */
if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main id="tresc" tabindex="-1" class="wrap document-main"><p class="breadcrumbs"><a href="<?php echo esc_url(home_url('/')); ?>">Strona główna</a></p><h1><?php the_title(); ?></h1><?php get_template_part('template-parts/branches'); ?><?php while (have_posts()) { the_post(); ?><div class="entry-content article-detail"><?php the_content(); ?></div><?php } ?></main><?php get_footer(); ?>
