///////////////////////
// Couleurs des joy-con
//// Couleurs possibles pour les 2 Joy-Con
const joyconColors = [
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
];
//// Couleurs propres au Joy-Con gauche
const joyconGonlyColors = [
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
];
//// Couleurs propres au Joy-Con droit
const joyconDonlyColors = [
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
];

let typeCouleur = 'hex';
const joyconGColors = [...joyconColors, ...joyconGonlyColors]; // Toutes les couleurs possibles du Joy-Con gauche
const joyconDColors = [...joyconColors, ...joyconDonlyColors]; // Toutes les couleurs possibles du Joy-Con droit
const allPossibleColors = [...joyconColors, ...joyconGonlyColors, ...joyconDonlyColors]; // Toutes les couleurs possibles