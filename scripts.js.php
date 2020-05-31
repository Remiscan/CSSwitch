// ▼ ES module cache-busted grâce à PHP
/*<?php
// en commentaire rusé pour éviter l'erreur de syntaxe dans VS code due à la présence de PHP au milieu du JS
echo '*'.'/';
$commonDir = dirname(__DIR__, 1).'/_common';
require_once $commonDir . '/php/version.php';
$versionModule = version($commonDir.'/js', 'traduction.js');
echo "\n";
echo "import { traduire, getString, switchLangage, getLangage } from '../_common/js/traduction--".$versionModule.".js';";
echo "\n";
echo '/'.'*'; ?>*/



// Nom des éléments et variables
let doIrandomizeColors = false;
let animationEnCours = false;
const nintendoSwitch = document.querySelector('.nintendo-switch');
const consoleSwitch = document.querySelector('.screen');
const joyconG = document.getElementsByClassName('joycon')[0];
const joyconD = document.getElementsByClassName('joycon')[1];
const homeButton = document.querySelector('.home');
const choix = document.querySelector('.choix');
const conteneur = document.querySelector('main');
const bruit = document.getElementById('clic-switch');
function wait(time) { return new Promise(resolve => setTimeout(resolve, time)); }
let takeoffHeight = 0;

///////////////////////
// Couleurs des joy-con
//// Couleurs possibles pour les 2 Joy-Con
const joyconColors = [
  {
    hex: '#6E757B',
    hexOfficiel: '#828282',
    id: 'gris'
  }, {
    hex: '#00BFDF',
    hexOfficiel: '#0AB9E6',
    id: 'bleu-neon'
  }, {
    hex: '#FF5E52',
    hexOfficiel: '#FF3C28',
    id: 'rouge-neon'
  }, {
    hex: '#D9EF64',
    hexOfficiel: '#E6FF00',
    id: 'jaune-neon'
  }, {
    hex: '#00E259',
    hexOfficiel: '#1EDC00',
    id: 'vert-neon'
  }, {
    hex: '#F85187',
    hexOfficiel: '#FF3278',
    id: 'rose-neon'
  }, {
    hex: '#EE2D37',
    hexOfficiel: '#E10F00',
    id: 'mario'
  }, {
    hex: '#D0A880',
    hexOfficiel: '#D7AA73',
    id: 'labo'
  }
];
//// Couleurs propres au Joy-Con gauche
const joyconGonlyColors = [
  {
    hex: '#CAA25A',
    hexOfficiel: '#C88D32',
    id: 'evoli'
  }, {
    hex: '#4456C2',
    id: 'bleu'
  }, {
    hex: '#912FA8',
    id: 'violet-neon'
  }, {
    hex: '#8DE6AF',
    id: 'ac-vert'
  }
];
//// Couleurs propres au Joy-Con droit
const joyconDonlyColors = [
  {
    hex: '#F6D962',
    hexOfficiel: '#FFDD00',
    id: 'pikachu'
  }, {
    hex: '#F0BB37',
    id: 'orange-neon'
  }, {
    hex: '#7DDCE2',
    id: 'ac-bleu'
  }
];
let typeCouleur = 'hex';
const joyconGColors = [...joyconColors, ...joyconGonlyColors]; // Toutes les couleurs possibles du Joy-Con gauche
const joyconDColors = [...joyconColors, ...joyconDonlyColors]; // Toutes les couleurs possibles du Joy-Con droit
const allPossibleColors = [...joyconColors, ...joyconGonlyColors, ...joyconDonlyColors]; // Toutes les couleurs possibles

function getCouleur(couleur, type = typeCouleur || 'hex') { return couleur[type] || couleur['hex']; }
function changeType(type = false) {
  const k1 = allPossibleColors.findIndex(d => d[typeCouleur] == joyconG.style.getPropertyValue('--joycon-color'));
  const k2 = allPossibleColors.findIndex(d => d[typeCouleur] == joyconD.style.getPropertyValue('--joycon-color'));
  if (['hex', 'hexOfficiel'].includes(type))
    typeCouleur = type;
  else
    typeCouleur = (typeCouleur == 'hex') ? 'hexOfficiel' : 'hex';
  changeJoyconColors(allPossibleColors[k1], allPossibleColors[k2], false);
  genererMenuChoix();
}

