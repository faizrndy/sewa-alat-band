import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'

const routes = [
  // 🏠 Home (Tetap, karena Home biasanya landing page umum)
  { path: '/', name: 'Home', component: Home },

  // --- BAGIAN CUSTOMER (Sudah dipindahkan ke folder customer) ---

  // 📦 KATALOG
  { 
    path: '/katalog', 
    name: 'Katalog', 
    component: () => import('../views/customer/Katalog.vue') // Path baru
  },

  // 🔍 DETAIL PRODUK
  {
    path: '/katalog/:id',
    name: 'DetailAlat',
    component: () => import('../views/customer/DetailAlat.vue'), // Path baru
    props: true
  },

  // 🛒 KERANJANG
  {
    path: '/keranjang',
    name: 'Keranjang',
    component: () => import('../views/customer/Keranjang.vue'), // Path baru
    meta: { requiresAuth: true }
  },

  // 👤 PROFILE
  {
    path: '/profile',
    name: 'Profile',
    component: () => import('../views/customer/Profile.vue'), // Path baru
    meta: { requiresAuth: true }
  },

  // 💳 PEMBAYARAN
  {
    path: "/pembayaran",
    name: "Pembayaran",
    component: () => import("../views/customer/Pembayaran.vue"), // Path baru
    meta: { requiresAuth: true }
  },

  // 📜 RIWAYAT
  {
    path: "/riwayat",
    name: "Riwayat",
    component: () => import("../views/customer/Riwayat.vue"), // Path baru
    meta: { requiresAuth: false },
  },

  // --- BAGIAN UMUM / AUTH (Tetap di root views atau folder lain) ---

  // INFO PAGES
  { path: '/about', name: 'About', component: () => import('../views/About.vue') },
  { path: '/faq', name: 'FAQ', component: () => import('../views/FAQ.vue') },
  { path: '/terms', name: 'Terms', component: () => import('../views/Terms.vue') },

  // AUTH
  { path: '/login', name: 'Login', component: () => import('../views/Login.vue') },
  { path: '/register', name: 'Register', component: () => import('../views/Register.vue') },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation Guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("buyer_token");

  if (to.meta.requiresAuth && !token) {
    return next("/login");
  }

  next();
});

export default router;