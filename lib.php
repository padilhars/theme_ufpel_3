<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme UFPel lib functions - Fixed for Moodle 5.x.
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Returns the main SCSS content for the theme.
 *
 * @param theme_config $theme The theme config object.
 * @return string The SCSS content.
 */
function theme_ufpel_get_main_scss_content($theme) {
    global $CFG;
    
    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : 'default.scss';
    
    // Security check for filename
    $filename = clean_param($filename, PARAM_FILE);
    
    $fs = get_file_storage();
    $context = context_system::instance();
    
    // Try to load preset file from theme settings
    if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_ufpel', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Load default preset
        $scss .= theme_ufpel_get_default_preset_content($CFG->dirroot);
    }
    
    // Append post.scss content
    $postscss = file_get_contents($CFG->dirroot . '/theme/ufpel/scss/post.scss');
    if ($postscss !== false) {
        $scss .= "\n" . $postscss;
    }
    
    return $scss;
}

/**
 * Get default preset content.
 *
 * @param string $dirroot The Moodle dirroot.
 * @return string The default preset content.
 */
function theme_ufpel_get_default_preset_content($dirroot) {
    $defaultfile = $dirroot . '/theme/ufpel/scss/preset/default.scss';
    
    if (file_exists($defaultfile) && is_readable($defaultfile)) {
        return file_get_contents($defaultfile);
    }
    
    // Fallback to Boost's default
    $boostdefault = $dirroot . '/theme/boost/scss/preset/default.scss';
    if (file_exists($boostdefault) && is_readable($boostdefault)) {
        return file_get_contents($boostdefault);
    }
    
    return '';
}

/**
 * Get pre-SCSS code.
 * Injects variables before SCSS compilation.
 *
 * @param theme_config $theme The theme config object.
 * @return string The pre-SCSS code.
 */
function theme_ufpel_get_pre_scss($theme) {
    $scss = '';
    $configurable = [];
    
    // Primary color (formerly brand color) with validation
    $primarycolor = get_config('theme_ufpel', 'primarycolor');
    // Check for legacy brandcolor setting if primarycolor is not set
    if (empty($primarycolor)) {
        $primarycolor = get_config('theme_ufpel', 'brandcolor');
    }
    
    if (!empty($primarycolor) && preg_match('/^#[a-f0-9]{6}$/i', $primarycolor)) {
        $configurable['primarycolor'] = $primarycolor;
        $configurable['primary'] = $primarycolor; // Bootstrap variable
    } else {
        $configurable['primarycolor'] = '#003366';
        $configurable['primary'] = '#003366';
    }
    
    // Secondary color with validation
    $secondarycolor = get_config('theme_ufpel', 'secondarycolor');
    if (!empty($secondarycolor) && preg_match('/^#[a-f0-9]{6}$/i', $secondarycolor)) {
        $configurable['secondarycolor'] = $secondarycolor;
        $configurable['secondary'] = $secondarycolor;
    } else {
        $configurable['secondarycolor'] = '#0066cc';
        $configurable['secondary'] = '#0066cc';
    }
    
    // Background color
    $backgroundcolor = get_config('theme_ufpel', 'backgroundcolor');
    if (!empty($backgroundcolor) && preg_match('/^#[a-f0-9]{6}$/i', $backgroundcolor)) {
        $configurable['backgroundcolor'] = $backgroundcolor;
        $configurable['body-bg'] = $backgroundcolor;
    } else {
        $configurable['backgroundcolor'] = '#ffffff';
        $configurable['body-bg'] = '#ffffff';
    }
    
    // Highlight color
    $highlightcolor = get_config('theme_ufpel', 'highlightcolor');
    if (!empty($highlightcolor) && preg_match('/^#[a-f0-9]{6}$/i', $highlightcolor)) {
        $configurable['highlightcolor'] = $highlightcolor;
        $configurable['warning'] = $highlightcolor;
    } else {
        $configurable['highlightcolor'] = '#ffc107';
        $configurable['warning'] = '#ffc107';
    }
    
    // Content text color
    $contenttextcolor = get_config('theme_ufpel', 'contenttextcolor');
    if (!empty($contenttextcolor) && preg_match('/^#[a-f0-9]{6}$/i', $contenttextcolor)) {
        $configurable['contenttextcolor'] = $contenttextcolor;
        $configurable['body-color'] = $contenttextcolor;
    } else {
        $configurable['contenttextcolor'] = '#212529';
        $configurable['body-color'] = '#212529';
    }
    
    // Highlight text color
    $highlighttextcolor = get_config('theme_ufpel', 'highlighttextcolor');
    if (!empty($highlighttextcolor) && preg_match('/^#[a-f0-9]{6}$/i', $highlighttextcolor)) {
        $configurable['highlighttextcolor'] = $highlighttextcolor;
    } else {
        $configurable['highlighttextcolor'] = '#ffffff';
    }
    
    // Custom fonts
    $customfonts = get_config('theme_ufpel', 'customfonts');
    if (!empty($customfonts)) {
        $scss .= $customfonts . "\n";
    }
    
    // Build SCSS variables
    foreach ($configurable as $configkey => $configval) {
        $scss .= sprintf('$%s: %s !default;' . "\n", $configkey, $configval);
    }
    
    // Import utility files first - only if they exist
    $utilityfiles = [
        'utilities/variables',
        'utilities/mixins',
        'utilities/functions'
    ];
    
    foreach ($utilityfiles as $file) {
        $filepath = __DIR__ . '/scss/' . $file . '.scss';
        if (file_exists($filepath)) {
            $scss .= '@import "' . $file . '";' . "\n";
        }
    }
    
    // Prepend custom pre-scss
    if (!empty($theme->settings->rawscsspre)) {
        $scss .= "\n" . $theme->settings->rawscsspre . "\n";
    }
    
    return $scss;
}

