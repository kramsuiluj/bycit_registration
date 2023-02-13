import './bootstrap';

import { createApp } from 'vue';
window.createApp = createApp;

import Attendees from './Attendees.vue';

const app = createApp(Attendees);
const vm  = app.mount('#app');
window.vm = vm;

import QrScanner from 'qr-scanner';
window.QrScanner = QrScanner;
