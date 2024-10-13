import sheet from 'boole-and-the-boulders-styles' with { type: 'css' };
import template from 'boole-and-the-boulders-template';
import { Jeu } from 'jeu';



export default class BooleAndTheBoulders extends Jeu {
  constructor() {
    super(template);
    this.element.shadow.adoptedStyleSheets = [sheet];
    this.start();
  }

  static get id() {
    return 'boole-and-the-boulders';
  }

  async start() {
    await super.start();
    this.element.dataset.jeu = BooleAndTheBoulders.id;
    const lang = document.documentElement.lang;
    this.element.shadow.querySelector('iframe')?.setAttribute('src', `/games/boole-and-the-boulders/?lang=${lang}`);
    this.element.setAttribute('open', '');
    console.log('Jeu démarré');
  }
}