// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Jeu } from '../../modules/mod_jeu.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const template = document.createElement('template');
template.innerHTML = `
  <style><?php include './styles.css'; ?></style>
  <?php include './template.html'; ?>
`;

export default class PressOnSound extends Jeu {
  constructor() {
    super(template);
    this.lastGame = Date.now();
    this.bestScore = localStorage.getItem('csswitch/best-score');
    this.start();
  }

  static get id() {
    return 'press-on-sound';
  }

  async start() {
    await super.start();
    this.element.dataset.jeu = PressOnSound.id;
    this.element.setAttribute('open', '');
    console.log('Jeu démarré');

    await this.prepareSound();

    const storedScore = localStorage.getItem('csswitch/best-score');
    const bestScore = (storedScore == null) ? '---' : storedScore;

    const bestScoreElement = this.element.shadowRoot.querySelector('#score-record');
    const lastScoreElement = this.element.shadowRoot.querySelector('#score-dernier');
    bestScoreElement.innerHTML = bestScore;

    const boutonJeu = this.element.shadowRoot.querySelector('.jeu-bouton');
    window.addEventListener('buttonpress', event => {
      if (!['up', 'down', 'left', 'right', 'a', 'b', 'x', 'y'].includes(event.detail.button.key)) return;
      boutonJeu.dispatchEvent(new Event('click'));
      boutonJeu.classList.add('active');
    });
    window.addEventListener('buttonrelease', event => {
      if (!['up', 'down', 'left', 'right', 'a', 'b', 'x', 'y'].includes(event.detail.button.key)) return;
      boutonJeu.classList.remove('active');
    });

    const newRound = async () => {
      if (this.element.isConnected === false) return;
      document.removeEventListener('visibilitychange', newRound);

      try {
        const score = await this.round();
        lastScoreElement.innerHTML = score;
        this.bestScore = (this.bestScore == null) ? score
                       : (this.bestScore > score) ? score
                       : this.bestScore;
        localStorage.setItem('csswitch/best-score', this.bestScore);
        bestScoreElement.innerHTML = this.bestScore;
      }
      catch(error) {
        console.log(error);
      }

      if (!document.hidden) return newRound();
      else document.addEventListener('visibilitychange', newRound);
    }
    return newRound();
  }

  async round(id = Date.now()) {
    this.lastGame = id;

    const wait = time => new Promise(resolve => setTimeout(resolve, time));
    const boutonJeu = this.element.shadowRoot.querySelector('.jeu-bouton');
    boutonJeu.classList.remove('on');
    boutonJeu.classList.remove('cheating');
    let startTime;

    boutonJeu.addEventListener('click', async () => {
      if (id != this.lastGame) return;

      if (startTime == null) {
        boutonJeu.classList.add('cheating');
        await wait(3000);
        boutonJeu.classList.remove('cheating');
        return;
      }

      const endTime = Date.now();
      const score = endTime - startTime;
      boutonJeu.dispatchEvent(
        new CustomEvent('gamewin', { detail: { id, score } })
      );
    });

    const delai = Math.round(5000 + 10000 * Math.random());
    await wait(delai);

    if (id != this.lastGame)
      throw 'Nouvelle partie commencée';

    if (this.element.isConnected === false || this.element.getAttribute('open') === null)
      throw 'Jeu fermé';

    if (document.hidden)
      throw 'Page inactive';

    if (boutonJeu.classList.contains('cheating'))
      throw 'Tentative de triche';

    this.playSound();
    startTime = Date.now() + 1000 * this.audioCtx.baseLatency;
    await wait(1000 * this.audioCtx.baseLatency);
    boutonJeu.classList.add('on');

    setTimeout(() => {
      boutonJeu.classList.remove('on');
      boutonJeu.dispatchEvent(
        new CustomEvent('gamelose', { detail: { id } })
      );
    }, 900);

    return await new Promise((resolve, reject) => {
      boutonJeu.addEventListener('gamewin', event => {
        resolve(event.detail.score);
      });
      boutonJeu.addEventListener('gamelose', event => {
        reject('Délai écoulé');
      });
    });
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