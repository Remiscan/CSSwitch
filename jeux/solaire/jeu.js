import { Jeu } from 'jeu';
import sheet from 'solaire-styles' with { type: 'css' };
import template from 'solaire-template';



export default class Solaire extends Jeu {
  constructor() {
    super(template);
    this.element.shadow.adoptedStyleSheets = [sheet];
    this.start();
  }

  static get id() {
    return 'solaire';
  }

  async start() {
    await super.start();
    this.element.dataset.jeu = Solaire.id;
    const lang = document.documentElement.lang;
    this.element.shadow.querySelector('iframe')?.setAttribute('src', `/solaire/?lang=${lang}`);
    this.element.setAttribute('open', '');
    console.log('Jeu démarré');
  }
}