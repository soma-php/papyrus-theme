// Assign jquery to global
const jquery = require('jquery');
window.jQuery = window.$ = jquery;

// Bootstrap dependency
require('popper.js');

// Bootstrap
require('./_bootstrap.js');