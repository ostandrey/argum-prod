<?php
/**
 * Polylang Homepage Setup Script
 * 
 * This script helps set up proper homepage configuration for Polylang
 * Run this once to configure homepages for both languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    require_once('wp-config.php');
    require_once('wp-load.php');
}

// Check if Polylang is active
if (!function_exists('pll_current_language')) {
    die('Polylang is not active. Please activate Polylang Pro first.');
}

echo "<h2>Polylang Homepage Setup</h2>";

// Get current settings
$ua_homepage = get_option('page_on_front_ua');
$en_homepage = get_option('page_on_front_en');
$main_homepage = get_option('page_on_front');

echo "<p><strong>Current Settings:</strong></p>";
echo "<ul>";
echo "<li>Ukrainian Homepage: " . ($ua_homepage ? get_the_title($ua_homepage) : 'Not set') . "</li>";
echo "<li>English Homepage: " . ($en_homepage ? get_the_title($en_homepage) : 'Not set') . "</li>";
echo "<li>Main Homepage: " . ($main_homepage ? get_the_title($main_homepage) : 'Not set') . "</li>";
echo "</ul>";

// Find pages for each language
$ua_pages = get_posts(array(
    'post_type' => 'page',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => '_pll_lang',
            'value' => 'ua'
        )
    )
));

$en_pages = get_posts(array(
    'post_type' => 'page',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => '_pll_lang',
            'value' => 'en'
        )
    )
));

echo "<h3>Available Pages:</h3>";

echo "<h4>Ukrainian Pages:</h4>";
echo "<ul>";
foreach ($ua_pages as $page) {
    echo "<li>" . $page->post_title . " (ID: " . $page->ID . ")</li>";
}
echo "</ul>";

echo "<h4>English Pages:</h4>";
echo "<ul>";
foreach ($en_pages as $page) {
    echo "<li>" . $page->post_title . " (ID: " . $page->ID . ")</li>";
}
echo "</ul>";

// Setup form
if (isset($_POST['setup_homepages'])) {
    $ua_homepage_id = intval($_POST['ua_homepage']);
    $en_homepage_id = intval($_POST['en_homepage']);
    
    // Update options
    update_option('page_on_front_ua', $ua_homepage_id);
    update_option('page_on_front_en', $en_homepage_id);
    
    // Set main homepage to current language
    $current_lang = pll_current_language();
    if ($current_lang === 'ua') {
        update_option('page_on_front', $ua_homepage_id);
    } elseif ($current_lang === 'en') {
        update_option('page_on_front', $en_homepage_id);
    }
    
    echo "<div style='background: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;'>";
    echo "<strong>Success!</strong> Homepages have been configured.";
    echo "</div>";
    
    // Refresh the page
    echo "<script>setTimeout(function(){ location.reload(); }, 2000);</script>";
}

echo "<h3>Setup Homepages:</h3>";
echo "<form method='post'>";
echo "<p><label>Ukrainian Homepage: <select name='ua_homepage'>";
echo "<option value=''>Select Ukrainian Homepage</option>";
foreach ($ua_pages as $page) {
    $selected = ($ua_homepage == $page->ID) ? 'selected' : '';
    echo "<option value='" . $page->ID . "' $selected>" . $page->post_title . "</option>";
}
echo "</select></label></p>";

echo "<p><label>English Homepage: <select name='en_homepage'>";
echo "<option value=''>Select English Homepage</option>";
foreach ($en_pages as $page) {
    $selected = ($en_homepage == $page->ID) ? 'selected' : '';
    echo "<option value='" . $page->ID . "' $selected>" . $page->post_title . "</option>";
}
echo "</select></label></p>";

echo "<p><input type='submit' name='setup_homepages' value='Setup Homepages' style='background: #0073aa; color: white; padding: 10px 20px; border: none; cursor: pointer;'></p>";
echo "</form>";

echo "<h3>Instructions:</h3>";
echo "<ol>";
echo "<li>Select the appropriate homepage for each language</li>";
echo "<li>Click 'Setup Homepages' to save the configuration</li>";
echo "<li>Test the homepage switching by changing languages</li>";
echo "<li>Delete this file after setup is complete</li>";
echo "</ol>";

echo "<p><strong>Note:</strong> After setup, make sure to delete this file for security reasons.</p>";
?> 
