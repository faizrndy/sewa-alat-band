<template>
  <div class="min-h-screen bg-[#0a0a0a] text-gray-200 pt-24 pb-12 font-sans selection:bg-rose-500 selection:text-white">
    <Navbar />

    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-10 animate-fade-in-down">
        <h1 class="text-4xl md:text-5xl font-black text-white italic uppercase tracking-tighter mb-2">
          List <span class="text-rose-600">Gear</span>
        </h1>
        <p class="text-gray-500 text-lg">Cek ketersediaan dulu, baru sikat barangnya!</p>
      </div>

      <div class="bg-gray-900 p-6 rounded-3xl border border-rose-900/30 shadow-2xl mb-8 relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rose-600 to-purple-600"></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end relative z-10">
          <div>
            <label class="text-xs font-bold text-rose-500 uppercase tracking-widest mb-2 block">Mulai Sewa</label>
            <input v-model="tglMulai" type="date" class="input-dark bg-[#050505]" />
          </div>
          <div>
            <label class="text-xs font-bold text-rose-500 uppercase tracking-widest mb-2 block">Selesai Sewa</label>
            <input v-model="tglSelesai" type="date" class="input-dark bg-[#050505]" />
          </div>
          <div>
            <button @click="cekKetersediaan" :disabled="isLoading" 
              class="w-full h-[50px] bg-rose-600 hover:bg-rose-700 text-white font-black uppercase tracking-widest rounded-xl transition shadow-[0_0_20px_rgba(225,29,72,0.4)] hover:shadow-[0_0_30px_rgba(225,29,72,0.6)] flex items-center justify-center gap-2">
              <span v-if="isLoading">Loading...</span>
              <span v-else>🔍 Cek Ketersediaan</span>
            </button>
          </div>
        </div>
      </div>

      <div class="bg-[#1a1a1a] p-6 rounded-3xl border border-gray-800 mb-10 shadow-xl">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          
          <div class="space-y-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Cari Alat</label>
            <div class="relative">
              <input v-model="searchQuery" type="text" placeholder="Ketik nama alat..." 
                class="w-full bg-[#151515] border border-gray-800 text-white px-4 py-3 rounded-xl focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition placeholder-gray-600" />
              <i class="fas fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
            </div>
          </div>

          <div class="space-y-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Kategori</label>
            <div class="relative">
              <select v-model="filterKategori" 
                class="w-full bg-[#151515] border border-gray-800 text-white px-4 py-3 rounded-xl appearance-none focus:border-rose-600 focus:ring-1 focus:ring-rose-600 transition cursor-pointer">
                <option value="">Semua Kategori</option>
                <option value="Gitar">Gitar</option>
                <option value="Bass">Bass</option>
                <option value="Drum">Drum</option>
                <option value="Keyboard">Keyboard</option>
                <option value="Sound System">Sound System</option>
                <option value="Aksesoris">Aksesoris</option>
              </select>
              <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-500">
                <i class="fas fa-chevron-down text-xs"></i>
              </div>
            </div>
          </div>

          <div class="space-y-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Budget Min</label>
            <div class="relative">
              <select v-model="filterHargaMin" 
                class="w-full bg-[#151515] border border-gray-800 text-white px-4 py-3 rounded-xl appearance-none focus:border-rose-600 focus:ring-1 focus:ring-rose-600 transition cursor-pointer">
                <option value="0">Semua Harga</option>
                <option value="25000">Rp 25.000 +</option>
                <option value="50000">Rp 50.000 +</option>
                <option value="100000">Rp 100.000 +</option>
                <option value="200000">Rp 200.000 +</option>
                <option value="500000">Rp 500.000 +</option>
              </select>
              <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-500">
                <i class="fas fa-chevron-down text-xs"></i>
              </div>
            </div>
          </div>

          <div class="flex items-end">
            <button @click="resetFilter" 
              class="w-full bg-[#252525] hover:bg-rose-600 hover:text-white text-gray-400 font-bold py-3 px-4 rounded-xl transition border border-gray-700 hover:border-rose-600 h-[50px] uppercase tracking-widest text-sm flex items-center justify-center gap-2 group">
              <i class="fas fa-undo group-hover:-rotate-180 transition-transform duration-500"></i>
              Reset Filter
            </button>
          </div>
        </div>
      </div>

      <div v-if="loading" class="text-center py-20">
         <div class="animate-spin h-10 w-10 border-4 border-rose-600 border-t-transparent rounded-full mx-auto mb-4"></div>
         <p>Memuat Gear...</p>
      </div>

      <div v-else-if="alatBand.length === 0" class="text-center py-20 bg-[#151515] rounded-3xl border border-gray-800 border-dashed">
        <p class="text-gray-400 text-xl font-bold uppercase tracking-wide">Waduh, Gear Tidak Ditemukan 😭</p>
        <button @click="resetFilter" class="mt-6 text-rose-500 hover:text-rose-400 font-bold hover:underline transition">Coba Reset Filter</button>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div v-for="item in alatBand" :key="item.id" 
          class="group bg-[#151515] rounded-3xl overflow-hidden border border-gray-800 relative top-0 flex flex-col transition-all duration-300 hover:border-rose-600/50 hover:shadow-[0_0_30px_rgba(225,29,72,0.15)] hover:-top-2">

          <div class="relative h-64 bg-[#0a0a0a] p-6 flex items-center justify-center overflow-hidden border-b border-gray-800">
            <img :src="getImgUrl(item.gambar)" 
              @error="$event.target.src = 'https://placehold.co/400x400/1a1a1a/FFF?text=No+Image'"
              class="w-full h-full object-contain group-hover:scale-110 group-hover:rotate-3 transition duration-500 drop-shadow-2xl" />

            <div class="absolute top-3 left-3">
              <span class="bg-green-500 text-black text-[10px] font-black px-3 py-1 uppercase tracking-widest rounded-full shadow-lg">
                Stok: {{ item.stok_tersedia !== undefined ? item.stok_tersedia : item.stok }}
              </span>
            </div>
          </div>

          <div class="p-6 flex flex-col flex-grow">
            <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mb-1">{{ item.kategori }}</p>
            <h3 class="text-white text-lg font-black uppercase italic leading-tight mb-4 line-clamp-2">
              {{ item.nama_alat }}
            </h3>

            <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-800">
              <div>
                <p class="text-gray-500 text-[10px] uppercase font-bold tracking-wider">Sewa Harian</p>
                <p class="text-white font-bold text-lg">Rp {{ Number(item.harga_sewa).toLocaleString() }}</p>
              </div>

              <button @click="addToCart(item)" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2 rounded-lg uppercase tracking-wider transition shadow-lg shadow-rose-900/20 active:scale-95">
                + ADD
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Navbar from "@/components/Navbar.vue";

