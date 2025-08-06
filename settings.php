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
 * UFPel theme settings.
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // Configurações gerais.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingufpel', get_string('configtitle', 'theme_ufpel'));

    // Aba de configurações gerais.
    $page = new admin_settingpage('theme_ufpel_general', get_string('generalsettings', 'theme_ufpel'));

    // Preset.
    $name = 'theme_ufpel/preset';
    $title = get_string('preset', 'theme_ufpel');
    $description = get_string('preset_desc', 'theme_ufpel');
    $default = 'default.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_ufpel', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }

    $choices['default.scss'] = 'default.scss';
    $setting = new admin_setting_configthemepreset($name, $title, $description, $default, $choices, 'ufpel');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Arquivo de preset.
    $name = 'theme_ufpel/presetfiles';
    $title = get_string('presetfiles', 'theme_ufpel');
    $description = get_string('presetfiles_desc', 'theme_ufpel');

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        ['maxfiles' => 20, 'accepted_types' => ['.scss']]);
    $page->add($setting);

    // Cor da marca.
    $name = 'theme_ufpel/brandcolor';
    $title = get_string('brandcolor', 'theme_ufpel');
    $description = get_string('brandcolor_desc', 'theme_ufpel');
    $default = '#0061A5';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // SCSS customizado.
    $name = 'theme_ufpel/scss';
    $title = get_string('customscss', 'theme_ufpel');
    $description = get_string('customscss_desc', 'theme_ufpel');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // Aba avançada.
    $page = new admin_settingpage('theme_ufpel_advanced', get_string('advancedsettings', 'theme_ufpel'));

    // CSS não processado.
    $name = 'theme_ufpel/unprocessedcss';
    $title = get_string('unprocessedcss', 'theme_ufpel');
    $description = get_string('unprocessedcss_desc', 'theme_ufpel');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}