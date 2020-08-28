// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Traduction, getString } from './mod_traduction.js.php';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 2).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/



const template = document.createElement('template');
template.innerHTML = `
  <style><?php include '../jeux/menu/styles.css'; ?></style>
  <?php include '../jeux/menu/template.html'; ?>
`;

class MenuSwitch extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.appendChild(template.content.cloneNode(true));
  }

  static get observedAttributes() {
    return ['open'];
  }

  async traduire() {
    await Traduction.traduire(this.shadowRoot);

    Array.from(this.shadowRoot.querySelectorAll('.menu-liste button')).forEach(e => {
      e.dataset.titre = getString('titre-' + e.id);
      e.setAttribute('aria-label', getString('bouton-start-jeu') + ' ' + getString('titre-' + e.id));
    });

    return;
  }

  update(attributes = MenuSwitch.observedAttributes) {
    if (!this.ready) return;
  }

  connectedCallback() {
    this.ready = true;
    this.update();
    this.traduire();

  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
customElements.define('menu-switch', MenuSwitch);