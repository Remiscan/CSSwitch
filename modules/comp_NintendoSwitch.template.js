const template = document.createElement('template');
template.innerHTML = /*html*/`
<div class="joycon gauche">
  <div class="interieur"></div>

  <div class="select"></div>

  <div class="boutons gauche">
    <div></div>
    <button type="button" class="c" data-key="up" data-other-keys="KeyW,ArrowUp" aria-keyshortcuts="ArrowUp">Up button</button>
    <div></div>

    <button type="button" class="c" data-key="left" data-other-keys="KeyA,ArrowLeft" aria-keyshortcuts="ArrowLeft">Left button</button>
    <div></div>
    <button type="button" class="c" data-key="right" data-other-keys="KeyD,ArrowRight" aria-keyshortcuts="ArrowRight">Right button</button>

    <div></div>
    <button type="button" class="c" data-key="down" data-other-keys="KeyS,ArrowDown" aria-keyshortcuts="ArrowDown">Down button</button>
    <div></div>
  </div>

  <div class="joystick gauche">
    <div class="coeur"></div>
  </div>

  <div class="capture">
    <div class="coeur"></div>
  </div>

  <div class="attache gauche"></div>
  <div class="attache2 gauche"></div>
  <div class="sidebuttons gauche">
    <div class="sbutton b1"></div>
    <div class="sbutton b2"></div>
  </div>
  <div class="trigger gauche"></div>
</div>

<div class="volume"></div>

<div class="console">
  <div class="screen-border">
    <div class="screen">
      <div class="jeu">
        <span class="clue" data-string="clue"></span>
        <main-menu></main-menu>
      </div>
    </div>

    <div class="trapeze gauche"></div>
    <div class="sensor"></div>
    <div class="trapeze droite"></div>
  </div>
</div>

<div class="joycon droit">
  <div class="interieur"></div>

  <div class="start"></div>

  <div class="boutons droite">
    <div></div>
    <button type="button" class="c" data-key="x" data-other-keys="KeyE" aria-keyshortcuts="E">X button</button>
    <div></div>

    <button type="button" class="c" data-key="y" data-other-keys="ShiftLeft" aria-keyshortcuts="Shift">Y button</button>
    <div></div>
    <button type="button" class="c" data-key="a" data-other-keys="KeyF" aria-keyshortcuts="F">A button</button>

    <div></div>
    <button type="button" class="c" data-key="b" data-other-keys="ControlLeft,Backspace" aria-keyshortcuts="Backspace">B button</button>
    <div></div>
  </div>

  <div class="joystick droite">
    <div class="coeur"></div>
  </div>

  <!--<label for="homeButton">Switch Home button</label>
  <button type="button" class="home" id="homeButton" aria-label="Home">-->
  <button type="button" class="home" data-key="home" data-other-keys="Escape" aria-keyshortcuts="Escape">
    Home button
    <div class="coeur"></div>
  </button>

  <div class="attache droite"></div>
  <div class="attache2 droite"></div>
  <div class="sidebuttons droite">
    <div class="sbutton b1"></div>
    <div class="sbutton b2"></div>
  </div>
  <div class="trigger droite"></div>
</div>
`;

export default template;