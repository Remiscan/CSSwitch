// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import '../jeux/menu/comp_joyconIcon.js';
import '../jeux/menu/comp_settingsIcon.js';
import '../jeux/menu/comp_powerIcon.js';
import { Traduction, getString } from './mod_traduction.js.php';
import PressOnSound from '../jeux/press-on-sound/jeu.js.php';
import ControllerMenu from '../jeux/controller-menu/jeu.js.php';
import PageSettingsMenu from '../jeux/page-settings-menu/jeu.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const jeux = [PressOnSound];



const template = document.createElement('template');
template.innerHTML = `
  <style><?php include '../jeux/menu/styles.css'; ?></style>
  <?php include '../jeux/menu/template.html'; ?>
`;

class MainMenu extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.appendChild(template.content.cloneNode(true));
  }

  static get observedAttributes() {
    return ['open'];
  }

  async traduire() {
    await Traduction.traduire(this.shadowRoot);

    Array.from(this.shadowRoot.querySelectorAll('.menu-liste button')).forEach(e => {
      e.dataset.titre = getString('titre-jeu-' + e.dataset.jeu);
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
      const button = document.createElement('button');
      button.classList.add('menu-icone-jeu');
      button.id = `icone-jeu-${Jeu.id}`;
      button.dataset.jeu = Jeu.id;
      button.style.setProperty('--icone', `url('/csswitch/jeux/${Jeu.id}/icone.png')`);

      button.addEventListener('click', () => {
        new Jeu();
        window.dispatchEvent(new Event('gameopen'));
      });

      iconList.appendChild(button);
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
    window.addEventListener('translate', () => this.traduire());
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
if (!customElements.get('main-menu')) customElements.define('main-menu', MainMenu);