const boutonsOptions = [document.querySelector('[data-string=option-couleurs-photos]'), document.querySelector('[data-string=option-couleurs-officielles]')];
boutonsOptions.forEach(b => {
  b.addEventListener('click', () => {
    if (b.dataset.string == 'option-couleurs-photos')
      changeType('hex');
    else if (b.dataset.string == 'option-couleurs-officielles')
      changeType('hexOfficiel');

    boutonsOptions.forEach(bouton => {
      if (bouton != b)
        bouton.disabled = false;
      else
        bouton.disabled = true;
    });
  });
});



/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! TEXTE ET TRADUCTION !!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/


let langage;
let Textes;
///////////////////////////////////////////////////////
// Place le texte français ou anglais aux bons endroits
function textualiser()
{
  return traduire('csswitch')
  .then(() => {
    Array.from(document.querySelectorAll('.menu-liste button')).forEach(e => {
      e.style.setProperty('--titre', '\'' + getString('titre-' + e.id) + '\'');
    });

    Array.from(document.querySelectorAll('button.menu-icone-jeu')).forEach(e => {
      e.setAttribute('aria-label', getString('bouton-start-jeu') + ' ' + getString('titre-' + e.id));
    });

    document.title = 'CSSwitch - ' + getString('titre-page-description');

    genererMenuChoix();
    return;
  })
  .catch(error => console.error(error));
}


///////////////////////////////////////////
// Active le bouton de changement de langue
Array.from(document.querySelectorAll('[data-lang]')).forEach(bouton => {
  bouton.addEventListener('click', () => {
    switchLangage(bouton.dataset.lang)
    .then(textualiser);
  });
});


/////////////////////////////////////////////////
// Génération de la fenêtre de choix des couleurs
function genererMenuChoix()
{
  let i = 0;
  Array.from(document.querySelectorAll('.choix button')).forEach(e => e.remove());
  allPossibleColors.forEach(c => {
    const template = document.getElementById('template-couleur').content.cloneNode(true);
    const div = template.querySelector('.couleur');
    div.style.setProperty('--couleur', getCouleur(c));
    div.querySelector('.nom').innerHTML = getString('couleur-nom-' + c.id);
    div.style.setProperty('--n', i);
    div.tabIndex = 0;
    if (joyconGonlyColors.includes(c))
      div.classList.add('gauche');
    else if (joyconDonlyColors.includes(c))
      div.classList.add('droite');
    else
      i++;

    div.addEventListener('mouseover', () => {
      chooseColor(c);
    });
    div.addEventListener('focus', () => {
      chooseColor(c);
    });
    div.addEventListener('click', () => {
      chooseColor(c);
      let side = choix.classList.contains('gauche') ? 'gauche' : choix.classList.contains('droite') ? 'droite' : false;
      
      return openChoice(side);
      //.then(() => conteneur.classList.remove('gauche', 'droite'));
    });

    document.querySelector('.choix').appendChild(template);
  });
}


