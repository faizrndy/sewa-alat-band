<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white font-sans md:pl-64 p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold">Kelola Alat Band</h1>
      <router-link to="/admin/alat/create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl font-bold shadow-lg transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Alat
      </router-link>
    </div>

    <div class="bg-[#151515] rounded-3xl border border-gray-800 overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-[#222] text-gray-400 uppercase text-xs font-bold">
          <tr>
            <th class="px-6 py-4">Gambar</th>
            <th class="px-6 py-4">Nama Alat</th>
            <th class="px-6 py-4">Kategori</th>
            <th class="px-6 py-4">Harga</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
          <tr v-for="item in alatBand" :key="item.id" class="hover:bg-white/5 transition">
            <td class="px-6 py-4">
              <img :src="`http://127.0.0.1:8000/${item.gambar}`" class="w-12 h-12 rounded-lg object-cover bg-black" />
            </td>
            <td class="px-6 py-4 font-bold">{{ item.nama_alat }}</td>
            <td class="px-6 py-4 text-sm text-gray-400">{{ item.kategori }}</td>
            <td class="px-6 py-4 text-indigo-400 font-mono">Rp {{ Number(item.harga_sewa).toLocaleString() }}</td>
            <td class="px-6 py-4">
              <span :class="item.status === 'Tersedia' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'" class="px-3 py-1 rounded-full text-xs font-bold uppercase">
                {{ item.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-right space-x-2">
              <button class="text-blue-500 hover:bg-blue-900/30 p-2 rounded-lg transition"><i class="fas fa-edit"></i></button>
              <button @click="hapusAlat(item.id)" class="text-red-500 hover:bg-red-900/30 p-2 rounded-lg transition"><i class="fas fa-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
      
      <div v-if="alatBand.length === 0" class="text-center py-12 text-gray-500">
        Belum ada data alat.
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const alatBand = ref([]);

const getAlat = async () => {
  try {
    const res = await axios.get('/api/alat-band');
    alatBand.value = res.data;
  } catch (e) { console.error(e); }
};

const hapusAlat = async (id) => {
  const result = await Swal.fire({
    title: 'Yakin hapus?',
    text: "Data gak bisa balik lagi loh!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, Hapus!',
    background: '#111', color: '#fff'
  });

  if (result.isConfirmed) {
    try {
      // Pastikan endpoint delete sudah ada di backend (Route::delete)
      // await axios.delete(`/api/alat-band/${id}`); 
      Swal.fire({title: 'Terhapus!', icon: 'success', background: '#111', color: '#fff'});
      getAlat(); // Refresh data
    } catch (e) {
      Swal.fire('Gagal', 'Terjadi kesalahan server', 'error');
    }
  }
};

onMounted(getAlat);
</script>