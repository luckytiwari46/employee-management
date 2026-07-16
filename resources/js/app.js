import './bootstrap';
import 'bootstrap';          // bootstrap JS (requires popper)
import $ from 'jquery';     // if you need jQuery-using code
window.$ = window.jQuery = $;

// your custom JS
console.log('Local assets loaded');