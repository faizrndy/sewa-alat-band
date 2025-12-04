import { createRouter, createWebHistory } from 'vue-router'

// Import View
import Home from '@/views/Home.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

const routes = [
  // ==========================================
  // 🏠 PUBLIC & CUSTOMER
  // ==========================================

  { path: '/', name: 'Home', component: Home },

  {
    path: '/verify-otp',
    name: 'verify-otp',
    component: () => import('@/views/VerifyOtp.vue')
  },


  {
    path: '/katalog',
    name: 'Katalog',
    component: () => import('@/views/customer/Katalog.vue')
  },

  {
    path: '/katalog/:id',
    name: 'DetailAlat',
    component: () => import('@/views/customer/DetailAlat.vue'),
    props: true
  },

  {
    path: '/keranjang',
    name: 'Keranjang',
    component: () => import('@/views/customer/Keranjang.vue'),
    meta: { requiresAuth: true }
  },

  {
    path: '/profile',
    name: 'Profile',
    component: () => import('@/views/customer/Profile.vue'),
    meta: { requiresAuth: true }
  },

  {
    path: "/pembayaran",
    name: "Pembayaran",
    component: () => import("@/views/customer/Pembayaran.vue"),
    meta: { requiresAuth: true }
  },

  {
    path: "/riwayat",
    name: "Riwayat",
    component: () => import("@/views/customer/Riwayat.vue"),
    meta: { requiresAuth: true }
  },

  { path: '/about', name: 'About', component: () => import('@/views/About.vue') },
  { path: '/faq', name: 'FAQ', component: () => import('@/views/FAQ.vue') },
  { path: '/terms', name: 'Terms', component: () => import('@/views/Terms.vue') },

  // AUTH CUSTOMER
  { path: '/login', name: 'Login', component: () => import('@/views/Login.vue') },
  { path: '/register', name: 'Register', component: () => import('@/views/Register.vue') },

  // ==========================================
  // 🔐 ADMIN AREA
  // ==========================================

  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: () => import('@/views/admin/LoginAdmin.vue')
  },

  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAdmin: true },
    redirect: '/admin/dashboard',
    children: [
      { path: 'dashboard', name: 'AdminDashboard', component: () => import('@/views/admin/Dashboard.vue') },
      { path: 'alat', name: 'AdminAlatIndex', component: () => import('@/views/admin/alat/Index.vue') },
      { path: 'alat/create', name: 'AdminAlatCreate', component: () => import('@/views/admin/alat/Create.vue') },
      { path: 'alat/edit/:id', name: 'AdminAlatEdit', component: () => import('@/views/admin/alat/Edit.vue') },
      { path: 'transaksi', name: 'AdminTransaksiIndex', component: () => import('@/views/admin/transaksi/Index.vue') },
    ]
  },

  // CATCH ALL
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// ==========================================
// 🛡️ NAVIGATION GUARD FIXED
// ==========================================
router.beforeEach((to, from, next) => {
  const buyerToken = localStorage.getItem("buyer_token");
  const adminToken = localStorage.getItem("admin_token");

  // 1️⃣ ADMIN PROTECTED ROUTES
  if (to.matched.some(r => r.meta.requiresAdmin)) {
    if (!adminToken) {
      return next({ name: 'AdminLogin' });
    }
  }

  // 2️⃣ CUSTOMER PROTECTED ROUTES
  if (to.matched.some(r => r.meta.requiresAuth)) {
    if (!buyerToken) {
      return next({ name: 'Login' });
    }
  }

  // 3️⃣ ADMIN LOGIN -> kalau sudah login, langsung masuk dashboard
  if (to.name === 'AdminLogin' && adminToken) {
    return next({ name: 'AdminDashboard' });
  }

  // 4️⃣ BUYER LOGIN -> kalau sudah login, jauhkan dari login/register
  if ((to.name === 'Login' || to.name === 'Register') && buyerToken) {
    return next({ name: 'Home' });
  }

  next();
});

export default router;
