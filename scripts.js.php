// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Traduction } from './modules/mod_traduction.js.php';
import { Params } from './modules/mod_Params.js.php';
import './modules/comp_NintendoSwitch.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



document.documentElement.dataset.theme = Params.theme;



///////////////////////
// Au redimensionnement
function onResize() {
  const nintendoSwitch = document.querySelector('nintendo-switch');
  const screen = nintendoSwitch.shadowRoot.querySelector('.jeu');
  nintendoSwitch.setAttribute('screen-size', Math.round(screen.getBoundingClientRect().width));
}
window.addEventListener('resize', onResize);
window.addEventListener('orientationchange', onResize);
window.addEventListener('load', async () => {
  onResize();
  document.documentElement.style.setProperty('--h-diff', 0);
  await Traduction.traduire();
});