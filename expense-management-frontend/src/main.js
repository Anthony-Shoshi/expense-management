import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import App from './App.vue'
import router from './router'
import './assets/main.css'
import 'bootstrap/dist/css/bootstrap.css'
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.js'

const app = createApp(App)

axios.defaults.baseURL = 'http://localhost'
app.config.globalProperties.$axios = axios

app.use(createPinia())
app.use(router)
app.use(bootstrap)
app.mount('#app')