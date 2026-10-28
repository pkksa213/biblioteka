<?php if (!defined('ABSPATH')) { exit; } $ids = br_gallery_ids(); if (!$ids) { return; } ?>
<section class="section" id="galeria" aria-labelledby="gallery-title"><div class="section-heading"><div><p class="eyebrow">Nasze wspólne chwile</p><h2 id="gallery-title">Biblioteka w obiektywie</h2></div><a class="text-link" href="<?php echo esc_url(br_page_url('gallery')); ?>">Cała galeria</a></div><div class="gallery-grid">
<?php foreach ($ids as $id) { ?><figure><?php echo wp_get_attachment_image($id, 'br-card'); ?><?php if (wp_get_attachment_caption($id)) { ?><figcaption><?php echo esc_html(wp_get_attachment_caption($id)); ?></figcaption><?php } ?></figure><?php } ?>
</div></section>
