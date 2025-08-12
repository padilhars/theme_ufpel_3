<?php
/**
 * Check UFPel theme logo configuration
 * 
 * Run from Moodle root: php theme/ufpel/cli/check_logo.php
 */

define('CLI_SCRIPT', true);
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/filelib.php');

echo "=== UFPel Theme Logo Diagnostic ===\n\n";

// 1. Check if logo is configured
echo "1. Checking logo configuration...\n";
$logoconfig = get_config('theme_ufpel', 'logo');
if ($logoconfig !== false) {
    echo "✓ Logo configuration found: $logoconfig\n";
} else {
    echo "✗ No logo configuration found\n";
}

// 2. Check theme_config method
echo "\n2. Testing theme_config methods...\n";
try {
    $theme = theme_config::load('ufpel');
    echo "✓ Theme loaded successfully\n";
    
    // Try to get logo URL
    $logourl = $theme->setting_file_url('logo', 'logo');
    if ($logourl) {
        $url = is_object($logourl) ? $logourl->out(false) : (string)$logourl;
        echo "✓ Logo URL from theme: $url\n";
    } else {
        echo "✗ No logo URL from theme->setting_file_url()\n";
    }
} catch (Exception $e) {
    echo "✗ Error loading theme: " . $e->getMessage() . "\n";
}

// 3. Check file storage directly
echo "\n3. Checking file storage...\n";
$fs = get_file_storage();
$context = context_system::instance();
$files = $fs->get_area_files($context->id, 'theme_ufpel', 'logo', 0, 'sortorder', false);

if ($files && count($files) > 0) {
    echo "✓ Found " . count($files) . " file(s) in logo area:\n";
    foreach ($files as $file) {
        echo "  - " . $file->get_filename() . " (" . $file->get_filesize() . " bytes)\n";
        
        // Generate URL
        $url = moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );
        echo "    URL: " . $url->out(false) . "\n";
    }
} else {
    echo "✗ No files found in theme_ufpel/logo area\n";
}

// 4. Check if pluginfile.php is handling logo requests
echo "\n4. Testing pluginfile handler...\n";
if (function_exists('theme_ufpel_pluginfile')) {
    echo "✓ theme_ufpel_pluginfile function exists\n";
    
    // Check if 'logo' is in allowed areas
    $reflection = new ReflectionFunction('theme_ufpel_pluginfile');
    $filename = $reflection->getFileName();
    $source = file_get_contents($filename);
    if (strpos($source, "'logo'") !== false) {
        echo "✓ 'logo' is in allowed file areas\n";
    } else {
        echo "✗ 'logo' is NOT in allowed file areas - this needs to be fixed!\n";
    }
} else {
    echo "✗ theme_ufpel_pluginfile function not found\n";
}

// 5. Test renderer
echo "\n5. Testing renderer methods...\n";
try {
    $PAGE->set_context(context_system::instance());
    $PAGE->set_url('/');
    $PAGE->set_pagelayout('standard');
    
    $renderer = $PAGE->get_renderer('core');
    if (method_exists($renderer, 'get_logo_url')) {
        $logourl = $renderer->get_logo_url();
        if ($logourl) {
            echo "✓ Renderer get_logo_url() returned: $logourl\n";
        } else {
            echo "✗ Renderer get_logo_url() returned null\n";
        }
    } else {
        echo "✗ Renderer does not have get_logo_url() method\n";
    }
    
    if (method_exists($renderer, 'navbar_brand')) {
        $brand = $renderer->navbar_brand();
        if (strpos($brand, '<img') !== false) {
            echo "✓ navbar_brand() contains an image tag\n";
            // Extract src attribute
            if (preg_match('/src=["\']([^"\']+)["\']/', $brand, $matches)) {
                echo "  Image src: " . $matches[1] . "\n";
            }
        } else {
            echo "✗ navbar_brand() does not contain an image tag\n";
            echo "  Output: " . substr(strip_tags($brand), 0, 100) . "...\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Error testing renderer: " . $e->getMessage() . "\n";
}

// 6. Recommendations
echo "\n=== Recommendations ===\n";
echo "1. Make sure you've uploaded a logo in Site administration > Appearance > Themes > UFPel\n";
echo "2. Clear all caches after uploading: php admin/cli/purge_caches.php\n";
echo "3. Check that the web server has read permissions on moodledata/filedir\n";
echo "4. Verify the logo file is a valid image format (PNG, JPG, SVG)\n";

echo "\nDone!\n";