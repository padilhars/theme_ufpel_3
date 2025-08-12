<?php
/**
 * Purge UFPel theme hooks and cache
 * 
 * Run from Moodle root: php theme/ufpel/cli/purge_hooks.php
 * 
 * This script clears all theme caches and hook registrations
 * to ensure the new hooks system is properly loaded.
 */

define('CLI_SCRIPT', true);
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');

// Get cli options
list($options, $unrecognized) = cli_get_params(
    array(
        'help' => false,
        'force' => false,
        'verbose' => false,
    ),
    array(
        'h' => 'help',
        'f' => 'force',
        'v' => 'verbose'
    )
);

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help']) {
    $help = "
UFPel Theme Hooks and Cache Purge Script

This script clears all theme caches and ensures the new hooks system
is properly registered for Moodle 5.x compatibility.

Options:
  -h, --help       Print this help
  -f, --force      Force purge without confirmation
  -v, --verbose    Show detailed output

Example:
    php theme/ufpel/cli/purge_hooks.php
    php theme/ufpel/cli/purge_hooks.php --force --verbose
";
    echo $help;
    exit(0);
}

$verbose = $options['verbose'];

cli_heading('UFPel Theme Hooks and Cache Purge');

if (!$options['force']) {
    $confirm = cli_input('This will clear all theme caches and rebuild hook registrations. Continue? (y/N)', 'N');
    if (strtolower($confirm) !== 'y') {
        cli_writeln('Aborted.');
        exit(0);
    }
}

cli_writeln('');
cli_writeln('Starting purge process...');
cli_writeln('');

// Step 1: Clear theme caches
cli_heading('Step 1: Clearing theme caches', 2);
theme_reset_all_caches();
if ($verbose) {
    cli_writeln('✓ Theme caches cleared');
}

// Step 2: Purge all Moodle caches
cli_heading('Step 2: Purging all Moodle caches', 2);
purge_all_caches();
if ($verbose) {
    cli_writeln('✓ All caches purged');
}

// Step 3: Clear specific UFPel theme caches
cli_heading('Step 3: Clearing UFPel specific caches', 2);
try {
    $cache = cache::make('theme_ufpel', 'courseteachers');
    $cache->purge();
    if ($verbose) {
        cli_writeln('✓ Course teachers cache cleared');
    }
} catch (Exception $e) {
    if ($verbose) {
        cli_writeln('- Course teachers cache not found (this is normal on first install)');
    }
}

try {
    $cache = cache::make('theme_ufpel', 'themesettings');
    $cache->purge();
    if ($verbose) {
        cli_writeln('✓ Theme settings cache cleared');
    }
} catch (Exception $e) {
    if ($verbose) {
        cli_writeln('- Theme settings cache not found (this is normal on first install)');
    }
}

// Step 4: Clear compiled mustache templates
cli_heading('Step 4: Clearing compiled templates', 2);
$cachedir = $CFG->dataroot . '/localcache/mustache';
if (is_dir($cachedir)) {
    // Clear UFPel templates
    $ufpeldir = $cachedir . '/-1/ufpel';
    if (is_dir($ufpeldir)) {
        remove_dir($ufpeldir, true);
        if ($verbose) {
            cli_writeln('✓ UFPel mustache templates cleared');
        }
    }
}

// Step 5: Check for deprecated functions
cli_heading('Step 5: Checking for deprecated functions', 2);
$libfile = $CFG->dirroot . '/theme/ufpel/lib.php';
if (file_exists($libfile)) {
    $content = file_get_contents($libfile);
    
    // Check for deprecated function
    if (strpos($content, 'function theme_ufpel_before_standard_html_head') !== false) {
        cli_problem('WARNING: Deprecated function "theme_ufpel_before_standard_html_head" found in lib.php');
        cli_writeln('This function should be removed as it\'s replaced by the hooks system.');
    } else {
        if ($verbose) {
            cli_writeln('✓ No deprecated functions found');
        }
    }
}

// Step 6: Verify hooks are registered
cli_heading('Step 6: Verifying hooks registration', 2);
$hooksfile = $CFG->dirroot . '/theme/ufpel/db/hooks.php';
if (file_exists($hooksfile)) {
    if ($verbose) {
        cli_writeln('✓ Hooks file exists: db/hooks.php');
    }
    
    // Check if callbacks class exists
    $callbacksfile = $CFG->dirroot . '/theme/ufpel/classes/hooks/output_callbacks.php';
    if (file_exists($callbacksfile)) {
        if ($verbose) {
            cli_writeln('✓ Callbacks class exists: classes/hooks/output_callbacks.php');
        }
    } else {
        cli_problem('ERROR: Callbacks class not found: classes/hooks/output_callbacks.php');
    }
} else {
    cli_problem('ERROR: Hooks file not found: db/hooks.php');
}

// Step 7: Check theme configuration
cli_heading('Step 7: Checking theme configuration', 2);
try {
    $theme = theme_config::load('ufpel');
    if ($verbose) {
        cli_writeln('✓ Theme loads successfully');
        cli_writeln('  Theme name: ' . $theme->name);
        cli_writeln('  Parent theme: ' . implode(', ', $theme->parents));
    }
} catch (Exception $e) {
    cli_problem('ERROR: Failed to load theme: ' . $e->getMessage());
}

// Step 8: Rebuild course cache
cli_heading('Step 8: Rebuilding course cache', 2);
rebuild_course_cache(0, true);
if ($verbose) {
    cli_writeln('✓ Course cache rebuilt');
}

// Step 9: Test critical functionality
cli_heading('Step 9: Testing critical functionality', 2);

// Test if helper class exists and works
if (file_exists($CFG->dirroot . '/theme/ufpel/classes/helper.php')) {
    require_once($CFG->dirroot . '/theme/ufpel/classes/helper.php');
    
    if (class_exists('\theme_ufpel\helper')) {
        if ($verbose) {
            cli_writeln('✓ Helper class loaded successfully');
        }
        
        // Try to get theme settings
        try {
            $settings = \theme_ufpel\helper::get_theme_settings();
            if ($verbose) {
                cli_writeln('✓ Theme settings retrieved successfully');
            }
        } catch (Exception $e) {
            cli_problem('WARNING: Failed to get theme settings: ' . $e->getMessage());
        }
    }
}

// Final summary
cli_writeln('');
cli_separator();
cli_heading('Purge Complete!');
cli_writeln('');
cli_writeln('Next steps:');
cli_writeln('1. Access your Moodle site');
cli_writeln('2. Navigate to Site administration > Appearance > Themes > Theme selector');
cli_writeln('3. Ensure UFPel theme is selected');
cli_writeln('4. Check that the theme displays correctly');
cli_writeln('');
cli_writeln('If you still see deprecation warnings:');
cli_writeln('1. Make sure you\'ve uploaded all the updated files');
cli_writeln('2. Restart your web server');
cli_writeln('3. Clear your browser cache');
cli_writeln('');

exit(0);