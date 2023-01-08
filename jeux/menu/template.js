const template = document.createElement('template');
template.innerHTML = /*html*/`
<div class="menu-top">
  <div class="menu-profile"></div>
  <div class="menu-battery"></div>
</div>

<div class="menu-liste">
  <div class="menu-icones">
  </div>
</div>

<div class="menu-bottom">
  <div class="menu-rond"></div>
  <div class="menu-rond"></div>
  <div class="menu-rond"></div>
  <button type="button" data-key="controllers" class="menu-rond">
    <joycon-icon style="--size: 50%;"></joycon-icon>
  </button>
  <button type="button" data-key="settings" class="menu-rond">
    <settings-icon></settings-icon>
  </button>
  <button type="button" data-key="power" class="menu-rond">
    <power-icon></power-icon>
  </button>
</div>

<div class="menu-verybottom">
  <div class="menu-clue" data-string="instruction-choix-jeu"></div>
</div>
`;

export default template;