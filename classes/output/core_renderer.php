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
 * UFPel theme core renderer.
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_ufpel\output;

use html_writer;
use custom_menu;
use moodle_url;
use navigation_node;
use stdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * Renderer do tema UFPel.
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Retorna dados de contexto para o template principal.
     *
     * @param moodle_page $page
     * @param stdClass $target
     * @return stdClass
     */
    public function get_mustache_template_data(\moodle_page $page, $target): stdClass {
        $data = parent::get_mustache_template_data($page, $target);
        
        // Adiciona dados personalizados do tema UFPel.
        $data->ufpel = new stdClass();
        $data->ufpel->year = date('Y');
        $data->ufpel->institution = get_string('institution', 'theme_ufpel');
        
        // Adiciona configurações do tema.
        $theme = \theme_config::load('ufpel');
        if (!empty($theme->settings->brandcolor)) {
            $data->ufpel->brandcolor = $theme->settings->brandcolor;
        }
        
        return $data;
    }

    /**
     * Renderiza o logo customizado ou o nome do site.
     *
     * @param bool $mobile
     * @return string
     */
    public function render_logo($mobile = false): string {
        global $CFG;
        
        $theme = \theme_config::load('ufpel');
        $logourl = theme_ufpel_update_settings_images('logo');
        
        if (!empty($logourl)) {
            $logo = html_writer::img($logourl, get_string('logo', 'theme_ufpel'), [
                'class' => 'logo img-fluid',
                'alt' => format_string($SITE->fullname)
            ]);
        } else {
            $logo = html_writer::tag('span', format_string($SITE->fullname), [
                'class' => 'site-name d-none d-md-inline'
            ]);
        }
        
        return html_writer::link(new moodle_url('/'), $logo, [
            'class' => 'navbar-brand ' . ($mobile ? 'navbar-brand-mobile' : '')
        ]);
    }

    /**
     * Renderiza o rodapé personalizado.
     *
     * @return string
     */
    public function render_footer(): string {
        global $CFG;
        
        $output = '';
        $data = new stdClass();
        
        // Informações institucionais.
        $data->institution = get_string('institution', 'theme_ufpel');
        $data->year = date('Y');
        $data->address = get_string('address', 'theme_ufpel');
        $data->phone = get_string('phone', 'theme_ufpel');
        $data->email = get_string('email', 'theme_ufpel');
        
        // Links do rodapé.
        $data->footerlinks = [
            [
                'title' => get_string('aboutus', 'theme_ufpel'),
                'url' => new moodle_url('/about')
            ],
            [
                'title' => get_string('privacy', 'theme_ufpel'),
                'url' => new moodle_url('/privacy')
            ],
            [
                'title' => get_string('accessibility', 'theme_ufpel'),
                'url' => new moodle_url('/accessibility')
            ]
        ];
        
        return $this->render_from_template('theme_ufpel/footer', $data);
    }
}