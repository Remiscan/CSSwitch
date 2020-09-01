// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import './comp_MainMenu.js.php';
import { Params } from './mod_Params.js.php';
import { moveJoycon, bounce } from './mod_animateJoycons.js.php';

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

  getElement(selector) {
    return this.shadowRoot.querySelector(selector);
  }

  getElements(selector) {
    return Array.from(this.shadowRoot.querySelectorAll(selector));
  }

  turnOn() {
    if (!this.audioCtx) this.prepareSound();
    const menu = this.getElement('main-menu');
    menu.setAttribute('open', '');
    menu.shadowRoot.querySelector('button.menu-icone-jeu:first-child').focus();
    this.setAttribute('on', '');
  }

  turnOff() {
    const menu = this.getElement('main-menu');
    menu.removeAttribute('open');
    this.getElements('jeu-switch').forEach(jeu => jeu.remove());
    this.removeAttribute('on');
  }

  goHome() {
    this.getElements('jeu-switch').forEach(jeu => {
      const close = jeu.animate([
        { opacity: '1', transform: 'scale(1)' },
        { opacity: '0', transform: 'scale(.8)' }
      ], {
        duration: 100,
        fill: 'forwards'
      });
      close.onfinish = () => jeu.remove();
    });
    const menu = this.getElement('main-menu');
    menu.enable();
  }

  get on() {
    return this.getElement('main-menu').getAttribute('open') !== null;
  }

  detectColorChanges() {
    window.addEventListener('controllercolorchange', async event => {
      const colorMenu = this.shadowRoot.querySelector('jeu-switch');
      colorMenu.disable();

      if (!this.audioCtx) await this.prepareSound();

      const side = (event.detail.section == 'right') ? 'droit' : 'gauche';
      const joycon = this.getElement(`.joycon.${side}`);
      await moveJoycon(side, 'up');

      joycon.style.setProperty('--joycon-color', Params.getColorHex(event.detail.color.id));
      joycon.dataset.color = event.detail.color.id;
      Params.currentColors[event.detail.section] = event.detail.color.id;
      localStorage.setItem(`csswitch/joycon-${side}`, event.detail.color.id);

      await moveJoycon(side, 'down');
      this.playSound();
      await new Promise(resolve => setTimeout(resolve, this.audioCtx.baseLatency));
      await bounce();

      colorMenu.enable();
    });

    window.addEventListener('colorsetcolorchange', event => {
      Params.currentColors[event.detail.section] = event.detail.color.id;
      localStorage.setItem('csswitch/colorset', event.detail.color.id);
      this.colorizeJoycons();
    });

    window.addEventListener('themecolorchange', event => {
      Params.currentColors[event.detail.section] = event.detail.color.id;
      localStorage.setItem('csswitch/theme', event.detail.color.id);
      this.setAttribute('theme', Params.theme);
      document.documentElement.dataset.theme = Params.theme;
    });
  }

  colorizeJoycons() {
    ['gauche', 'droit'].map(side => this.getElement(`.joycon.${side}`)).forEach(joycon => {
      const side = (joycon.classList.contains('gauche')) ? 'left' : 'right';
      joycon.style.setProperty('--joycon-color', Params.getColorHex(joycon.dataset.color || Params.currentColors[side]));
    });
  }

  detectButtonPresses() {
    const buttonElements = [
      ...this.getElements('button'),
      ...Array.from(this.getElement('main-menu').shadowRoot.querySelectorAll('button'))
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
    this.setAttribute('theme', Params.theme);
    this.ready = true;
    this.update();
    this.detectButtonPresses();
    this.detectColorChanges();
    this.colorizeJoycons();

    Element.prototype.disable = function() {
      const el = this.shadowRoot || this;
      const buttons = Array.from(el.querySelectorAll('button'));
      buttons.forEach(button => { button.disabled = true; button.tabIndex = -1; });
      const inputs = Array.from(el.querySelectorAll('input'));
      inputs.forEach(input => { input.disabled = true; });
    }
  
    Element.prototype.enable = function() {
      const el = this.shadowRoot || this;
      const buttons = Array.from(el.querySelectorAll('button'));
      buttons.forEach(button => { button.disabled = false; button.tabIndex = 0; });
      const inputs = Array.from(el.querySelectorAll('input'));
      inputs.forEach(input => { input.disabled = false; });
    }

    // Detect clicks on home button
    this.shadowRoot.querySelector('button[data-key=home]')
        .addEventListener('click', () => {
      if (this.on) this.goHome();
      else this.turnOn();
    });
    // Detect alternative buttons for home
    window.addEventListener('buttonclick', event => {
      if (event.detail.button.key != 'home') return;
      if (this.on) this.goHome();
      else this.turnOn();
    });
    // Detect clicks on power button
    this.shadowRoot.querySelector('main-menu')
        .shadowRoot.querySelector('button[data-key=power]')
        .addEventListener('click', () => {
        if (this.on) this.turnOff();
    });
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
if (!customElements.get('nintendo-switch')) customElements.define('nintendo-switch', NintendoSwitch);



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