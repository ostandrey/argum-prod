<?php
/**
 * Footer
 */

$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'uk';

// Cache footer data to avoid repeated queries
function get_cached_footer_data($lang) {
    static $footer_cache = array();
    
    if (!isset($footer_cache[$lang])) {
        $footer_cache[$lang] = array();
        
        if ($lang === 'en') {
            // Use ACF functions instead of direct SQL for better performance
            $footer_cache[$lang] = array(
                'copyright' => get_field('copyright_en', 'options') ?: 'Created by Arctic Web',
                'footer_navigation_title' => get_field('footer_navigation_title_en', 'options') ?: 'Navigation',
                'footer_contacts_title' => get_field('footer_contacts_title_en', 'options') ?: 'Contacts',
                'footer_email' => get_field('footer_email_en', 'options') ?: get_field('footer_email', 'options'),
                'footer_phone' => get_field('footer_phone_en', 'options') ?: get_field('footer_phone', 'options'),
                'footer_phone_link' => get_field('footer_phone_link_en', 'options') ?: get_field('footer_phone_link', 'options'),
                'footer_logo' => get_field('footer_logo_en', 'options') ?: get_field('footer_logo', 'options'),
                'social_networks' => array(),
                'bottom_links' => array()
            );
            
            // Get social networks efficiently
            if (have_rows('footer_social_en', 'options')) {
                while (have_rows('footer_social_en', 'options')) {
                    the_row();
                    $platform = get_sub_field('social_platform');
                    $social_link = get_sub_field('social_link');
                    $social_icon = get_sub_field('social_icon');

                    if ($social_link) {
                        $footer_cache[$lang]['social_networks'][] = array(
                            'platform' => $platform,
                            'url' => $social_link['url'],
                            'title' => $social_link['title'],
                            'target' => $social_link['target'] ?: '_blank',
                            'icon' => $social_icon
                        );
                    }
                }
            }
            
            // Get bottom links efficiently - use English fields (_en suffix) from Ukrainian language context
            if (have_rows('footer_bottom_links_en', 'options')) {
                while (have_rows('footer_bottom_links_en', 'options')) {
                    the_row();
                    $bottom_link = get_sub_field('bottom_link_en');

                    if ($bottom_link) {
                        $footer_cache[$lang]['bottom_links'][] = array(
                            'url' => $bottom_link['url'],
                            'title' => $bottom_link['title'],
                            'target' => $bottom_link['target'] ?: '_self'
                        );
                    }
                }
            }
            
        } else {
            // Ukrainian language
            $footer_cache[$lang] = array(
                'copyright' => get_field('copyright', 'options') ?: '© 2024 Arctic Web. Всі права захищені.',
                'footer_navigation_title' => get_field('footer_navigation_title', 'options') ?: 'Навігація',
                'footer_contacts_title' => get_field('footer_contacts_title', 'options') ?: 'Контакти',
                'footer_logo' => get_field('footer_logo', 'options'),
                'footer_email' => get_field('footer_email', 'options'),
                'footer_phone' => get_field('footer_phone', 'options'),
                'footer_phone_link' => get_field('footer_phone_link', 'options'),
                'social_networks' => array(),
                'bottom_links' => array()
            );
            
            // Get social networks efficiently
            if (have_rows('footer_social', 'options')) {
                while (have_rows('footer_social', 'options')) {
                    the_row();
                    $platform = get_sub_field('social_platform');
                    $social_link = get_sub_field('social_link');
                    $social_icon = get_sub_field('social_icon');

                    if ($social_link) {
                        $footer_cache[$lang]['social_networks'][] = array(
                            'platform' => $platform,
                            'url' => $social_link['url'],
                            'title' => $social_link['title'],
                            'target' => $social_link['target'] ?: '_blank',
                            'icon' => $social_icon
                        );
                    }
                }
            }
            
            // Get bottom links efficiently
            if (have_rows('footer_bottom_links', 'options')) {
                while (have_rows('footer_bottom_links', 'options')) {
                    the_row();
                    $bottom_link = get_sub_field('bottom_link');

                    if ($bottom_link) {
                        $footer_cache[$lang]['bottom_links'][] = array(
                            'url' => $bottom_link['url'],
                            'title' => $bottom_link['title'],
                            'target' => $bottom_link['target'] ?: '_self'
                        );
                    }
                }
            }
        }
    }
    
    return $footer_cache[$lang];
}

// Get cached footer data
$footer_data = get_cached_footer_data($current_lang);

// Extract variables for easier use
$copyright = $footer_data['copyright'];
$footer_navigation_title = $footer_data['footer_navigation_title'];
$footer_contacts_title = $footer_data['footer_contacts_title'];
$footer_logo = $footer_data['footer_logo'];
$footer_email = $footer_data['footer_email'];
$footer_phone = $footer_data['footer_phone'];
$footer_phone_link = $footer_data['footer_phone_link'];
$social_networks = $footer_data['social_networks'];
$bottom_links = $footer_data['bottom_links'];

?>

