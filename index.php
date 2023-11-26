<?php
$commonDir = '../_common';
require_once $commonDir.'/php/Translation.php';

$translation = new Translation(__DIR__.'/strings.json');
$httpLanguage = $translation->getLanguage();
?>
<!doctype html>
<html data-http-lang="<?=$httpLanguage?>">
  <head>
    <meta charset="utf-8">
    <title>CSSwitch - <?=$translation->get('titre-page-description')?></title>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1">
    <meta name="theme-color" content="#FFFFFF">

    <meta property="og:title" content="CSSwitch">
    <meta name="description" content="<?=$translation->get('description-site')?>">
    <meta property="og:description" content="<?=$translation->get('description-site')?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://remiscan.fr/csswitch/">
    <meta property="og:image" content="https://remiscan.fr/mon-portfolio/projets/csswitch/og-preview.png">

    <link rel="icon" type="image/svg+xml" href="/csswitch/icons/favicon.svg">
    <link rel="apple-touch-icon" href="/csswitch/icons/apple-touch-icon.png">
    <link rel="manifest" href="/csswitch/manifest.json">

    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P" media="print" onload="this.media='all'">

    <!-- ▼ Cache-busted files -->
    <!--<?php versionizeStart(); ?>-->

    <link rel="preload" as="audio" href="/csswitch/jeux/menu/switch.ogg">
    <link rel="preload" as="fetch" href="/csswitch/strings.json" crossorigin
          id="strings" data-version="<?=version([__DIR__.'/strings.json'])?>">

    <link rel="stylesheet" href="page.css">

    <!-- Import map -->
    <script defer src="/_common/polyfills/adoptedStyleSheets.min.js"></script>
    <script>window.esmsInitOptions = { polyfillEnable: ['css-modules', 'json-modules'] }</script>
    <script defer src="/_common/polyfills/es-module-shims.js"></script>
    <script type="importmap"><?php include 'importMap.json'; ?></script>

    <script type="module" src="scripts.js"></script>

    <!--<?php versionizeEnd(__DIR__); ?>-->
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