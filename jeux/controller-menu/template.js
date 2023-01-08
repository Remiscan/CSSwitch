const template = document.createElement('template');
template.innerHTML = /*html*/`
<header>
  <joycon-icon style="--size: 60%"></joycon-icon>
  <span>Personnalisez les manettes</span>
</header>

<nav>
  <button type="button" data-side="left" class="pick-a-side">
    <span>Joy-Con gauche</span>
  </button>
  <button type="button" data-side="right" class="pick-a-side">
    <span>Joy-Con droit</span>
  </button>
</nav>

<section></section>
`;

export default template;