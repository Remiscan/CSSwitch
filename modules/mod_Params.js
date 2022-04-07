import { Traduction } from 'traduction';



class Settings {
  constructor() {
    // Official hex codes from here: https://switchbrew.org/wiki/Joy-Con
    this.joyconColors = [
      {
        hex: '#828282',
        id: 'gris',
        left: true,
        right: true
      }, {
        hex: '#0AB9E6',
        id: 'bleu-neon',
        left: true,
        right: true
      }, {
        hex: '#FF3C28',
        id: 'rouge-neon',
        left: true,
        right: true
      }, {
        hex: '#E6FF00',
        id: 'jaune-neon',
        left: true,
        right: true
      }, {
        hex: '#1EDC00',
        id: 'vert-neon',
        left: true,
        right: true
      }, {
        hex: '#FF3278',
        id: 'rose-neon',
        left: true,
        right: true
      }, {
        hex: '#E10F00',
        id: 'mario',
        left: true,
        right: true
      }, {
        hex: '#C88D32',
        id: 'evoli',
        left: true,
        right: false
      }, {
        hex: '#FFDD00',
        id: 'pikachu',
        left: false,
        right: true
      }, {
        hex: '#D7AA73',
        id: 'labo',
        left: true,
        right: true
      }, {
        hex: '#1473FA',
        id: 'dq-bleu',
        left: true,
        right: true
      }, {
        hex: '#4655F5',
        id: 'bleu',
        left: true,
        right: false
      }, {
        hex: '#B400E6',
        id: 'violet-neon',
        left: true,
        right: false
      }, {
        hex: '#FAA005',
        id: 'orange-neon',
        left: false,
        right: true
      }, {
        hex: '#82FF96',
        id: 'ac-vert',
        left: true,
        right: false
      }, {
        hex: '#96F5F5',
        id: 'ac-bleu',
        left: false,
        right: true
      }, {
        hex: '#FFCC00',
        id: 'fortnite-jaune',
        left: true,
        right: true
      }, {
        hex: '#0084FF',
        id: 'fortnite-bleu',
        left: true,
        right: true
      }, {
        hex: '#F04614',
        id: 'mario-switch-rouge',
        left: true,
        right: true
      }, {
        hex: '#2D50F0',
        id: 'zelda-ss-bleu',
        left: true,
        right: false
      }, {
        hex: '#500FC8',
        id: 'zelda-ss-violet',
        left: false,
        right: true
      }, {
        hex: '#E6E6E6',
        id: 'blanc',
        left: true,
        right: true
      }
    ];

    this.defaultColors = {
      left: 'rouge-neon',
      right: 'bleu-neon'
    };

    this.themes = [
      {
        hex: 'linear-gradient(to bottom right, white 0% 49%, black 51% 100%)',
        id: 'auto'
      }, {
        hex: '#fff',
        id: 'light'
      } , {
        hex: '#111',
        id: 'dark'
      }
    ];

    this.languages = ['en', 'fr'];

    this.models = ['release', 'oled'];

    this.currentColors = {
      left: localStorage.getItem('csswitch/joycon-gauche') || 'rouge-neon',
      right: localStorage.getItem('csswitch/joycon-droit') || 'bleu-neon',
      theme: ['dark', 'light'].includes(localStorage.getItem('csswitch/theme')) ? localStorage.getItem('csswitch/theme') : 'auto'
    };
  }

  get currentLanguage() { return Traduction.language; }
  get currentModel() { return localStorage.getItem('csswitch/model') || 'oled'; }

  colors(side = 'all') {
    if (['left', 'gauche'].includes(side))
      return this.joyconColors.filter(c => c.left);
    if (['right', 'droit', 'droite'].includes(side))
      return this.joyconColors.filter(c => c.right);
    if (side == 'theme')
      return [...this.themes];
    return this.joyconColors;
  }

  findColor(id, set = 'all') {
    return this.colors(set).find(c => c.id == id);
  }

  getColorHex(id, set = 'all') {
    const color = this.findColor(id, set) || this.defaultColors['left'];
    return color.hex;
  }

  get theme() {
    const theme = this.currentColors.theme;
    if (theme != 'auto') return theme;

    if (window.matchMedia('(prefers-color-scheme: dark)').matches) return 'dark';
    else if (window.matchMedia('(prefers-color-scheme: light)').matches) return 'light';
    else return 'light';
  }
};

export const Params = new Settings();

export const wait = time => new Promise(resolve => setTimeout(resolve, time));