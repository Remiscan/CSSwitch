import { Params } from 'Params';
import { bounce, moveJoycon } from 'animateJoycons';
import 'component-mainMenu';
import sheet from 'component-nintendoSwitch-styles' with { type: 'css' };
import template from 'component-nintendoSwitch-template';
import { Traduction } from 'traduction';



let animationEnCours = false;

class NintendoSwitch extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.appendChild(template.content.cloneNode(true));
    this.shadow.adoptedStyleSheets = [sheet];
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
    menu.addEventListener('animationend', event => {
      if (event.target != menu) return;
      menu.shadowRoot.querySelector('button, input').focus();
    });
    menu.setAttribute('open', '');
    this.setAttribute('on', '');
    this.setAttribute('data-was-open', '');
  }

  turnOff() {
    const menu = this.getElement('main-menu');
    menu.removeAttribute('open');
    this.getElements('jeu-switch').forEach(jeu => jeu.remove());
    this.removeAttribute('on');
  }

  enableButtons() {
    const buttons = Array.from(this.shadowRoot.querySelectorAll('button.c[data-key]'));
    buttons.forEach(b => { b.disabled = false; b.tabIndex = 0; });
  }

  disableButtons() {
    const buttons = Array.from(this.shadowRoot.querySelectorAll('button.c[data-key]'));
    buttons.forEach(b => { b.disabled = true; b.tabIndex = -1; });
  }

  goHome() {
    let iconToFocus = null;
    const menu = this.getElement('main-menu');
    this.getElements('jeu-switch').forEach(jeu => {
      iconToFocus = jeu.dataset.menu || jeu.dataset.jeu;
      const close = jeu.animate([
        { opacity: '1', transform: 'scale(1)' },
        { opacity: '0', transform: 'scale(.8)' }
      ], {
        duration: 100,
        fill: 'forwards'
      });
      close.onfinish = () => {
        jeu.remove();
        if (iconToFocus) iconToFocus = menu.shadowRoot.querySelector(`[data-key=${iconToFocus}]`)
                                    || menu.shadowRoot.querySelector(`button[data-jeu=${iconToFocus}]`);
        else iconToFocus = menu.shadowRoot.querySelector('button, input');
        iconToFocus.focus();
      }
    });
    menu.enable();
    this.disableButtons();
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
      colorMenu.shadowRoot.querySelector('input:checked').focus();
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
      const switchButton = this.buttons.find(b => b.otherKeys.includes(event.code || event.key));
      if (!switchButton) return;
      if (!switchButton.element.disabled)
        switchButton.element.classList.add('active');
      switchButton.element.dispatchEvent(new Event('mousedown'));
    });
    window.addEventListener('keyup', event => {
      const switchButton = this.buttons.find(b => b.otherKeys.includes(event.code || event.key));
      if (!switchButton) return;
      switchButton.element.classList.remove('active');
      switchButton.element.dispatchEvent(new Event('mouseup'));
    });

    // Enable buttons when a game opens
    window.addEventListener('gameopen', event => this.enableButtons());
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
    return ['width', 'screen-size', 'screen-width', 'screen-height', 'model'];
  }

  update(attributes = NintendoSwitch.observedAttributes) {
    width: {
      if (!attributes.includes('width')) break width;
      this.style.setProperty('--width', this.getAttribute('width'));
    }

    screenSize: {
      if (!attributes.includes('screen-size')) break screenSize;
      this.style.setProperty('--screen-size', this.getAttribute('screen-size'));
    }

    screenWidth: {
      if (!attributes.includes('screen-width')) break screenWidth;
      this.style.setProperty('--screen-width', this.getAttribute('screen-width'));
    }

    screenHeight: {
      if (!attributes.includes('screen-height')) break screenHeight;
      this.style.setProperty('--screen-height', this.getAttribute('screen-height'));
    }

    model: {
      if (!attributes.includes('model')) break model;
      this.setAttribute('screen-size', Math.round(this.shadow.querySelector('.screen').getBoundingClientRect().width));
    }
  }

  async traduire() {
    await Traduction.traduire(this.shadowRoot);
    return;
  }

  connectedCallback() {
    this.setAttribute('theme', Params.theme);
    this.disableButtons();
    this.detectButtonPresses();
    this.detectColorChanges();
    this.colorizeJoycons();
    this.traduire();

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

const log = !true;
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