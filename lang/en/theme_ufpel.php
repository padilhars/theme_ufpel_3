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
 * Language file for theme_ufpel - English (Complete with all required strings)
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// General strings
$string['pluginname'] = 'UFPel';
$string['choosereadme'] = 'UFPel is a modern theme based on Boost, customized for the Federal University of Pelotas, fully compatible with Moodle 5.x and Bootstrap 5.';

// Settings page strings
$string['configtitle'] = 'UFPel theme settings';
$string['generalsettings'] = 'General settings';
$string['advancedsettings'] = 'Advanced settings';
$string['features'] = 'Features';
$string['default'] = 'Default';

// Color settings
$string['primarycolor'] = 'Primary color';
$string['primarycolor_desc'] = 'The primary color for the theme. This will be used for main elements like the header and buttons.';
$string['secondarycolor'] = 'Secondary color';
$string['secondarycolor_desc'] = 'The secondary color for the theme. Used for links and secondary elements.';
$string['backgroundcolor'] = 'Background color';
$string['backgroundcolor_desc'] = 'The main background color for the site pages.';
$string['highlightcolor'] = 'Highlight color';
$string['highlightcolor_desc'] = 'The color used for highlighting important elements and accents.';
$string['contenttextcolor'] = 'Content text color';
$string['contenttextcolor_desc'] = 'The color for general text content throughout the site.';
$string['highlighttextcolor'] = 'Highlight text color';
$string['highlighttextcolor_desc'] = 'The color for text that appears on primary colored backgrounds.';

// Feature settings
$string['showcourseimage'] = 'Show course image';
$string['showcourseimage_desc'] = 'Display the course image in the header of course pages.';
$string['showteachers'] = 'Show teachers';
$string['showteachers_desc'] = 'Display teacher names in the header of course pages.';
$string['courseheaderoverlay'] = 'Course header overlay';
$string['courseheaderoverlay_desc'] = 'Add a dark overlay to the course header to improve text readability.';
$string['footercontent'] = 'Footer content';
$string['footercontent_desc'] = 'Custom HTML content to display in the site footer.';

// Logo and images
$string['logo'] = 'Logo';
$string['logo_desc'] = 'Upload your institution logo. This will replace the site name in the navigation bar.';
$string['loginbackgroundimage'] = 'Login page background image';
$string['loginbackgroundimage_desc'] = 'An image that will be displayed as the background of the login page.';
$string['favicon'] = 'Favicon';
$string['favicon_desc'] = 'Upload a custom favicon. Should be an .ico, .png or .svg file.';

// Custom CSS/SCSS
$string['customcss'] = 'Custom CSS';
$string['customcss_desc'] = 'Whatever CSS rules you add to this textarea will be reflected in every page.';
$string['rawscss'] = 'Raw SCSS';
$string['rawscss_desc'] = 'Use this field to provide SCSS code which will be injected at the end of the stylesheet.';
$string['rawscsspre'] = 'Raw initial SCSS';
$string['rawscsspre_desc'] = 'In this field you can provide initializing SCSS code, it will be injected before everything else.';

// Preset settings
$string['preset'] = 'Theme preset';
$string['preset_desc'] = 'Pick a preset to broadly change the look of the theme.';
$string['preset_default'] = 'Default';
$string['preset_dark'] = 'Dark mode';
$string['presetfiles'] = 'Additional theme preset files';
$string['presetfiles_desc'] = 'Preset files can be used to dramatically alter the appearance of the theme.';

// Font settings
$string['customfonts'] = 'Custom fonts URL';
$string['customfonts_desc'] = 'Enter URL to import custom fonts (e.g., Google Fonts).';

// Footer strings - Essential for the footer template
$string['footerdescription'] = 'Learning management system of the Federal University of Pelotas';
$string['quicklinks'] = 'Quick links';
$string['support'] = 'Support';
$string['policies'] = 'Policies';
$string['contactus'] = 'Contact us';
$string['mobileapp'] = 'Mobile app';
$string['downloadapp'] = 'Download the Moodle app';
$string['allrightsreserved'] = 'All rights reserved';
$string['poweredby'] = 'Powered by';
$string['theme'] = 'Theme';

// Navigation strings
$string['home'] = 'Home';
$string['courses'] = 'Courses';
$string['myhome'] = 'Dashboard';
$string['calendar'] = 'Calendar';
$string['help'] = 'Help';
$string['documentation'] = 'Documentation';
$string['login'] = 'Log in';
$string['privacy'] = 'Privacy';
$string['privacypolicy'] = 'Privacy policy';
$string['dataprivacy'] = 'Data privacy';

// Course page strings
$string['teacher'] = 'Teacher';
$string['teachers'] = 'Teachers';
$string['enrolledusers'] = '{$a} enrolled users';
$string['startdate'] = 'Start date';
$string['enddate'] = 'End date';
$string['coursecompleted'] = 'Congratulations! You have completed this course.';
$string['congratulations'] = 'Congratulations!';
$string['progress'] = 'Progress';
$string['complete'] = 'complete';
$string['courseheader'] = 'Course header';
$string['breadcrumb'] = 'Breadcrumb navigation';