/**
 * Get extra SCSS to append.
 *
 * @param theme_config $theme The theme config object.
 * @return string The extra SCSS.
 */
function theme_ufpel_get_extra_scss($theme) {
    $scss = '';
    
    // Add custom SCSS
    if (!empty($theme->settings->rawscss)) {
        $scss .= "\n" . $theme->settings->rawscss;
    }
    
    // Add custom CSS (will be processed as SCSS)
    if (!empty($theme->settings->customcss)) {
        $scss .= "\n" . $theme->settings->customcss;
    }
    
    return $scss;
}

/**
 * CSS tree post processor - Updated for Moodle 5.x.
 * Uses the new CSS post-processing API instead of string manipulation.
 *
 * @param string $css The CSS.
 * @param theme_config $theme The theme config.
 * @return string The processed CSS.
 */
function theme_ufpel_css_tree_post_processor($css, $theme) {
    // No longer manipulate CSS directly with string replacements
    // The theme settings are now handled via SCSS variables
    return $css;
}

/**
 * Serves any files associated with the theme settings.
 * FIXED: Better handling of file serving to prevent URL issues.
 *
 * @param stdClass $course The course object.
 * @param stdClass $cm The course module object.
 * @param context $context The context.
 * @param string $filearea The file area.
 * @param array $args The arguments.
 * @param bool $forcedownload Whether to force download.
 * @param array $options Additional options.
 * @return bool|void
 */
function theme_ufpel_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    // Check context level
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }
    
    // Validate file areas
    $allowedareas = ['logo', 'loginbackgroundimage', 'preset', 'favicon', 'footerlogo'];
    if (!in_array($filearea, $allowedareas)) {
        return false;
    }
    
    // Use theme_config to serve the file
    $theme = theme_config::load('ufpel');
    
    // The setting_file_serve method handles the file serving correctly
    return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
}

/**
 * Get the current user preferences that are available for the theme.
 *
 * @return array The preferences
 */
function theme_ufpel_get_user_preferences() {
    return [
        'drawer-open-index' => [
            'type' => PARAM_BOOL,
            'null' => NULL_NOT_ALLOWED,
            'default' => true,
            'permissioncallback' => [core_user::class, 'is_current_user'],
        ],
        'drawer-open-block' => [
            'type' => PARAM_BOOL,
            'null' => NULL_NOT_ALLOWED,
            'default' => false,
            'permissioncallback' => [core_user::class, 'is_current_user'],
        ],
        'theme_ufpel_darkmode' => [
            'type' => PARAM_BOOL,
            'null' => NULL_ALLOWED,
            'default' => null,
            'permissioncallback' => [core_user::class, 'is_current_user'],
        ],
        'theme_ufpel_compactview' => [
            'type' => PARAM_BOOL,
            'null' => NULL_ALLOWED,
            'default' => null,
            'permissioncallback' => [core_user::class, 'is_current_user'],
        ],
    ];
}

