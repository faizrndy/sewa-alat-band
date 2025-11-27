<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white font-sans md:pl-64 p-8">
    <div class="max-w-3xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">Tambah Alat Baru</h1>

      <div class="bg-[#151515] border border-gray-800 rounded-3xl p-8">
        <form @submit.prevent="simpanAlat" class="space-y-6">
          
          <div>
            <label class="label-dark">Nama Alat</label>
            <input v-model="form.nama_alat" type="text" class="input-dark" required />
          </div>

          <div class="grid grid-cols-2 gap-6">
            <div>
              <label class="label-dark">Kategori</label>
              <select v-model="form.kategori" class="input-dark">
                <option>Gitar</option><option>Bass</option><option>Drum</option><option>Keyboard</option><option>Sound</option>
              </select>
            </div>
            <div>
              <label class="label-dark">Harga Sewa (per hari)</label>
              <input v-model="form.harga_sewa" type="number" class="input-dark" required />
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
            <button type="submit" :disabled="loading" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition w-full">
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
const form = ref({ nama_alat: '', kategori: 'Gitar', harga_sewa: '', deskripsi: '' });

const handleFile = (e) => { fileGambar.value = e.target.files[0]; };

const simpanAlat = async () => {
  loading.value = true;
  try {
    const formData = new FormData();
    formData.append('nama_alat', form.value.nama_alat);
    formData.append('kategori', form.value.kategori);
    formData.append('harga_sewa', form.value.harga_sewa);
    formData.append('deskripsi', form.value.deskripsi);
    formData.append('stok', 1); // Default
    if (fileGambar.value) formData.append('gambar', fileGambar.value);

    // Pastikan Header Authorization ada jika backend butuh (Ambil token admin)
    // const token = localStorage.getItem('admin_token');
    
    await axios.post('/api/alat-band', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    Swal.fire({ icon: 'success', title: 'Berhasil!', background: '#111', color: '#fff' });
    router.push('/admin/alat');

  } catch (e) {
    Swal.fire({ icon: 'error', title: 'Gagal', text: e.message, background: '#111', color: '#fff' });
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.label-dark { @apply block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2; }
.input-dark { @apply w-full bg-black border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-indigo-600 focus:ring-1 focus:ring-indigo-500 outline-none transition; }
</style>