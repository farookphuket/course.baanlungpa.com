
import {createApp} from 'vue'

import App from './App.vue'
import axios from 'axios'
import router from './router'

import "bulma/bulma.sass"

// font awesome 28 Jan 2022
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { fas } from '@fortawesome/free-solid-svg-icons'
library.add(fas)

// jodit 
// copy code from https://libraries.io/npm/jodit-vue3
import 'jodit/build/jodit.min.css'
//import JoditEditor from 'jodit-vue3'
import JoditEditor from 'jodit-vue3'


// vue3-cookies
// copy code from https://www.npmjs.com/package/vue3-cookies
import VueCookies from 'vue3-cookies'

// moment 
import moment from 'moment'



// ============== code from 27 June 2021 ================================================
import Form from './core/Form';
import CustomText from './core/CustomText';

window.Form = Form;
window.CustomText = CustomText;

window.axios = require('axios')

const app = createApp(App)

app.config.globalProperties.$axios = axios;
app.component('font-awesome-icon',FontAwesomeIcon)
app.use(router)
app.use(JoditEditor)
app.use(VueCookies)
app.mount('#app')