/**
 * Get list of available presets.
 *
 * @return array Array of preset choices.
 */
function theme_ufpel_get_presets_list() {
    global $CFG;
    
    $choices = [];
    $presetsdir = $CFG->dirroot . '/theme/ufpel/scss/preset/';
    
    if (is_dir($presetsdir)) {
        $presets = scandir($presetsdir);
        foreach ($presets as $preset) {
            if (substr($preset, -5) === '.scss' && $preset !== '.' && $preset !== '..') {
                $presetname = substr($preset, 0, -5);
                $stringkey = 'preset_' . $presetname;
                
                // Check if string exists for this preset
                if (get_string_manager()->string_exists($stringkey, 'theme_ufpel')) {
                    $choices[$preset] = get_string($stringkey, 'theme_ufpel');
                } else {
                    // Fallback to formatted preset name
                    $choices[$preset] = ucfirst(str_replace('_', ' ', $presetname));
                }
            }
        }
    }
    
    // Ensure default is always available
    if (!isset($choices['default.scss'])) {
        $choices['default.scss'] = get_string('preset_default', 'theme_ufpel');
    }
    
    return $choices;
}

/**
 * Post update callback.
 * Called after the theme is updated.
 *
 * @return void
 */
function theme_ufpel_post_update() {
    // Clear all caches after theme update
    theme_reset_all_caches();
    
    // Purge theme-specific caches if they exist
    try {
        cache_helper::purge_by_definition('theme_ufpel', 'courseteachers');
        cache_helper::purge_by_definition('theme_ufpel', 'themesettings');
    } catch (Exception $e) {
        // Cache definitions might not exist yet
        debugging('Cache definitions not found during theme update: ' . $e->getMessage(), DEBUG_DEVELOPER);
    }
    
    // Rebuild course cache to ensure new theme features are applied
    rebuild_course_cache(0, true);
    
    // Log the update
    debugging('UFPel theme updated successfully', DEBUG_DEVELOPER);
}

/**
 * Get icon mapping for font-awesome.
 *
 * @return array
 */
function theme_ufpel_get_fontawesome_icon_map() {
    return [
        'theme_ufpel:course' => 'fa-graduation-cap',
        'theme_ufpel:teacher' => 'fa-user-tie',
        'theme_ufpel:progress' => 'fa-chart-line',
        'theme_ufpel:calendar' => 'fa-calendar-alt',
        'theme_ufpel:notification' => 'fa-bell',
        'theme_ufpel:settings' => 'fa-cog',
        'theme_ufpel:help' => 'fa-question-circle',
        'theme_ufpel:expand' => 'fa-expand',
        'theme_ufpel:collapse' => 'fa-compress',
        'theme_ufpel:menu' => 'fa-bars',
        'theme_ufpel:close' => 'fa-times',
        'theme_ufpel:search' => 'fa-search',
        'theme_ufpel:filter' => 'fa-filter',
        'theme_ufpel:sort' => 'fa-sort',
        'theme_ufpel:edit' => 'fa-edit',
        'theme_ufpel:delete' => 'fa-trash',
        'theme_ufpel:add' => 'fa-plus',
        'theme_ufpel:remove' => 'fa-minus',
        'theme_ufpel:check' => 'fa-check',
        'theme_ufpel:warning' => 'fa-exclamation-triangle',
        'theme_ufpel:info' => 'fa-info-circle',
        'theme_ufpel:success' => 'fa-check-circle',
        'theme_ufpel:error' => 'fa-times-circle',
    ];
}

/**
 * Add body classes.
 * This function is called early in the page setup, before output starts.
 *
 * @param moodle_page $page The page object.
 * @return void
 */
function theme_ufpel_add_body_classes($page) {
    $bodyattributes = [];
    
    // Check dark mode
    if (theme_ufpel_should_use_dark_mode()) {
        $bodyattributes[] = 'ufpel-dark-mode';
    }
    
    // Check compact view
    if (get_user_preferences('theme_ufpel_compactview', false)) {
        $bodyattributes[] = 'ufpel-compact-view';
    }
    
    // Device type detection
    $devicetype = \core_useragent::get_device_type();
    switch ($devicetype) {
        case \core_useragent::DEVICETYPE_MOBILE:
            $bodyattributes[] = 'ufpel-mobile';
            break;
        case \core_useragent::DEVICETYPE_TABLET:
            $bodyattributes[] = 'ufpel-tablet';
            break;
        default:
            $bodyattributes[] = 'ufpel-desktop';
            break;
    }
    
    // Apply body attributes
    foreach ($bodyattributes as $class) {
        $page->add_body_class($class);
    }
}

