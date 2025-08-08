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
 * Login layout for theme_ufpel - Final working version
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// This is the safest approach - just use Boost's login layout
// The UFPel customizations can be applied via CSS instead
$boostlayout = $CFG->dirroot . '/theme/boost/layout/login.php';

if (file_exists($boostlayout)) {
    // Use Boost's login layout directly
    include($boostlayout);
} else {
    // Emergency fallback - basic HTML output
    $templatecontext = [
        'sitename' => format_string($SITE->shortname, true, [
            'context' => context_course::instance(SITEID),
            'escape' => false
        ]),
        'output' => $OUTPUT,
        'bodyattributes' => $OUTPUT->body_attributes(['class' => 'login-page']),
    ];
    
    // Output basic HTML structure
    echo $OUTPUT->doctype() . "\n";
    echo html_writer::start_tag('html', $OUTPUT->htmlattributes());
    echo html_writer::start_tag('head');
    echo html_writer::tag('title', $templatecontext['sitename'] . ': ' . get_string('login'));
    echo html_writer::tag('meta', '', [
        'name' => 'viewport',
        'content' => 'width=device-width, initial-scale=1.0'
    ]);
    echo $OUTPUT->standard_head_html();
    
    // Add some basic inline CSS for the login page
    echo html_writer::tag('style', '
        body.login-page {
            background: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            margin: 1rem;
        }
        .login-container h2 {
            color: #003366;
            text-align: center;
            margin-bottom: 1.5rem;
        }
    ');
    
    echo html_writer::end_tag('head');
    echo html_writer::start_tag('body', $templatecontext['bodyattributes']);
    echo $OUTPUT->standard_top_of_body_html();
    
    // Main login container
    echo html_writer::start_div('login-container');
    echo html_writer::tag('h2', $templatecontext['sitename']);
    
    // Main content (login form)
    echo html_writer::start_div('login-form');
    echo $OUTPUT->main_content();
    echo html_writer::end_div();
    
    echo html_writer::end_div(); // login-container
    
    echo $OUTPUT->standard_footer_html();
    echo $OUTPUT->standard_end_of_body_html();
    echo html_writer::end_tag('body');
    echo html_writer::end_tag('html');
}