// Ouvre le menu de choix des couleurs
async function openChoice(side)
{
  animationEnCours = true;
  let coteOuvert = false;

  if (choix.classList.contains('gauche'))
    coteOuvert = 'gauche';
  else if (choix.classList.contains('droite'))
    coteOuvert = 'droite';

  if (!coteOuvert)
  {
    const autreCote = (side == 'gauche') ? 'droite' : 'gauche';
    choix.classList.remove('gauche', 'droite');
    conteneur.classList.remove('gauche', 'droite');
    conteneur.classList.add(side);
    await animeSwitchDetache(side);
    choix.classList.add(side);
    animationEnCours = false;
    Array.from(document.querySelectorAll('[data-lang]')).forEach(bouton => { bouton.tabIndex = -1; });
    homeButton.tabIndex = -1;
    document.getElementById('choix-' + autreCote).querySelector('button').tabIndex = -1;
  }
  else
  {
    choix.classList.remove('gauche', 'droite');
    await animeSwitchAttache(coteOuvert);
    conteneur.classList.remove('gauche', 'droite');
    Array.from(document.querySelectorAll('[data-lang]')).forEach(bouton => { bouton.tabIndex = 0; });
    homeButton.tabIndex = 0;
    document.getElementById('choix-gauche').querySelector('button').tabIndex = 0;
    document.getElementById('choix-droite').querySelector('button').tabIndex = 0;
  }
}
document.getElementById('choix-gauche').addEventListener('click', () => {
  if (!animationEnCours)
    openChoice('gauche');
});
document.getElementById('choix-droite').addEventListener('click', () => {
  if (!animationEnCours)
    openChoice('droite');
});


// Change la couleur des Joy-Con
function changeJoyconColors(colorG = false, colorD = false, random = true)
{
  const joycolorG = colorG || ((random == true) ? joyconGColors[Math.floor(joyconGColors.length * Math.random())] : false);
  const joycolorD = colorD || ((random == true) ? joyconDColors[Math.floor(joyconDColors.length * Math.random())] : false);
  if (joycolorG)
    joyconG.style.setProperty('--joycon-color', getCouleur(joycolorG));
  if (joycolorD)
    joyconD.style.setProperty('--joycon-color', getCouleur(joycolorD));
}


// Change la couleur d'un Joy-Con selon le choix du menu
function chooseColor(couleur)
{
  if (choix.classList.contains('gauche'))
    changeJoyconColors(couleur, false, false);
  if (choix.classList.contains('droite'))
    changeJoyconColors(false, couleur, false);
}


//////////////////////////////////////////////////
// Animations de la Nintendo Switch et des Joy-Con
const easingStandard = 'cubic-bezier(0.4, 0.0, 0.2, 1)';
const easingDecelerate = 'cubic-bezier(0.0, 0.0, 0.2, 1)';
const easingAccelerate = 'cubic-bezier(0.4, 0.0, 1, 1)';

// Déplace un Joy-Con
function moveJoycon(joycon, direction = 'up')
{
  animationEnCours = true;
  const takeoffKeyframes = [
    { transform: 'translate3d(0, 0, 0)'},
    { transform: 'translate3d(0, ' + Math.round(-1 * takeoffHeight) + 'px, 0)'}
  ];

  const options = {
    easing: easingStandard,
    fill: 'forwards',
    duration: (direction == 'up') ? 500 : 250
  };

  return new Promise((resolve, reject) => {
    let keyframes;
    if (direction == 'up')
      keyframes = [takeoffKeyframes[0], takeoffKeyframes[1]];
    else
      keyframes = [takeoffKeyframes[1], takeoffKeyframes[0]];
    
    const animation = joycon.animate(keyframes, options);
    animation.onfinish = resolve;
  })
}

// Déplace la console
function moveSwitch(direction = 'up')
{
  animationEnCours = true;
  const reverseKeyframes = [
    { transform: 'translate3d(0, 0, 0)'},
    { transform: 'translate3d(0, ' + takeoffHeight + 'px, 0)'}
  ];

  const options = {
    easing: easingStandard,
    fill: 'forwards',
    duration: 500
  };

  return new Promise((resolve, reject) => {
    let keyframes;
    if (direction == 'up')
      keyframes = [reverseKeyframes[1], reverseKeyframes[0]];
    else
      keyframes = [reverseKeyframes[0], reverseKeyframes[1]];
    
    const animation = nintendoSwitch.animate(keyframes, options);
    animation.onfinish = resolve;
  })
}


