<?php
/**
 * Contact Form Template Part
 */

$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'ua';

// Cache contact form data to avoid repeated queries
function get_cached_contact_form_data($lang) {
    static $contact_form_cache = array();
    
    if (!isset($contact_form_cache[$lang])) {
        if ($lang === 'en') {
            $form_data = get_option('options_contact_form_en') ?: get_option('options_contact_form');
            $contact_form_cache[$lang] = array(
                'title' => get_option('options_contact_form_title_en') ?: get_option('options_contact_form_title'),
                'form' => $form_data ? maybe_unserialize($form_data) : null
            );
        } else {
            $form_data = get_option('options_contact_form');
            $contact_form_cache[$lang] = array(
                'title' => get_option('options_contact_form_title'),
                'form' => $form_data ? maybe_unserialize($form_data) : null
            );
        }
    }
    
    return $contact_form_cache[$lang];
}

$contact_form_data = get_cached_contact_form_data($current_lang);
$title = $contact_form_data['title'];
$form = $contact_form_data['form'];


?>

<section class="form-section" id="contact-form" data-lang="<?php echo esc_attr($current_lang); ?>">
    <div class="container">
        <div class="form-section__content">
            <?php if ($title): ?>
                <h2 class="form-section__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <div id="form-messages" class="form-messages" style="display: none;"></div>

            <div class="form-section__form">
                <?php
                if ($form && function_exists('gravity_form')) {
                    $form_id = is_array($form) || is_object($form) ? $form['id'] : $form;
                    gravity_form($form_id, false, false, false, '', false, 1);
                } else {
                    echo '<p>' . ($current_lang === 'en' ? 'The form is not found.' : 'Форма не знайдена.') . '</p>';
                }
                ?>
            </div>
        </div>
    </div>
</section>
