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

      <div v-if="hasSearched" class="bg-[#151515] p-6 rounded-3xl border border-gray-800 shadow-xl mb-12 transition-all duration-500">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
          <div class="md:col-span-4 lg:col-span-1">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Cari Alat</label>
            <input v-model="search" type="text" placeholder="Ketik nama alat..." class="input-dark" />
          </div>

          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Kategori</label>
            <select v-model="kategori" class="input-dark">
              <option>Semua Kategori</option>
              <option>Gitar</option>
              <option>Bass</option>
              <option>Drum</option>
              <option>Keyboard</option>
              <option>Mikrofon</option>
              <option>Sound</option>
            </select>
          </div>

          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Budget Min</label>
            <select v-model="minHarga" class="input-dark">
              <option :value="0">Rp 0</option>
              <option v-for="n in hargaList" :key="n" :value="n">Rp {{ n.toLocaleString() }}</option>
            </select>
          </div>

          <div>
            <button @click="resetFilter" class="w-full h-[50px] bg-gray-800 hover:bg-gray-700 text-white font-bold uppercase tracking-widest rounded-xl transition border border-gray-700">
              Reset Filter
            </button>
          </div>
        </div>
      </div>

      <div v-if="!hasSearched && !isLoading" class="text-center py-20">
        <div class="inline-block p-6 rounded-full bg-gray-900 border border-gray-800 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-white uppercase italic">Tentukan Tanggal Mainmu!</h3>
        <p class="text-gray-500 mt-2">Pilih tanggal di atas untuk melihat senjata yang nganggur.</p>
      </div>

      <div v-else-if="filteredProduk.length === 0 && hasSearched" class="text-center py-20 bg-[#151515] rounded-3xl border border-gray-800 border-dashed">
        <p class="text-gray-400 text-xl font-bold uppercase tracking-wide">Waduh, Gear Habis / Tidak Ditemukan 😭</p>
        <button @click="resetFilter" class="mt-6 text-rose-500 hover:text-rose-400 font-bold hover:underline transition">Coba Reset Filter</button>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        <div v-for="item in filteredProduk" :key="item.id" 
          class="group bg-[#151515] rounded-3xl overflow-hidden border border-gray-800 relative top-0 flex flex-col transition-all duration-300 hover:border-rose-600/50 hover:shadow-[0_0_30px_rgba(225,29,72,0.15)] hover:-top-2">

          <div class="relative h-64 bg-[#0a0a0a] p-6 flex items-center justify-center overflow-hidden border-b border-gray-800">
            <img :src="getImgUrl(item.gambar)" 
              @error="$event.target.src = 'https://placehold.co/400x400/1a1a1a/FFF?text=No+Image'"
              class="w-full h-full object-contain group-hover:scale-110 group-hover:rotate-3 transition duration-500 drop-shadow-2xl" />

            <div class="absolute top-3 left-3">
              <span class="bg-green-500 text-black text-[10px] font-black px-3 py-1 uppercase tracking-widest rounded-full shadow-lg">
                Stok: {{ item.stok_tersedia }}
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
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2' // Pastikan install: npm install sweetalert2
import Navbar from "@/components/Navbar.vue"

// State
const alatBand = ref([])
const search = ref('')
const kategori = ref('Semua Kategori')
const minHarga = ref(0)
const hargaList = Array.from({ length: 11 }, (_, i) => i * 10000 + 50000)

// State Baru untuk Filter Tanggal
const tglMulai = ref('')
const tglSelesai = ref('')
const isLoading = ref(false)
const hasSearched = ref(false) // Penanda apakah user sudah klik cari

const route = useRoute()

// Helper Image URL
const getImgUrl = (path) => {
  if (!path) return 'https://placehold.co/400x400/1a1a1a/FFF?text=No+Image'
  if (path.startsWith('http')) return path
  return `http://127.0.0.1:8000/${path}`
}

