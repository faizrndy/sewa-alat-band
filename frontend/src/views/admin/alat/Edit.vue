<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white font-sans">
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

      <div v-else class="bg-[#151515] border border-gray-800 rounded-3xl p-8 shadow-lg">
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

          <div class="grid grid-cols-2 gap-6">
            <div>
              <label class="label-dark">Stok Alat</label>
              <input v-model="form.stok" type="number" min="0" class="input-dark" required placeholder="Jumlah alat..." />
            </div>
            <div>
              <label class="label-dark">Status Ketersediaan</label>
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

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start border-t border-gray-800 pt-6">
            <div>
              <label class="label-dark">Ganti Foto (Opsional)</label>
              <input type="file" @change="handleFile" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700"/>
              <p class="text-xs text-gray-500 mt-2">*Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>
            
            <div class="text-center">
              <p class="text-xs text-gray-500 mb-2">Preview Gambar:</p>
              <div class="bg-black p-2 rounded-xl border border-gray-700 inline-block">
                <img :src="previewImage" class="h-32 object-contain rounded-lg" @error="$event.target.src='https://placehold.co/150x150/000/FFF?text=No+Img'" />
              </div>
            </div>
          </div>

          <div class="pt-6 flex gap-4">
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

// Tambahkan 'stok' di sini
const form = ref({ 
  nama_alat: '', kategori: '', harga_sewa: '', stok: 0, deskripsi: '', status: '' 
});

// Helper URL
const getImgUrl = (path) => {
  if (!path) return 'https://placehold.co/150x150/000/FFF?text=No+Img';
  if (path.startsWith('http')) return path;
  return `http://127.0.0.1:8000/storage/${path}`; // Sesuaikan jika di public biasa
}

// 1. Ambil Data Lama
onMounted(async () => {
  try {
    const res = await axios.get(`/api/alat-band/${route.params.id}`);
    const data = res.data;
    form.value = {
      nama_alat: data.nama_alat,
      kategori: data.kategori,
      harga_sewa: data.harga_sewa,
      stok: data.stok, // Ambil data stok dari database
      deskripsi: data.deskripsi,
      status: data.status
    };
    if (data.gambar) {
        previewImage.value = data.gambar.startsWith('http') ? data.gambar : getImgUrl(data.gambar);
    }
  } catch (e) {
    Swal.fire('Error', 'Gagal mengambil data alat', 'error');
    router.push('/admin/alat');
  } finally {
    loadingData.value = false;
  }
});

const handleFile = (e) => {
  const file = e.target.files[0];
  if (file) {
    fileGambar.value = file;
    previewImage.value = URL.createObjectURL(file);
  }
};

// 2. Update Data
const updateAlat = async () => {
  loading.value = true;
  try {
    const token = localStorage.getItem('admin_token');
    const formData = new FormData();
    
    formData.append('nama_alat', form.value.nama_alat);
    formData.append('kategori', form.value.kategori);
    formData.append('harga_sewa', form.value.harga_sewa);
    formData.append('stok', form.value.stok); // Kirim data stok ke backend
    formData.append('deskripsi', form.value.deskripsi || '');
    formData.append('status', form.value.status);
    
    // Upload gambar jika ada
    if (fileGambar.value) {
      formData.append('gambar', fileGambar.value);
    }

    // Gunakan POST murni (karena Backend sudah POST)
    await axios.post(`/api/alat-band/${route.params.id}`, formData, {
      headers: { 
        'Content-Type': 'multipart/form-data',
        'Authorization': `Bearer ${token}` 
      }
    });

    Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data alat terupdate.', background: '#111', color: '#fff' });
    router.push('/admin/alat');

  } catch (e) {
    console.error(e);
    // Tampilkan pesan error detail jika ada
    const errorMsg = e.response?.data?.message || 'Server Error';
    Swal.fire({ icon: 'error', title: 'Gagal', text: errorMsg, background: '#111', color: '#fff' });
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.label-dark { @apply block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2; }
.input-dark { @apply w-full bg-black border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition; }
</style>