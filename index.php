<?php
$commonDir = '../_common';
require_once $commonDir.'/php/version.php';
require_once $commonDir.'/php/httpLanguage.php';
require_once $commonDir.'/php/getStrings.php';
$Textes = new Textes('csswitch');
?>
<!doctype html>
<html lang="fr" data-version="<?=version(__DIR__)?>" data-http-lang="<?=httpLanguage()?>">
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
    <link rel="modulepreload" href="/_common/js/traduction.js">

    <link rel="stylesheet" href="page.css">

    <!--<?php $imports = ob_get_clean();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
    echo versionizeFiles($imports, __DIR__); ?>-->
  </head>

  <body>

    <div class="groupe-langages options">
      <button class="bouton-langage" data-string="option-couleurs-photos" disabled><?=$Textes->getString('option-couleurs-photos')?></button>
      <button class="bouton-langage" data-string="option-couleurs-officielles"><?=$Textes->getString('option-couleurs-officielles')?></button>
    </div>

    <main id="conteneur">
      <div class="la-switch">
        <nintendo-switch width="inherit"></nintendo-switch>
      </div>

      <div class="le-bouton gauche off" id="choix-gauche">
        <button data-string="bouton-choix-couleur-gauche"><?=$Textes->getString('bouton-choix-couleur-gauche')?></button>
      </div>

      <div class="le-bouton droite off" id="choix-droite">
        <button data-string="bouton-choix-couleur-droite"><?=$Textes->getString('bouton-choix-couleur-droite')?></button>
      </div>

      <div class="indice">
        <div class="ligne off"></div>
        <div class="texte" data-string="instruction-choix-couleur"><?=$Textes->getString('instruction-choix-couleur')?></div>
      </div>

      <div class="choix">
        <template id="template-couleur">
          <button class="couleur">
            <div class="hex"></div>
            <div class="nom"></div>
          </button>
        </template>
      </div>
    </main>

    <div class="groupe-langages">
      <button class="bouton-langage" data-lang="fr" disabled>Français</button>
      <button class="bouton-langage" data-lang="en">English</button>
    </div>

    <!-- To preload the font -->
    <span style="position: absolute; height: 30px; top: -40px; pointer-events: none; font-family: 'Press Start 2P', cursive;">Hello</span>

    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php ob_start();?>-->

    <script src="/_common/js/test-support.js" id="test-support-script"></script>
    <script id="test-support-script-exe">
      TestSupport.getSupportResults([
        { name: 'CSS custom properties', priority: 1 },
        { name: 'web animations', priority: 1 },
        { name: 'ES const & let', priority: 1 },
        { name: 'ES class', priority: 1 },
        { name: 'ES modules', priority: 1 }
      ]);
    </script>
    <script type="module" src="scripts.js.php"></script>

    <!--<?php $imports = ob_get_clean();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
    echo versionizeFiles($imports, __DIR__); ?>-->

  </body>
</html>