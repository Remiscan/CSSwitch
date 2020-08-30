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
          id: 'bleu'
        }, {
          hex: '#912FA8',
          id: 'violet-neon'
        }, {
          hex: '#8DE6AF',
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
          id: 'orange-neon'
        }, {
          hex: '#7DDCE2',
          id: 'ac-bleu'
        }
      ]
    };

    this.colorType = 'hex';

    this.defaultColors = {
      left: 'rouge-neon',
      right: 'bleu-neon'
    };

    this.currentColors = {
      left: 'rouge-neon',
      right: 'bleu-neon',
      theme: 'auto'
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
  }

  colors(side = 'all') {
    if (['left', 'gauche'].includes(side))
      return [...this.joyconColors.both, ...this.joyconColors.left];
    if (['right', 'droit', 'droite'].includes(side))
      return [...this.joyconColors.both, ...this.joyconColors.right];
    return [...this.joyconColors.both, ...this.joyconColors.left, ...this.joyconColors.right];
  }

  findColor(id) {
    return this.colors().find(c => c.id == id);
  }
};

export const Params = new Settings();