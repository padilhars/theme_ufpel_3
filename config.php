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
 * Theme UFPel config file - Updated for Moodle 5.x.
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Theme name.
$THEME->name = 'ufpel';

// Theme parent - inherits all settings from Boost.
$THEME->parents = ['boost'];

// Theme sheets - none as we use SCSS.
$THEME->sheets = [];

// Editor sheets to load.
$THEME->editor_sheets = ['editor'];

// Use parent editor sheets from Boost.
$THEME->parentseditorsheets = ['boost'];

// SCSS processing functions.
$THEME->scss = function($theme) {
    return theme_ufpel_get_main_scss_content($theme);
};

// Pre-SCSS callback - for variables.
$THEME->prescsscallback = 'theme_ufpel_get_pre_scss';

// Extra SCSS callback - for additional styles.
$THEME->extrascsscallback = 'theme_ufpel_get_extra_scss';

// CSS post-processing callback - Updated for Moodle 5.x.
$THEME->csstreepostprocessor = 'theme_ufpel_css_tree_post_processor';

// Inherit all layouts from Boost.
$THEME->layouts = [
    // Use parent theme login layout to avoid issues
    'login' => [
        'file' => 'login.php',
        'regions' => [],
        'options' => ['nofooter' => true, 'nonavbar' => true],
    ],
];

// Renderer factory.
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

// Required blocks - none specific to UFPel.
$THEME->requiredblocks = '';

// Add blocks position - Updated for Moodle 5.x.
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;

// Icon system - FontAwesome.
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;

// Features inherited from Boost - Updated for Moodle 5.x.
$THEME->haseditswitch = true;
$THEME->usescourseindex = true;
$THEME->primary_navigation_favourites = true;
$THEME->usescombolistbox = true;

// Activity header configuration - Updated for Moodle 5.x.
$THEME->activityheaderconfig = [
    'notitle' => false,
    'nocompletion' => false,
    'nodescription' => false,
    'noavailability' => false,
    'notitlelink' => false
];

// Block RTL manipulations.
$THEME->blockrtlmanipulations = [
    'side-pre' => 'side-post',
    'side-post' => 'side-pre'
];

// AMD modules to be loaded - New for optimized loading.
$THEME->requiredmodules = [
    'theme_ufpel/theme'
];

// JavaScript initialization - New for Moodle 5.x.
$THEME->javascripts = [];
$THEME->javascripts_footer = [];

// UFPel specific settings.
$THEME->supportscssoptimisation = true;
$THEME->yuicssmodules = [];
$THEME->enablecourseajax = true;

// Dock is deprecated and not used.
$THEME->enable_dock = false;

// Preset files available.
$THEME->presetsfiles = [
    'default.scss',
    'dark.scss'
];

// Clonable region format.
$THEME->clonable_region_format = 'aside';

// Remove any duplicate settings.
$THEME->removedprimarynavitems = [];

// Use secure login layout if required.
$THEME->securelayout = 'secure';

// New settings for Moodle 5.x

// Support for content bank.
$THEME->usescontentbank = true;

// Support for course index.
$THEME->usescourseindex = true;

// Support for activity chooser.
$THEME->useactivitychooser = true;

// Support for user tours.
$THEME->usesusertours = true;

// Support for notifications.
$THEME->usesnotifications = true;

// Performance settings - New for Moodle 5.x.
$THEME->hidefromselector = false;
$THEME->rendererthemeparent = 'boost';

// Content areas that support file serving.
$THEME->contentareas = [
    'logo' => [
        'maxfiles' => 1,
        'accepted_types' => 'web_image'
    ],
    'loginbackgroundimage' => [
        'maxfiles' => 1,
        'accepted_types' => 'web_image'
    ],
    'favicon' => [
        'maxfiles' => 1,
        'accepted_types' => '.ico,.png,.svg'
    ],
    'footerlogo' => [
        'maxfiles' => 1,
        'accepted_types' => 'web_image'
    ]
];

// Theme capabilities.
$THEME->capabilities = [];

// Theme update callback - New for Moodle 5.x.
$THEME->postupdatecallback = 'theme_ufpel_post_update';

// ARIA labels - New for accessibility in Moodle 5.x.
$THEME->arialabels = [
    'region-side-pre' => 'theme_ufpel',
    'region-side-post' => 'theme_ufpel'
];

// Define regions for specific layouts if needed.
$THEME->defaultregions = ['side-pre'];

// Support for CSS grid layout - New in Moodle 5.x.
$THEME->usescssGrid = true;

// Support for CSS custom properties - New in Moodle 5.x.
$THEME->usescustomproperties = true;

// Theme metadata - New for Moodle 5.x.
$THEME->metadata = [
    'author' => 'Universidade Federal de Pelotas',
    'license' => 'GPL v3',
    'version' => '1.1.0',
    'moodle_version' => '5.0+'
];