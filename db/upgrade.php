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
 * Theme UFPel upgrade script - Updated for Moodle 5.x with hooks migration.
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade function for theme_ufpel.
 *
 * @param int $oldversion The version we are upgrading from.
 * @return bool Result of upgrade.
 */
function xmldb_theme_ufpel_upgrade($oldversion) {
    global $DB, $CFG;
    
    $dbman = $DB->get_manager();
    
    // Version 2025072901 - Migrate brandcolor to primarycolor
    if ($oldversion < 2025072901) {
        upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Migrating brand color to primary color');
        
        // Migrate brandcolor setting to primarycolor if it exists
        $brandcolor = get_config('theme_ufpel', 'brandcolor');
        if ($brandcolor !== false && get_config('theme_ufpel', 'primarycolor') === false) {
            set_config('primarycolor', $brandcolor, 'theme_ufpel');
            upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Brand color migrated to primary color: ' . $brandcolor);
        }
        
        // Set default values for new color settings if not already set
        $colorsettings = [
            'backgroundcolor' => '#ffffff',
            'highlightcolor' => '#ffc107',
            'contenttextcolor' => '#212529',
            'highlighttextcolor' => '#ffffff'
        ];
        
        foreach ($colorsettings as $setting => $default) {
            if (get_config('theme_ufpel', $setting) === false) {
                set_config($setting, $default, 'theme_ufpel');
                upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', "Set default {$setting}: {$default}");
            }
        }
        
        // Clear all theme caches to ensure new styles are loaded
        theme_reset_all_caches();
        
        upgrade_plugin_savepoint(true, 2025072901, 'theme', 'ufpel');
    }
    
    // Version 2025080100 - Migrate to Bootstrap 5 classes
    if ($oldversion < 2025080100) {
        upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Migrating to Bootstrap 5');
        
        // Update any stored HTML content with old Bootstrap classes
        // This would typically update content in settings that contain HTML
        $htmlsettings = ['footercontent', 'customhtml'];
        
        foreach ($htmlsettings as $setting) {
            $content = get_config('theme_ufpel', $setting);
            if ($content !== false) {
                // Replace Bootstrap 4 classes with Bootstrap 5
                $replacements = [
                    'ml-' => 'ms-',
                    'mr-' => 'me-',
                    'pl-' => 'ps-',
                    'pr-' => 'pe-',
                    'text-left' => 'text-start',
                    'text-right' => 'text-end',
                    'float-left' => 'float-start',
                    'float-right' => 'float-end',
                    'sr-only' => 'visually-hidden',
                    'sr-only-focusable' => 'visually-hidden-focusable',
                    'badge-pill' => 'rounded-pill',
                    'badge-' => 'bg-',
                    'close' => 'btn-close',
                    'custom-control' => 'form-check',
                    'custom-checkbox' => 'form-check',
                    'custom-control-input' => 'form-check-input',
                    'custom-control-label' => 'form-check-label',
                    'custom-switch' => 'form-switch',
                    'custom-select' => 'form-select',
                    'custom-file' => 'd-none',
                    'form-control-file' => 'form-control',
                    'input-group-append' => 'input-group-text',
                    'input-group-prepend' => 'input-group-text',
                ];
                
                foreach ($replacements as $old => $new) {
                    $content = str_replace($old, $new, $content);
                }
                
                set_config($setting, $content, 'theme_ufpel');
                upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', "Updated Bootstrap classes in {$setting}");
            }
        }
        
        // Clear theme caches
        theme_reset_all_caches();
        
        upgrade_plugin_savepoint(true, 2025080100, 'theme', 'ufpel');
    }
    
    // Version 2025090100 - Add new features for Moodle 5.x
    if ($oldversion < 2025090100) {
        upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Adding Moodle 5.x features');
        
        // Add new settings for Moodle 5.x features
        $newsettings = [
            'enabledarkmode' => '0',
            'enablecompactview' => '0',
            'showcourseprogressinheader' => '1',
            'showcoursesummary' => '1',
            'enablelazyloading' => '1',
            'enableanimations' => '1',
            'enableaccessibilitytools' => '1',
        ];
        
        foreach ($newsettings as $setting => $default) {
            if (get_config('theme_ufpel', $setting) === false) {
                set_config($setting, $default, 'theme_ufpel');
                upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', "Added setting {$setting}: {$default}");
            }
        }
        
        // Purge caches with new definitions
        cache_helper::purge_by_definition('theme_ufpel', 'courseteachers');
        cache_helper::purge_by_definition('theme_ufpel', 'themesettings');
        
        upgrade_plugin_savepoint(true, 2025090100, 'theme', 'ufpel');
    }
    
    // Version 2025100100 - Performance optimizations
    if ($oldversion < 2025100100) {
        upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Applying performance optimizations');
        
        // Enable CSS optimization by default
        if (get_config('theme_ufpel', 'enablecssoptimization') === false) {
            set_config('enablecssoptimization', '1', 'theme_ufpel');
        }
        
        // Enable resource hints
        if (get_config('theme_ufpel', 'enableresourcehints') === false) {
            set_config('enableresourcehints', '1', 'theme_ufpel');
        }
        
        // Clear all caches and rebuild
        theme_reset_all_caches();
        cache_helper::purge_all();
        
        // Rebuild course cache to apply new features
        rebuild_course_cache(0, true);
        
        upgrade_plugin_savepoint(true, 2025100100, 'theme', 'ufpel');
    }
    
    // Version 2025110100 - Clean up deprecated settings
    if ($oldversion < 2025110100) {
        upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Cleaning up deprecated settings');
        
        // Remove deprecated settings
        $deprecated = [
            'brandcolor',  // Migrated to primarycolor
            'oldsettingname',  // Example deprecated setting
        ];
        
        foreach ($deprecated as $setting) {
            if (get_config('theme_ufpel', $setting) !== false) {
                unset_config($setting, 'theme_ufpel');
                upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', "Removed deprecated setting: {$setting}");
            }
        }
        
        // Final cache clear
        theme_reset_all_caches();
        
        upgrade_plugin_savepoint(true, 2025110100, 'theme', 'ufpel');
    }
    
    // Version 2025120100 - Migrate to new hooks system
    if ($oldversion < 2025120100) {
        upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Migrating to new hooks system');
        
        // Clear all caches to ensure new hooks are registered
        cache_helper::purge_all();
        theme_reset_all_caches();
        
        // The actual migration is handled by the new files
        // Old callbacks will still work but show deprecation notices
        
        upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Hooks system migration completed');
        
        upgrade_plugin_savepoint(true, 2025120100, 'theme', 'ufpel');
    }
    
    // Version 2025120102 - Remove deprecated callbacks completely
    if ($oldversion < 2025120102) {
        upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Removing deprecated callback functions');
        
        // Clear all caches to ensure the new system is used
        purge_all_caches();
        
        // Clear compiled mustache templates
        $cachedir = $CFG->dataroot . '/localcache/mustache';
        if (is_dir($cachedir)) {
            $ufpeldir = $cachedir . '/-1/ufpel';
            if (is_dir($ufpeldir)) {
                remove_dir($ufpeldir, true);
                upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Cleared compiled mustache templates');
            }
        }
        
        // Rebuild theme cache
        theme_reset_all_caches();
        
        // Note: The deprecated functions have been removed from lib.php
        // The new hooks system in classes/hooks/output_callbacks.php handles all functionality
        
        upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Deprecated callbacks removed, using hooks system exclusively');
        
        upgrade_plugin_savepoint(true, 2025120102, 'theme', 'ufpel');
    }
    
    // Always clear theme caches at the end of upgrade
    theme_reset_all_caches();
    
    // Log successful upgrade
    upgrade_log(UPGRADE_LOG_NORMAL, 'theme_ufpel', 'Theme UFPel upgrade completed successfully');
    
    return true;
}

/**
 * Helper function to log upgrade messages.
 *
 * @param int $level Log level
 * @param string $component Component name
 * @param string $message Log message
 */
function upgrade_log($level, $component, $message) {
    if (defined('UPGRADE_LOG_NORMAL')) {
        mtrace("{$component}: {$message}");
    }
}