// Détachement d'un Joy-Con pour choisir sa couleur
function animeSwitchDetache(side)
{
  animationEnCours = true;
  const promises = [moveSwitch('down')];
  if (side == 'gauche')
    promises.push(moveJoycon(joyconG, 'up'));
  else if (side == 'droite')
    promises.push(moveJoycon(joyconD, 'up'));
  
  return Promise.all(promises);
}

// Attachement d'un Joy-Con après choix de sa couleur
function animeSwitchAttache(side)
{
  animationEnCours = true;
  return moveSwitch('up')
  .then(() => {
    if (side == 'gauche')
      return moveJoycon(joyconG, 'down');
    else if (side == 'droite')
      return moveJoycon(joyconD, 'down');
  })
  .then(bounceSwitch)
  .then(() => wait(100));
}


// Attachement d'un Joy-Con
function landJoycons()
{
  return Promise.all([moveJoycon(joyconG, 'down'), moveJoycon(joyconD, 'down')]);
}

// Réaction de la Switch à l'attachement d'un Joy-Con
function bounceSwitch()
{
  return new Promise((resolve, reject) => {
    bruit.play();
    if (doIrandomizeColors)
      changeJoyconColors();
    const swounce = document.querySelector('.la-switch').animate([
      { transform: 'translate3d(0, 0, 0)'},
      { transform: 'translate3d(0, 10%, 0)'},
      { transform: 'translate3d(0, 0, 0)'}
    ], {
        easing: easingDecelerate,
        duration: 100
    });
    swounce.addEventListener('finish', () => {
      doIrandomizeColors = false;
      animationEnCours = false;
      resolve();
    });
  });
}


// Change aléatoirement la couleur des Joy-Con si on clique sur la Switch pendant qu'elle est éteinte
consoleSwitch.addEventListener('click', () => {
  if (!nintendoSwitch.classList.contains('on') && !animationEnCours)
  {
    doIrandomizeColors = true;
    nintendoSwitch.classList.remove('hover');
    animationEnCours = true;

    Promise.all([moveJoycon(joyconG, 'up'), moveJoycon(joyconD, 'up')])
    .then(landJoycons)
    .then(bounceSwitch);
  }
});


//////////////////////////////////////////////////////
// Contenu de l'écran de la Switch — menu et mini-jeux

let boutonJeu;
let idPartie;
let idCheat;
let hasCheated = false;
let cheating = true;
let startTime;

// Allume / éteint l'écran de la Switch
homeButton.addEventListener('click', () => {
  homeButton.blur();
  if (nintendoSwitch.classList.contains('on'))
  {
    // Fermer menu et jeux
    nintendoSwitch.classList.remove('on');
    nintendoSwitch.classList.remove('gaming');
    document.querySelector('.menu-bg button').tabIndex = -1;
    document.querySelector('.jeu-bg button').tabIndex = -1;
  }
  else
  {
    // Ouvrir menu
    nintendoSwitch.classList.add('on');
    document.querySelector('.menu-bg button').tabIndex = 0;
    document.querySelector('.jeu-bg button').tabIndex = -1;
    document.getElementById('jeu-1').focus();
  }
});


