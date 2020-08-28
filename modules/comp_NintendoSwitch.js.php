// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import './comp_MenuSwitch.js.php';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 2).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/



const template = document.createElement('template');
template.innerHTML = `
  <style><?php include './comp_NintendoSwitch.css'; ?></style>
  <?php include './comp_NintendoSwitch.html'; ?>
`;

let animationEnCours = false;

class NintendoSwitch extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.appendChild(template.content.cloneNode(true));
  }

  turnOn() {
    this.shadowRoot.querySelector('menu-switch').setAttribute('open', '');
    this.shadowRoot.querySelector('menu-switch').shadowRoot.querySelector('#jeu-1').focus();
  }

  turnOff() {
    this.shadowRoot.querySelector('menu-switch').removeAttribute('open');
    Array.from(this.shadowRoot.querySelectorAll('jeu-switch')).forEach(jeu => jeu.remove());
  }

  get on() {
    return this.shadowRoot.querySelector('menu-switch').getAttribute('open') !== null;
  }

  detectButtonPresses() {
    const buttonElements = Array.from(this.shadowRoot.querySelectorAll('button'));
    this.buttons = buttonElements.map(buttonEl => {
      return { 
        key: buttonEl.dataset.key,
        pressed: false,
        lastPress: 0,
        element: buttonEl
      }
    });

    for (const buttonEl of buttonElements) {
      const button = this.buttons.find(b => b.key == buttonEl.dataset.key);
      buttonEl.addEventListener('mousedown', () => NintendoSwitch.dispatchButtonEvent(button, 'buttonpress'));
      buttonEl.addEventListener('touchstart', () => NintendoSwitch.dispatchButtonEvent(button, 'buttonpress'));
    }
  }

  static dispatchButtonEvent(button, type, duration = 0) {
    const event = new CustomEvent(type, { 
      detail: { 
        button,
        time: Date.now(),
        duration
      }
    });
    return window.dispatchEvent(event);
  }

  static get observedAttributes() {
    return ['width', 'screen-size'];
  }

  update(attributes = NintendoSwitch.observedAttributes) {
    if (!this.ready) return;

    width: {
      if (!attributes.includes('width')) break width;
      this.style.setProperty('--width', this.getAttribute('width'));
    }

    screenSize: {
      if (!attributes.includes('screen-size')) break screenSize;
      this.style.setProperty('--screen-size', this.getAttribute('screen-size'));
    }
  }

  connectedCallback() {
    this.ready = true;
    const nintendoSwitch = document.querySelector('.nintendo-switch');
    const consoleSwitch = document.querySelector('.screen');
    const joyconG = document.getElementsByClassName('joycon')[0];
    const joyconD = document.getElementsByClassName('joycon')[1];
    const homeButton = document.querySelector('.home');
    this.update();
    this.detectButtonPresses();

    window.addEventListener('buttonclick', event => {
      // Turn on the console when the Home button is clicked
      if (event.detail.button.key == 'home') {
        if (this.on) this.turnOff();
        else this.turnOn();
      }
    })
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
customElements.define('nintendo-switch', NintendoSwitch);



//////////////////////////////////////////////////
// Let's dispatch events related to button presses

window.addEventListener('buttonpress', event => {
  const button = event.detail.button;
  if (button.pressed) return;
  button.pressed = true;
  const buttonEl = button.element;
  button.lastPress = event.detail.time;
  buttonEl.addEventListener('mouseup', () => NintendoSwitch.dispatchButtonEvent(button, 'buttonrelease'));
  buttonEl.addEventListener('mouseleave', () => NintendoSwitch.dispatchButtonEvent(button, 'buttonrelease'));
  buttonEl.addEventListener('touchend', () => NintendoSwitch.dispatchButtonEvent(button, 'buttonrelease'));
  buttonEl.addEventListener('touchcancel', () => NintendoSwitch.dispatchButtonEvent(button, 'buttonrelease'));
  //console.log('Button press:', button.key, event.detail.time);
});

window.addEventListener('buttonrelease', event => {
  const button = event.detail.button;
  if (!button.pressed) return;
  button.pressed = false;
  const duration = event.detail.time - button.lastPress;
  NintendoSwitch.dispatchButtonEvent(button, 'buttonclick', duration);
  //console.log('Button release:', button.key, event.detail.time);
});

window.addEventListener('buttonclick', event => {
  const button = event.detail.button;
  //console.log('Button click:', button.key, event.detail.duration);
});