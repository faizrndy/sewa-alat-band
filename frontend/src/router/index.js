import { createRouter, createWebHistory } from 'vue-router'

// Import View Home
import Home from '@/views/Home.vue'

// Import Layout Admin
import AdminLayout from '@/layouts/AdminLayout.vue'

const routes = [
  // ==========================================
  // 🏠 BAGIAN PUBLIC & CUSTOMER
  // ==========================================
  
  { path: '/', name: 'Home', component: Home },

  // 📦 KATALOG
  { 
    path: '/katalog', 
    name: 'Katalog', 
    component: () => import('@/views/customer/Katalog.vue') 
  },

  // 🔍 DETAIL PRODUK
  {
    path: '/katalog/:id',
    name: 'DetailAlat',
    component: () => import('@/views/customer/DetailAlat.vue'),
    props: true
  },

  // 🛒 KERANJANG (Butuh Login Buyer)
  {
    path: '/keranjang',
    name: 'Keranjang',
    component: () => import('@/views/customer/Keranjang.vue'),
    meta: { requiresAuth: true }
  },

  // 👤 PROFILE (Butuh Login Buyer)
  {
    path: '/profile',
    name: 'Profile',
    component: () => import('@/views/customer/Profile.vue'),
    meta: { requiresAuth: true }
  },

  // 💳 PEMBAYARAN (Butuh Login Buyer)
  {
    path: "/pembayaran",
    name: "Pembayaran",
    component: () => import("@/views/customer/Pembayaran.vue"),
    meta: { requiresAuth: true }
  },

  // 📜 RIWAYAT
  {
    path: "/riwayat",
    name: "Riwayat",
    component: () => import("@/views/customer/Riwayat.vue"),
    meta: { requiresAuth: false },
  },

  // INFO PAGES
  { path: '/about', name: 'About', component: () => import('@/views/About.vue') },
  { path: '/faq', name: 'FAQ', component: () => import('@/views/FAQ.vue') },
  { path: '/terms', name: 'Terms', component: () => import('@/views/Terms.vue') },

  // AUTH CUSTOMER
  { path: '/login', name: 'Login', component: () => import('@/views/Login.vue') },
  { path: '/register', name: 'Register', component: () => import('@/views/Register.vue') },


  // ==========================================
  // 🔐 BAGIAN ADMIN (DENGAN LAYOUT)
  // ==========================================

  // 1. Login Admin
  { 
    path: '/admin/login', 
    name: 'AdminLogin', 
    component: () => import('@/views/admin/LoginAdmin.vue') 
  },

  // 2. Panel Admin (PROTECTED)
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAdmin: true }, // 👈 Semua anak di bawah ini otomatis terproteksi
    redirect: '/admin/dashboard',
    children: [
      { 
        path: 'dashboard', 
        name: 'AdminDashboard', 
        component: () => import('@/views/admin/Dashboard.vue')
      },
      { 
        path: 'alat', 
        name: 'AdminAlatIndex', 
        component: () => import('@/views/admin/alat/Index.vue') 
      },
      { 
        path: 'alat/create', 
        name: 'AdminAlatCreate', 
        component: () => import('@/views/admin/alat/Create.vue') 
      },
      { 
        path: 'alat/edit/:id', 
        name: 'AdminAlatEdit', 
        component: () => import('@/views/admin/alat/Edit.vue') 
      },
      { 
        path: 'transaksi', 
        name: 'AdminTransaksiIndex', 
        component: () => import('@/views/admin/transaksi/Index.vue') 
      },
    ]
  },
  
  // 404 Not Found (Redirect ke Home)
  { 
    path: '/:pathMatch(.*)*', 
    redirect: '/' 
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// ==========================================
// 🛡️ NAVIGATION GUARD (SATPAM) - VERSI FINAL
// ==========================================
router.beforeEach((to, from, next) => {
  // Ambil Token dari LocalStorage
  const buyerToken = localStorage.getItem("buyer_token");
  const adminToken = localStorage.getItem("admin_token");

  // 1. CEK AKSES ADMIN (Gunakan matched.some agar Parent Route terdeteksi)
  if (to.matched.some(record => record.meta.requiresAdmin)) {
    if (!adminToken) {
      // Kalau mau ke admin tapi gak punya token -> TENDANG ke Login Admin
      return next({ name: 'AdminLogin' });
    }
  }

  // 2. CEK AKSES BUYER
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!buyerToken) {
      // Kalau mau belanja tapi gak punya token -> TENDANG ke Login Buyer
      return next({ name: 'Login' });
    }
  }

  // 3. LOGIC REDIRECT JIKA SUDAH LOGIN (Biar gak balik ke halaman login)
  
  // Jika Admin sudah login, tendang balik ke Dashboard jika buka halaman login admin
  if (to.name === 'AdminLogin' && adminToken) {
    return next({ name: 'AdminDashboard' });
  }
  
  // Jika Buyer sudah login, tendang balik ke Home jika buka login/register
  if ((to.name === 'Login' || to.name === 'Register') && buyerToken) {
    return next({ name: 'Home' });
  }

  // Kalau aman semua, silakan lewat
  next();
});

export default router;