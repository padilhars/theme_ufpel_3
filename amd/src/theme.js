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
 * UFPel theme main JavaScript module.
 *
 * @module     theme_ufpel/theme
 * @copyright  2025 Universidade Federal de Pelotas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/str', 'core/notification', 'core/ajax'], function($, Str, Notification, Ajax) {
    'use strict';

    /**
     * Inicializa o tema UFPel.
     */
    const init = () => {
        // Inicializa componentes.
        initNavbar();
        initAnimations();
        initAccessibility();
        initDynamicContent();
    };

    /**
     * Inicializa comportamentos da navbar.
     */
    const initNavbar = () => {
        const navbar = document.querySelector('.ufpel-navbar');
        if (!navbar) {
            return;
        }

        // Adiciona efeito de scroll.
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > lastScroll && currentScroll > 100) {
                navbar.classList.add('navbar-hidden');
            } else {
                navbar.classList.remove('navbar-hidden');
            }
            
            if (currentScroll > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
            
            lastScroll = currentScroll;
        });
    };

    /**
     * Inicializa animações.
     */
    const initAnimations = () => {
        // Observador de interseção para animações.
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fadeInUp');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observa elementos com data-animate.
        document.querySelectorAll('[data-animate]').forEach(el => {
            observer.observe(el);
        });
    };

    /**
     * Inicializa recursos de acessibilidade.
     */
    const initAccessibility = () => {
        // Navegação por teclado.
        document.addEventListener('keydown', (e) => {
            // Alt + 1: Ir para conteúdo principal.
            if (e.altKey && e.key === '1') {
                e.preventDefault();
                const mainContent = document.querySelector('#region-main');
                if (mainContent) {
                    mainContent.focus();
                    mainContent.scrollIntoView({ behavior: 'smooth' });
                }
            }
            
            // Alt + 2: Ir para navegação.
            if (e.altKey && e.key === '2') {
                e.preventDefault();
                const nav = document.querySelector('.primary-navigation');
                if (nav) {
                    nav.querySelector('a, button').focus();
                }
            }
        });

        // Adiciona indicadores de foco visíveis.
        document.querySelectorAll('a, button, input, select, textarea').forEach(el => {
            el.addEventListener('focus', () => {
                el.classList.add('ufpel-focus');
            });
            
            el.addEventListener('blur', () => {
                el.classList.remove('ufpel-focus');
            });
        });
    };

    /**
     * Inicializa conteúdo dinâmico.
     */
    const initDynamicContent = () => {
        // Carregamento lazy de imagens.
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.dataset.src;
                        
                        if (src) {
                            img.src = src;
                            img.classList.add('loaded');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Tooltips personalizados.
        document.querySelectorAll('[data-tooltip]').forEach(el => {
            el.addEventListener('mouseenter', showTooltip);
            el.addEventListener('mouseleave', hideTooltip);
            el.addEventListener('focus', showTooltip);
            el.addEventListener('blur', hideTooltip);
        });
    };

    /**
     * Mostra tooltip.
     * @param {Event} e
     */
    const showTooltip = (e) => {
        const el = e.target;
        const text = el.dataset.tooltip;
        
        if (!text) {
            return;
        }

        const tooltip = document.createElement('div');
        tooltip.className = 'ufpel-tooltip';
        tooltip.textContent = text;
        tooltip.setAttribute('role', 'tooltip');
        
        document.body.appendChild(tooltip);
        
        const rect = el.getBoundingClientRect();
        tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10}px`;
        tooltip.style.left = `${rect.left + (rect.width - tooltip.offsetWidth) / 2}px`;
        
        setTimeout(() => {
            tooltip.classList.add('visible');
        }, 10);
        
        el._tooltip = tooltip;
    };

    /**
     * Esconde tooltip.
     * @param {Event} e
     */
    const hideTooltip = (e) => {
        const tooltip = e.target._tooltip;
        
        if (tooltip) {
            tooltip.classList.remove('visible');
            setTimeout(() => {
                tooltip.remove();
            }, 300);
            delete e.target._tooltip;
        }
    };

    /**
     * Utilitário para chamadas AJAX.
     * @param {string} method
     * @param {Object} args
     * @returns {Promise}
     */
    const callWebService = (method, args) => {
        return Ajax.call([{
            methodname: method,
            args: args
        }])[0];
    };

    return {
        init: init,
        callWebService: callWebService
    };
});