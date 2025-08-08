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
 * Login page layout for theme_ufpel.
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Get the renderer safely
$renderer = $PAGE->get_renderer('core');

// Add login background if configured
$loginbackground = '';
try {
    $loginbgurl = $PAGE->theme->setting_file_url('loginbackgroundimage', 'loginbackgroundimage');
    if (!empty($loginbgurl)) {
        $loginbackground = $renderer->login_background();
    }
} catch (Exception $e) {
    // Silently ignore errors with login background
    $loginbackground = '';
}

// Get site name safely
$sitename = '';
try {
    $sitename = format_string($SITE->shortname, true, [
        'context' => context_course::instance(SITEID), 
        'escape' => false
    ]);
} catch (Exception $e) {
    $sitename = 'Moodle';
}

// Prepare template context with error handling
$templatecontext = [
    'sitename' => $sitename,
    'output' => $OUTPUT,
    'bodyattributes' => $OUTPUT->body_attributes(), 
    'loginbackground' => $loginbackground,
    'doctype' => $OUTPUT->doctype(),
    'htmlattributes' => $OUTPUT->htmlattributes(),
    'headhtml' => $OUTPUT->standard_head_html(),
    'topofbodyhtml' => $OUTPUT->standard_top_of_body_html(),
    'standardfooterhtml' => $OUTPUT->standard_footer_html(),
    'standardendbodyhtml' => $OUTPUT->standard_end_of_body_html(),
    'currentyear' => date('Y'),
];

// Render the template safely
try {
    echo $OUTPUT->render_from_template('theme_ufpel/login', $templatecontext);
} catch (Exception $e) {
    // Fallback to basic HTML if template fails
    debugging('Error rendering login template: ' . $e->getMessage(), DEBUG_DEVELOPER);
    
    // Simple fallback HTML
    echo $OUTPUT->doctype();
    echo html_writer::start_tag('html', $OUTPUT->htmlattributes());
    echo html_writer::start_tag('head');
    echo html_writer::tag('title', get_string('login') . ' - ' . $sitename);
    echo $OUTPUT->standard_head_html();
    echo html_writer::end_tag('head');
    echo html_writer::start_tag('body', $OUTPUT->body_attributes());
    echo $OUTPUT->standard_top_of_body_html();
    
    echo $loginbackground;
    
    echo html_writer::start_div('container-fluid d-flex align-items-center justify-content-center', ['style' => 'min-height: 100vh;']);
    echo html_writer::start_div('row justify-content-center');
    echo html_writer::start_div('col-12 col-md-6 col-lg-4');
    echo html_writer::start_div('login-container card p-4');
    
    echo html_writer::tag('h1', $sitename, ['class' => 'text-center mb-4']);
    echo $OUTPUT->main_content();
    
    echo html_writer::end_div(); // login-container
    echo html_writer::end_div(); // col
    echo html_writer::end_div(); // row
    echo html_writer::end_div(); // container-fluid
    
    echo $OUTPUT->standard_footer_html();
    echo $OUTPUT->standard_end_of_body_html();
    echo html_writer::end_tag('body');
    echo html_writer::end_tag('html');
}