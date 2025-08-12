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
 * Helper functions for theme_ufpel.
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_ufpel;

use context_course;
use moodle_url;
use cache;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper class for theme_ufpel.
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {
    
    /**
     * Get theme settings with caching.
     *
     * @return \stdClass Theme settings object.
     */
    public static function get_theme_settings() {
        $cache = cache::make('theme_ufpel', 'themesettings');
        $settings = $cache->get('settings');
        
        if ($settings === false) {
            $settings = new \stdClass();
            
            // Get all theme settings.
            $settings->primarycolor = get_config('theme_ufpel', 'primarycolor');
            $settings->secondarycolor = get_config('theme_ufpel', 'secondarycolor');
            $settings->backgroundcolor = get_config('theme_ufpel', 'backgroundcolor');
            $settings->highlightcolor = get_config('theme_ufpel', 'highlightcolor');
            $settings->contenttextcolor = get_config('theme_ufpel', 'contenttextcolor');
            $settings->highlighttextcolor = get_config('theme_ufpel', 'highlighttextcolor');
            $settings->showcourseimage = get_config('theme_ufpel', 'showcourseimage');
            $settings->showteachers = get_config('theme_ufpel', 'showteachers');
            $settings->courseheaderoverlay = get_config('theme_ufpel', 'courseheaderoverlay');
            $settings->footercontent = get_config('theme_ufpel', 'footercontent');
            $settings->customfonts = get_config('theme_ufpel', 'customfonts');
            
            // Process and validate settings.
            $settings = self::validate_settings($settings);
            
            // Cache the settings.
            $cache->set('settings', $settings);
        }
        
        return $settings;
    }
    
    /**
     * Validate and process theme settings.
     *
     * @param \stdClass $settings Raw settings object.
     * @return \stdClass Validated settings object.
     */
    protected static function validate_settings($settings) {
        // Validate colors.
        if (empty($settings->primarycolor) || !preg_match('/^#[a-f0-9]{6}$/i', $settings->primarycolor)) {
            $settings->primarycolor = '#003366';
        }
        
        if (empty($settings->secondarycolor) || !preg_match('/^#[a-f0-9]{6}$/i', $settings->secondarycolor)) {
            $settings->secondarycolor = '#0066cc';
        }
        
        if (empty($settings->backgroundcolor) || !preg_match('/^#[a-f0-9]{6}$/i', $settings->backgroundcolor)) {
            $settings->backgroundcolor = '#ffffff';
        }
        
        if (empty($settings->highlightcolor) || !preg_match('/^#[a-f0-9]{6}$/i', $settings->highlightcolor)) {
            $settings->highlightcolor = '#ffc107';
        }
        
        if (empty($settings->contenttextcolor) || !preg_match('/^#[a-f0-9]{6}$/i', $settings->contenttextcolor)) {
            $settings->contenttextcolor = '#212529';
        }
        
        if (empty($settings->highlighttextcolor) || !preg_match('/^#[a-f0-9]{6}$/i', $settings->highlighttextcolor)) {
            $settings->highlighttextcolor = '#ffffff';
        }
        
        // Ensure boolean values.
        $settings->showcourseimage = !empty($settings->showcourseimage);
        $settings->showteachers = !empty($settings->showteachers);
        $settings->courseheaderoverlay = !empty($settings->courseheaderoverlay);
        
        // Clean HTML content.
        if (!empty($settings->footercontent)) {
            $settings->footercontent = clean_text($settings->footercontent, FORMAT_HTML);
        }
        
        return $settings;
    }
    
    /**
     * Get course image URL with fallback.
     *
     * @param int $courseid Course ID.
     * @param string $filearea File area to check (default: overviewfiles).
     * @return string|null Image URL or null if not found.
     */
    public static function get_course_image_url($courseid, $filearea = 'overviewfiles') {
        if (empty($courseid) || $courseid == SITEID) {
            return null;
        }
        
        $context = context_course::instance($courseid);
        $fs = get_file_storage();
        
        // Try to get course image.
        $files = $fs->get_area_files($context->id, 'course', $filearea, 0, 'filename', false);
        
        if ($files) {
            foreach ($files as $file) {
                if ($file->is_valid_image()) {
                    return moodle_url::make_pluginfile_url(
                        $context->id,
                        'course',
                        $filearea,
                        0,
                        '/',
                        $file->get_filename()
                    )->out();
                }
            }
        }
        
        // Try legacy course image location.
        if ($filearea === 'overviewfiles') {
            return self::get_course_image_url($courseid, 'images');
        }
        
        return null;
    }
    
    /**
     * Get a list of available presets.
     *
     * @return array Array of preset files.
     */
    public static function get_available_presets() {
        global $CFG;
        
        $presets = [];
        $presetsdir = $CFG->dirroot . '/theme/ufpel/scss/preset/';
        
        if (is_dir($presetsdir) && is_readable($presetsdir)) {
            $files = scandir($presetsdir);
            foreach ($files as $file) {
                if (preg_match('/^[a-z0-9_-]+\.scss$/i', $file)) {
                    $presets[] = $file;
                }
            }
        }
        
        // Always include default.
        if (!in_array('default.scss', $presets)) {
            array_unshift($presets, 'default.scss');
        }
        
        return $presets;
    }
    
    /**
     * Generate CSS variables from theme settings.
     *
     * @return string CSS variables.
     */
    public static function get_css_variables() {
        $settings = self::get_theme_settings();
        $css = ":root {\n";
        
        // Color variables.
        $css .= "    --ufpel-primary: {$settings->primarycolor};\n";
        $css .= "    --ufpel-secondary: {$settings->secondarycolor};\n";
        $css .= "    --ufpel-background: {$settings->backgroundcolor};\n";
        $css .= "    --ufpel-highlight: {$settings->highlightcolor};\n";
        $css .= "    --ufpel-text: {$settings->contenttextcolor};\n";
        $css .= "    --ufpel-text-highlight: {$settings->highlighttextcolor};\n";
        
        // Generate color variations.
        $css .= "    --ufpel-primary-light: " . self::lighten_color($settings->primarycolor, 20) . ";\n";
        $css .= "    --ufpel-primary-dark: " . self::darken_color($settings->primarycolor, 20) . ";\n";
        $css .= "    --ufpel-secondary-light: " . self::lighten_color($settings->secondarycolor, 20) . ";\n";
        $css .= "    --ufpel-secondary-dark: " . self::darken_color($settings->secondarycolor, 20) . ";\n";
        
        $css .= "}\n";
        
        return $css;
    }
    
    /**
     * Lighten a hex color.
     *
     * @param string $hex Hex color code.
     * @param int $percent Percentage to lighten (0-100).
     * @return string Lightened hex color.
     */
    public static function lighten_color($hex, $percent) {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        $r = min(255, $r + ($r * $percent / 100));
        $g = min(255, $g + ($g * $percent / 100));
        $b = min(255, $b + ($b * $percent / 100));
        
        return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
                  . str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
                  . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    }
    
    /**
     * Darken a hex color.
     *
     * @param string $hex Hex color code.
     * @param int $percent Percentage to darken (0-100).
     * @return string Darkened hex color.
     */
    public static function darken_color($hex, $percent) {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        $r = max(0, $r - ($r * $percent / 100));
        $g = max(0, $g - ($g * $percent / 100));
        $b = max(0, $b - ($b * $percent / 100));
        
        return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
                  . str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
                  . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    }
    
    /**
     * Check if the current page should use dark mode.
     *
     * @return bool True if dark mode should be used.
     */
    public static function should_use_dark_mode() {
        // Check user preference.
        $userpref = get_user_preferences('theme_ufpel_darkmode', null);
        if ($userpref !== null) {
            return (bool)$userpref;
        }
        
        // Check system setting.
        $systemdark = get_config('theme_ufpel', 'enabledarkmode');
        return !empty($systemdark);
    }
    
    /**
     * Get optimized inline CSS for critical rendering path.
     *
     * @return string Critical CSS.
     */
    public static function get_critical_css() {
        $settings = self::get_theme_settings();
        
        $css = "
        /* Critical CSS for UFPel Theme */
        :root {
            --ufpel-primary: {$settings->primarycolor};
            --ufpel-secondary: {$settings->secondarycolor};
            --ufpel-background: {$settings->backgroundcolor};
            --ufpel-highlight: {$settings->highlightcolor};
            --ufpel-text: {$settings->contenttextcolor};
            --ufpel-text-highlight: {$settings->highlighttextcolor};
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
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
        ";
        
        return preg_replace('/\s+/', ' ', trim($css));
    }
}