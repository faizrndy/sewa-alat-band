<template>
    <div class="min-h-screen bg-[#0a0a0a] text-gray-200 pt-24 pb-12 font-sans selection:bg-rose-500 selection:text-white">
      <Navbar />

      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12 animate-fade-in-down">
          <h1 class="text-4xl md:text-5xl font-black text-white italic uppercase tracking-tighter mb-2">
            List <span class="text-rose-600">Gear</span>
          </h1>
          <p class="text-gray-500 text-lg">Pilih senjata lo buat tempur di panggung.</p>
        </div>

        <!-- FILTERS -->
        <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 shadow-2xl mb-12">
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
                Reset
              </button>
            </div>
          </div>
        </div>

        <!-- NO RESULTS -->
        <div v-if="filteredProduk.length === 0" class="text-center py-20 bg-[#151515] rounded-3xl border border-gray-800 border-dashed">
          <p class="text-gray-400 text-xl font-bold uppercase tracking-wide">Gear tidak ditemukan 😭</p>
          <button @click="resetFilter" class="mt-6 text-rose-500 hover:text-rose-400 font-bold hover:underline transition">Reset Pencarian</button>
        </div>

        <!-- LIST PRODUK -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

          <component
            v-for="item in filteredProduk"
            :key="item.id"
            :is="item.status === 'Tersedia' ? 'router-link' : 'div'"
            :to="item.status === 'Tersedia' ? `/katalog/${item.id}` : null"
            class="group bg-[#151515] rounded-3xl overflow-hidden border border-gray-800 relative top-0 flex flex-col transition-all duration-300"
            :class="item.status !== 'Tersedia'
              ? 'opacity-60 pointer-events-none select-none'
              : 'hover:border-rose-600/50 hover:shadow-[0_0_30px_rgba(225,29,72,0.15)] hover:-top-2'"
          >

            <div class="relative h-64 bg-[#0a0a0a] p-6 flex items-center justify-center overflow-hidden border-b border-gray-800">
              <img
                :src="getImgUrl(item.gambar)"
                @error="$event.target.src = 'https://placehold.co/400x400/1a1a1a/FFF?text=No+Image'"
                class="w-full h-full object-contain group-hover:scale-110 group-hover:rotate-3 transition duration-500 drop-shadow-2xl"
              />

              <!-- BADGE STATUS -->
              <div class="absolute top-3 left-3">
                <span
                  :class="item.status === 'Tersedia'
                    ? 'bg-green-500 text-black'
                    : 'bg-red-600 text-white'"
                  class="text-[10px] font-black px-3 py-1 uppercase tracking-widest rounded-full shadow-lg"
                >
                  {{ item.status }}
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

                <button class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2 rounded-lg uppercase tracking-wider transition shadow-lg shadow-rose-900/20">
                  LIHAT
                </button>
              </div>
            </div>

          </component>

        </div>

      </div>
    </div>
  </template>

  <script setup>
  import { ref, computed, onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  import axios from 'axios'
  import Navbar from "@/components/Navbar.vue"

  const route = useRoute()
  const alatBand = ref([])
  const search = ref('')
  const kategori = ref('Semua Kategori')
  const minHarga = ref(0)
  const hargaList = Array.from({ length: 11 }, (_, i) => i * 10000 + 50000)

  const getImgUrl = (path) => {
    if (!path) return 'https://placehold.co/400x400/1a1a1a/FFF?text=No+Image'
    if (path.startsWith('http')) return path
    return `http://127.0.0.1:8000/${path}`
  }

  const getAlatBand = async () => {
    try {
      const res = await axios.get('/api/alat-band')
      alatBand.value = res.data
    } catch (err) { console.error(err) }
  }

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
    getAlatBand()
    if (route.query.kategori) kategori.value = route.query.kategori
  })
  </script>

  <style scoped>
  .input-dark {
    @apply w-full bg-[#050505] border border-gray-700 text-white px-4 py-3 rounded-xl
           focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition
           placeholder-gray-600 h-[50px];
  }
  </style>
