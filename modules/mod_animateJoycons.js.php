const easingStandard = 'cubic-bezier(0.4, 0.0, 0.2, 1)';
const easingDecelerate = 'cubic-bezier(0.0, 0.0, 0.2, 1)';
const easingAccelerate = 'cubic-bezier(0.4, 0.0, 1, 1)';
const animations = [];
const isUp = {
  gauche: false,
  droit: false
};
let bouncing = false;



export async function moveJoycon(side = 'gauche', direction = 'up') {
  if (bouncing) return;
  if (isUp[side] == (direction == 'up')) return;

  const joycon = document.querySelector('nintendo-switch').shadowRoot.querySelector(`.joycon.${side}`);
  const keyframes = [
    { transform: 'translate3d(0, 0, 0)'},
    { transform: 'translate3d(0, -50%, 0)'}
  ];
  const options = {
    easing: easingStandard,
    fill: 'forwards',
    duration: (direction == 'up') ? 500 : 250
  };
  const i0 = (direction == 'up') ? 0 : 1;
  const i1 = (direction == 'up') ? 1 : 0;

  return await new Promise(resolve => {
    const animation = joycon.animate([keyframes[i0], keyframes[i1]], options);
    isUp[side] = (direction == 'up');
    animations.push(animation);
    animation.onfinish = resolve;
  });
}

export async function bounce() {
  if (bouncing) return;
  bouncing = true;
  if (isUp.gauche + isUp.droite) return;

  const console = document.querySelector('nintendo-switch');
  const keyframes = [
    { transform: 'translate3d(0, 0, 0)'},
    { transform: 'translate3d(0, 2%, 0)'},
    { transform: 'translate3d(0, 0, 0)'}
  ];
  const options = {
    easing: easingDecelerate,
    duration: 100
  }

  await new Promise(resolve => {
    const animation = console.animate(keyframes, options);
    animations.push(animation);
    animation.onfinish = resolve;
  });
  bouncing = false;
  return cancelAnimations();
}

function cancelAnimations() {
  animations.forEach(a => a.cancel());
}