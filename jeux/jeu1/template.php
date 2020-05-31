<div class="jeu-bg">
  <div class="jeu-titre" data-string="titre-jeu-1"><?=$Textes->getString('titre-jeu-1')?></div>
  <button class="jeu-bouton" tabindex="-1" data-string="press-on-sound-bouton">
    <?=$Textes->getString('press-on-sound-bouton')?>
    <div class="jeu-bouton-bouton"></div>
  </button>
  <div class="jeu-score">
    <div class="jeu-score-record">
      <span data-string="score-record"><?=$Textes->getString('score-record')?></span>
      <span id="score-record">---</span>
      <span>ms</span>
    </div>
    <div class="jeu-score-record">
      <span data-string="score-dernier"><?=$Textes->getString('score-dernier')?></span>
      <span id="score-dernier">---</span>
      <span>ms</span>
    </div>
  </div>
</div>