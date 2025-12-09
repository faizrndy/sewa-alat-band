import './index.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// ✅ AUTO LOGOUT
import { startAutoLogout } from './utils/autoLogout'

// --- 1. IMPORT LIBRARY ---
import axios from "axios";
import Cookies from "js-cookie";
import "leaflet/dist/leaflet.css";

// --- 2. IMPORT UNTUK FIX GAMBAR LEAFLET (Vite Issue) ---
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
axios.defaults.baseURL = "http://127.0.0.1:8000";
axios.defaults.withCredentials = true;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// --- 4. CONFIG SANCTUM CSRF (KEAMANAN) ---
axios.defaults.xsrfCookieName = "XSRF-TOKEN";
axios.defaults.xsrfHeaderName = "X-XSRF-TOKEN";

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

// ✅ AKTIFKAN AUTO LOGOUT (INI YANG BARU)
startAutoLogout(router);
