// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import SettingsMenu from '../settings-menu/jeu.js.php';
import { ColorChoice } from '../../modules/comp_colorChoice.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



export default class ControllerMenu extends SettingsMenu {
  constructor() {
    const sections = ['left', 'right'];
    super(sections, ControllerMenu.id, 'joycon');
    this.start();
  }

  static get id() {
    return 'controller-menu';
  }

  async start() {
    await super.start();

    this.element.dataset.menu = 'controllers';

    const conteneur = this.getElement('section');
    this.sections.forEach(section => {
      const choix = new ColorChoice();
      choix.setAttribute('data-section', section);
      choix.setAttribute('subject', 'controller');
      conteneur.replaceChild(choix, this.getElement(`div[data-section='${section}']`));
    });

    this.element.addEventListener('animationend', event => {
      if (event.target != this.element) return;
      this.element.shadowRoot.querySelector('button, input').focus();
    });
    this.element.setAttribute('open', '');

    console.log('Jeu démarré');
  }
}