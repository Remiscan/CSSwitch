import { Params, wait } from 'Params';
import { getString } from 'traduction';



const template = document.createElement('template');
template.innerHTML = `
  <input type="radio" class="color">
  <label></label>
`;

export class ColorChoice extends HTMLElement {
  constructor() {
    super();
  }

  makeMenu() {
    const colors = this.colors;
    this.style.setProperty('--total', colors.length);
    this.innerHTML = '';
    for (const color of colors) {
      const buttonCont = template.content.cloneNode(true);
      const input = buttonCont.querySelector('input');
      input.id = `color-${this.section}-${color.id}`;
      input.name = `color-${this.section}`;
      if (color.id == Params.currentColors[this.section]) input.checked = true;
      
      const button = buttonCont.querySelector('label');
      button.setAttribute('for', input.id);
      button.style.setProperty('--color', Params.getColorHex(color.id, ['theme'].includes(this.section) ? this.section : 'all'));
      button.innerHTML = getString(`couleur-nom-${color.id}`);
      button.dataset.string = `couleur-nom-${color.id}`;

      input.addEventListener('change', async () => {
        await wait(300);
        if (!input.checked) return;
        window.dispatchEvent(new CustomEvent(`${this.subject}colorchange`, { detail: { section: this.section, color: color } }));
      });
      input.addEventListener('focus', () => button.scrollIntoView({block:'nearest'}));

      this.appendChild(buttonCont);
    }
  }

  static get observedAttributes() {
    return ['open'];
  }

  update(attributes = ColorChoice.observedAttributes) {
    if (!this.ready) return;
  }

  connectedCallback() {
    const stylesheet = this.getRootNode().styleSheets[0];
    stylesheet.insertRule(`
      color-choice {
        display: grid;
        grid-template-rows: repeat(var(--total), 3em);
        gap: 1em;
      }
    `);
    this.ready = true;
    this.section = this.getAttribute('data-section');
    this.subject = this.getAttribute('subject');
    if (this.subject == 'controller')
      this.colors = Params.colors(this.section);
    else if (this.subject == 'theme')
      this.colors = Params.themes;
    else if (this.subject == 'colorset')
      this.colors = Params.colorSets;
    this.update();
    this.makeMenu();
    this.querySelector('input:checked + label').scrollIntoView({block:'center'});
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
if (!customElements.get('color-choice')) customElements.define('color-choice', ColorChoice);