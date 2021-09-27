import DefTraduction from 'default-traduction';



class ExtTraduction extends DefTraduction {
  constructor() {
    const version = document.querySelector('link#strings').dataset.version || document.documentElement.dataset.version || 0;
    const path = `/csswitch/strings--${version}.json`;
    super('csswitch', path, 'en');
  }

  async traduire(element = document) {
    await super.traduire(element);

    document.title = 'CSSwitch - ' + this.getString('titre-page-description');
    return;
  }
}

export const Traduction = new ExtTraduction();
export const getString = Traduction.getString.bind(Traduction);