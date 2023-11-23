import { Jeu } from 'jeu';
import sheet from 'solaire-styles' assert { type: 'css' };
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
    this.element.setAttribute('open', '');
    console.log('Jeu démarré');
  }
}