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
 * UFPel theme library functions.
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Retorna o conteúdo SCSS principal compilado para o tema.
 *
 * @param theme_config $theme O objeto de configuração do tema.
 * @return string
 */
function theme_ufpel_get_main_scss_content($theme) {
    global $CFG;
    
    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();
    
    $context = context_system::instance();
    
    // Pré-CSS - sempre vem primeiro.
    if (file_exists($CFG->dirroot . '/theme/ufpel/scss/pre.scss')) {
        $scss .= file_get_contents($CFG->dirroot . '/theme/ufpel/scss/pre.scss');
    }
    
    // Preset files.
    if ($filename == 'default.scss') {
        // Preset padrão - importa o Boost.
        $scss .= file_get_contents($CFG->dirroot . '/theme/ufpel/scss/preset/default.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_ufpel', 'preset', 0, '/', $filename))) {
        // Preset customizado carregado.
        $scss .= $presetfile->get_content();
    } else {
        // Fallback para o preset padrão.
        $scss .= file_get_contents($CFG->dirroot . '/theme/ufpel/scss/preset/default.scss');
    }
    
    // Post CSS.
    if (file_exists($CFG->dirroot . '/theme/ufpel/scss/post.scss')) {
        $scss .= file_get_contents($CFG->dirroot . '/theme/ufpel/scss/post.scss');
    }
    
    return $scss;
}

/**
 * Retorna as variáveis SCSS pré-compiladas.
 *
 * @param theme_config $theme O objeto de configuração do tema.
 * @return string
 */
function theme_ufpel_get_pre_scss($theme) {
    global $CFG;
    
    // Importa as variáveis do Boost primeiro.
    $boosttheme = theme_config::load('boost');
    $scss = '';
    
    // Obtém as variáveis do Boost.
    if (method_exists('\theme_boost\scss\preset', 'get_variables')) {
        $scss .= \theme_boost\scss\preset::get_variables($boosttheme);
    }
    
    // Adiciona nossas variáveis personalizadas.
    $configurable = [
        'brandcolor' => '#0061A5',
        'navbarbg' => '#FFFFFF',
        'navbarcolor' => '#333333',
    ];
    
    foreach ($configurable as $configkey => $defaultvalue) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : $defaultvalue;
        if (!empty($value)) {
            $scss .= '$' . $configkey . ': ' . $value . ";\n";
        }
    }
    
    return $scss;
}

/**
 * Retorna SCSS extra para o tema.
 *
 * @param theme_config $theme O objeto de configuração do tema.
 * @return string
 */
function theme_ufpel_get_extra_scss($theme) {
    $content = '';
    
    // SCSS customizado das configurações.
    if (!empty($theme->settings->scss)) {
        $content .= $theme->settings->scss;
    }
    
    return $content;
}

/**
 * Retorna CSS pré-compilado.
 * Esta função não é usada mas é mantida por compatibilidade.
 *
 * @param theme_config $theme O objeto de configuração do tema.
 * @return string
 */
function theme_ufpel_get_precompiled_css($theme) {
    return '';
}

/**
 * Serve arquivos do tema.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_ufpel_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo' || $filearea === 'backgroundimage' || $filearea === 'preset')) {
        $theme = theme_config::load('ufpel');
        
        if (!array_key_exists($filearea, $theme->settings)) {
            return false;
        }
        
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }
    
    send_file_not_found();
}

/**
 * Retorna configurações de atualização de imagem para uso em settings.php.
 *
 * @param string $settingname
 * @return string
 */
function theme_ufpel_update_settings_images($settingname) {
    global $CFG;

    if (empty($settingname)) {
        return '';
    }

    $theme = theme_config::load('ufpel');

    if (empty($theme->settings->$settingname)) {
        return '';
    }

    $fs = get_file_storage();
    $context = context_system::instance();
    $files = $fs->get_area_files($context->id, 'theme_ufpel', $settingname, 0, 'sortorder', false);

    if (count($files) == 0) {
        return '';
    }

    foreach ($files as $file) {
        return moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
            $file->get_itemid(), $file->get_filepath(), $file->get_filename(), false);
    }

    return '';
}