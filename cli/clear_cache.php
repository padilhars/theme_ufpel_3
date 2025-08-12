<?php
/**
 * Clear UFPel theme cache
 * 
 * Run from Moodle root: php theme/ufpel/cli/clear_cache.php
 */

define('CLI_SCRIPT', true);
require_once(__DIR__ . '/../../../config.php');

echo "=== UFPel Theme Cache Clear ===\n\n";

echo "1. Clearing theme caches...\n";
theme_reset_all_caches();
echo "✓ Theme caches cleared\n";

echo "\n2. Purging all Moodle caches...\n";
purge_all_caches();
echo "✓ All caches purged\n";

echo "\n3. Checking theme configuration...\n";
$configs = [
    'primarycolor' => 'Primary Color',
    'secondarycolor' => 'Secondary Color',
    'backgroundcolor' => 'Background Color',
    'highlightcolor' => 'Highlight Color',
    'contenttextcolor' => 'Content Text Color',
    'highlighttextcolor' => 'Highlight Text Color',
    'logo' => 'Logo'
];

foreach ($configs as $key => $label) {
    $value = get_config('theme_ufpel', $key);
    if ($value !== false) {
        $display = is_string($value) ? substr($value, 0, 50) : 'Set';
        echo "✓ $label: $display\n";
    } else {
        echo "- $label: Not configured\n";
    }
}

echo "\n4. Testing theme load...\n";
try {
    $theme = theme_config::load('ufpel');
    echo "✓ Theme loads successfully\n";
    
    // Test CSS compilation
    $css = $theme->get_css_content();
    if (!empty($css)) {
        echo "✓ CSS compiled: " . strlen($css) . " bytes\n";
    } else {
        echo "⚠ Warning: No CSS generated\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Cache Clear Complete ===\n";
echo "\nNext steps:\n";
echo "1. Access your Moodle site\n";
echo "2. Check if the theme is working correctly\n";
echo "3. If issues persist, check the web server error logs\n";
echo "\nDone!\n";