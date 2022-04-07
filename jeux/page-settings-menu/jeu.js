import { ColorChoice } from 'component-colorChoice';
import { Params } from 'Params';
import SettingsMenu from 'settings-menu';
import { getString, Traduction } from 'traduction';



export default class PageSettingsMenu extends SettingsMenu {
  constructor() {
    const sections = ['theme', 'language', 'model'];
    super(sections, PageSettingsMenu.id);

    const stylesheet = this.element.shadowRoot.styleSheets[0];
    stylesheet.insertRule(`
      input[type=radio] + label[for^=language-switch],
      input[type=radio] + label[for^=model-switch] {
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
          Traduction.changeLanguage(lang);
          Traduction.traduire();
          localStorage.setItem('csswitch/langage', lang);
          window.dispatchEvent(new Event('translation-request'));
        });

        window.addEventListener('languagechange', event => {
          if (event.detail.lang == lang) input.checked = true;
        });

        section.appendChild(button);
      }
    }

    model: {
      const section = conteneur.querySelector(`[data-section='model']`);
      const models = Params.models;
      section.style.setProperty('--total', models.length);

      for (const model of models) {
        const templateModel = document.createElement('template');
        templateModel.innerHTML = `
          <input type="radio" id="model-switch-${model}" name="model-switch">
          <label for="model-switch-${model}" data-string="model-${model}">${getString(`model-${model}`)}</label>
        `;

        const button = templateModel.content.cloneNode(true);
        const input = button.querySelector('input');
        if (model == Params.currentModel) input.checked = true;

        input.addEventListener('change', async () => {
          document.querySelector('nintendo-switch').setAttribute('model', model);
          localStorage.setItem('csswitch/model', model);
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