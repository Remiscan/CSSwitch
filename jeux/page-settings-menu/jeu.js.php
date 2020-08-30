// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import SettingsMenu from '../settings-menu/jeu.js.php';
import { ColorChoice } from '../../modules/comp_colorChoice.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const template = document.createElement('template');
template.innerHTML = `
  <style><?php include './styles.css'; ?></style>
`;



export default class PageSettingsMenu extends SettingsMenu {
  constructor() {
    const sections = ['theme', 'colorset', 'language'];
    super(sections, PageSettingsMenu.id);
    this.start();
  }

  static get id() {
    return 'page-settings-menu';
  }

  async start() {
    await super.start();

    const conteneur = this.getElement('section');
    {
      const choix = new ColorChoice();
      choix.setAttribute('data-section', 'theme');
      choix.setAttribute('subject', 'theme');
      conteneur.replaceChild(choix, this.getElement(`div[data-section='theme']`));
    }
    {
      const choix = new ColorChoice();
      choix.setAttribute('data-section', 'colorset');
      choix.setAttribute('subject', 'colorset');
      conteneur.replaceChild(choix, this.getElement(`div[data-section='colorset']`));
    }

    console.log('Jeu démarré');
  }
}