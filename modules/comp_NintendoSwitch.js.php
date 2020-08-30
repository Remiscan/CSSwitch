// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import './comp_MainMenu.js.php';
import { Params } from './mod_Params.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



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
    const menu = this.shadowRoot.querySelector('main-menu');
    menu.setAttribute('open', '');
    menu.shadowRoot.querySelector('button.menu-icone-jeu:first-child').focus();
    this.setAttribute('on', '');
  }

  turnOff() {
    const menu = this.shadowRoot.querySelector('main-menu');
    menu.removeAttribute('open');
    Array.from(this.shadowRoot.querySelectorAll('jeu-switch')).forEach(jeu => jeu.remove());
    this.removeAttribute('on');
  }

  goHome() {
    Array.from(this.shadowRoot.querySelectorAll('jeu-switch')).forEach(jeu => {
      const close = jeu.animate([
        { opacity: '1', transform: 'scale(1)' },
        { opacity: '0', transform: 'scale(.8)' }
      ], {
        duration: 100,
        fill: 'forwards'
      });
      close.onfinish = () => jeu.remove();
    });
  }

  get on() {
    return this.shadowRoot.querySelector('main-menu').getAttribute('open') !== null;
  }

  detectColorChanges() {
    window.addEventListener('controllercolorchange', event => {
      const side = (event.detail.section == 'right') ? 'droit' : 'gauche';
      const joycon = this.shadowRoot.querySelector(`.joycon.${side}`);
      joycon.style.setProperty('--joycon-color', Params.getColorHex(event.detail.color.id));
      joycon.dataset.color = event.detail.color.id;
      Params.currentColors[event.detail.section] = event.detail.color.id;
      localStorage.setItem(`csswitch/joycon-${side}`, event.detail.color.id);
    });

    window.addEventListener('colorsetcolorchange', event => {
      Params.currentColors[event.detail.section] = event.detail.color.id;
      this.colorizeJoycons();
      localStorage.setItem('csswitch/colorset', event.detail.color.id);
    });
  }

  colorizeJoycons() {
    ['gauche', 'droit'].map(side => this.shadowRoot.querySelector(`.joycon.${side}`)).forEach(joycon => {
      const side = (joycon.classList.contains('gauche')) ? 'left' : 'right';
      joycon.style.setProperty('--joycon-color', Params.getColorHex(joycon.dataset.color || Params.currentColors[side]));
    });
  }

  detectButtonPresses() {
    const buttonElements = [
      ...Array.from(this.shadowRoot.querySelectorAll('button')),
      ...Array.from(this.shadowRoot.querySelector('main-menu').shadowRoot.querySelectorAll('button'))
    ];
    this.buttons = buttonElements.map(buttonEl => {
      return { 
        key: buttonEl.dataset.key,
        pressed: false,
        lastPress: 0,
        element: buttonEl,
        otherKeys: (buttonEl.dataset.otherKeys || '').split(',')
      }
    });

    for (const buttonEl of buttonElements) {
      const button = this.buttons.find(b => b.key == buttonEl.dataset.key);
      buttonEl.addEventListener('mousedown', () => NintendoSwitch.dispatchButtonEvent(button, 'buttonpress'));
      buttonEl.addEventListener('touchstart', () => NintendoSwitch.dispatchButtonEvent(button, 'buttonpress'));
    }

    window.addEventListener('keydown', event => {
      const switchButton = this.buttons.find(b => b.otherKeys.includes(event.code));
      if (!switchButton) return;
      switchButton.element.classList.add('active');
      switchButton.element.dispatchEvent(new Event('mousedown'));
    });
    window.addEventListener('keyup', event => {
      const switchButton = this.buttons.find(b => b.otherKeys.includes(event.code));
      if (!switchButton) return;
      switchButton.element.classList.remove('active');
      switchButton.element.dispatchEvent(new Event('mouseup'));
    });
  }

  static dispatchButtonEvent(button, type, duration = 0) {
    const event = new CustomEvent(type, { 
      bubbles: true,
      composed: true,
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
    this.update();
    this.detectButtonPresses();
    this.detectColorChanges();

    this.colorizeJoycons();

    window.addEventListener('buttonclick', event => {
      // Turn on the console when the Home button is clicked
      // or go back to the Home menu if it's already on
      if (event.detail.button.key == 'home') {
        if (this.on) this.goHome();
        else this.turnOn();
        return;
      }

      // Turn off the console when the Power button is clicked
      if (event.detail.button.key == 'power') {
        if (this.on) this.turnOff();
        return;
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

const log = true;
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
  if (log) console.log('Button press:', button.key, event.detail.time);
});

window.addEventListener('buttonrelease', event => {
  const button = event.detail.button;
  if (!button.pressed) return;
  button.pressed = false;
  const duration = event.detail.time - button.lastPress;
  NintendoSwitch.dispatchButtonEvent(button, 'buttonclick', duration);
  if (log) console.log('Button release:', button.key, event.detail.time);
});

window.addEventListener('buttonclick', event => {
  const button = event.detail.button;
  if (log) console.log('Button click:', button.key, event.detail.duration);
});