// STATE FILTER
const searchQuery = ref('');
const filterKategori = ref(''); // Default string kosong
const filterHargaMin = ref(0);

// STATE TANGGAL
const tglMulai = ref('');
const tglSelesai = ref('');

// DATA
const alatBand = ref([]);
const loading = ref(false);

const getImgUrl = (path) => {
  if (!path) return 'https://placehold.co/400x400/1a1a1a/FFF?text=No+Image';
  if (path.startsWith('http')) return path;
  return `http://127.0.0.1:8000/${path}`;
};

// ==========================================
// 1. FETCH DATA (LOGIC UTAMA)
// ==========================================
const loadData = async () => {
  loading.value = true;
  try {
    let url = '';
    let params = {
      search: searchQuery.value,
      kategori: filterKategori.value === 'Semua Kategori' ? '' : filterKategori.value
    };

    // LOGIC: Kalau tanggal diisi, pakai API Cek Stok. Kalau tidak, pakai API Public Biasa.
    if (tglMulai.value && tglSelesai.value) {
        url = 'http://127.0.0.1:8000/api/alat-check';
        params.tgl_mulai = tglMulai.value;
        params.tgl_selesai = tglSelesai.value;
        
        // Simpan tanggal biar ga ilang pas refresh
        localStorage.setItem('sewa_tgl_mulai', tglMulai.value);
        localStorage.setItem('sewa_tgl_selesai', tglSelesai.value);
    } else {
        url = 'http://127.0.0.1:8000/api/alat-band-public';
    }

    const res = await axios.get(url, { params });
    let results = res.data.data || res.data;

    // Filter Harga di Frontend (Client Side)
    if (parseInt(filterHargaMin.value) > 0) {
      results = results.filter(item => parseInt(item.harga_sewa) >= parseInt(filterHargaMin.value));
    }

    alatBand.value = results;

  } catch (err) {
    console.error("Gagal load data:", err);
  } finally {
    loading.value = false;
  }
};

