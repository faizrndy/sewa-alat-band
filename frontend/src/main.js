import './index.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from "axios";
import Cookies from "js-cookie";
import "leaflet/dist/leaflet.css";

// BASE URL API
axios.defaults.baseURL = "http://127.0.0.1:8000";

// KIRIM COOKIES KE SERVER
axios.defaults.withCredentials = true;

// NAMA COOKIE DAN HEADER UNTUK SANCTUM
axios.defaults.xsrfCookieName = "XSRF-TOKEN";
axios.defaults.xsrfHeaderName = "X-XSRF-TOKEN";

// INTERCEPTOR → MASUKKAN XSRF TOKEN DARI COOKIE KE HEADER REQUEST
axios.interceptors.request.use((config) => {
  const token = Cookies.get("XSRF-TOKEN");
  if (token) {
    config.headers["X-XSRF-TOKEN"] = token;
  }
  return config;
});

createApp(App).use(router).mount('#app');
