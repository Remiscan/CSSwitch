import sheet from 'boole-and-the-boulders-styles' assert { type: 'css' };
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
    this.element.setAttribute('open', '');
    console.log('Jeu démarré');
  }
}