<?php
/**
 * Test UFPel theme hooks system
 * 
 * Run from Moodle root: php theme/ufpel/cli/test_hooks.php
 * 
 * This script tests if the hooks system is properly configured
 * and working for the UFPel theme.
 */

define('CLI_SCRIPT', true);
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');

cli_heading('UFPel Theme Hooks System Test');
cli_writeln('');

$errors = 0;
$warnings = 0;
$success = 0;

// Test 1: Check if hooks file exists
cli_writeln('Test 1: Checking hooks file...');
$hooksfile = $CFG->dirroot . '/theme/ufpel/db/hooks.php';
if (file_exists($hooksfile)) {
    cli_writeln('  ✓ hooks.php exists');
    $success++;
    
    // Load and validate hooks
    $callbacks = [];
    include($hooksfile);
    
    if (!empty($callbacks)) {
        cli_writeln('  ✓ ' . count($callbacks) . ' hooks registered');
        $success++;
        
        foreach ($callbacks as $callback) {
            if (isset($callback['hook']) && isset($callback['callback'])) {
                $hookClass = $callback['hook'];
                $callbackStr = $callback['callback'];
                
                // Check if hook class exists
                if (class_exists($hookClass)) {
                    cli_writeln('  ✓ Hook class exists: ' . $hookClass);
                    $success++;
                } else {
                    cli_error_text('  ✗ Hook class not found: ' . $hookClass);
                    $errors++;
                }
                
                // Check if callback class and method exist
                if (strpos($callbackStr, '::') !== false) {
                    list($class, $method) = explode('::', $callbackStr);
                    if (class_exists($class)) {
                        if (method_exists($class, $method)) {
                            cli_writeln('  ✓ Callback method exists: ' . $callbackStr);
                            $success++;
                        } else {
                            cli_error_text('  ✗ Callback method not found: ' . $callbackStr);
                            $errors++;
                        }
                    } else {
                        cli_error_text('  ✗ Callback class not found: ' . $class);
                        $errors++;
                    }
                }
            }
        }
    } else {
        cli_error_text('  ✗ No hooks registered');
        $errors++;
    }
} else {
    cli_error_text('  ✗ hooks.php not found');
    $errors++;
}

cli_writeln('');

// Test 2: Check for deprecated functions
cli_writeln('Test 2: Checking for deprecated functions...');
$libfile = $CFG->dirroot . '/theme/ufpel/lib.php';
if (file_exists($libfile)) {
    $content = file_get_contents($libfile);
    
    $deprecated = [
        'theme_ufpel_before_standard_html_head',
        'theme_ufpel_before_standard_top_of_body_html',
        'theme_ufpel_before_standard_html_body_end'
    ];
    
    $found_deprecated = false;
    foreach ($deprecated as $func) {
        if (strpos($content, 'function ' . $func) !== false) {
            cli_error_text('  ✗ Deprecated function found: ' . $func);
            $warnings++;
            $found_deprecated = true;
        }
    }
    
    if (!$found_deprecated) {
        cli_writeln('  ✓ No deprecated functions found');
        $success++;
    }
} else {
    cli_error_text('  ✗ lib.php not found');
    $errors++;
}

cli_writeln('');

// Test 3: Check output_callbacks class
cli_writeln('Test 3: Checking output_callbacks class...');
$callbacksfile = $CFG->dirroot . '/theme/ufpel/classes/hooks/output_callbacks.php';
if (file_exists($callbacksfile)) {
    cli_writeln('  ✓ output_callbacks.php exists');
    $success++;
    
    require_once($callbacksfile);
    
    if (class_exists('\theme_ufpel\hooks\output_callbacks')) {
        cli_writeln('  ✓ output_callbacks class exists');
        $success++;
        
        // Check required methods
        $required_methods = [
            'before_standard_head_html_generation',
            'before_footer_html_generation',
            'before_http_headers'
        ];
        
        foreach ($required_methods as $method) {
            if (method_exists('\theme_ufpel\hooks\output_callbacks', $method)) {
                cli_writeln('  ✓ Method exists: ' . $method);
                $success++;
            } else {
                cli_error_text('  ✗ Method not found: ' . $method);
                $errors++;
            }
        }
    } else {
        cli_error_text('  ✗ output_callbacks class not found');
        $errors++;
    }
} else {
    cli_error_text('  ✗ output_callbacks.php not found');
    $errors++;
}

cli_writeln('');

// Test 4: Check theme configuration
cli_writeln('Test 4: Checking theme configuration...');
try {
    $theme = theme_config::load('ufpel');
    cli_writeln('  ✓ Theme loads successfully');
    $success++;
    
    // Check parent theme
    if (in_array('boost', $theme->parents)) {
        cli_writeln('  ✓ Inherits from Boost theme');
        $success++;
    } else {
        cli_error_text('  ✗ Does not inherit from Boost theme');
        $errors++;
    }
    
    // Check version
    $version = get_config('theme_ufpel', 'version');
    if ($version) {
        cli_writeln('  ✓ Theme version: ' . $version);
        $success++;
    }
    
} catch (Exception $e) {
    cli_error_text('  ✗ Failed to load theme: ' . $e->getMessage());
    $errors++;
}

cli_writeln('');

// Test 5: Functional test
cli_writeln('Test 5: Functional test...');
try {
    // Try to get critical CSS (tests helper integration)
    if (function_exists('theme_ufpel_get_critical_css')) {
        $css = theme_ufpel_get_critical_css();
        if (!empty($css)) {
            cli_writeln('  ✓ Critical CSS generation works');
            $success++;
        } else {
            cli_problem('  ⚠ Critical CSS is empty');
            $warnings++;
        }
    } else {
        cli_error_text('  ✗ theme_ufpel_get_critical_css function not found');
        $errors++;
    }
    
    // Check if page_init function exists and doesn't add body classes
    if (function_exists('theme_ufpel_page_init')) {
        cli_writeln('  ✓ theme_ufpel_page_init function exists');
        $success++;
    } else {
        cli_problem('  ⚠ theme_ufpel_page_init function not found');
        $warnings++;
    }
    
} catch (Exception $e) {
    cli_error_text('  ✗ Functional test failed: ' . $e->getMessage());
    $errors++;
}

// Summary
cli_writeln('');
cli_separator();
cli_heading('Test Summary');
cli_writeln('');
cli_writeln('✓ Success: ' . $success);
if ($warnings > 0) {
    cli_problem('⚠ Warnings: ' . $warnings);
}
if ($errors > 0) {
    cli_error_text('✗ Errors: ' . $errors);
}

cli_writeln('');

if ($errors === 0) {
    cli_writeln('Result: All tests passed! The hooks system is properly configured.');
    exit(0);
} else {
    cli_error('Result: Some tests failed. Please review the errors above.');
    exit(1);
}

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