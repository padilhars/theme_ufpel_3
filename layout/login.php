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
 * Login layout for theme_ufpel - Fixed version
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Get the renderer
$renderer = $PAGE->get_renderer('core');

// Build template context
$templatecontext = [
    'sitename' => format_string($SITE->fullname, true, [
        'context' => context_system::instance(),
        'escape' => false
    ]),
    'output' => $OUTPUT,
    'bodyattributes' => $OUTPUT->body_attributes(['class' => 'pagelayout-login']),
];

// FIXED: Handle logo URL correctly - get_logo_url() returns moodle_url object or null
if (method_exists($renderer, 'get_logo_url')) {
    $logourl = $renderer->get_logo_url();
    if ($logourl) {
        // get_logo_url returns a moodle_url object, so we use ->out(false) to get the string
        $templatecontext['logourl'] = $logourl->out(false);
    }
}

// FIXED: Handle background image URL correctly
$loginbgimg = $PAGE->theme->setting_file_url('loginbackgroundimage', 'loginbackgroundimage');
if (!empty($loginbgimg)) {
    // Process the background image URL
    if ($loginbgimg instanceof moodle_url) {
        // It's already a moodle_url object
        $templatecontext['loginbackgroundimage'] = $loginbgimg->out(false);
    } else {
        // It's a string, need to process it
        $bgimgstr = (string)$loginbgimg;
        
        // Parse the URL to check if it's absolute
        $parsed = parse_url($bgimgstr);
        
        if (!empty($parsed['scheme'])) {
            // It's an absolute URL, extract the path
            global $CFG;
            $wwwroot_parsed = parse_url($CFG->wwwroot);
            $wwwroot_path = $wwwroot_parsed['path'] ?? '';
            
            $path = $parsed['path'] ?? '';
            if (!empty($parsed['query'])) {
                $path .= '?' . $parsed['query'];
            }
            
            // If the path starts with the wwwroot path, make it relative
            if (!empty($wwwroot_path) && strpos($path, $wwwroot_path) === 0) {
                $relative_path = substr($path, strlen($wwwroot_path));
                $templatecontext['loginbackgroundimage'] = (new moodle_url($relative_path))->out(false);
            } else {
                $templatecontext['loginbackgroundimage'] = (new moodle_url($path))->out(false);
            }
        } else {
            // It's a relative URL
            $templatecontext['loginbackgroundimage'] = (new moodle_url($bgimgstr))->out(false);
        }
    }
    $templatecontext['hasloginbackgroundimage'] = true;
} else {
    $templatecontext['hasloginbackgroundimage'] = false;
}

// Add URLs for login links
$templatecontext['homeurl'] = (new moodle_url('/'))->out(false);
$templatecontext['forgotpasswordurl'] = (new moodle_url('/login/forgot_password.php'))->out(false);

// Check if signup is enabled - with error handling
$cansignup = false;
try {
    $authplugins = get_enabled_auth_plugins();
    foreach ($authplugins as $authplugin) {
        $authpluginobj = get_auth_plugin($authplugin);
        if ($authpluginobj && method_exists($authpluginobj, 'can_signup') && $authpluginobj->can_signup()) {
            $cansignup = true;
            break;
        }
    }
} catch (Exception $e) {
    // If there's an error checking auth plugins, just don't show signup
    $cansignup = false;
}

if ($cansignup) {
    $templatecontext['cansignup'] = true;
    $templatecontext['signupurl'] = (new moodle_url('/login/signup.php'))->out(false);
}

$templatecontext['haslogininfo'] = true;

// Render the template
echo $OUTPUT->render_from_template('theme_ufpel/login_simple', $templatecontext);