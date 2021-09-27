import { Traduction } from 'traduction';
import { Params } from 'Params';
import 'component-nintendoSwitch';



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

onResize();
document.documentElement.style.setProperty('--h-diff', 0);
const lang = localStorage.getItem('csswitch/lang') || document.documentElement.dataset.httpLang;
Traduction.traduire(document, lang);