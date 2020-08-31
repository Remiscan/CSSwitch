// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Traduction, getString } from './mod_traduction.js.php';
import { Params } from './mod_Params.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const template = document.createElement('template');
template.innerHTML = `
  <input type="radio" class="color">
  <label></label>
`;

export class ColorChoice extends HTMLElement {
  constructor() {
    super();
  }

  makeMenu() {
    const colors = this.colors;
    this.style.setProperty('--total', colors.length);
    this.innerHTML = '';
    for (const color of colors) {
      const buttonCont = template.content.cloneNode(true);
      const input = buttonCont.querySelector('input');
      input.id = `color-${this.section}-${color.id}`;
      input.name = `color-${this.section}`;
      if (color.id == Params.currentColors[this.section]) input.checked = true;
      
      const button = buttonCont.querySelector('label');
      button.setAttribute('for', input.id);
      button.style.setProperty('--color', Params.getColorHex(color.id, ['theme', 'colorset'].includes(this.section) ? this.section : 'all'));
      button.innerHTML = getString(`couleur-nom-${color.id}`);
      button.dataset.string = `couleur-nom-${color.id}`;

      input.addEventListener('change', () => {
        window.dispatchEvent(new CustomEvent(`${this.subject}colorchange`, { detail: { section: this.section, color: color } }));
      });

      this.appendChild(buttonCont);
    }
  }

  static get observedAttributes() {
    return ['open'];
  }

  update(attributes = ColorChoice.observedAttributes) {
    if (!this.ready) return;
  }

  connectedCallback() {
    const stylesheet = this.getRootNode().styleSheets[0];
    stylesheet.insertRule(`
      color-choice {
        display: grid;
        grid-template-rows: repeat(var(--total), 3em);
        gap: 1em;
      }
    `);
    this.ready = true;
    this.section = this.getAttribute('data-section');
    this.subject = this.getAttribute('subject');
    if (this.subject == 'controller')
      this.colors = Params.colors(this.section);
    else if (this.subject == 'theme')
      this.colors = Params.themes;
    else if (this.subject == 'colorset')
      this.colors = Params.colorSets;
    this.update();
    this.makeMenu();
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
customElements.define('color-choice', ColorChoice);