// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import DefTraduction from '../../_common/js/traduction.js';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



class ExtTraduction extends DefTraduction {
  constructor() {
    const version = document.querySelector('link#strings').dataset.version || document.documentElement.dataset.version || 0;
    const path = `/csswitch/strings--${version}.json`;
    super('csswitch', path, 'fr');
  }

  async traduire(element = document) {
    await super.traduire(element);

    document.title = 'CSSwitch - ' + this.getString('titre-page-description');
    return;
  }
}

export const Traduction = new ExtTraduction();
export const getString = Traduction.getString.bind(Traduction);