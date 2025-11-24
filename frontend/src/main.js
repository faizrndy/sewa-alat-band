import './index.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// --- 1. IMPORT LIBRARY ---
import axios from "axios";
import Cookies from "js-cookie";
import "leaflet/dist/leaflet.css";

// --- 2. IMPORT UNTUK FIX GAMBAR LEAFLET (Vite Issue) ---
// Tanpa ini, gambar pin/marker di peta sering error (broken image)
import L from 'leaflet';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

// Terapkan fix icon Leaflet
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow
});

// --- 3. KONFIGURASI AXIOS GLOBAL ---
// Set URL Backend sekali saja di sini. 
// Di file lain (Profile.vue, dll) cukup pakai axios.get('/api/...')
axios.defaults.baseURL = "http://127.0.0.1:8000";

// Wajib 'true' agar cookie/token bisa bolak-balik antara frontend & backend
axios.defaults.withCredentials = true;

// Header standar agar Laravel tahu ini request Ajax/API
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// --- 4. CONFIG SANCTUM CSRF (KEAMANAN) ---
axios.defaults.xsrfCookieName = "XSRF-TOKEN";
axios.defaults.xsrfHeaderName = "X-XSRF-TOKEN";

// Interceptor: Otomatis ambil token dari cookie dan tempel ke header request
axios.interceptors.request.use((config) => {
  const token = Cookies.get("XSRF-TOKEN");
  if (token) {
    config.headers["X-XSRF-TOKEN"] = token;
  }
  return config;
});

// --- 5. MOUNT APP ---
const app = createApp(App);

app.use(router);
app.mount('#app');