// ==========================================
// 2. WATCHER (AUTO RELOAD SAAT FILTER GANTI)
// ==========================================
let timeout = null;
watch([searchQuery, filterKategori, filterHargaMin], () => {
  // Debounce search biar ga spam request
  if (timeout) clearTimeout(timeout);
  timeout = setTimeout(() => {
    loadData();
  }, 300);
});

// ==========================================
// 3. FUNGSI CEK KETERSEDIAAN (TOMBOL)
// ==========================================
const cekKetersediaan = () => {
  if (!tglMulai.value || !tglSelesai.value) {
    return Swal.fire({icon: 'warning', title: 'Pilih Tanggal', text: 'Tentukan tanggal dulu!', background: '#151515', color:'#fff'});
  }
  if (tglSelesai.value < tglMulai.value) {
    return Swal.fire({icon: 'error', title: 'Tanggal Salah', text: 'Tanggal selesai tidak boleh mundur.', background: '#151515', color:'#fff'});
  }
  loadData(); // Panggil fungsi loadData yang sudah pintar memilih API
};

// ==========================================
// 4. RESET FILTER
// ==========================================
const resetFilter = () => {
  searchQuery.value = '';
  filterKategori.value = '';
  filterHargaMin.value = 0;
  
  // Opsi: Reset tanggal juga jika mau
  tglMulai.value = '';
  tglSelesai.value = '';
  localStorage.removeItem('sewa_tgl_mulai');
  localStorage.removeItem('sewa_tgl_selesai');

  loadData();
};

// ==========================================
// 5. ADD TO CART
// ==========================================
const addToCart = (item) => {
  if (!tglMulai.value || !tglSelesai.value) {
     return Swal.fire({ icon: 'warning', title: 'Pilih Tanggal Dulu', text: 'Tentukan tanggal sewa di atas!', background: '#151515', color: '#fff' });
  }

  const stok = item.stok_tersedia !== undefined ? item.stok_tersedia : item.stok;
  if (stok <= 0) {
     return Swal.fire({ icon: 'error', title: 'Stok Habis', text: 'Barang ini kosong di tanggal tersebut.', background: '#151515', color: '#fff' });
  }

  let cart = JSON.parse(localStorage.getItem('keranjang')) || [];
  const exist = cart.find(c => c.id === item.id);
  
  if (exist) {
     return Swal.fire({ icon: 'info', title: 'Sudah di Keranjang', text: 'Barang ini sudah kamu ambil.', background: '#151515', color: '#fff' });
  }

  cart.push({ ...item, qty: 1 });
  localStorage.setItem('keranjang', JSON.stringify(cart));
  window.dispatchEvent(new Event('cart-updated'));
  
  Swal.fire({
    icon: 'success', title: 'Masuk Keranjang!', text: `${item.nama_alat} siap dibungkus.`,
    background: '#151515', color: '#fff', confirmButtonColor: '#e11d48'
  });
};

onMounted(() => {
  // Load tanggal dari storage jika ada
  const savedStart = localStorage.getItem('sewa_tgl_mulai');
  const savedEnd = localStorage.getItem('sewa_tgl_selesai');
  
  if (savedStart && savedEnd) {
      tglMulai.value = savedStart;
      tglSelesai.value = savedEnd;
  }
  
  loadData();
});
</script>

<style scoped>
.input-dark {
  @apply w-full border border-gray-700 text-white px-4 py-3 rounded-xl
         focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition
         placeholder-gray-600 h-[50px];
}
</style>