// User interface strings
$string['darkmodeon'] = 'Dark mode enabled';
$string['darkmodeoff'] = 'Dark mode disabled';
$string['totop'] = 'Back to top';
$string['skipmain'] = 'Skip to main content';
$string['skipnav'] = 'Skip navigation';
$string['themepreferences'] = 'Theme preferences';

// Privacy strings
$string['privacy:metadata'] = 'The UFPel theme does not store any personal data.';
$string['privacy:metadata:preference:darkmode'] = 'User preference for dark mode.';
$string['privacy:metadata:preference:compactview'] = 'User preference for compact view.';
$string['privacy:metadata:preference:draweropen'] = 'User preference for navigation drawer state.';

// Region strings
$string['region-side-pre'] = 'Left';
$string['region-side-post'] = 'Right';

// Accessibility strings
$string['skipto'] = 'Skip to {$a}';
$string['accessibilitymenu'] = 'Accessibility menu';
$string['increasefontsize'] = 'Increase font size';
$string['decreasefontsize'] = 'Decrease font size';
$string['resetfontsize'] = 'Reset font size';
$string['highcontrast'] = 'High contrast';
$string['normalcontrast'] = 'Normal contrast';

// Notification strings
$string['loading'] = 'Loading...';
$string['error'] = 'Error';
$string['success'] = 'Success';
$string['warning'] = 'Warning';
$string['info'] = 'Information';
$string['close'] = 'Close';
$string['expand'] = 'Expand';
$string['collapse'] = 'Collapse';
$string['menu'] = 'Menu';
$string['search'] = 'Search';
$string['filter'] = 'Filter';
$string['sort'] = 'Sort';
$string['settings'] = 'Settings';
$string['notifications'] = 'Notifications';

// Additional feature strings
$string['showcourseprogressinheader'] = 'Show progress in header';
$string['showcourseprogressinheader_desc'] = 'Display the course progress bar in the header when completion tracking is enabled.';
$string['showcoursesummary'] = 'Show course summary';
$string['showcoursesummary_desc'] = 'Display the course summary in the course page header.';
$string['enablelazyloading'] = 'Enable lazy loading';
$string['enablelazyloading_desc'] = 'Load images and iframes only when needed to improve performance.';
$string['enablecssoptimization'] = 'Optimize CSS';
$string['enablecssoptimization_desc'] = 'Enable CSS optimization and minification for better performance.';
$string['enableresourcehints'] = 'Enable resource hints';
$string['enableresourcehints_desc'] = 'Use preload and prefetch to improve resource loading.';
$string['enableanimations'] = 'Enable animations';
$string['enableanimations_desc'] = 'Enable smooth animations and transitions.';
$string['enableaccessibilitytools'] = 'Accessibility tools';
$string['enableaccessibilitytools_desc'] = 'Enable additional accessibility tools.';
$string['enabledarkmode'] = 'Enable dark mode';
$string['enabledarkmode_desc'] = 'Allow users to switch to dark mode.';
$string['enablecompactview'] = 'Enable compact view';
$string['enablecompactview_desc'] = 'Allow users to switch to a more compact view.';

// Social media strings
$string['social_facebook'] = 'Facebook URL';
$string['social_facebook_desc'] = 'URL of the institution\'s Facebook page';
$string['social_twitter'] = 'Twitter/X URL';
$string['social_twitter_desc'] = 'URL of the institution\'s Twitter/X page';
$string['social_linkedin'] = 'LinkedIn URL';
$string['social_linkedin_desc'] = 'URL of the institution\'s LinkedIn page';
$string['social_youtube'] = 'YouTube URL';
$string['social_youtube_desc'] = 'URL of the institution\'s YouTube channel';
$string['social_instagram'] = 'Instagram URL';
$string['social_instagram_desc'] = 'URL of the institution\'s Instagram page';

// Dashboard and navigation
$string['dashboard'] = 'Dashboard';
$string['sitehome'] = 'Site home';
$string['participants'] = 'Participants';
$string['reports'] = 'Reports';
$string['badges'] = 'Badges';
$string['competencies'] = 'Competencies';
$string['grades'] = 'Grades';
$string['messages'] = 'Messages';
$string['preferences'] = 'Preferences';
$string['logout'] = 'Log out';

// Course display
$string['coursecategories'] = 'Course categories';
$string['recentactivity'] = 'Recent activity';
$string['nocoursesyet'] = 'No courses available yet';
$string['viewallcourses'] = 'View all courses';
$string['mycourses'] = 'My courses';
$string['timeline'] = 'Timeline';

// Development and debugging
$string['version'] = 'Version';
$string['author'] = 'Author';
$string['license'] = 'License';
$string['website'] = 'Website';
$string['repository'] = 'Repository';
$string['issuetracker'] = 'Issue tracker';
$string['documentation_link'] = 'Documentation link';