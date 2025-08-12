<?php
/**
 * Fix login page issues for UFPel theme
 * 
 * Run from Moodle root: php theme/ufpel/cli/fix_login.php
 */

define('CLI_SCRIPT', true);
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');

// Get cli options
list($options, $unrecognized) = cli_get_params(
    array(
        'help' => false,
        'check' => false,
        'fix' => false,
        'verbose' => false,
    ),
    array(
        'h' => 'help',
        'c' => 'check',
        'f' => 'fix',
        'v' => 'verbose'
    )
);

if ($options['help']) {
    $help = "
UFPel Theme Login Fix Script

This script diagnoses and fixes login page issues.

Options:
  -h, --help       Print this help
  -c, --check      Check for issues only (don't fix)
  -f, --fix        Apply fixes automatically
  -v, --verbose    Show detailed output

Example:
    php theme/ufpel/cli/fix_login.php --check
    php theme/ufpel/cli/fix_login.php --fix --verbose
";
    echo $help;
    exit(0);
}

$verbose = $options['verbose'];
$checkonly = $options['check'];
$fix = $options['fix'] || (!$checkonly);

cli_heading('UFPel Theme Login Page Fix');
cli_writeln('');

$issues = 0;
$fixed = 0;

// Step 1: Check login layout file
cli_writeln('Step 1: Checking login layout file...');
$loginlayout = $CFG->dirroot . '/theme/ufpel/layout/login.php';

if (!file_exists($loginlayout)) {
    cli_problem('  ✗ login.php layout file not found');
    $issues++;
    
    if ($fix) {
        // Create a simple login layout
        $content = '<?php
defined(\'MOODLE_INTERNAL\') || die();

// Use parent theme login layout
$parentlayout = $CFG->dirroot . \'/theme/boost/layout/login.php\';
if (file_exists($parentlayout)) {
    include($parentlayout);
} else {
    echo $OUTPUT->doctype();
    echo html_writer::start_tag(\'html\', $OUTPUT->htmlattributes());
    echo html_writer::start_tag(\'head\');
    echo html_writer::tag(\'title\', get_string(\'login\'));
    echo $OUTPUT->standard_head_html();
    echo html_writer::end_tag(\'head\');
    echo html_writer::tag(\'body\', $OUTPUT->main_content(), $OUTPUT->body_attributes());
    echo html_writer::end_tag(\'html\');
}';
        
        if (file_put_contents($loginlayout, $content)) {
            cli_writeln('  ✓ Created login.php layout file');
            $fixed++;
        } else {
            cli_error_text('  ✗ Failed to create login.php');
        }
    }
} else {
    if ($verbose) {
        cli_writeln('  ✓ login.php exists');
    }
    
    // Check if it has errors
    $content = file_get_contents($loginlayout);
    
    // Check for problematic renderer calls
    if (strpos($content, '->login_background()') !== false) {
        cli_problem('  ⚠ login.php uses login_background() method');
        $issues++;
        
        if ($fix) {
            // Remove the problematic call
            $content = str_replace('$renderer->login_background()', '\'\'', $content);
            $content = str_replace('$OUTPUT->login_background()', '\'\'', $content);
            
            if (file_put_contents($loginlayout, $content)) {
                cli_writeln('  ✓ Fixed login_background() call');
                $fixed++;
            }
        }
    }
}

// Step 2: Check login template
cli_writeln('');
cli_writeln('Step 2: Checking login template...');
$logintemplate = $CFG->dirroot . '/theme/ufpel/templates/login.mustache';

if (file_exists($logintemplate)) {
    $content = file_get_contents($logintemplate);
    
    // Check for problematic strings
    $problematic = [
        'login_signup_url' => 'output.login_signup_url',
        'page_doc_link' => 'output.page_doc_link',
        'login_background' => 'loginbackground'
    ];
    
    foreach ($problematic as $problem => $search) {
        if (strpos($content, $search) !== false) {
            cli_problem("  ⚠ Template uses potentially undefined: $search");
            $issues++;
        }
    }
    
    if ($issues > 0 && $fix) {
        // Use simple template
        $simpletemplate = $CFG->dirroot . '/theme/ufpel/templates/login_simple.mustache';
        if (file_exists($simpletemplate)) {
            copy($logintemplate, $logintemplate . '.backup');
            copy($simpletemplate, $logintemplate);
            cli_writeln('  ✓ Replaced with simple login template');
            $fixed++;
        }
    }
} else {
    cli_problem('  ✗ login.mustache not found');
    $issues++;
}

// Step 3: Clear template cache
cli_writeln('');
cli_writeln('Step 3: Clearing template cache...');
$cachedir = $CFG->dataroot . '/localcache/mustache/-1/ufpel';

if (is_dir($cachedir)) {
    $files = glob($cachedir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    rmdir($cachedir);
    cli_writeln('  ✓ Template cache cleared');
    $fixed++;
} else {
    if ($verbose) {
        cli_writeln('  - No template cache to clear');
    }
}

// Step 4: Check config.php for login layout
cli_writeln('');
cli_writeln('Step 4: Checking theme configuration...');
$configfile = $CFG->dirroot . '/theme/ufpel/config.php';

if (file_exists($configfile)) {
    $content = file_get_contents($configfile);
    
    // Check if login layout is defined
    if (strpos($content, "'login'") === false && strpos($content, '"login"') === false) {
        cli_problem('  ⚠ Login layout might not be configured');
        $issues++;
        
        if ($fix) {
            cli_writeln('  ℹ Login layout will use parent theme (Boost)');
        }
    } else {
        if ($verbose) {
            cli_writeln('  ✓ Login layout is configured');
        }
    }
}

// Step 5: Test login page access
cli_writeln('');
cli_writeln('Step 5: Testing login page access...');

// Try to load theme config
try {
    $theme = theme_config::load('ufpel');
    
    // Check if login layout exists
    if (isset($theme->layouts['login'])) {
        if ($verbose) {
            cli_writeln('  ✓ Login layout is defined in theme');
        }
    } else {
        // Use parent theme layout
        if ($verbose) {
            cli_writeln('  ℹ Using parent theme login layout');
        }
    }
} catch (Exception $e) {
    cli_error_text('  ✗ Error loading theme: ' . $e->getMessage());
    $issues++;
}

// Step 6: Final cache purge
if ($fix && $fixed > 0) {
    cli_writeln('');
    cli_writeln('Step 6: Purging all caches...');
    purge_all_caches();
    cli_writeln('  ✓ All caches purged');
}

// Summary
cli_writeln('');
cli_separator();
cli_heading('Summary');
cli_writeln('');

if ($checkonly) {
    cli_writeln("Issues found: $issues");
    if ($issues > 0) {
        cli_writeln('');
        cli_writeln('Run with --fix to apply corrections automatically');
    }
} else {
    cli_writeln("Issues found: $issues");
    cli_writeln("Issues fixed: $fixed");
    
    if ($issues > $fixed) {
        cli_writeln('');
        cli_problem('Some issues could not be fixed automatically.');
        cli_writeln('');
        cli_writeln('Manual steps required:');
        cli_writeln('1. Check the layout/login.php file for syntax errors');
        cli_writeln('2. Verify templates/login.mustache is valid');
        cli_writeln('3. Clear browser cache and cookies');
        cli_writeln('4. Try accessing: ' . $CFG->wwwroot . '/login/index.php');
    } else if ($fixed > 0) {
        cli_writeln('');
        cli_writeln('✓ All issues have been fixed!');
        cli_writeln('');
        cli_writeln('Next steps:');
        cli_writeln('1. Clear your browser cache');
        cli_writeln('2. Try accessing the login page');
        cli_writeln('3. If issues persist, restart your web server');
    } else {
        cli_writeln('');
        cli_writeln('✓ No issues found!');
    }
}

cli_writeln('');

/**
 * Helper function to display error text in red
 */
function cli_error_text($text) {
    if (defined('STDOUT') && function_exists('posix_isatty') && posix_isatty(STDOUT)) {
        echo "\033[31m" . $text . "\033[0m\n";
    } else {
        cli_writeln($text);
    }
}

exit($issues > $fixed ? 1 : 0);