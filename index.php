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
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Press+Start+2P&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P&display=swap" media="print" onload="this.media='all'">

    <link rel="preload" as="audio" href="console/switch.mp3">
    <link rel="preload" as="fetch" href="/csswitch/strings--<?=version(__DIR__, 'strings.json')?>.json" crossorigin
          id="strings" data-version="<?=version(__DIR__, 'strings.json')?>">
    <link rel="modulepreload" href="../_common/js/traduction--<?=version($commonDir.'/js', 'traduction.js')?>.js">

    <link rel="stylesheet" href="console/switch--<?=version(__DIR__.'/console', 'switch.css')?>.css">
    <link rel="stylesheet" href="page--<?=version(__DIR__, 'page.css')?>.css">
  </head>

  <body>

    <div class="groupe-langages options">
      <button class="bouton-langage" data-string="option-couleurs-photos" disabled><?=$Textes->getString('option-couleurs-photos')?></button>
      <button class="bouton-langage" data-string="option-couleurs-officielles"><?=$Textes->getString('option-couleurs-officielles')?></button>
    </div>

    <main id="conteneur">
      <div class="la-switch">
        <?php include 'console/switch.html'; ?>
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

    <audio id="clic-switch" preload="auto">
      <source src="console/switch.mp3" type="audio/mpeg">
    </audio>

    <div class="groupe-langages">
      <button class="bouton-langage" data-lang="fr" disabled>Français</button>
      <button class="bouton-langage" data-lang="en">English</button>
    </div>

    <template id="menu-switch">
      <?php include 'jeux/menu/template.php'; ?>
    </template>

    <template id="jeu1">
      <?php include 'jeux/jeu1/template.php'; ?>
    </template>

    <script src="/_common/js/test-support--<?=version($commonDir.'/js', 'test-support.js')?>.js" id="test-support-script"></script>
    <script id="test-support-script-exe">
      TestSupport.getSupportResults([
        { name: 'CSS custom properties', priority: 1 },
        { name: 'web animations', priority: 1 },
        { name: 'ES const & let', priority: 1 },
        { name: 'ES class', priority: 1 },
        { name: 'ES modules', priority: 1 }
      ]);
    </script>
    <script type="module" src="scripts--<?=version(__DIR__, 'scripts.js.php')?>.js.php"></script>

  </body>
</html>