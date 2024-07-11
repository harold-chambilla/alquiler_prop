import { registerVueControllerComponents } from '@symfony/ux-vue';
import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import { createApp } from 'vue';
import Hello from './vue/controllers/Hello.vue';
import { createPinia } from 'pinia';

const pinia = createPinia();

const inicio = createApp(Hello);
inicio.use(pinia);
inicio.mount('#inicio');

registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));