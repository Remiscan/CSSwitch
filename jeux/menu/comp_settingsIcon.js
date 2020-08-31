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
    fill: var(--bottom-button-icon);
  }
  circle {
    stroke: var(--bottom-button-icon);
    fill: transparent;
  }
</style>

<svg width="100%" height="100%" viewBox="0 0 48 48">
  <circle cx="24" cy="24" r="14" stroke-width="4"/>
  <rect x="22" y="1" width="4" height="8"/>
  <rect x="22" y="1" width="4" height="8" transform-origin="24 24" transform="rotate(45)"/>
  <rect x="22" y="1" width="4" height="8" transform-origin="24 24" transform="rotate(90)"/>
  <rect x="22" y="1" width="4" height="8" transform-origin="24 24" transform="rotate(135)"/>
  <rect x="22" y="1" width="4" height="8" transform-origin="24 24" transform="rotate(180)"/>
  <rect x="22" y="1" width="4" height="8" transform-origin="24 24" transform="rotate(-45)"/>
  <rect x="22" y="1" width="4" height="8" transform-origin="24 24" transform="rotate(-90)"/>
  <rect x="22" y="1" width="4" height="8" transform-origin="24 24" transform="rotate(-135)"/>
</svg>
`;

class SettingsIcon extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.appendChild(template.content.cloneNode(true));
  }
}
customElements.define('settings-icon', SettingsIcon);