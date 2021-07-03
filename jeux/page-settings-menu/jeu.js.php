// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import SettingsMenu from '../settings-menu/jeu.js.php';
import { ColorChoice } from '../../modules/comp_colorChoice.js.php';
import { Params } from '../../modules/mod_Params.js.php';
import { Traduction } from '../../modules/mod_traduction.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



export default class PageSettingsMenu extends SettingsMenu {
  constructor() {
    const sections = ['theme', 'colorset', 'language'];
    super(sections, PageSettingsMenu.id);

    const stylesheet = this.element.shadowRoot.styleSheets[0];
    stylesheet.insertRule(`
      input[type=radio] + label[for^=language-switch] {
        grid-template-columns: 0 1fr auto;
      }
    `);

    this.start();
  }

  static get id() {
    return 'page-settings-menu';
  }

  async start() {
    await super.start();

    this.element.dataset.menu = 'settings';

    const conteneur = this.getElement('section');
    theme: {
      const choix = new ColorChoice();
      choix.setAttribute('data-section', 'theme');
      choix.setAttribute('subject', 'theme');
      conteneur.replaceChild(choix, this.getElement(`div[data-section='theme']`));
    }
    colorset: {
      const choix = new ColorChoice();
      choix.setAttribute('data-section', 'colorset');
      choix.setAttribute('subject', 'colorset');
      conteneur.replaceChild(choix, this.getElement(`div[data-section='colorset']`));
    }
    language: {
      const section = conteneur.querySelector(`[data-section='language']`);
      const languages = Params.languages;
      section.style.setProperty('--total', languages.length);

      for (const lang of languages) {
        const templateLang = document.createElement('template');
        templateLang.innerHTML = `
          <input type="radio" id="language-switch-${lang}" name="language-switch">
          <label for="language-switch-${lang}">${
            (lang == 'fr') ? 'Français' :
            (lang == 'en') ? 'English' :
            'unknown'
          }</label>
        `;
        
        const button = templateLang.content.cloneNode(true);
        const input = button.querySelector('input');
        if (lang == Params.currentLanguage) input.checked = true;
        
        input.addEventListener('change', async () => {
          await Traduction.switchLanguage(lang);
          Params.currentLanguage = lang;
          Traduction.traduire();
          window.dispatchEvent(new Event('translation-request'));
        });

        section.appendChild(button);
      }
    }

    this.element.addEventListener('animationend', event => {
      if (event.target != this.element) return;
      this.element.shadowRoot.querySelector('button, input').focus();
    });
    this.element.setAttribute('open', '');

    console.log('Jeu démarré');
  }
}