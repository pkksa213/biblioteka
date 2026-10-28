<?php if (!defined('ABSPATH')) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#tresc">Przejdź do treści</a>
<header>
    <div class="wrap utility"><p>Biblioteka główna w Raniżowie · Filie: Wola Raniżowska i Mazury</p>
        <div class="accessibility" role="group" aria-label="Ustawienia czytelności"><span>Czytelność</span><button id="font-size" type="button" aria-label="Powiększ tekst" aria-pressed="false">A+</button><button id="contrast" type="button" aria-pressed="false"><span class="contrast-icon" aria-hidden="true"></span> Kontrast</button></div>
    </div>
    <div class="wrap brand-row">
        <?php if (has_custom_logo()) { the_custom_logo(); } else { ?>
            <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Gminna Biblioteka Publiczna w Raniżowie — strona główna"><span class="brand-symbol"><?php echo br_icon(); ?></span><span class="brand-name">Gminna Biblioteka Publiczna<strong>w Raniżowie</strong></span></a>
        <?php } ?>
        <p class="header-message">Książki, informacje i spotkania.<br><strong>Biblioteka blisko mieszkańców.</strong></p>
        <button class="menu-toggle" type="button" aria-controls="menu" aria-expanded="true"><span class="menu-icon" aria-hidden="true"></span> Menu</button>
    </div>
    <div class="navigation-shell"><nav aria-label="Menu główne"><?php
        wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_id' => 'menu', 'menu_class' => 'nav wrap', 'depth' => 2, 'fallback_cb' => 'br_fallback_menu', 'walker' => new BR_Menu_Walker()));
    ?></nav></div>
</header>
