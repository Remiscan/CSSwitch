import { Traduction } from 'traduction';



export class Jeu {
  constructor(template) {
    this.template = template;
    const screen = document.querySelector('nintendo-switch').shadowRoot.querySelector('.jeu');
    // Remove other open games
    Array.from(screen.querySelectorAll('jeu-switch')).forEach(jeu => jeu.remove());
    // Disable main menu
    const menu = document.querySelector('nintendo-switch').shadowRoot.querySelector('main-menu');
    menu.disable();
    // Open new game
    this.element = new JeuSwitch();
    screen.appendChild(this.element);
  }

  async start() {
    this.element.shadow.appendChild(this.template.content.cloneNode(true));
    await Traduction.traduire(this.element.shadowRoot);
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
      max-height: 100%;
      overflow: hidden;
      font-size: calc(1.3 * var(--font-size));
    }
    :host([open]) {
      opacity: 1;
      pointer-events: auto;
      animation: allumage .1s var(--easing-decelerate);
    }
    @keyframes allumage {
      0% { opacity: .5; transform: scale(.8); }
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
    window.addEventListener('translation-request', () => this.traduire());
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
if (!customElements.get('jeu-switch')) customElements.define('jeu-switch', JeuSwitch);