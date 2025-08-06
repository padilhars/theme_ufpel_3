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
 * UFPel theme config.
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Nome do tema.
$THEME->name = 'ufpel';

// Herda todas as funcionalidades do tema Boost.
$THEME->parents = ['boost'];

// Habilita o dock de blocos.
$THEME->enable_dock = false;

// Não usa sheets CSS tradicionais, usa SCSS compilado.
$THEME->sheets = [];
$THEME->editor_sheets = [];

// SCSS principal para compilar.
$THEME->scss = function($theme) {
    return theme_ufpel_get_main_scss_content($theme);
};

// Configuração de pré-compilação SCSS (variáveis).
$THEME->prescsscallback = 'theme_ufpel_get_pre_scss';

// Configuração extra de SCSS (customizações do usuário).
$THEME->extrascsscallback = 'theme_ufpel_get_extra_scss';

// Usa renderizadores personalizados.
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

// Layouts do tema herdados do Boost com customizações.
$THEME->layouts = [
    // Layout base.
    'base' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout padrão.
    'standard' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout do curso.
    'course' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout de categoria de curso.
    'coursecategory' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout de atividade do curso.
    'incourse' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout da página inicial.
    'frontpage' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout de administração.
    'admin' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout do meu painel.
    'mydashboard' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout dos meus cursos.
    'mycourses' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout público dos meus cursos.
    'mypublic' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout de login.
    'login' => [
        'file' => 'drawers.php',
        'regions' => [],
    ],
    // Layout de popup.
    'popup' => [
        'file' => 'columns2.php',
        'regions' => [],
    ],
    // Layout sem blocos.
    'frametop' => [
        'file' => 'columns2.php',
        'regions' => [],
    ],
    // Layout de conteúdo incorporado.
    'embedded' => [
        'file' => 'embedded.php',
        'regions' => [],
    ],
    // Layout de manutenção.
    'maintenance' => [
        'file' => 'maintenance.php',
        'regions' => [],
    ],
    // Layout de impressão.
    'print' => [
        'file' => 'columns2.php',
        'regions' => [],
    ],
    // Layout de redirecionamento.
    'redirect' => [
        'file' => 'redirect.php',
        'regions' => [],
    ],
    // Layout de relatório.
    'report' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout seguro.
    'secure' => [
        'file' => 'secure.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
];

// Configuração de ícones do sistema.
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;

// Recursos do tema Boost.
$THEME->haseditswitch = true;
$THEME->usescourseindex = true;
$THEME->usesblockdrawer = true;

// Configuração de blocos.
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->requiredblocks = '';

// Desabilita CSS de plugins Moodle.
$THEME->yuicssmodules = [];