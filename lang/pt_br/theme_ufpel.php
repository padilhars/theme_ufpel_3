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
 * Arquivo de idioma para theme_ufpel - Português Brasil
 *
 * @package    theme_ufpel
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Strings gerais
$string['pluginname'] = 'UFPel';
$string['choosereadme'] = 'UFPel é um tema moderno baseado no Boost, personalizado para a Universidade Federal de Pelotas, totalmente compatível com Moodle 5.x e Bootstrap 5.';

// Strings da página de configurações
$string['configtitle'] = 'Configurações do tema UFPel';
$string['generalsettings'] = 'Configurações gerais';
$string['advancedsettings'] = 'Configurações avançadas';
$string['features'] = 'Recursos';
$string['performance'] = 'Desempenho';
$string['accessibility'] = 'Acessibilidade';
$string['default'] = 'Padrão';

// Configurações de preset
$string['preset'] = 'Predefinição do tema';
$string['preset_desc'] = 'Escolha uma predefinição para alterar amplamente a aparência do tema.';
$string['preset_default'] = 'Padrão';
$string['preset_dark'] = 'Modo escuro';
$string['presetfiles'] = 'Arquivos de predefinição adicionais do tema';
$string['presetfiles_desc'] = 'Arquivos de predefinição podem ser usados para alterar drasticamente a aparência do tema. Veja <a href="https://docs.moodle.org/dev/Boost_Presets">Predefinições do Boost</a> para informações sobre criar e compartilhar seus próprios arquivos de predefinição.';

// Configurações de cores
$string['primarycolor'] = 'Cor primária';
$string['primarycolor_desc'] = 'A cor primária do tema. Será usada para elementos principais como cabeçalho e botões.';
$string['secondarycolor'] = 'Cor secundária';
$string['secondarycolor_desc'] = 'A cor secundária para o tema. Usada para links e elementos secundários.';
$string['backgroundcolor'] = 'Cor de fundo';
$string['backgroundcolor_desc'] = 'A cor de fundo principal para as páginas do site.';
$string['highlightcolor'] = 'Cor de destaque';
$string['highlightcolor_desc'] = 'A cor usada para destacar elementos importantes e acentos.';
$string['contenttextcolor'] = 'Cor do texto de conteúdo';
$string['contenttextcolor_desc'] = 'A cor para o texto geral de conteúdo em todo o site.';
$string['highlighttextcolor'] = 'Cor de destaque do texto';
$string['highlighttextcolor_desc'] = 'A cor para textos que aparecem em fundos com cor primária.';

// Configurações de logotipo
$string['logo'] = 'Logotipo';
$string['logo_desc'] = 'Faça upload do logotipo da instituição. Isso substituirá o nome do site na barra de navegação. Recomenda-se uma imagem com altura máxima de 40px.';
$string['footerlogo'] = 'Logotipo do rodapé';
$string['footerlogo_desc'] = 'Logotipo específico para o rodapé. Se não definido, o logotipo principal será usado.';

// Configurações de CSS personalizado
$string['customcss'] = 'CSS personalizado';
$string['customcss_desc'] = 'Quaisquer regras CSS que você adicionar a esta área de texto serão refletidas em todas as páginas, facilitando a personalização deste tema.';

// Configurações da página de login
$string['loginbackgroundimage'] = 'Imagem de fundo da página de login';
$string['loginbackgroundimage_desc'] = 'Uma imagem que será exibida como fundo da página de login. Recomenda-se uma imagem de alta qualidade (1920x1080 ou maior).';

// Configurações de favicon
$string['favicon'] = 'Favicon';
$string['favicon_desc'] = 'Faça upload de um favicon personalizado. Deve ser um arquivo .ico, .png ou .svg.';

// Configurações de fontes
$string['customfonts'] = 'URL de fontes personalizadas';
$string['customfonts_desc'] = 'Insira a URL para importar fontes personalizadas (ex: Google Fonts). Use a tag @import completa.';

// Configurações avançadas
$string['rawscss'] = 'SCSS adicional';
$string['rawscss_desc'] = 'Use este campo para fornecer código SCSS que será injetado no final da folha de estilo.';
$string['rawscsspre'] = 'SCSS de inicialização';
$string['rawscsspre_desc'] = 'Neste campo você pode fornecer código SCSS de inicialização, ele será injetado antes de todo o resto. Na maioria das vezes você usará esta configuração para definir variáveis.';

