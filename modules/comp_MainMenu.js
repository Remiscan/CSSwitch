import BooleAndTheBoulders from 'boole-and-the-boulders';
import 'component-joyconIcon';
import 'component-powerIcon';
import 'component-settingsIcon';
import ControllerMenu from 'controller-menu';
import sheet from 'main-menu-styles' with { type: 'css' };
import template from 'main-menu-template';
import PageSettingsMenu from 'page-settings-menu';
import PressOnSound from 'press-on-sound';
import Solaire from 'solaire';
import { Traduction, getString } from 'traduction';



const jeux = [BooleAndTheBoulders, Solaire, PressOnSound];



class MainMenu extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.appendChild(template.content.cloneNode(true));
    this.shadow.adoptedStyleSheets = [sheet];
  }

  static get observedAttributes() {
    return ['open'];
  }

  async traduire() {
    await Traduction.traduire(this.shadow);

    Array.from(this.shadow.querySelectorAll('.menu-liste button')).forEach(e => {
      e.querySelector('.menu-icone-actual-titre').innerHTML = getString('titre-jeu-' + e.dataset.jeu);
      e.setAttribute('aria-label', getString('bouton-start-jeu') + ' ' + getString('titre-jeu-' + e.dataset.jeu));
    });

    return;
  }

  update(attributes = MainMenu.observedAttributes) {
    if (!this.ready) return;
  }

  connectedCallback() {
    this.ready = true;
    this.update();

    // Populate the menu with game icons
    let minIcons = 5;
    const iconList = this.shadowRoot.querySelector('.menu-icones');
    for (const Jeu of jeux) {
      const buttonTemplate = document.createElement('template');
      buttonTemplate.innerHTML = /*html*/`
        <button class="menu-icone-jeu" id="icone-jeu-${Jeu.id}" data-jeu="${Jeu.id}" style="--icone: url('/csswitch/jeux/${Jeu.id}/icone.png');">
          <span class="menu-icone-titre">
            <span class="menu-icone-actual-titre"></span>
          </span>
        </button>
      `;

      const contents = buttonTemplate.content.cloneNode(true);
      const button = contents.querySelector('button');
      button?.addEventListener('click', () => {
        new Jeu();
        window.dispatchEvent(new Event('gameopen'));
      });
      button?.addEventListener('mouseenter', () => {
        button.focus();
      });
      iconList.appendChild(contents);
    }
    for (let i = 0; i < Math.max(minIcons - jeux.length, 0); i++) {
      const div = document.createElement('div');
      div.classList.add('menu-icone-jeu');
      iconList.appendChild(div);
    }

    // Listen to the bottom icons
    controllers: {
      const button = this.shadowRoot.querySelector('button[data-key=controllers]');
      button.addEventListener('click', () => {
        button.blur();
        new ControllerMenu();
      });
    }
    settings: {
      const button = this.shadowRoot.querySelector('button[data-key=settings]');
      button.addEventListener('click', () => {
        button.blur();
        new PageSettingsMenu();
      });
    }

    this.traduire();
    window.addEventListener('translation-request', () => this.traduire());
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
if (!customElements.get('main-menu')) customElements.define('main-menu', MainMenu);