// 🟢 FUNGSI UTAMA: CEK KETERSEDIAAN (API BARU)
const cekKetersediaan = async () => {
  if (!tglMulai.value || !tglSelesai.value) {
    Swal.fire({
      icon: 'warning',
      title: 'Oops...',
      text: 'Pilih Tanggal Mulai & Selesai dulu ya!',
      background: '#151515',
      color: '#fff'
    })
    return
  }

  // Validasi Tanggal
  if (tglSelesai.value < tglMulai.value) {
    Swal.fire({
       icon: 'error',
       title: 'Tanggal Error',
       text: 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
       background: '#151515',
       color: '#fff'
    })
    return
  }

  isLoading.value = true
  
  try {
    // Tembak API searchAvailable yang baru kita buat
    const res = await axios.get('/api/alat-check', {
      params: {
        tgl_mulai: tglMulai.value,
        tgl_selesai: tglSelesai.value
      }
    })
    
    alatBand.value = res.data.data
    hasSearched.value = true

    // 💾 SIMPAN TANGGAL KE LOCALSTORAGE (PENTING BUAT CHECKOUT)
    localStorage.setItem('sewa_tgl_mulai', tglMulai.value)
    localStorage.setItem('sewa_tgl_selesai', tglSelesai.value)

    if (alatBand.value.length === 0) {
       Swal.fire({
        icon: 'info',
        title: 'Kosong',
        text: 'Tidak ada alat tersedia di tanggal tersebut.',
        background: '#151515',
        color: '#fff'
      })
    }

  } catch (err) {
    console.error(err)
    Swal.fire('Error', 'Gagal memuat data alat', 'error')
  } finally {
    isLoading.value = false
  }
}

// 🛒 FUNGSI ADD TO CART (BULK ORDER LOGIC)
const addToCart = (item) => {
  // Cek lagi tanggal (safety)
  if(!localStorage.getItem('sewa_tgl_mulai')) {
      Swal.fire('Eits!', 'Pilih tanggal dulu di atas!', 'warning')
      return
  }

  let cart = JSON.parse(localStorage.getItem('keranjang')) || []

  // Cek Duplikasi
  const exist = cart.find(c => c.id === item.id)
  if (exist) {
      Swal.fire({
        icon: 'info',
        title: 'Sudah Ada',
        text: 'Alat ini sudah ada di keranjang.',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        background: '#151515',
        color: '#fff'
      })
      return
  }

  // Masukkan ke cart (Hanya data barang, tanggal ikut global)
  cart.push({
      id: item.id,
      nama_alat: item.nama_alat,
      harga_sewa: item.harga_sewa,
      gambar: item.gambar,
      qty: 1
  })

  localStorage.setItem('keranjang', JSON.stringify(cart))

  Swal.fire({
      icon: 'success',
      title: 'Masuk Keranjang!',
      text: 'Lanjut pilih alat lain atau checkout.',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      background: '#151515',
      color: '#fff'
  })
}

// Filter Lokal (Berjalan di atas hasil API)
const filteredProduk = computed(() => {
  return alatBand.value.filter((item) => {
    return item.nama_alat.toLowerCase().includes(search.value.toLowerCase()) &&
           (kategori.value === 'Semua Kategori' || item.kategori === kategori.value) &&
           item.harga_sewa >= minHarga.value
  })
})

const resetFilter = () => {
  search.value = ''
  kategori.value = 'Semua Kategori'
  minHarga.value = 0
}

onMounted(() => {
  // Cek apakah user pernah search sebelumnya (opsional, biar UX enak)
  const savedStart = localStorage.getItem('sewa_tgl_mulai')
  const savedEnd = localStorage.getItem('sewa_tgl_selesai')

  if(savedStart && savedEnd) {
    tglMulai.value = savedStart
    tglSelesai.value = savedEnd
    // Opsional: Langsung load data jika mau
    // cekKetersediaan() 
  }

  if (route.query.kategori) kategori.value = route.query.kategori
})
</script>

<style scoped>
.input-dark {
  @apply w-full border border-gray-700 text-white px-4 py-3 rounded-xl
         focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition
         placeholder-gray-600 h-[50px];
}
</style>