// Configurações de recursos
$string['showcourseimage'] = 'Mostrar imagem do curso';
$string['showcourseimage_desc'] = 'Exibir a imagem do curso no cabeçalho das páginas do curso.';
$string['showteachers'] = 'Mostrar professores';
$string['showteachers_desc'] = 'Exibir os nomes dos professores no cabeçalho das páginas do curso.';
$string['courseheaderoverlay'] = 'Sobrepor cabeçalho do curso';
$string['courseheaderoverlay_desc'] = 'Adicionar uma sobreposição escura ao cabeçalho do curso para melhorar a legibilidade do texto.';
$string['showcourseprogressinheader'] = 'Mostrar progresso no cabeçalho';
$string['showcourseprogressinheader_desc'] = 'Exibir a barra de progresso do curso no cabeçalho quando o rastreamento de conclusão estiver ativado.';
$string['showcoursesummary'] = 'Mostrar resumo do curso';
$string['showcoursesummary_desc'] = 'Exibir o resumo do curso no cabeçalho da página do curso.';
$string['footercontent'] = 'Conteúdo do rodapé';
$string['footercontent_desc'] = 'Conteúdo HTML personalizado para exibir no rodapé do site.';

// Configurações de desempenho
$string['enablelazyloading'] = 'Ativar carregamento sob demanda';
$string['enablelazyloading_desc'] = 'Carregar imagens e iframes apenas quando necessário para melhorar o desempenho.';
$string['enablecssoptimization'] = 'Otimizar CSS';
$string['enablecssoptimization_desc'] = 'Ativar otimização e minificação de CSS para melhor desempenho.';
$string['enableresourcehints'] = 'Ativar dicas de recursos';
$string['enableresourcehints_desc'] = 'Usar preload e prefetch para melhorar o carregamento de recursos.';
$string['enableanimations'] = 'Ativar animações';
$string['enableanimations_desc'] = 'Ativar animações e transições suaves. Desative para melhor desempenho em dispositivos mais lentos.';

// Configurações de acessibilidade
$string['enableaccessibilitytools'] = 'Ferramentas de acessibilidade';
$string['enableaccessibilitytools_desc'] = 'Ativar ferramentas adicionais de acessibilidade como ajuste de contraste e tamanho de fonte.';
$string['enabledarkmode'] = 'Ativar modo escuro';
$string['enabledarkmode_desc'] = 'Permitir que os usuários alternem para o modo escuro.';
$string['enablecompactview'] = 'Ativar visualização compacta';
$string['enablecompactview_desc'] = 'Permitir que os usuários alternem para uma visualização mais compacta.';

// Strings de navegação e interface
$string['darkmodeon'] = 'Modo escuro ativado';
$string['darkmodeoff'] = 'Modo escuro desativado';
$string['totop'] = 'Voltar ao topo';
$string['skipmain'] = 'Pular para o conteúdo principal';
$string['skipnav'] = 'Pular navegação';
$string['themepreferences'] = 'Preferências do tema';

// Strings de privacidade
$string['privacy:metadata'] = 'O tema UFPel não armazena nenhum dado pessoal.';
$string['privacy:metadata:preference:darkmode'] = 'Preferência do usuário para modo escuro.';
$string['privacy:metadata:preference:compactview'] = 'Preferência do usuário para visualização compacta.';
$string['privacy:metadata:preference:draweropen'] = 'Preferência do usuário para o estado da gaveta de navegação.';

// Strings de região
$string['region-side-pre'] = 'Esquerda';
$string['region-side-post'] = 'Direita';

// Strings da página do curso
$string['teacher'] = 'Professor(a)';
$string['teachers'] = 'Professores';
$string['enrolledusers'] = '{$a} usuários inscritos';
$string['startdate'] = 'Data de início';
$string['enddate'] = 'Data de término';
$string['coursecompleted'] = 'Parabéns! Você concluiu este curso.';
$string['congratulations'] = 'Parabéns!';
$string['progress'] = 'Progresso';
$string['complete'] = 'completo';

