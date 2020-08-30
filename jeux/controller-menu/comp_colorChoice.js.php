// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Traduction, getString } from '../../modules/mod_traduction.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



///////////////////////
// Couleurs des joy-con
//// Couleurs possibles pour les 2 Joy-Con
const joyconColors = [
  {
    hex: '#6E757B',
    hexOfficiel: '#828282',
    id: 'gris'
  }, {
    hex: '#00BFDF',
    hexOfficiel: '#0AB9E6',
    id: 'bleu-neon'
  }, {
    hex: '#FF5E52',
    hexOfficiel: '#FF3C28',
    id: 'rouge-neon'
  }, {
    hex: '#D9EF64',
    hexOfficiel: '#E6FF00',
    id: 'jaune-neon'
  }, {
    hex: '#00E259',
    hexOfficiel: '#1EDC00',
    id: 'vert-neon'
  }, {
    hex: '#F85187',
    hexOfficiel: '#FF3278',
    id: 'rose-neon'
  }, {
    hex: '#EE2D37',
    hexOfficiel: '#E10F00',
    id: 'mario'
  }, {
    hex: '#D0A880',
    hexOfficiel: '#D7AA73',
    id: 'labo'
  }
];
//// Couleurs propres au Joy-Con gauche
const joyconGonlyColors = [
  {
    hex: '#CAA25A',
    hexOfficiel: '#C88D32',
    id: 'evoli'
  }, {
    hex: '#4456C2',
    id: 'bleu'
  }, {
    hex: '#912FA8',
    id: 'violet-neon'
  }, {
    hex: '#8DE6AF',
    id: 'ac-vert'
  }
];
//// Couleurs propres au Joy-Con droit
const joyconDonlyColors = [
  {
    hex: '#F6D962',
    hexOfficiel: '#FFDD00',
    id: 'pikachu'
  }, {
    hex: '#F0BB37',
    id: 'orange-neon'
  }, {
    hex: '#7DDCE2',
    id: 'ac-bleu'
  }
];

let typeCouleur = 'hex';



const template = document.createElement('template');
template.innerHTML = `
  <button class="color">
    <div class="color-preview"></div>
    <span class="color-name"></span>
  </button>
`;

export class ColorChoice extends HTMLElement {
  constructor() {
    super();
    /*this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.appendChild(template.content.cloneNode(true));*/
  }

  makeMenu() {
    const colors = ColorChoice.colors(this.side);
    this.innerHTML = '';
    for (const color of colors) {
      const button = template.content.cloneNode(true);
      button.querySelector('.color-preview').style.setProperty('--color', color.hex);
      button.querySelector('.color-name').innerHTML = getString(`couleur-nom-${color.id}`);

      button.querySelector('button').addEventListener('click', () => {
        console.log('hi');
        window.dispatchEvent(new CustomEvent('controllercolorchange', { detail: { side: this.side, color: color.hex } }));
      });

      this.appendChild(button);
    }
  }

  static colors(side = 'both') {
    if (side == 'left')   return [...joyconColors, ...joyconGonlyColors];
    if (side == 'right')  return [...joyconColors, ...joyconDonlyColors];
    else                  return [...joyconColors, ...joyconGonlyColors, ...joyconDonlyColors];
  }

  static get observedAttributes() {
    return ['open'];
  }

  async traduire() {
    //await Traduction.traduire(this.shadowRoot);
    return;
  }

  update(attributes = ColorChoice.observedAttributes) {
    if (!this.ready) return;
  }

  connectedCallback() {
    this.ready = true;
    this.side = this.getAttribute('side');
    this.update();
    this.makeMenu();
    this.traduire();
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
customElements.define('color-choice', ColorChoice);