/**
 * Initialize page requirements for the theme.
 * This function initializes JavaScript but does NOT add body classes.
 *
 * @param moodle_page $page The page object.
 * @return void
 */
function theme_ufpel_page_init(moodle_page $page) {
    global $CFG;
    
    // Initialize AMD modules only if they exist
    if (file_exists($CFG->dirroot . '/theme/ufpel/amd/build/theme.min.js') ||
        file_exists($CFG->dirroot . '/theme/ufpel/amd/src/theme.js')) {
        
        $page->requires->js_call_amd('theme_ufpel/theme', 'init', [
            [
                'enableDarkMode' => get_config('theme_ufpel', 'enabledarkmode'),
                'enableCompactView' => get_config('theme_ufpel', 'enablecompactview'),
                'enableLazyLoad' => true,
                'enableStickyHeader' => true,
                'enableScrollTop' => true,
                'scrollTopOffset' => 300
            ]
        ]);
    }
    
    // Add strings for JavaScript - only if component exists
    if (get_string_manager()->string_exists('darkmodeon', 'theme_ufpel')) {
        $page->requires->strings_for_js([
            'darkmodeon',
            'darkmodeoff',
            'totop',
            'skipmain',
            'loading',
            'error',
            'close',
        ], 'theme_ufpel');
    }
}

/**
 * Check if dark mode should be used.
 *
 * @return bool
 */
function theme_ufpel_should_use_dark_mode() {
    // Check user preference first
    $userpref = get_user_preferences('theme_ufpel_darkmode', null);
    if ($userpref !== null) {
        return (bool)$userpref;
    }
    
    // Check system setting
    return (bool)get_config('theme_ufpel', 'enabledarkmode');
}

/**
 * Get course header data for AJAX.
 * Used by the JavaScript module to refresh course header.
 *
 * @param int $courseid The course ID.
 * @return array Course header data.
 */
function theme_ufpel_get_course_header_data($courseid) {
    global $DB, $PAGE, $CFG;
    
    $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
    $PAGE->set_course($course);
    
    // Check if course_header class exists
    if (file_exists($CFG->dirroot . '/theme/ufpel/classes/output/course_header.php')) {
        require_once($CFG->dirroot . '/theme/ufpel/classes/output/course_header.php');
        
        if (class_exists('\theme_ufpel\output\course_header')) {
            $courseheader = new \theme_ufpel\output\course_header($course, $PAGE);
            $renderer = $PAGE->get_renderer('core');
            return $courseheader->export_for_template($renderer);
        }
    }
    
    // Fallback data if class doesn't exist
    return [
        'courseid' => $courseid,
        'coursename' => format_string($course->fullname),
        'courseshortname' => format_string($course->shortname),
        'showcourseheader' => false
    ];
}

/**
 * Get critical CSS for inline inclusion.
 *
 * @return string
 */
