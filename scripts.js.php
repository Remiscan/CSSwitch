// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Traduction } from './modules/mod_traduction.js.php';
import './modules/comp_NintendoSwitch.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/


// Nom des éléments et variables
let doIrandomizeColors = false;


const choix = document.querySelector('.choix');
const conteneur = document.querySelector('main');
const bruit = document.getElementById('clic-switch');
function wait(time) { return new Promise(resolve => setTimeout(resolve, time)); }
let takeoffHeight = 0;


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
/*document.getElementById('choix-gauche').addEventListener('click', () => {
  if (!animationEnCours)
    openChoice('gauche');
});
document.getElementById('choix-droite').addEventListener('click', () => {
  if (!animationEnCours)
    openChoice('droite');
});*/


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
/*consoleSwitch.addEventListener('click', () => {
  if (!nintendoSwitch.classList.contains('on') && !animationEnCours)
  {
    doIrandomizeColors = true;
    nintendoSwitch.classList.remove('hover');
    animationEnCours = true;

    Promise.all([moveJoycon(joyconG, 'up'), moveJoycon(joyconD, 'up')])
    .then(landJoycons)
    .then(bounceSwitch);
  }
});*/


///////////////////////
// Au redimensionnement
function onResize() {
  const nintendoSwitch = document.querySelector('nintendo-switch');
  const screen = nintendoSwitch.shadowRoot.querySelector('.jeu');
  //takeoffHeight = Math.round(1.2 * nintendoSwitch.offsetHeight);
  nintendoSwitch.setAttribute('screen-size', Math.round(screen.getBoundingClientRect().width));
}
window.addEventListener('resize', onResize);
window.addEventListener('orientationchange', onResize);
window.addEventListener('load', async () => {
  /*changeJoyconColors(joyconColors[2], joyconColors[1], false);

  const menu = document.getElementById('menu-switch').content.cloneNode(true);
  document.querySelector('.jeu').appendChild(menu);

  const jeu = document.getElementById('jeu1').content.cloneNode(true);
  document.querySelector('.jeu').appendChild(jeu);

  Array.from(document.querySelectorAll('button.menu-icone-jeu')).forEach(e => {
    e.addEventListener('click', event => startGame(event));
  });*/

  onResize();
  await Traduction.traduire();

  document.documentElement.style.setProperty('--h-diff', 0);
  /*document.querySelector('.ligne').classList.remove('off');
  document.getElementById('choix-gauche').classList.remove('off');
  document.getElementById('choix-droite').classList.remove('off');*/
});