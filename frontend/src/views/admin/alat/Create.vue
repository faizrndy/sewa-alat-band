<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white font-sans md:pl-64 p-8">
    <div class="max-w-3xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">Tambah Alat Baru</h1>

      <div class="bg-[#151515] border border-gray-800 rounded-3xl p-8 shadow-lg">
        <form @submit.prevent="simpanAlat" class="space-y-6">
          
          <div>
            <label class="label-dark">Nama Alat</label>
            <input v-model="form.nama_alat" type="text" class="input-dark" required />
          </div>

          <div class="grid grid-cols-2 gap-6">
            <div>
              <label class="label-dark">Kategori</label>
              <select v-model="form.kategori" class="input-dark">
                <option>Gitar</option><option>Bass</option><option>Drum</option><option>Keyboard</option><option>Sound</option><option>Mikrofon</option>
              </select>
            </div>
            <div>
              <label class="label-dark">Harga Sewa</label>
              <input v-model="form.harga_sewa" type="number" class="input-dark" required />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-6">
            <div>
              <label class="label-dark">Stok</label>
              <input v-model="form.stok" type="number" min="1" class="input-dark" required />
            </div>
            <div>
              <label class="label-dark">Status</label>
              <select v-model="form.status" class="input-dark">
                <option value="Tersedia">🟢 Tersedia</option>
                <option value="Disewa">🔴 Sedang Disewa</option>
                <option value="Dalam Perbaikan">🔧 Dalam Perbaikan</option>
              </select>
            </div>
          </div>

          <div>
            <label class="label-dark">Deskripsi</label>
            <textarea v-model="form.deskripsi" rows="4" class="input-dark"></textarea>
          </div>

          <div>
            <label class="label-dark">Foto Alat</label>
            <input type="file" @change="handleFile" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700"/>
          </div>

          <div class="pt-4 flex gap-4">
            <button type="submit" :disabled="loading" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition w-full shadow-lg shadow-indigo-900/20">
              {{ loading ? 'Menyimpan...' : 'Simpan Data' }}
            </button>
            <router-link to="/admin/alat" class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-bold transition">
              Batal
            </router-link>
          </div>

        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const router = useRouter();
const loading = ref(false);
const fileGambar = ref(null);
const form = ref({ 
  nama_alat: '', 
  kategori: 'Gitar', 
  harga_sewa: '', 
  stok: 1, 
  deskripsi: '', 
  status: 'Tersedia' 
});

const handleFile = (e) => { fileGambar.value = e.target.files[0]; };

const simpanAlat = async () => {
  loading.value = true;
  try {
    // 1. Ambil Token Admin dari LocalStorage
    const token = localStorage.getItem('admin_token');
    
    // Cek jika token hilang (misal session habis)
    if (!token) {
        Swal.fire('Session Habis', 'Silakan login ulang', 'warning');
        router.push('/admin/login');
        return;
    }

    const formData = new FormData();
    formData.append('nama_alat', form.value.nama_alat);
    formData.append('kategori', form.value.kategori);
    formData.append('harga_sewa', form.value.harga_sewa);
    formData.append('stok', form.value.stok);
    formData.append('deskripsi', form.value.deskripsi);
    formData.append('status', form.value.status);
    
    if (fileGambar.value) formData.append('gambar', fileGambar.value);

    // 2. Kirim dengan Header Authorization (INI YANG DULU KURANG)
    await axios.post('/api/alat-band', formData, {
      headers: { 
        'Content-Type': 'multipart/form-data',
        'Authorization': `Bearer ${token}` // <--- KUNCI MASALAHNYA DISINI
      }
    });

    Swal.fire({ icon: 'success', title: 'Berhasil!', background: '#111', color: '#fff' });
    router.push('/admin/alat');

  } catch (e) {
    console.error(e);
    // Tampilkan pesan error detail
    const msg = e.response?.data?.message || 'Gagal menyimpan data';
    Swal.fire({ icon: 'error', title: 'Gagal', text: msg, background: '#111', color: '#fff' });
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.label-dark { @apply block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2; }
.input-dark { @apply w-full bg-black border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition; }
</style>