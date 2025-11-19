import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/Home.vue'

const routes = [
  // 🏠 Home tampil saat login sukses
  { path: '/', name: 'Home', component: Home },

  // 📦 KATALOG
  { path: '/katalog', name: 'Katalog', component: () => import('../views/Katalog.vue') },

  // 🔍 DETAIL PRODUK
  {
    path: '/katalog/:id',
    name: 'DetailAlat',
    component: () => import('../views/DetailAlat.vue'),
    props: true
  },

  // INFO PAGES
  { path: '/about', name: 'About', component: () => import('../views/About.vue') },
  { path: '/faq', name: 'FAQ', component: () => import('../views/FAQ.vue') },
  { path: '/terms', name: 'Terms', component: () => import('../views/Terms.vue') },

  // AUTH
  { path: '/login', name: 'Login', component: () => import('../views/Login.vue') },
  { path: '/register', name: 'Register', component: () => import('../views/Register.vue') },

  // halaman yang butuh login buyer
  {
    path: '/keranjang',
    name: 'Keranjang',
    component: () => import('../views/Keranjang.vue'),
    meta: { requiresAuth: true }
  },

  {
    path: '/profile',
    name: 'Profile',
    component: () => import('../views/Profile.vue'),
    meta: { requiresAuth: true }
  },

  {
    path: "/pembayaran",
    name: "Pembayaran",
    component: () => import("../views/Pembayaran.vue"),
    meta: { requiresAuth: true }
  },

  {
    path: "/riwayat",
    name: "Riwayat",
    component: () => import("../views/Riwayat.vue"),
    meta: { requiresAuth: false }, // boleh true kalau mau wajib login
  },


]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("buyer_token");

  if (to.meta.requiresAuth && !token) {
    return next("/login");
  }

  next();
});

export default router;