function theme_ufpel_get_critical_css() {
    global $CFG;
    
    // Check if helper class exists
    if (file_exists($CFG->dirroot . '/theme/ufpel/classes/helper.php')) {
        require_once($CFG->dirroot . '/theme/ufpel/classes/helper.php');
        
        if (class_exists('\theme_ufpel\helper') && method_exists('\theme_ufpel\helper', 'get_theme_settings')) {
            $settings = \theme_ufpel\helper::get_theme_settings();
        } else {
            // Fallback: get settings directly
            $settings = new stdClass();
            $settings->primarycolor = get_config('theme_ufpel', 'primarycolor') ?: '#003366';
            $settings->secondarycolor = get_config('theme_ufpel', 'secondarycolor') ?: '#0066cc';
            $settings->backgroundcolor = get_config('theme_ufpel', 'backgroundcolor') ?: '#ffffff';
            $settings->highlightcolor = get_config('theme_ufpel', 'highlightcolor') ?: '#ffc107';
            $settings->contenttextcolor = get_config('theme_ufpel', 'contenttextcolor') ?: '#212529';
            $settings->highlighttextcolor = get_config('theme_ufpel', 'highlighttextcolor') ?: '#ffffff';
        }
    } else {
        // Fallback: use default settings
        $settings = new stdClass();
        $settings->primarycolor = '#003366';
        $settings->secondarycolor = '#0066cc';
        $settings->backgroundcolor = '#ffffff';
        $settings->highlightcolor = '#ffc107';
        $settings->contenttextcolor = '#212529';
        $settings->highlighttextcolor = '#ffffff';
    }
    
    $css = "
    :root {
        --ufpel-primary: {$settings->primarycolor};
        --ufpel-secondary: {$settings->secondarycolor};
        --ufpel-background: {$settings->backgroundcolor};
        --ufpel-highlight: {$settings->highlightcolor};
        --ufpel-text: {$settings->contenttextcolor};
        --ufpel-text-highlight: {$settings->highlighttextcolor};
    }
    
    body {
        background-color: var(--ufpel-background);
        color: var(--ufpel-text);
    }
    
    .navbar {
        background-color: var(--ufpel-primary) !important;
    }
    
    a {
        color: var(--ufpel-secondary);
    }
    
    .btn-primary {
        background-color: var(--ufpel-primary);
        border-color: var(--ufpel-primary);
    }
    
    .visually-hidden {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0,0,0,0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }
    
    .visually-hidden-focusable:not(:focus):not(:focus-within) {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0,0,0,0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }
    ";
    
    // Minify CSS
    $css = preg_replace('/\s+/', ' ', trim($css));
    $css = str_replace(': ', ':', $css);
    $css = str_replace('; ', ';', $css);
    $css = str_replace(' {', '{', $css);
    $css = str_replace('} ', '}', $css);
    
    return $css;
}

// NOTE: The function theme_ufpel_before_standard_html_head has been removed
// as it's deprecated in Moodle 5.x. The functionality is now handled 
// by the hooks system in classes/hooks/output_callbacks.php

/**
 * Process a theme file URL to ensure it returns a proper moodle_url object
 * without duplication issues.
 * FIXED: This function prevents URL duplication problems.
 *
 * @param mixed $url The URL to process (can be string or moodle_url)
 * @return moodle_url|null The processed moodle_url object or null if empty
 */
function theme_ufpel_process_theme_file_url($url) {
    global $CFG;
    
    if (empty($url)) {
        return null;
    }
    
    // If it's already a moodle_url object, return it
    if ($url instanceof moodle_url) {
        return $url;
    }
    
    // Convert to string for processing
    $urlstr = (string)$url;
    
    if (empty($urlstr)) {
        return null;
    }
    
    // Parse the URL to check its structure
    $parsed = parse_url($urlstr);
    
    // If the URL has a scheme (http/https), it's absolute
    if (!empty($parsed['scheme'])) {
        // Extract the path and query components
        $path = $parsed['path'] ?? '';
        if (!empty($parsed['query'])) {
            $path .= '?' . $parsed['query'];
        }
        if (!empty($parsed['fragment'])) {
            $path .= '#' . $parsed['fragment'];
        }
        
        // Check if the path contains the wwwroot path
        $wwwroot_parsed = parse_url($CFG->wwwroot);
        $wwwroot_path = $wwwroot_parsed['path'] ?? '';
        
        if (!empty($wwwroot_path) && strpos($path, $wwwroot_path) === 0) {
            // Remove the wwwroot path to make it relative
            $relative_path = substr($path, strlen($wwwroot_path));
            // Ensure it starts with /
            if (strpos($relative_path, '/') !== 0) {
                $relative_path = '/' . $relative_path;
            }
            return new moodle_url($relative_path);
        } else {
            // Use the path as is
            return new moodle_url($path);
        }
    } else {
        // It's a relative URL, safe to use with moodle_url constructor
        return new moodle_url($urlstr);
    }
}

/**
 * Safely convert a theme file URL to a string without duplication.
 * FIXED: This function ensures URLs are not duplicated when converted to strings.
 *
 * @param mixed $url The URL to convert (can be string, moodle_url, or null)
 * @return string|null The URL string or null if empty
 */
