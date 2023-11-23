import { Params } from 'Params';
import 'component-nintendoSwitch';
import { Traduction } from 'traduction';



document.documentElement.dataset.theme = Params.theme;
const nintendoSwitch = document.querySelector('nintendo-switch');
nintendoSwitch.setAttribute('model', localStorage.getItem('csswitch/model') || 'oled');



window.addEventListener('translation-request', event => {
  Traduction.traduire(document.querySelector('nintendo-switch').shadowRoot);
});



///////////////////////
// Au redimensionnement
function onResize() {
  const nintendoSwitch = document.querySelector('nintendo-switch');
  const screen = nintendoSwitch.shadowRoot.querySelector('.jeu');
  const screenRect = screen.getBoundingClientRect();
  nintendoSwitch.setAttribute('screen-size', Math.round(screenRect.width));
  nintendoSwitch.setAttribute('screen-width', Math.round(screenRect.width));
  nintendoSwitch.setAttribute('screen-height', Math.round(screenRect.height));
}
window.addEventListener('resize', onResize);
window.addEventListener('orientationchange', onResize);

onResize();
document.documentElement.style.setProperty('--h-diff', 0);
const lang = localStorage.getItem('csswitch/lang') || document.documentElement.dataset.httpLang;
Traduction.traduire(document, lang);