<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white font-sans flex">
    
    <aside class="w-64 bg-[#111] border-r border-gray-800 hidden md:flex flex-col fixed h-full">
      <div class="p-6 border-b border-gray-800">
        <h2 class="text-2xl font-black italic tracking-tighter text-white">KRATAK <span class="text-indigo-500">ADMIN</span></h2>
      </div>
      <nav class="flex-1 p-4 space-y-2">
        <router-link to="/admin/dashboard" class="nav-item" active-class="active">
          <i class="fas fa-home w-6"></i> Dashboard
        </router-link>
        <router-link to="/admin/alat" class="nav-item" active-class="active">
          <i class="fas fa-guitar w-6"></i> Kelola Alat
        </router-link>
        <router-link to="/admin/transaksi" class="nav-item" active-class="active">
          <i class="fas fa-file-invoice-dollar w-6"></i> Transaksi
        </router-link>
      </nav>
      <div class="p-4 border-t border-gray-800">
        <button @click="logout" class="w-full text-left px-4 py-3 text-red-500 hover:bg-red-900/10 rounded-xl transition flex items-center gap-3 font-bold">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </div>
    </aside>

    <main class="flex-1 md:ml-64 p-8">
      <header class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold">Dashboard Overview</h1>
          <p class="text-gray-500">Selamat datang kembali, Admin.</p>
        </div>
      </header>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-stat bg-indigo-900/20 border-indigo-500/30">
          <h3 class="text-indigo-400 text-sm font-bold uppercase">Total Alat</h3>
          <p class="text-4xl font-black mt-2">120</p>
        </div>
        <div class="card-stat bg-green-900/20 border-green-500/30">
          <h3 class="text-green-400 text-sm font-bold uppercase">Pendapatan</h3>
          <p class="text-4xl font-black mt-2">Rp 15jt</p>
        </div>
        <div class="card-stat bg-orange-900/20 border-orange-500/30">
          <h3 class="text-orange-400 text-sm font-bold uppercase">Perlu Diproses</h3>
          <p class="text-4xl font-black mt-2">5</p>
        </div>
      </div>

      <div class="bg-[#151515] p-8 rounded-3xl border border-gray-800 text-center py-20">
        <i class="fas fa-chart-line text-6xl text-gray-700 mb-4"></i>
        <p class="text-gray-500">Grafik statistik akan muncul di sini.</p>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const logout = async () => {
  try {
    const token = localStorage.getItem('admin_token');
    await axios.post('/api/buyer/logout', {}, { headers: { Authorization: `Bearer ${token}` } });
  } catch (e) {}
  localStorage.removeItem('admin_token');
  localStorage.removeItem('user_role');
  router.push('/admin/login');
};
</script>

<style scoped>
.nav-item { @apply flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl transition font-medium; }
.active { @apply bg-indigo-600 text-white shadow-lg shadow-indigo-900/50; }
.card-stat { @apply p-6 rounded-2xl border border-dashed; }
</style>