<?php
$commonDir = '../_common';
require_once $commonDir.'/php/version.php';
require_once $commonDir.'/php/httpLanguage.php';
require_once $commonDir.'/php/getStrings.php';

$lang = httpLanguage() ?? 'en';
$Textes = new Textes('csswitch', $lang);
?>
<!doctype html>
<html data-version="<?=version(__DIR__)?>" data-http-lang="<?=httpLanguage()?>">
  <head>
    <meta charset="utf-8">
    <title>CSSwitch - <?=$Textes->getString('titre-page-description')?></title>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1">
    <meta name="theme-color" content="#FFFFFF">

    <meta property="og:title" content="CSSwitch">
    <meta name="description" content="<?=$Textes->getString('description-site')?>">
    <meta property="og:description" content="<?=$Textes->getString('description-site')?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://remiscan.fr/csswitch/">
    <meta property="og:image" content="https://remiscan.fr/mon-portfolio/projets/csswitch/og-preview.png">

    <link rel="icon" type="image/png" href="/csswitch/icons/icon-192.png">
    <link rel="apple-touch-icon" href="/csswitch/icons/apple-touch-icon.png">
    <link rel="manifest" href="/csswitch/manifest.json">

    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P" media="print" onload="this.media='all'">

    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php ob_start();?>-->

    <link rel="preload" as="audio" href="/csswitch/jeux/menu/switch.ogg">
    <link rel="preload" as="fetch" href="/csswitch/strings.json" crossorigin
          id="strings" data-version="<?=version(__DIR__, 'strings.json')?>">

    <link rel="stylesheet" href="page.css">

    <!-- Import map -->
    <script defer src="/_common/polyfills/es-module-shims.js"></script>
    <script type="importmap">
      {
        "imports": {
          "component-colorChoice": "./modules/comp_colorChoice.js",
          "component-mainMenu": "./modules/comp_mainMenu.js.php",
          "component-nintendoSwitch": "./modules/comp_nintendoSwitch.js.php",
          "animateJoycons": "./modules/mod_animateJoycons.js",
          "jeu": "./modules/mod_jeu.js",
          "Params": "./modules/mod_Params.js",
          "traduction": "./modules/mod_traduction.js",

          "component-joyconIcon": "./jeux/menu/comp_joyconIcon.js",
          "component-settingsIcon": "./jeux/menu/comp_settingsIcon.js",
          "component-powerIcon": "./jeux/menu/comp_powerIcon.js",

          "controller-menu": "./jeux/controller-menu/jeu.js",
          "page-settings-menu": "./jeux/page-settings-menu/jeu.js",
          "press-on-sound": "./jeux/press-on-sound/jeu.js.php",
          "settings-menu": "./jeux/settings-menu/jeu.js.php",

          "default-traduction": "/_common/js/traduction.js"
        }
      }
    </script>

    <script type="module" src="scripts.js"></script>

    <!--<?php $imports = ob_get_clean();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
    echo versionizeFiles($imports, __DIR__); ?>-->
  </head>

  <body>

    <main id="conteneur">
      <div class="la-switch">
        <nintendo-switch width="inherit"></nintendo-switch>
      </div>
    </main>

    <!-- To preload the font -->
    <span style="position: absolute; height: 30px; top: -40px; pointer-events: none; font-family: 'Press Start 2P', cursive;">Hello</span>
  </body>
</html>