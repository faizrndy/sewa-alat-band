<template>
    <!-- Navbar -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <div class="flex items-center gap-2">
            <Music2 class="w-8 h-8 text-blue-600" />
            <span class="text-slate-900 font-medium text-lg">Kratak FC</span>
          </div>

          <!-- Nav Menu -->
          <nav class="hidden md:flex items-center gap-8">
            <router-link to="/" class="text-slate-600 hover:text-blue-600">Beranda</router-link>
            <router-link to="/katalog" class="text-slate-600 hover:text-blue-600">Katalog</router-link>
            <router-link to="/about" class="text-slate-600 hover:text-blue-600">About</router-link>
            <router-link to="/terms" class="text-slate-600 hover:text-blue-600">Syarat & Ketentuan</router-link>
          </nav>

          <!-- Button -->
           <!-- 🛒 Icon Keranjang -->
<router-link to="/keranjang" class="relative">
  <span class="text-2xl">🛒</span>
  <span
    v-if="cartCount > 0"
    class="absolute -top-2 -right-3 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full"
  >
    {{ cartCount }}
  </span>
</router-link>

        </div>
      </div>
    </header>

    <!-- HERO -->
    <section class="bg-gradient-to-r from-purple-600 to-fuchsia-500 text-center py-24 text-white">
      <h1 class="text-4xl font-bold mb-2">Katalog Alat Band</h1>
      <p class="text-white/90">Temukan alat band terbaik untuk kebutuhan Anda</p>
    </section>

    <!-- FILTER SECTION -->
    <section class="relative z-10 max-w-6xl mx-auto px-6 -mt-5 mb-24">
      <div class="bg-white/80 backdrop-blur-md border border-border/30 shadow-xl shadow-black/5 rounded-3xl p-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
          <!-- Harga Min -->
          <div>
            <label class="block mb-2 text-sm font-medium">Harga Minimal per Hari</label>
            <select v-model="minHarga" class="w-full border border-slate-300 rounded-xl px-4 py-3">
              <option v-for="n in hargaList" :key="n" :value="n">Rp {{ n.toLocaleString() }}</option>
            </select>
          </div>

          <!-- Harga Max -->
          <div>
            <label class="block mb-2 text-sm font-medium">Harga Maksimal per Hari</label>
            <select v-model="maxHarga" class="w-full border border-slate-300 rounded-xl px-4 py-3">
              <option v-for="n in hargaList" :key="'max' + n" :value="n">Rp {{ n.toLocaleString() }}</option>
            </select>
          </div>

          <!-- Kategori -->
          <div>
            <label class="block mb-2 text-sm font-medium">Kategori</label>
            <select v-model="kategori" class="w-full border border-slate-300 rounded-xl px-4 py-3">
              <option>Semua Kategori</option>
              <option>Gitar</option>
              <option>Bass</option>
              <option>Drum</option>
              <option>Keyboard</option>
              <option>Mikrofon</option>
            </select>
          </div>

          <!-- Status -->
          <div>
            <label class="block mb-2 text-sm font-medium">Status</label>
            <select v-model="status" class="w-full border border-slate-300 rounded-xl px-4 py-3">
              <option>Semua Status</option>
              <option>Tersedia</option>
              <option>Disewa</option>
              <option>Dalam Perbaikan</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-end">
          <div class="md:col-span-2">
            <label class="block mb-2 text-sm font-medium">Cari Alat</label>
            <input
              v-model="search"
              type="text"
              placeholder="Cari nama alat..."
              class="w-full border border-slate-300 rounded-xl px-4 py-3"
            />
          </div>

          <div class="flex gap-3 justify-center md:justify-end">
            <button
              @click="filterProduk"
              class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition"
            >
              🔍 Cari
            </button>
            <button
              @click="resetFilter"
              class="border border-slate-300 px-6 py-3 rounded-xl hover:bg-slate-100 transition"
            >
              🔄 Reset
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- GRID PRODUK -->
    <section class="bg-white py-10">
      <div class="max-w-6xl mx-auto px-6 mt-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <router-link
          v-for="item in filteredProduk"
          :key="item.id"
          :to="`/katalog/${item.id}`"
          class="rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden bg-gradient-to-tr from-fuchsia-500 to-blue-500 text-white cursor-pointer"
        >
          <div class="h-40 overflow-hidden">
            <img :src="`http://127.0.0.1:8000/${item.gambar}`" alt="alat band" class="w-full h-full object-cover" />
          </div>
          <div class="bg-white text-gray-800 p-5">
            <p class="text-lg font-semibold">{{ item.nama_alat }}</p>
            <p class="text-sm text-gray-500">{{ item.kategori }}</p>
            <p class="text-blue-600 font-semibold mt-2">
              Rp {{ Number(item.harga_sewa).toLocaleString() }}/hari
            </p>
            <p
              :class="item.status === 'Tersedia' ? 'text-green-600' : 'text-red-500'"
              class="text-sm font-medium mt-1"
            >
              {{ item.status }}
            </p>
          </div>
        </router-link>
      </div>
    </section>
  </template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

/* ==========================================================
   🔹 STATE PRODUK
   ========================================================== */
const alatBand = ref([])

/* ==========================================================
   🔹 FILTER STATE
   ========================================================== */
const search = ref('')
const kategori = ref('Semua Kategori')
const status = ref('Semua Status')
const minHarga = ref(0)
const maxHarga = ref(50000)
const hargaList = Array.from({ length: 11 }, (_, i) => i * 5000)

/* ==========================================================
   🔹 ICON KERANJANG STATE (GLOBAL DI HEADER)
   ========================================================== */
const cartCount = ref(0)

/* ==========================================================
   🔹 FETCH DATA PRODUK DARI API LARAVEL
   ========================================================== */
const getAlatBand = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/alat-band')
    alatBand.value = res.data
  } catch (err) {
    console.error('Gagal ambil data alat band:', err)
  }
}

/* ==========================================================
   🔹 FILTER LOGIC
   ========================================================== */
const filteredProduk = computed(() => {
  return alatBand.value.filter((item) => {
    const cocokNama = item.nama_alat.toLowerCase().includes(search.value.toLowerCase())
    const cocokKategori =
      kategori.value === 'Semua Kategori' ||
      item.kategori.toLowerCase() === kategori.value.toLowerCase()
    const cocokStatus =
      status.value === 'Semua Status' ||
      item.status.toLowerCase() === status.value.toLowerCase()
    const cocokHarga =
      item.harga_sewa >= minHarga.value && item.harga_sewa <= maxHarga.value

    return cocokNama && cocokKategori && cocokStatus && cocokHarga
  })
})

/* ==========================================================
   🔹 RESET FILTER
   ========================================================== */
const resetFilter = () => {
  search.value = ''
  kategori.value = 'Semua Kategori'
  status.value = 'Semua Status'
  minHarga.value = 0
  maxHarga.value = 50000
}

/* ==========================================================
   🔹 UPDATE JUMLAH ITEM DI ICON KERANJANG 🛒
   ========================================================== */
onMounted(() => {
  getAlatBand()

  // Ambil isi keranjang dari localStorage
  const cart = JSON.parse(localStorage.getItem('cart') || '[]')
  cartCount.value = cart.length

  // Realtime update badge ketika keranjang berubah
  window.addEventListener('cart-updated', () => {
    const updatedCart = JSON.parse(localStorage.getItem('cart') || '[]')
    cartCount.value = updatedCart.length
  })
})
</script>
