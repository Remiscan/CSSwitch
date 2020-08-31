const style = document.createElement('template');
style.innerHTML = `
<style>
  :host {
    --size: 100%;
    width: var(--size);
    height: var(--size);
    display: grid;
    place-items: center;
    position: relative;
    top: 0;
    font-size: 200%;
    transform: rotate(var(--deg, 45deg));
  }
  path {
    stroke: var(--bottom-button-icon);
    fill: transparent;
    stroke-width: var(--stroke-width, 4);
  }
  circle {
    fill: var(--bottom-button-icon);
  }
</style>
`;

const templateDroite = document.createElement('template');
templateDroite.innerHTML = `
<svg width="100%" height="100%" viewBox="-1 0 47 48">
  <path d="M 13 2 L 22 2 Q 35 2, 35 12 L 35 36 Q 35 46, 22 46 L 13 46 Z"/>
  <circle r="5" cx="24" cy="31"/>
  <circle r="2" cx="24" cy="11"/>
  <circle r="2" cx="24" cy="19"/>
  <circle r="2" cx="20" cy="15"/>
  <circle r="2" cx="28" cy="15"/>
</svg>
`;

const templateGauche = document.createElement('template');
templateGauche.innerHTML = `
<svg width="100%" height="100%" viewBox="1 0 49 48">
  <path d="M 35 2 L 26 2 Q 13 2, 13 12 L 13 36 Q 13 46, 26 46 L 35 46 Z"/>
  <circle r="5" cx="24" cy="14"/>
  <circle r="2" cx="24" cy="26"/>
  <circle r="2" cx="24" cy="34"/>
  <circle r="2" cx="20" cy="30"/>
  <circle r="2" cx="28" cy="30"/>
</svg>
`;

class JoyconIcon extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.appendChild(style.content.cloneNode(true));
  }

  connectedCallback() {
    const side = (this.getAttribute('side') == 'left') ? 'left' : 'right';
    const template = (side == 'left') ? templateGauche : templateDroite;
    this.shadow.appendChild(template.content.cloneNode(true));
  }
}
if (!customElements.get('joycon-icon')) customElements.define('joycon-icon', JoyconIcon);