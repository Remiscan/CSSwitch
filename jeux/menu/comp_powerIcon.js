const template = document.createElement('template');
template.innerHTML = `
<style>
  :host {
    --size: 50%;
    width: var(--size);
    height: var(--size);
    display: grid;
    place-items: center;
    position: relative;
    top: 0;
    font-size: 200%;
  }
  svg {
    fill: #DADADA;
  }
  circle {
    stroke: #DADADA;
    fill: transparent;
  }
  rect.bg {
    fill: var(--bouton-color);
  }
</style>

<svg width="100%" height="100%" viewBox="0 0 48 48">
  <circle cx="24" cy="24" r="18" stroke-width="4"/>
  <rect x="16" y="0" width="16" height="22" class="bg"/>
  <rect x="22" y="0" width="4" height="22"/>
</svg>
`;

class PowerIcon extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.appendChild(template.content.cloneNode(true));
  }
}
customElements.define('power-icon', PowerIcon);