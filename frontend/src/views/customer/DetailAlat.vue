<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white pt-28 pb-12 font-sans">
    <Navbar />
    
    <div class="max-w-6xl mx-auto px-6" v-if="alat">
      <router-link to="/katalog" class="inline-flex items-center gap-2 text-gray-500 hover:text-white mb-8 transition font-bold uppercase text-sm tracking-wider">
        <i class="fas fa-arrow-left"></i> Kembali ke List Gear
      </router-link>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div class="bg-[#151515] border border-gray-800 rounded-3xl p-10 flex items-center justify-center relative group">
          <div class="absolute inset-0 bg-rose-600/5 opacity-0 group-hover:opacity-100 transition duration-500 blur-3xl rounded-full"></div>
          <img :src="alat.gambar" class="w-full max-h-[500px] object-contain relative z-10 drop-shadow-2xl" />
        </div>

        <div>
          <div class="flex items-center gap-3 mb-4">
             <span class="bg-rose-600 text-white text-[10px] font-black px-3 py-1 uppercase rounded-sm tracking-widest">{{ alat.kategori }}</span>
             <span :class="alat.status === 'Tersedia' ? 'text-green-500' : 'text-red-500'" class="text-sm font-bold uppercase tracking-wide flex items-center gap-1">
               <span class="w-2 h-2 rounded-full bg-current"></span> {{ alat.status }}
             </span>
          </div>

          <h1 class="text-4xl md:text-5xl font-black italic uppercase leading-none mb-6">{{ alat.nama_alat }}</h1>

          <div class="bg-[#151515] p-6 rounded-2xl border border-gray-800 mb-8">
            <p class="text-gray-400 text-sm font-bold uppercase mb-1">Harga Sewa</p>
            <p class="text-3xl font-bold text-white">Rp {{ Number(alat.harga_sewa).toLocaleString() }} <span class="text-sm text-gray-500 font-normal">/ 24 jam</span></p>
          </div>

          <h3 class="text-lg font-bold uppercase tracking-wide mb-3 text-gray-300">Deskripsi Gear</h3>
          <p class="text-gray-500 leading-relaxed mb-8">{{ alat.deskripsi || 'Tidak ada deskripsi spesifik.' }}</p>

          <div class="bg-[#151515] p-8 rounded-3xl border border-gray-800">
            <h3 class="font-bold text-xl text-white mb-6 uppercase italic">Booking Sekarang</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label class="label-dark">Mulai</label>
                <input type="date" v-model="tanggalMulai" class="input-dark" />
              </div>
              <div>
                <label class="label-dark">Selesai</label>
                <input type="date" v-model="tanggalSelesai" class="input-dark" />
              </div>
            </div>

            <div class="mb-6">
              <label class="label-dark">Jumlah Unit</label>
              <input type="number" v-model="jumlah" min="1" class="input-dark" />
            </div>

            <div v-if="lamaSewa > 0" class="flex justify-between items-center bg-black/50 p-4 rounded-xl mb-6 border border-gray-800">
               <span class="text-gray-400 text-sm">{{ lamaSewa }} Hari x {{ jumlah }} Unit</span>
               <span class="text-xl font-bold text-rose-500">Total: Rp {{ totalBiaya.toLocaleString() }}</span>
            </div>

            <button @click="tambahKeranjang" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-black py-4 rounded-xl uppercase tracking-widest shadow-[0_0_20px_rgba(225,29,72,0.4)] transition hover:scale-[1.02]">
              <i class="fas fa-plus mr-2"></i> Masukkan Keranjang
            </button>
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
import Navbar from "@/components/Navbar.vue"

const route = useRoute()
const alat = ref(null)
const tanggalMulai = ref('')
const tanggalSelesai = ref('')
const jumlah = ref(1)

// Logic fetch data SAMA
const getAlat = async () => {
  try {
    const res = await axios.get(`/api/alat-band/${route.params.id}`)
    alat.value = res.data
  } catch (err) {}
}
onMounted(getAlat)

const lamaSewa = computed(() => {
  if (!tanggalMulai.value || !tanggalSelesai.value) return 0
  const start = new Date(tanggalMulai.value)
  const end = new Date(tanggalSelesai.value)
  const diff = (end - start) / (1000 * 60 * 60 * 24)
  return diff > 0 ? diff : 0
})

const totalBiaya = computed(() => alat.value ? lamaSewa.value * alat.value.harga_sewa * jumlah.value : 0)

const tambahKeranjang = () => {
  // Logic SAMA, copy paste dari file lamamu agar logic tidak rusak
  if (!tanggalMulai.value || !tanggalSelesai.value) { alert("Pilih tanggal dulu bro!"); return; }
  const cart = JSON.parse(localStorage.getItem('cart') || '[]')
  cart.push({
      id: alat.value.id,
      nama_alat: alat.value.nama_alat,
      gambar: alat.value.gambar,
      harga_sewa: alat.value.harga_sewa,
      jumlah: jumlah.value,
      tanggalMulai: tanggalMulai.value,
      tanggalSelesai: tanggalSelesai.value,
  })
  localStorage.setItem('cart', JSON.stringify(cart))
  window.dispatchEvent(new Event('cart-updated'))
  alert("Sip! Masuk keranjang.")
}
</script>

<style scoped>
.label-dark { @apply block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2; }
.input-dark { @apply w-full bg-[#0a0a0a] border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition color-scheme-dark; }
</style>