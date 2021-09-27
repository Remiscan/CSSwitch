import { Traduction } from 'traduction';



class Settings {
  constructor() {
    this.joyconColors = {
      both: [
        {
          hex: '#6E757B',
          hexOfficiel: '#828282',
          id: 'gris'
        }, {
          hex: '#00BFDF',
          hexOfficiel: '#0AB9E6',
          id: 'bleu-neon'
        }, {
          hex: '#FF5E52',
          hexOfficiel: '#FF3C28',
          id: 'rouge-neon'
        }, {
          hex: '#D9EF64',
          hexOfficiel: '#E6FF00',
          id: 'jaune-neon'
        }, {
          hex: '#00E259',
          hexOfficiel: '#1EDC00',
          id: 'vert-neon'
        }, {
          hex: '#F85187',
          hexOfficiel: '#FF3278',
          id: 'rose-neon'
        }, {
          hex: '#EE2D37',
          hexOfficiel: '#E10F00',
          id: 'mario'
        }, {
          hex: '#D0A880',
          hexOfficiel: '#D7AA73',
          id: 'labo'
        }
      ],
      left: [
        {
          hex: '#CAA25A',
          hexOfficiel: '#C88D32',
          id: 'evoli'
        }, {
          hex: '#4456C2',
          hexOfficiel: '#4655F5',
          id: 'bleu'
        }, {
          hex: '#912FA8',
          hexOfficiel: '#B400E6',
          id: 'violet-neon'
        }, {
          hex: '#8DE6AF',
          hexOfficiel: '#82FF96',
          id: 'ac-vert'
        }
      ],
      right: [
        {
          hex: '#F6D962',
          hexOfficiel: '#FFDD00',
          id: 'pikachu'
        }, {
          hex: '#F0BB37',
          hexOfficiel: '#FAA005',
          id: 'orange-neon'
        }, {
          hex: '#7DDCE2',
          hexOfficiel: '#96F5F5',
          id: 'ac-bleu'
        }
      ]
    };

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

    this.colorSets = [
      {
        hex: 'linear-gradient(to right, #828282 0% 33%, #0AB9E6 34% 66%, #FF3C28 67% 100%)',
        id: 'officiel'
      }, {
        hex: 'linear-gradient(to right, #6E757B 0% 33%, #00BFDF 34% 66%, #FF5E5E 67% 100%)',
        id: 'photos'
      }
    ];

    this.languages = ['en', 'fr'];

    this.currentColors = {
      left: localStorage.getItem('csswitch/joycon-gauche') || 'rouge-neon',
      right: localStorage.getItem('csswitch/joycon-droit') || 'bleu-neon',
      theme: ['dark', 'light'].includes(localStorage.getItem('csswitch/theme')) ? localStorage.getItem('csswitch/theme') : 'auto',
      colorset: localStorage.getItem('csswitch/colorset') == 'photos' ? 'photos' : 'officiel'
    };
  }

  get currentLanguage() { return Traduction.language; }

  colors(side = 'all') {
    if (['left', 'gauche'].includes(side))
      return [...this.joyconColors.both, ...this.joyconColors.left];
    if (['right', 'droit', 'droite'].includes(side))
      return [...this.joyconColors.both, ...this.joyconColors.right];
    if (side == 'theme')
      return [...this.themes];
    if (side == 'colorset')
      return [...this.colorSets];
    return [...this.joyconColors.both, ...this.joyconColors.left, ...this.joyconColors.right];
  }

  findColor(id, set = 'all') {
    return this.colors(set).find(c => c.id == id);
  }

  getColorHex(id, set = 'all', type = this.currentColors.colorset) {
    const color = this.findColor(id, set) || this.defaultColors['left'];
    return ((type == 'officiel') ? (color.hexOfficiel || color.hex) : color.hex);
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