const template = document.createElement('template');
template.innerHTML = /*html*/`
<div class="jeu-bg">
  <div class="jeu-titre" data-string="titre-jeu-press-on-sound"></div>
  <button type="button" class="jeu-bouton" data-label="press-on-sound-bouton">
    <div class="jeu-bouton-bouton"></div>
  </button>
  <div class="jeu-score">
    <div class="jeu-score-record">
      <span data-string="score-record"></span>
      <span id="score-record">---</span>
      <span>ms</span>
    </div>
    <div class="jeu-score-record">
      <span data-string="score-dernier"></span>
      <span id="score-dernier">---</span>
      <span>ms</span>
    </div>
  </div>
</div>
`;

export default template;