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

      <div class="bg-[#151515] p-6 rounded-2xl border border-gray-800 shadow-xl mb-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input v-model="search" type="text" placeholder="Cari nama alat..." class="input-dark" />
          
          <select v-model="kategori" class="input-dark">
            <option>Semua Kategori</option>
            <option>Gitar</option><option>Bass</option><option>Drum</option><option>Keyboard</option><option>Mikrofon</option>
          </select>

          <select v-model="minHarga" class="input-dark">
            <option :value="0">Harga Min</option>
            <option v-for="n in hargaList" :key="n" :value="n">Rp {{ n.toLocaleString() }}</option>
          </select>

          <button @click="resetFilter" class="bg-gray-800 text-white font-bold py-3 rounded-xl hover:bg-gray-700 transition uppercase tracking-wide">
            Reset
          </button>
        </div>
      </div>

      <div v-if="filteredProduk.length === 0" class="text-center py-20 bg-[#111] rounded-3xl border border-gray-800 border-dashed">
        <p class="text-gray-500 text-xl font-bold">Gear yang lo cari gak ada nih 😭</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <router-link
          v-for="item in filteredProduk"
          :key="item.id"
          :to="`/katalog/${item.id}`"
          class="group bg-[#151515] rounded-3xl overflow-hidden border border-gray-800 hover:border-rose-600/50 hover:shadow-[0_0_20px_rgba(225,29,72,0.2)] transition-all duration-300"
        >
          <div class="relative h-64 bg-[#050505] p-6 flex items-center justify-center overflow-hidden">
            <img 
              :src="getImgUrl(item.gambar)"
              @error="$event.target.src = 'https://placehold.co/400x400/1a1a1a/FFF?text=No+Image'"
              class="w-full h-full object-contain group-hover:scale-110 group-hover:rotate-3 transition duration-500" 
            />
            <div class="absolute top-3 left-3">
               <span :class="item.status === 'Tersedia' ? 'bg-green-500 text-black' : 'bg-red-600 text-white'" class="text-[10px] font-black px-2 py-1 uppercase tracking-widest rounded-sm">
                 {{ item.status }}
               </span>
            </div>
          </div>

          <div class="p-6">
            <p class="text-rose-500 text-xs font-bold uppercase tracking-widest mb-1">{{ item.kategori }}</p>
            <h3 class="text-white text-xl font-black uppercase italic leading-none mb-4 truncate group-hover:text-rose-500 transition">{{ item.nama_alat }}</h3>
            
            <div class="flex items-center justify-between border-t border-gray-800 pt-4">
               <div>
                 <p class="text-gray-400 text-xs uppercase font-bold">Sewa Harian</p>
                 <p class="text-white font-bold">Rp {{ Number(item.harga_sewa).toLocaleString() }}</p>
               </div>
               <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white group-hover:bg-rose-600 transition">
                 <i class="fas fa-arrow-right"></i>
               </div>
            </div>
          </div>
        </router-link>
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

/* --- FUNGSI PINTAR URL GAMBAR --- */
// Karena di database path-nya 'images/alat-band/...', kita harus gabung dengan URL backend
const getImgUrl = (path) => {
  if (!path) return 'https://placehold.co/400x400/1a1a1a/FFF?text=No+Image';
  // Cek apakah path sudah ada http-nya (link luar)
  if (path.startsWith('http')) return path;
  // Arahkan ke storage backend
  return `http://127.0.0.1:8000/storage/${path}`;
  // ATAU jika file ada di folder public biasa (bukan storage):
  // return `http://127.0.0.1:8000/${path}`;
}

const getAlatBand = async () => {
  try {
    const res = await axios.get('/api/alat-band')
    alatBand.value = res.data
  } catch (err) {
    console.error("Gagal ambil data:", err)
  }
}

const filteredProduk = computed(() => {
  return alatBand.value.filter((item) => {
    return item.nama_alat.toLowerCase().includes(search.value.toLowerCase()) &&
           (kategori.value === 'Semua Kategori' || item.kategori === kategori.value) &&
           item.harga_sewa >= minHarga.value
  })
})

const resetFilter = () => { search.value = ''; kategori.value = 'Semua Kategori'; minHarga.value = 0; }

onMounted(() => {
  getAlatBand();
  if (route.query.kategori) {
    kategori.value = route.query.kategori;
  }
})
</script>

<style scoped>
.input-dark {
  @apply w-full bg-[#0a0a0a] border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-rose-600 focus:ring-1 focus:ring-rose-600 transition placeholder-gray-600;
}
</style>