// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Traduction, getString } from './mod_traduction.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



export class Jeu {
  constructor(template) {
    this.template = template;
    const screen = document.querySelector('nintendo-switch').shadowRoot.querySelector('.jeu');
    // Remove other open games
    Array.from(screen.querySelectorAll('jeu-switch')).forEach(jeu => jeu.remove());
    // Open new game
    this.element = new JeuSwitch();
    screen.appendChild(this.element);
  }

  async start() {
    this.element.shadow.appendChild(this.template.content.cloneNode(true));
    await Traduction.traduire(this.element.shadowRoot);
    this.element.setAttribute('open', '');
  }
}



const template = document.createElement('template');
template.innerHTML = `
  <style>
    :host {
      display: grid;
      opacity: 0;
      pointer-events: none;
      grid-row: 1;
      grid-column: 1;
      z-index: 2;
      position: relative;
    }
    :host([open]) {
      opacity: 1;
      pointer-events: auto;
      animation: allumage .2s var(--easing-decelerate) -.1s;
    }
    @keyframes allumage {
      0% { opacity: 0; transform: scale(.4); }
      100% { opacity: 1; transform: scale(1); }
    }
  </style>
`;

export class JeuSwitch extends HTMLElement {
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
    return;
  }

  update(attributes = JeuSwitch.observedAttributes) {
    if (!this.ready) return;

    open: {
      if (!attributes.includes('open')) break open;
      if (this.getAttribute('open') == null) {
        this.shadowRoot.innerHTML = '';
        this.shadow.appendChild(template.content.cloneNode(true));
      }
    }
  }

  connectedCallback() {
    this.ready = true;
    this.update();
    this.traduire();
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
customElements.define('jeu-switch', JeuSwitch);