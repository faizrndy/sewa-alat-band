<template>
  <div>
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold">Kelola Alat Band</h1>
      <router-link to="/admin/alat/create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl font-bold shadow-lg transition flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Alat
      </router-link>
    </div>

    <div class="bg-[#151515] rounded-3xl border border-gray-800 overflow-hidden shadow-xl">
      <table class="w-full text-left">
        <thead class="bg-[#222] text-gray-400 uppercase text-xs font-bold">
          <tr>
            <th class="px-6 py-4">Gambar</th>
            <th class="px-6 py-4">Nama Alat</th>
            <th class="px-6 py-4">Kategori</th>
            <th class="px-6 py-4">Harga</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
          <tr v-for="item in alatBand" :key="item.id" class="hover:bg-white/5 transition">
            
            <td class="px-6 py-4">
              <img 
                :src="getImgUrl(item.gambar)" 
                @error="$event.target.src = 'https://placehold.co/100x100/1a1a1a/FFF?text=No+Img'"
                class="w-12 h-12 rounded-lg object-cover bg-black border border-gray-700" 
              />
            </td>

            <td class="px-6 py-4 font-bold">{{ item.nama_alat }}</td>
            <td class="px-6 py-4 text-sm text-gray-400">{{ item.kategori }}</td>
            <td class="px-6 py-4 text-indigo-400 font-mono">Rp {{ Number(item.harga_sewa).toLocaleString() }}</td>
            <td class="px-6 py-4">
              <span :class="item.status === 'Tersedia' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'" class="px-3 py-1 rounded-full text-xs font-bold uppercase">
                {{ item.status }}
              </span>
            </td>

            <td class="px-6 py-4 text-center">
              <div class="flex items-center justify-center gap-3">
                
                <router-link :to="`/admin/alat/edit/${item.id}`" class="w-8 h-8 rounded-lg bg-blue-600/20 text-blue-500 flex items-center justify-center hover:bg-blue-600 hover:text-white transition" title="Edit">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                  </svg>
                </router-link>
                
                <button @click="hapusAlat(item.id)" class="w-8 h-8 rounded-lg bg-red-600/20 text-red-500 flex items-center justify-center hover:bg-red-600 hover:text-white transition" title="Hapus">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                  </svg>
                </button>

              </div>
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

const getImgUrl = (path) => {
  if (!path) return 'https://placehold.co/100x100/1a1a1a/FFF?text=No+Img';
  if (path.startsWith('http')) return path;
  return `http://127.0.0.1:8000/${path}`;
}

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
      const token = localStorage.getItem('admin_token');
      await axios.delete(`/api/alat-band/${id}`, { headers: { Authorization: `Bearer ${token}` } }); 
      Swal.fire({title: 'Terhapus!', icon: 'success', background: '#111', color: '#fff'});
      getAlat(); 
    } catch (e) {
      Swal.fire('Gagal', 'Terjadi kesalahan server', 'error');
    }
  }
};

onMounted(getAlat);
</script>