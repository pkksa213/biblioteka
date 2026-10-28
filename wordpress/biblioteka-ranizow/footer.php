<?php if (!defined('ABSPATH')) { exit; } $data = br_data(); ?>
<footer><div class="wrap"><div class="footer-grid">
    <section aria-labelledby="footer-contact-title"><h2 id="footer-contact-title">Gminna Biblioteka Publiczna w Raniżowie</h2>
        <p><?php echo esc_html($data['street'] . ', ' . $data['postal'] . ' Raniżów'); ?></p>
        <p><?php if ($data['phone']) { ?><a href="<?php echo esc_url(br_phone_url($data['phone'])); ?>"><?php echo esc_html($data['phone']); ?></a><?php } ?> <?php if ($data['email']) { ?> · <a href="<?php echo esc_url('mailto:' . $data['email']); ?>"><?php echo esc_html($data['email']); ?></a><?php } ?></p>
        <ul class="footer-hours"><?php foreach (br_days() as $key => $day) { if ($data['hours'][$key]) { ?><li><?php echo esc_html($day . ': ' . $data['hours'][$key]); ?></li><?php } } ?></ul>
    </section>
    <section aria-labelledby="footer-public-title"><h2 id="footer-public-title">Dokumenty i informacje publiczne</h2><ul>
        <li><a href="<?php echo esc_url(br_reader_anchor('dokumenty')); ?>">Dokumenty biblioteki i dostępność</a></li>
        <?php foreach (array('rules' => 'Regulamin', 'accessibility' => 'Deklaracja dostępności', 'privacy' => 'Prywatność i cookies', 'statute' => 'Statut', 'children_safety' => 'Ochrona małoletnich') as $key => $label) { if (br_published_page($key)) { ?><li><a href="<?php echo esc_url(br_page_url($key)); ?>"><?php echo esc_html($label); ?></a></li><?php } } ?>
        <?php if ($data['bip']) { ?><li><a href="<?php echo esc_url($data['bip']); ?>">BIP — serwis zewnętrzny</a></li><?php } ?>
    </ul></section>
</div><div class="footer-note">Gminna Biblioteka Publiczna w Raniżowie · Biblioteka główna i filie</div></div></footer>
<div class="sr-only" id="announcements" role="status" aria-live="polite"></div>
<?php wp_footer(); ?>
</body>
</html>
