// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Jeu } from '../../modules/mod_jeu.js.php';
import '../menu/comp_joyconIcon.js';
import { ColorChoice } from './comp_colorChoice.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const template = document.createElement('template');
template.innerHTML = `
  <style><?php include './styles.css'; ?></style>
  <?php include './template.html'; ?>
`;

export default class ControllerMenu extends Jeu {
  constructor() {
    super(template);
    this.start();
  }

  static get id() {
    return 'controller-menu';
  }

  async start() {
    await super.start();

    const buttons = Array.from(this.element.shadowRoot.querySelectorAll('.pick-a-side'));
    const dialog = this.element.shadowRoot.querySelector('.dialog');
    const colorMenu = this.element.shadowRoot.querySelector('.color-menu');
    buttons.forEach(bu => {
      bu.addEventListener('click', () => {
        Array.from(this.element.shadowRoot.querySelectorAll('color-choice')).forEach(e => e.remove());
        const choice = new ColorChoice();
        choice.setAttribute('side', bu.dataset.side);
        colorMenu.appendChild(choice);
        dialog.dataset.side = bu.dataset.side;
      });
    });

    this.element.shadowRoot.querySelector(`.pick-a-side[data-side='left']`).dispatchEvent(new Event('click'));
    console.log('Jeu démarré');
  }

  async prepareSound() {
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

    let response = await fetch('/csswitch/jeux/menu/switch.ogg');
    if (response.status != 200) throw 'Error while downloading sound file';
    response = await response.arrayBuffer();
    response = await audioCtx.decodeAudioData(response);

    this.audioCtx = audioCtx;
    this.bruitBuffer = response;
    return;
  }

  async playSound() {
    const bruit = this.audioCtx.createBufferSource();
    bruit.buffer = this.bruitBuffer;
    bruit.connect(this.audioCtx.destination);
    bruit.start();
    return;
  }
}