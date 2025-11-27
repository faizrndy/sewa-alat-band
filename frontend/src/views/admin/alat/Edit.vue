<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white font-sans md:pl-64 p-8">
    <div class="max-w-3xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Alat Band</h1>
        <router-link to="/admin/alat" class="text-gray-500 hover:text-white transition">
          <i class="fas fa-arrow-left"></i> Kembali
        </router-link>
      </div>

      <div v-if="loadingData" class="text-center py-10">
        <div class="animate-spin h-8 w-8 border-2 border-indigo-600 rounded-full border-t-transparent mx-auto"></div>
      </div>

      <div v-else class="bg-[#151515] border border-gray-800 rounded-3xl p-8">
        <form @submit.prevent="updateAlat" class="space-y-6">
          
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

          <div>
            <label class="label-dark">Status Ketersediaan</label>
            <select v-model="form.status" class="input-dark">
              <option value="Tersedia">🟢 Tersedia</option>
              <option value="Disewa">🔴 Sedang Disewa</option>
              <option value="Dalam Perbaikan">🔧 Dalam Perbaikan</option>
            </select>
          </div>

          <div>
            <label class="label-dark">Deskripsi</label>
            <textarea v-model="form.deskripsi" rows="4" class="input-dark"></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <div>
              <label class="label-dark">Ganti Foto (Opsional)</label>
              <input type="file" @change="handleFile" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700"/>
              <p class="text-xs text-gray-500 mt-2">*Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>
            
            <div v-if="previewImage" class="bg-black p-2 rounded-xl border border-gray-700 text-center">
              <p class="text-xs text-gray-500 mb-2">Gambar Saat Ini:</p>
              <img :src="previewImage" class="h-32 mx-auto object-contain rounded-lg" />
            </div>
          </div>

          <div class="pt-4 flex gap-4">
            <button type="submit" :disabled="loading" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition w-full shadow-lg shadow-indigo-900/20">
              {{ loading ? 'Menyimpan...' : 'Update Data' }}
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const route = useRoute();
const router = useRouter();
const loading = ref(false);
const loadingData = ref(true);
const fileGambar = ref(null);
const previewImage = ref('');

const form = ref({ 
  nama_alat: '', kategori: '', harga_sewa: '', deskripsi: '', status: '' 
});

// 1. Ambil Data Lama
onMounted(async () => {
  try {
    const res = await axios.get(`/api/alat-band/${route.params.id}`);
    const data = res.data;
    form.value = {
      nama_alat: data.nama_alat,
      kategori: data.kategori,
      harga_sewa: data.harga_sewa,
      deskripsi: data.deskripsi,
      status: data.status
    };
    // Set preview gambar dari server
    if (data.gambar) {
      previewImage.value = `http://127.0.0.1:8000/storage/${data.gambar}`;
    }
  } catch (e) {
    Swal.fire('Error', 'Gagal mengambil data alat', 'error');
    router.push('/admin/alat');
  } finally {
    loadingData.value = false;
  }
});

const handleFile = (e) => {
  fileGambar.value = e.target.files[0];
  // Preview lokal (opsional)
  previewImage.value = URL.createObjectURL(e.target.files[0]);
};

// 2. Update Data
const updateAlat = async () => {
  loading.value = true;
  try {
    const formData = new FormData();
    formData.append('nama_alat', form.value.nama_alat);
    formData.append('kategori', form.value.kategori);
    formData.append('harga_sewa', form.value.harga_sewa);
    formData.append('deskripsi', form.value.deskripsi || '');
    formData.append('status', form.value.status);
    
    // Trik Laravel: Method PUT via FormData harus pakai _method
    formData.append('_method', 'PUT');

    if (fileGambar.value) {
      formData.append('gambar', fileGambar.value);
    }

    await axios.post(`/api/alat-band/${route.params.id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    Swal.fire({ icon: 'success', title: 'Updated!', text: 'Data alat berhasil diperbarui.', background: '#111', color: '#fff' });
    router.push('/admin/alat');

  } catch (e) {
    Swal.fire({ icon: 'error', title: 'Gagal', text: e.response?.data?.message || 'Server Error', background: '#111', color: '#fff' });
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.label-dark { @apply block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2; }
.input-dark { @apply w-full bg-black border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition; }
</style>