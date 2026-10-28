<?php
if (!defined('ABSPATH')) { exit; }
$books = br_home_query('books');
if (!$books->have_posts()) { return; }
$data = br_data();
?>
<section class="section" id="nowosci" aria-labelledby="books-title"><div class="books-panel"><div class="section-heading"><div><p class="eyebrow">Jeszcze jeden rozdział</p><h2 id="books-title">Na Twojej liście do przeczytania</h2></div><?php if ($data['catalog_all']) { ?><a class="text-link" href="<?php echo esc_url($data['catalog_all']); ?>">Przejdź do katalogu</a><?php } ?></div><div class="book-grid">
<?php while ($books->have_posts()) { $books->the_post(); $author = get_post_meta(get_the_ID(), '_br_book_author', true); $catalog = get_post_meta(get_the_ID(), '_br_book_catalog', true); ?>
    <article class="book"><a class="book-image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php if (has_post_thumbnail()) { the_post_thumbnail('br-book', array('alt' => '')); } else { ?><span class="cover cover-green"><?php echo br_icon(); ?></span><?php } ?></a><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><?php if ($author) { ?><p><?php echo esc_html($author); ?></p><?php } ?><?php if ($catalog) { ?><a class="book-catalog" href="<?php echo esc_url($catalog); ?>">Sprawdź w katalogu</a><?php } ?></article>
<?php } wp_reset_postdata(); ?>
</div></div></section>
