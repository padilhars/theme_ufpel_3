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

// Add logo URL if available - Fixed to prevent URL duplication
if (method_exists($renderer, 'get_logo_url')) {
    $logourl = $renderer->get_logo_url();
    if ($logourl) {
        // Convert to string properly
        if ($logourl instanceof moodle_url) {
            $templatecontext['logourl'] = $logourl->out(false);
        } else {
            // It's already a string or can be cast to string
            $logourlstr = (string)$logourl;
            // Check if it's already an absolute URL to avoid duplication
            if (preg_match('#^(https?:)?//#', $logourlstr)) {
                $templatecontext['logourl'] = $logourlstr;
            } else {
                // It's a relative URL, make it absolute
                $templatecontext['logourl'] = (new moodle_url($logourlstr))->out(false);
            }
        }
    }
}

// Add background image if configured - Fixed to prevent URL duplication
$loginbgimg = $PAGE->theme->setting_file_url('loginbackgroundimage', 'loginbackgroundimage');
if (!empty($loginbgimg)) {
    // Ensure it's a string URL
    if ($loginbgimg instanceof moodle_url) {
        $templatecontext['loginbackgroundimage'] = $loginbgimg->out(false);
    } else {
        // Convert to string and check format
        $bgimgstr = (string)$loginbgimg;
        if (preg_match('#^(https?:)?//#', $bgimgstr)) {
            $templatecontext['loginbackgroundimage'] = $bgimgstr;
        } else {
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