function theme_ufpel_url_to_string($url) {
    if (empty($url)) {
        return null;
    }
    
    // If it's a moodle_url object, get its string representation
    if ($url instanceof moodle_url) {
        return $url->out(false);
    }
    
    // If it's already a string, process it to avoid duplication
    $urlstr = (string)$url;
    
    if (empty($urlstr)) {
        return null;
    }
    
    // Check for obvious duplication patterns
    global $CFG;
    $wwwroot = rtrim($CFG->wwwroot, '/');
    
    // Pattern: http://domain//domain/path or http://domain/domain/path
    if (preg_match('#^(https?://[^/]+)/+\1#i', $urlstr)) {
        // URL is duplicated, extract the correct part
        $parsed = parse_url($urlstr);
        if ($parsed && isset($parsed['scheme']) && isset($parsed['host'])) {
            $base = $parsed['scheme'] . '://' . $parsed['host'];
            $path = $parsed['path'] ?? '';
            
            // Remove duplicated host from path
            $path = preg_replace('#^/+' . preg_quote($parsed['host'], '#') . '#', '', $path);
            
            $urlstr = $base . $path;
            if (!empty($parsed['query'])) {
                $urlstr .= '?' . $parsed['query'];
            }
            if (!empty($parsed['fragment'])) {
                $urlstr .= '#' . $parsed['fragment'];
            }
        }
    }
    
    // Check if the URL contains the wwwroot twice
    $wwwroot_escaped = preg_quote($wwwroot, '#');
    if (preg_match('#' . $wwwroot_escaped . '/+' . $wwwroot_escaped . '#', $urlstr)) {
        // Remove the duplicate
        $urlstr = preg_replace('#' . $wwwroot_escaped . '/+' . $wwwroot_escaped . '#', $wwwroot, $urlstr);
    }
    
    return $urlstr;
}

/**
 * Validate and clean a file URL to ensure it's properly formatted.
 * This function also checks for common issues like URL duplication.
 *
 * @param string $url The URL to validate
 * @return string|false The cleaned URL or false if invalid
 */
function theme_ufpel_validate_file_url($url) {
    if (empty($url)) {
        return false;
    }
    
    // Convert to string if needed
    if (is_object($url) && method_exists($url, '__toString')) {
        $url = (string)$url;
    }
    
    if (!is_string($url)) {
        return false;
    }
    
    // Check for URL duplication
    // This pattern catches URLs like: http://domain//domain/path
    if (preg_match('#^(https?://[^/]+)/+\1#i', $url)) {
        // URL is duplicated, try to fix it
        debugging('Duplicated URL detected in theme_ufpel: ' . $url, DEBUG_DEVELOPER);
        
        // Extract the duplicated part and remove it
        $parsed = parse_url($url);
        if ($parsed && isset($parsed['scheme']) && isset($parsed['host'])) {
            $base = $parsed['scheme'] . '://' . $parsed['host'];
            $path = isset($parsed['path']) ? $parsed['path'] : '';
            
            // Remove the duplicated base from the path if it exists
            $path = preg_replace('#^/+' . preg_quote($parsed['host'], '#') . '#', '', $path);
            
            // Reconstruct the URL
            $url = $base . $path;
            if (isset($parsed['query'])) {
                $url .= '?' . $parsed['query'];
            }
            if (isset($parsed['fragment'])) {
                $url .= '#' . $parsed['fragment'];
            }
        }
    }
    
    // Additional validation
    $parsed = parse_url($url);
    if (!$parsed || !isset($parsed['path'])) {
        return false;
    }
    
    // Check for double slashes in the path (except at the beginning)
    $path = $parsed['path'];
    $path = preg_replace('#/{2,}#', '/', $path);
    
    // Rebuild the URL with the cleaned path
    $cleanurl = '';
    if (isset($parsed['scheme'])) {
        $cleanurl .= $parsed['scheme'] . '://';
    }
    if (isset($parsed['host'])) {
        $cleanurl .= $parsed['host'];
    }
    if (isset($parsed['port'])) {
        $cleanurl .= ':' . $parsed['port'];
    }
    $cleanurl .= $path;
    if (isset($parsed['query'])) {
        $cleanurl .= '?' . $parsed['query'];
    }
    if (isset($parsed['fragment'])) {
        $cleanurl .= '#' . $parsed['fragment'];
    }
    
    return $cleanurl;
}