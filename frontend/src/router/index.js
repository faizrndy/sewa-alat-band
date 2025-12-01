import { createRouter, createWebHistory } from 'vue-router'

// Import View
import Home from '@/views/Home.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

const routes = [
  // ==========================================
  // 🏠 BAGIAN PUBLIC & CUSTOMER
  // ==========================================
  
  { path: '/', name: 'Home', component: Home },

  // KATALOG
  { 
    path: '/katalog', 
    name: 'Katalog', 
    component: () => import('@/views/customer/Katalog.vue') 
  },

  // DETAIL PRODUK
  {
    path: '/katalog/:id',
    name: 'DetailAlat',
    component: () => import('@/views/customer/DetailAlat.vue'),
    props: true
  },

  // HALAMAN BUTUH LOGIN
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
    meta: { requiresAuth: true },
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
  
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// ==========================================
// 🛡️ NAVIGATION GUARD (SATPAM GALAK)
// ==========================================
router.beforeEach((to, from, next) => {
  const buyerToken = localStorage.getItem("buyer_token");
  const adminToken = localStorage.getItem("admin_token");
  
  // Ambil Data User & Cek Role
  const userDataStr = localStorage.getItem("user_data");
  const user = userDataStr ? JSON.parse(userDataStr) : null;

  // 1. CEK AKSES ADMIN
  if (to.matched.some(record => record.meta.requiresAdmin)) {
    // Kalau gak punya token admin -> TENDANG
    if (!adminToken) {
      return next({ name: 'AdminLogin' });
    }
    
    // 🔥 PERBAIKAN UTAMA: Cek Role User 🔥
    // Kalau punya token, tapi role-nya BUKAN admin (misal 'buyer'), TENDANG KE HOME
    if (user && user.role !== 'admin') {
       return next({ name: 'Home' });
    }
  }

  // 2. CEK AKSES BUYER (Harus Login)
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!buyerToken) {
      return next({ name: 'Login' });
    }
  }

  // 3. LOGIC REDIRECT LOGIN (Biar gak muter-muter)
  if (to.name === 'AdminLogin' && adminToken) {
    return next({ name: 'AdminDashboard' });
  }
  
  if ((to.name === 'Login' || to.name === 'Register') && buyerToken) {
    return next({ name: 'Home' });
  }

  next();
});

export default router;