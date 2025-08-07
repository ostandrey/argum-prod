<?php
/**
 * Custom Drone Form Template Part
 */

$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'ua';

// Cache form data to avoid repeated queries
function get_cached_form_data($lang) {
    static $form_cache = array();
    
    if (!isset($form_cache[$lang])) {
        if ($lang === 'en') {
            $form_cache[$lang] = array(
                'title' => get_option('options_form_title_en') ?: get_option('options_form_title'),
                'image_id' => get_option('options_form_image_en') ?: get_option('options_form_image'),
                'form_data' => get_option('options_form_en') ?: get_option('options_form')
            );
        } else {
            $form_cache[$lang] = array(
                'title' => get_option('options_form_title'),
                'image_id' => get_option('options_form_image'),
                'form_data' => get_option('options_form')
            );
        }
    }
    
    return $form_cache[$lang];
}

$form_data = get_cached_form_data($current_lang);
$title = $form_data['title'];
$image_id = $form_data['image_id'];
$form_data = $form_data['form_data'];

$image = null;
if ($image_id) {
    $image_id = maybe_unserialize($image_id);

    if (is_array($image_id)) {
        $image_id = isset($image_id['ID']) ? $image_id['ID'] : (isset($image_id['id']) ? $image_id['id'] : $image_id[0]);
    }

    if (is_numeric($image_id)) {
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

        if ($image_url) {
            $image = [
                'url' => $image_url,
                'alt' => $image_alt ?: $title
            ];
        }
    }
}

$form = $form_data ? maybe_unserialize($form_data) : null;
?>

<section class="form-section custom-drone-form" id="custom-drone-form">
    <div class="container">
        <div class="form-section__content">
            <?php if ($title): ?>
                <h2 class="form-section__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <?php if ($image && isset($image['url'])): ?>
                <div class="form-section__image">
                    <img src="<?php echo esc_url($image['url']); ?>"
                         alt="<?php echo esc_attr($image['alt']); ?>"
                         loading="lazy">
                </div>
            <?php else: ?>
                <!-- Debug: No image found -->
                <p>Image ID: <?php echo esc_html($image_id); ?></p>
            <?php endif; ?>

            <div class="form-section__form">
                <?php
                if ($form && function_exists('gravity_form')) {
                    $form_id = is_array($form) || is_object($form) ? $form['id'] : $form;
                    gravity_form($form_id, false, false, false, '', true, 1);
                } else {
                    $error_message = $current_lang === 'en' ? 'The form is not found.' : 'Форма не знайдена.';
                    echo '<p>' . esc_html($error_message) . '</p>';
                }
                ?>
            </div>
        </div>
    </div>
</section>
