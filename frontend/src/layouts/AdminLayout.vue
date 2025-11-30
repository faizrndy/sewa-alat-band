<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white font-sans flex">
    
    <aside class="w-64 bg-[#111] border-r border-gray-800 hidden md:flex flex-col fixed h-full z-50">
      <div class="p-6 border-b border-gray-800">
        <h2 class="text-2xl font-black italic tracking-tighter text-white">
          KRATAK <span class="text-indigo-500">ADMIN</span>
        </h2>
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

    <main class="flex-1 md:ml-64 p-8 transition-all duration-300">
      <router-view />
    </main>

  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import axios from 'axios';
import Swal from 'sweetalert2';

const router = useRouter();

const logout = async () => {
  const result = await Swal.fire({
    title: 'Logout?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    background: '#111', color: '#fff'
  });

  if (result.isConfirmed) {
    try {
      const token = localStorage.getItem('admin_token');
      await axios.post('/api/buyer/logout', {}, { headers: { Authorization: `Bearer ${token}` } });
    } catch (e) {}
    
    localStorage.removeItem('admin_token');
    localStorage.removeItem('user_role');
    router.push('/admin/login');
  }
};
</script>

<style scoped>
.nav-item { 
  @apply flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl transition font-medium; 
}
.active { 
  @apply bg-indigo-600 text-white shadow-lg shadow-indigo-900/50; 
}
</style>