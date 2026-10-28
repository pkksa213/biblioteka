<?php if (!defined('ABSPATH')) { exit; } $data = br_data(); ?>
<section class="section branch-section" id="filie" aria-labelledby="branches-title"><div class="section-heading"><div><p class="eyebrow">Biblioteka blisko Ciebie</p><h2 id="branches-title">Nasze filie</h2></div></div><div class="branch-grid">
<?php foreach (array('wola' => 'Wola Raniżowska', 'mazury' => 'Mazury') as $key => $name) { ?>
    <article class="branch-card" id="filia-<?php echo esc_attr($key); ?>"><h3><?php echo esc_html($name); ?></h3><p><strong><?php echo esc_html($data[$key . '_address']); ?></strong></p><?php if ($key === 'wola' && $data['wola_note']) { ?><p><?php echo esc_html($data['wola_note']); ?></p><?php } ?><?php if ($data[$key . '_person']) { ?><p>Bibliotekarka: <?php echo esc_html($data[$key . '_person']); ?></p><?php } ?><?php br_hours_table($data[$key . '_hours'], 'Godziny filii: ' . $name); ?>
    <?php if ($data[$key . '_phone']) { ?><p><a href="<?php echo esc_url(br_phone_url($data[$key . '_phone'])); ?>"><?php echo esc_html($data[$key . '_phone']); ?></a></p><?php } if ($data[$key . '_email']) { ?><p><a href="<?php echo esc_url('mailto:' . $data[$key . '_email']); ?>"><?php echo esc_html($data[$key . '_email']); ?></a></p><?php } ?>
    <?php $url = $key === 'wola' ? ($data['catalog_wola'] ?: $data['catalog_all']) : $data['catalog_mazury']; if ($url) { ?><a class="button" href="<?php echo esc_url($url); ?>"><?php echo $key === 'wola' && !$data['catalog_wola'] ? 'Katalog wspólny gminy' : 'Katalog filii — serwis zewnętrzny'; ?></a><?php } ?></article>
<?php } ?>
</div></section>