// Lance un mini-jeu
function startGame(event)
{
  nintendoSwitch.classList.add('gaming');
  const id = event.currentTarget.id.replace('jeu-', '');
  const cooEcran = document.querySelector('.jeu').getBoundingClientRect();
  const cooIcone = event.currentTarget.getBoundingClientRect();
  const clicX = Math.round(cooIcone.left + 0.5 * cooIcone.width - cooEcran.left);
  const clicY = Math.round(cooIcone.top + 0.5 * cooIcone.height - cooEcran.top);
  document.querySelector('.jeu').style.setProperty('--x', clicX + 'px');
  document.querySelector('.jeu').style.setProperty('--y', clicY + 'px');
  document.querySelector('.menu-bg button').tabIndex = -1;
  document.querySelector('.jeu-bg button').tabIndex = 0;

  // Mini-jeu : Press on Sound
  if (id == 1)
  {
    const storedScore = localStorage.getItem('csswitch/best-score');
    const bestScore = (storedScore == null) ? '---' : storedScore;
    document.getElementById('score-record').innerHTML = bestScore;

    boutonJeu = document.querySelector('.jeu-bouton');
    boutonJeu.addEventListener('click', () => {
      if (cheating)
      {
        const id = Date.now();
        idCheat = id;
        hasCheated = true;
        boutonJeu.classList.add('cheating');
        return wait(3000)
        .then(() => {
          if (idCheat == id)
            boutonJeu.classList.remove('cheating');
        });
      }
      else
      {
        if (!boutonJeu.classList.contains('cheating'))
        {
          const endTime = Date.now();
          const lastScore = endTime - startTime;
          document.getElementById('score-dernier').innerHTML = lastScore;
          const storedScore = localStorage.getItem('csswitch/best-score');
          const bestScore = (storedScore == null) ? lastScore : (storedScore > lastScore) ? lastScore : storedScore;
          localStorage.setItem('csswitch/best-score', bestScore);
          document.getElementById('score-record').innerHTML = bestScore;
        }
      }
    });
    boutonJeu.addEventListener('mouseout', () => boutonJeu.blur());
    boutonJeu.addEventListener('touchend', () => boutonJeu.blur());

    idPartie = Date.now();
    return jouer('Press on Sound', idPartie);
  }
}


// Lance une partie n°id d'un jeu
function jouer(jeu, id)
{
  document.removeEventListener('visibilitychange', () => jouer(jeu, id));

  return Promise.resolve()
  .then(() => {

    // Press on Sound
    if (jeu == 'Press on Sound')
    {
      const delai = Math.round(5000 + 10000 * Math.random());

      return wait(delai)
      .then(() => {
        if (idPartie != id)
          throw 'Nouvelle partie commencée';
        if (hasCheated)
          throw 'Tentative de triche';
        if (nintendoSwitch.classList.contains('gaming') && !document.hidden)
        {
          bruit.play();
          cheating = false;
          boutonJeu.classList.add('on');
          startTime = Date.now();
          return wait(900);
        }
        else if (nintendoSwitch.classList.contains('gaming') && document.hidden)
          throw 'Page inactive';
        else
          throw 'Jeu fermé';
      })
      .then(() => {
        boutonJeu.classList.remove('on');
        cheating = true;
        if (nintendoSwitch.classList.contains('gaming'))
          return jouer(jeu, id);
        else
          throw 'Jeu fermé';
      });
    }
    else
      throw 'Jeu inexistant';

  })
  .catch(raison => {
    //console.log('Partie ' + id + ' annulée :', raison);
    if (raison == 'Tentative de triche')
    {
      hasCheated = false;
      return jouer(jeu, id);
    }
    else if (raison == 'Page inactive')
    {
      idPartie = Date.now();
      document.addEventListener('visibilitychange', () => jouer(jeu, idPartie));
    }
  });
}


///////////////////////
// Au redimensionnement
function onResize() {
  takeoffHeight = Math.round(1.2 * nintendoSwitch.offsetHeight);
}
window.addEventListener('resize', onResize);
window.addEventListener('orientationchange', onResize);
window.addEventListener('load', () => {
  changeJoyconColors(joyconColors[2], joyconColors[1], false);

  const menu = document.getElementById('menu-switch').content.cloneNode(true);
  document.querySelector('.jeu').appendChild(menu);

  const jeu = document.getElementById('jeu1').content.cloneNode(true);
  document.querySelector('.jeu').appendChild(jeu);

  Array.from(document.querySelectorAll('button.menu-icone-jeu')).forEach(e => {
    e.addEventListener('click', event => startGame(event));
  });

  onResize();
  return textualiser()
  .then(() => {
    document.documentElement.style.setProperty('--h-diff', 0);
    document.querySelector('.ligne').classList.remove('off');
    document.getElementById('choix-gauche').classList.remove('off');
    document.getElementById('choix-droite').classList.remove('off');
  });
});