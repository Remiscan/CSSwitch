import { Jeu } from 'jeu';
import { getString } from 'traduction';
import 'component-joyconIcon';
import 'component-settingsIcon';



const template = document.createElement('template');
template.innerHTML = `
  <style>
    <?php include './styles.css'; ?>
  </style>
  
  <header></header>
  <nav></nav>
  <section></section>
  <footer></footer>
`;

export default class SettingsMenu extends Jeu {
  constructor(sections, screenName, icon = 'settings') {
    super(template);
    this.sections = sections;
    this.id = screenName;
    this.icon = icon;
  }

  getElement(selector) {
    return this.element.shadowRoot.querySelector(selector);
  }

  getElements(selector) {
    return Array.from(this.element.shadowRoot.querySelectorAll(selector));
  }

  async start() {
    await super.start();

    // Populate header
    const header = this.getElement('header');
    header.appendChild(document.createElement(`${this.icon}-icon`));

    const title = document.createElement('span');
    title.innerHTML = getString(`settings-${this.id}-title`);
    title.dataset.string = `settings-${this.id}-title`;
    header.appendChild(title);

    // Populate footer
    const footer = this.getElement('footer');
    footer.innerHTML += `<span data-string="settings-${this.id}-footer">${getString(`settings-${this.id}-footer`)}</span>`;

    // Populate side nav menu
    const nav = this.getElement('nav');
    nav.style.setProperty('--total', this.sections.length);
    const container = this.getElement('section');
    for (const section of this.sections) {
      const button = document.createElement('button');
      button.dataset.section = section;
      button.innerHTML = `<span data-string="settings-${this.id}-section-${section}">${getString(`settings-${this.id}-section-${section}`)}</span>`;
      nav.appendChild(button);

      const div = document.createElement('div');
      div.dataset.section = section;
      container.appendChild(div);
    }

    // Prepare button / section styling
    const stylesheet = this.element.shadow.styleSheets[1];
    stylesheet.insertRule(`
      ${this.sections.map(s => `:host([data-section=${s}])>nav>button[data-section=${s}]`).join(',')} {
        color: var(--settings-menu-highlight);
      }
    `);
    stylesheet.insertRule(`
      ${this.sections.map(s => `:host([data-section=${s}])>nav>button[data-section=${s}]::before`).join(',')} {
        content: '';
        grid-column: 2;
        display: block;
        width: 100%;
        height: 80%;
        background-color: var(--settings-menu-highlight);
        place-self: center;
      }
    `);
    stylesheet.insertRule(`
      ${this.sections.map(s => `:host([data-section=${s}])>section>[data-section=${s}]`).join(',')} {
        display: grid;
      }
    `);

    // Enable clicks on nav buttons
    for (const section of this.sections) {
      const button = this.getElement(`button[data-section='${section}']`);
      button.addEventListener('click', () => {
        this.element.dataset.section = section;
        const div = this.element.shadowRoot.querySelector(`div[data-section='${section}'], color-choice[data-section='${section}']`);
        div.querySelector('input:checked + label').scrollIntoView({block:'nearest'});
      });
    }

    this.element.dataset.section = this.sections[0];
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
}