// Strings para templates
$string['courseheader'] = 'Cabeçalho do curso';
$string['breadcrumb'] = 'Caminho de navegação';

// Strings do rodapé
$string['footerdescription'] = 'Sistema de gestão de aprendizagem da Universidade Federal de Pelotas';
$string['quicklinks'] = 'Links rápidos';
$string['support'] = 'Suporte';
$string['policies'] = 'Políticas';
$string['contactus'] = 'Fale conosco';
$string['help'] = 'Ajuda';
$string['documentation'] = 'Documentação';
$string['mobileapp'] = 'Aplicativo móvel';
$string['downloadapp'] = 'Baixar aplicativo';
$string['allrightsreserved'] = 'Todos os direitos reservados';
$string['poweredby'] = 'Desenvolvido com';
$string['theme'] = 'Tema';

// Strings de acessibilidade
$string['skipto'] = 'Pular para {$a}';
$string['accessibilitymenu'] = 'Menu de acessibilidade';
$string['increasefontsize'] = 'Aumentar tamanho da fonte';
$string['decreasefontsize'] = 'Diminuir tamanho da fonte';
$string['resetfontsize'] = 'Redefinir tamanho da fonte';
$string['highcontrast'] = 'Alto contraste';
$string['normalcontrast'] = 'Contraste normal';

// Strings de notificação
$string['loading'] = 'Carregando...';
$string['error'] = 'Erro';
$string['success'] = 'Sucesso';
$string['warning'] = 'Aviso';
$string['info'] = 'Informação';
$string['close'] = 'Fechar';
$string['expand'] = 'Expandir';
$string['collapse'] = 'Recolher';
$string['menu'] = 'Menu';
$string['search'] = 'Buscar';
$string['filter'] = 'Filtrar';
$string['sort'] = 'Ordenar';
$string['settings'] = 'Configurações';
$string['notifications'] = 'Notificações';

// Strings de erro
$string['error:missinglogo'] = 'Logotipo não encontrado';
$string['error:invalidcolor'] = 'Código de cor inválido';
$string['error:fileuploadfailed'] = 'Falha no upload do arquivo';

// Strings de ajuda
$string['help:primarycolor'] = 'Esta cor será aplicada aos elementos principais da interface';
$string['help:darkmode'] = 'O modo escuro reduz a fadiga ocular em ambientes com pouca luz';
$string['help:lazyloading'] = 'O carregamento sob demanda melhora significativamente o desempenho em páginas com muitas imagens';

// Strings administrativas
$string['themesettings'] = 'Configurações do tema UFPel';
$string['resetsettings'] = 'Redefinir configurações';
$string['resetsettings_desc'] = 'Redefinir todas as configurações do tema para os valores padrão';
$string['settingssaved'] = 'Configurações salvas com sucesso';
$string['settingsreset'] = 'Configurações redefinidas para os valores padrão';

// Redes sociais
$string['social_facebook'] = 'URL do Facebook';
$string['social_facebook_desc'] = 'URL da página da instituição no Facebook';
$string['social_twitter'] = 'URL do Twitter/X';
$string['social_twitter_desc'] = 'URL da página da instituição no Twitter/X';
$string['social_linkedin'] = 'URL do LinkedIn';
$string['social_linkedin_desc'] = 'URL da página da instituição no LinkedIn';
$string['social_youtube'] = 'URL do YouTube';
$string['social_youtube_desc'] = 'URL do canal da instituição no YouTube';
$string['social_instagram'] = 'URL do Instagram';
$string['social_instagram_desc'] = 'URL da página da instituição no Instagram';

// Strings do rodapé
$string['footerdescription'] = 'Sistema de gestão de aprendizagem da Universidade Federal de Pelotas';
$string['quicklinks'] = 'Links rápidos';
$string['support'] = 'Suporte';
$string['policies'] = 'Políticas';
$string['contactus'] = 'Fale conosco';
$string['help'] = 'Ajuda';
$string['documentation'] = 'Documentação';
$string['mobileapp'] = 'Aplicativo móvel';
$string['downloadapp'] = 'Baixe o aplicativo Moodle';
$string['allrightsreserved'] = 'Todos os direitos reservados';
$string['poweredby'] = 'Desenvolvido com';
$string['theme'] = 'Tema';