<footer class="footer">
    <div class="container">
        <div class="footer__main">
            <div class="footer__logo">
                <a href="<?php echo esc_url(function_exists('pll_home_url') ? pll_home_url() : home_url()); ?>">
                    <?php if ($footer_logo && isset($footer_logo['url'])): ?>
                        <img src="<?php echo esc_url($footer_logo['url']); ?>"
                             alt="<?php echo esc_attr($footer_logo['alt'] ?: get_bloginfo('name')); ?>"
                             <?php if ($footer_logo['width']): ?>width="<?php echo esc_attr($footer_logo['width']); ?>"<?php endif; ?>
                             <?php if ($footer_logo['height']): ?>height="<?php echo esc_attr($footer_logo['height']); ?>"<?php endif; ?>>
                    <?php else: ?>
                        <?php show_custom_logo(); ?>
                    <?php endif; ?>
                </a>
            </div>

            <nav class="footer__nav">
                <h3 class="footer__title"><?php echo esc_html($footer_navigation_title); ?></h3>
                <?php
                if (has_nav_menu('footer-menu')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu',
                        'menu_class' => 'footer__menu',
                        'depth' => 1
                    ));
                }
                ?>
            </nav>

            <div class="footer__contacts">
                <h3 class="footer__title"><?php echo esc_html($footer_contacts_title); ?></h3>
                <ul class="footer__contact-list">
                    <?php if ($footer_email): ?>
                        <li class="footer__contact-item">
                            <a href="mailto:<?php echo esc_attr($footer_email); ?>"
                               class="footer__contact-link"><?php echo esc_html($footer_email); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($footer_phone): ?>
                        <li class="footer__contact-item">
                            <a href="tel:<?php echo esc_attr($footer_phone_link ?: $footer_phone); ?>"
                               class="footer__contact-link"><?php echo esc_html($footer_phone); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>

                <?php if (!empty($social_networks)): ?>
                    <div class="footer__social">
                        <?php foreach ($social_networks as $social): ?>
                            <a href="<?php echo esc_url($social['url']); ?>"
                               class="footer__social-link"
                               target="<?php echo esc_attr($social['target'] ?? '_blank'); ?>"
                               aria-label="<?php echo esc_attr($social['title'] ?: ucfirst($social['platform'])); ?>">
                                <?php if (isset($social['icon']) && $social['icon'] && isset($social['icon']['url'])): ?>
                                    <img src="<?php echo esc_url($social['icon']['url']); ?>"
                                         alt="<?php echo esc_attr($social['icon']['alt'] ?: ucfirst($social['platform'])); ?>"
                                         class="footer__social-icon"
                                         width="24" height="24">
                                <?php else: ?>
                                    <span><?php echo esc_html(ucfirst($social['platform'])); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($bottom_links)): ?>
            <div class="footer__bottom">
                <div class="footer__links">
                    <?php foreach ($bottom_links as $link): ?>
                        <a href="<?php echo esc_url($link['url']); ?>"
                           class="footer__bottom-link"
                           target="<?php echo esc_attr($link['target'] ?? '_self'); ?>">
                            <?php echo esc_html($link['title']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Fallback footer links for when ACF fields are not configured -->
            <div class="footer__bottom">
                <div class="footer__links">
                    <?php if ($current_lang === 'en'): ?>
                        <?php
                        // Try to get English footer links from ACF fields with _en suffix
                        if (have_rows('footer_bottom_links_en', 'options')) {
                            while (have_rows('footer_bottom_links_en', 'options')) {
                                the_row();
                                $bottom_link = get_sub_field('bottom_link_en');
                                if ($bottom_link) {
                                    echo '<a href="' . esc_url($bottom_link['url']) . '" class="footer__bottom-link" target="' . esc_attr($bottom_link['target'] ?: '_self') . '">' . esc_html($bottom_link['title']) . '</a>';
                                }
                            }
                        } else {
                            // If no ACF fields, show default English links
                            echo '<a href="' . esc_url(home_url('/privacy-policy/')) . '" class="footer__bottom-link">Privacy Policy</a>';
                            echo '<a href="' . esc_url(home_url('/terms-of-service/')) . '" class="footer__bottom-link">Terms of Service</a>';
                            echo '<a href="' . esc_url(home_url('/cookie-policy/')) . '" class="footer__bottom-link">Cookie Policy</a>';
                        }
                        ?>
                    <?php else: ?>
                        <?php
                        // Try to get Ukrainian footer links from ACF fields
                        if (have_rows('footer_bottom_links', 'options')) {
                            while (have_rows('footer_bottom_links', 'options')) {
                                the_row();
                                $bottom_link = get_sub_field('bottom_link');
                                if ($bottom_link) {
                                    echo '<a href="' . esc_url($bottom_link['url']) . '" class="footer__bottom-link" target="' . esc_attr($bottom_link['target'] ?: '_self') . '">' . esc_html($bottom_link['title']) . '</a>';
                                }
                            }
                        } else {
                            // If no ACF fields, show default Ukrainian links
                            echo '<a href="' . esc_url(home_url('/uk/privacy-policy/')) . '" class="footer__bottom-link">Політика конфіденційності</a>';
                            echo '<a href="' . esc_url(home_url('/uk/terms-of-service/')) . '" class="footer__bottom-link">Умови використання</a>';
                            echo '<a href="' . esc_url(home_url('/uk/cookie-policy/')) . '" class="footer__bottom-link">Політика використання файлів cookie</a>';
                        }
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($copyright): ?>
            <div class="footer-copy">
                <?php echo wp_kses_post($copyright); ?>
            </div>
        <?php endif; ?>
    </div>
</footer>

<?php wp_footer(); ?>
<?php if ($ada_script = get_field('ada', 'options')): ?>
    <?php echo $ada_script; ?>
<?php endif; ?>
</body>
</html>
