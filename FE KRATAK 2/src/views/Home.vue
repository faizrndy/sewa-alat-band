<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-b from-slate-50 to-white text-slate-900">

      <!-- ================= NAVBAR ================= -->
      <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
          <div class="flex items-center gap-2">
            <Music2 class="w-8 h-8 text-blue-600" />
            <span class="text-slate-900 font-semibold text-lg">Kratak FC</span>
          </div>
          <nav class="hidden md:flex items-center gap-8">
            <a href="#home" class="hover:text-blue-600">Home</a>
            <router-link to="/katalog" class="hover:text-blue-600">Katalog</router-link>
            <router-link to="/about" class="hover:text-blue-600">About</router-link>
            <a href="#faq" class="hover:text-blue-600">FAQ</a>
            <router-link to="/terms" class="hover:text-blue-600">Syarat & Ketentuan</router-link>
          </nav>
          <router-link to="/keranjang" class="relative text-2xl">
            🛒
            <span
              v-if="cartCount > 0"
              class="absolute -top-2 -right-3 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full"
            >
              {{ cartCount }}
            </span>
          </router-link>
        </div>
      </header>

      <!-- ================= HERO ================= -->
      <section id="home" class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800">
        <div class="text-center py-24 md:py-32 max-w-4xl mx-auto px-6">
          <Badge class="mb-6 bg-white/20 text-white border-white/30">✨ Sewa Alat Band Profesional</Badge>
          <h1 class="text-white text-4xl font-bold mb-4">Wujudkan Impian Bermusik Anda</h1>
          <p class="text-blue-100 mb-8">
            Sewa alat musik profesional untuk konser, latihan, dan acara spesial Anda.
          </p>
          <router-link to="/katalog">
            <Button size="lg" class="bg-blue-600 text-white hover:bg-blue-700 shadow-lg">
              <Search class="w-5 h-5 mr-2" /> Lihat Katalog
            </Button>
          </router-link>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-slate-50 to-transparent"></div>
      </section>

      <!-- ================= FITUR ================= -->
      <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8 text-center">
          <div v-for="(f, i) in features" :key="i" class="flex flex-col items-center">
            <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center mb-3">
              <component :is="f.icon" class="w-7 h-7 text-blue-600" />
            </div>
            <h3 class="font-semibold text-slate-900 mb-1">{{ f.title }}</h3>
            <p class="text-slate-600 text-sm max-w-[200px]">{{ f.desc }}</p>
          </div>
        </div>
      </section>

     <!-- ================= PILIHAN ALAT BAND KAMI ================= -->
<section id="produk" class="py-20 bg-slate-50 border-t border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <Badge class="mb-4 bg-blue-100 text-blue-700 hover:bg-blue-200">
        🎵 Pilihan Terbaik
      </Badge>
      <h2 class="text-3xl font-bold mb-3">Pilihan Alat Band Kami</h2>
      <p class="text-slate-600 max-w-2xl mx-auto">
        Pilih alat musik terbaik yang siap mendukung performa panggung dan latihan Anda.
      </p>
    </div>

    <!-- Grid Produk -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
      <Card
        v-for="(product, index) in randomProducts"
        :key="index"
        class="border border-slate-200 hover:shadow-lg transition rounded-xl overflow-hidden"
      >
        <div class="aspect-square relative">
          <img
            :src="`http://localhost:8000/storage/${product.gambar}`"
            :alt="product.nama_alat"
            class="w-full h-full object-cover"
          />
          <div v-if="product.status === 'tersedia'" class="absolute top-3 left-3">
            <Badge class="bg-green-500 text-white">Tersedia</Badge>
          </div>
          <div class="absolute top-3 right-3">
            <Badge class="bg-white/90 text-slate-800">{{ product.kategori }}</Badge>
          </div>
        </div>

        <div class="p-5">
          <h3 class="text-slate-900 font-semibold mb-2">{{ product.nama_alat }}</h3>
          <p class="text-blue-600 font-medium">{{ product.harga_sewa }}</p>
          <p class="text-xs text-slate-500 mb-4">/hari</p>
          <Button class="w-full bg-blue-600 hover:bg-blue-700 text-white">
            Detail <ArrowRight class="w-4 h-4 ml-1" />
          </Button>
        </div>
      </Card>
    </div>
  </div>
</section>


      <!-- ================= REVIEW PELANGGAN ================= -->
      <section id="review" class="py-20 bg-[#0f1b4c] text-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
          <h2 class="text-3xl font-bold mb-2">Review Pelanggan</h2>
          <p class="text-white/80 mb-12">
            Simak apa kata pelanggan yang telah menggunakan layanan Kratak FC 🎸
          </p>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 justify-items-center">
            <div
              v-for="(r, i) in reviews"
              :key="i"
              class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/10 hover:bg-white/20 transition w-full max-w-[320px]"
            >
              <div class="w-full rounded-xl overflow-hidden mb-4">
                <img
                  :src="r.avatar"
                  alt="Screenshot Testimoni"
                  class="w-full h-auto object-contain rounded-xl"
                />
              </div>
              <p class="font-semibold text-white text-lg">{{ r.name }}</p>
              <p class="text-sm text-white/70">{{ r.role }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- ================= FAQ ================= -->
<section id="faq" class="py-20 bg-white border-t border-slate-200">
  <div class="max-w-4xl mx-auto px-6">
    <h2 class="text-center text-3xl font-bold mb-4">FAQ</h2>
    <p class="text-center text-slate-600 mb-12">
      Pertanyaan yang sering diajukan pelanggan
    </p>

    <div v-if="faqs.length" class="space-y-4">
      <div
        v-for="(faq, index) in faqs"
        :key="index"
        class="border border-slate-200 rounded-lg overflow-hidden transition-all"
      >
        <button
          @click="toggleFAQ(index)"
          class="w-full flex justify-between items-center text-left px-5 py-4 bg-slate-50 hover:bg-slate-100 transition"
        >
          <span class="font-medium text-slate-800">{{ faq.question }}</span>
          <span>
            <svg
              v-if="activeFAQ === index"
              xmlns="http://www.w3.org/2000/svg"
              class="w-5 h-5 text-slate-700"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
            </svg>
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              class="w-5 h-5 text-slate-700"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
            </svg>
          </span>
        </button>

        <transition name="fade">
          <div
            v-if="activeFAQ === index"
            class="px-5 py-4 bg-white text-slate-600 border-t border-slate-100"
          >
            {{ faq.answer }}
          </div>
        </transition>
      </div>
    </div>

    <div v-else class="text-center text-slate-500">
      Memuat FAQ...
    </div>
  </div>
</section>

      <!-- ================= FOOTER ================= -->
      <footer class="bg-slate-900 text-white py-10 mt-auto">
        <div class="text-center text-slate-400 text-sm">
          © 2025 Kratak FC. All rights reserved.
        </div>
      </footer>

    </div>
  </template>


<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { Music2, Search, Star, Package, Check, ArrowRight } from 'lucide-vue-next'
import Button from '../components/ui/button.vue'
import Badge from '../components/ui/badge.vue'
import Card from '../components/ui/card.vue'

/* ================= FITUR ================= */
const features = [
  { icon: Check, title: 'Kualitas Terjamin', desc: 'Semua alat musik terawat dan siap digunakan.' },
  { icon: Star, title: 'Rating 4.9/5', desc: 'Dipercaya oleh ratusan musisi dan event organizer.' },
  { icon: Package, title: 'Pengiriman Cepat', desc: 'Tersedia layanan antar-jemput alat ke lokasi.' },
]

/* ================= PRODUK UNGGULAN ================= */
/* ================= PRODUK DARI API ================= */
const allProducts = ref([])
const randomProducts = ref([])

const getRandomItems = (arr, count) => {
  const shuffled = [...arr].sort(() => 0.5 - Math.random())
  return shuffled.slice(0, count)
}

onMounted(async () => {
  try {
    // Ambil data produk dari Laravel API
    const res = await axios.get('http://localhost:8000/api/alat-band')
    allProducts.value = res.data

    // Ambil 4 produk acak
    randomProducts.value = getRandomItems(allProducts.value, 4)
  } catch (error) {
    console.error('Gagal memuat produk:', error)
  }
})


/* ================= REVIEW PELANGGAN ================= */
const reviews = ref([])

onMounted(async () => {
  try {
    const res = await axios.get('http://localhost:8000/api/reviews')
    reviews.value = res.data.map(r => ({
      name: r.nama_pelanggan ?? 'Anonim',
      comment: 'Screenshot testimoni pelanggan 📸',
      avatar: `http://localhost:8000/${r.gambar}`,
      role: 'Pelanggan'
    }))
  } catch (error) {
    console.error('Gagal memuat review:', error)
  }
})

/* ================= FAQ (ambil dari API Laravel) ================= */
const faqs = ref([])
const activeFAQ = ref(null)
const toggleFAQ = (i) => {
  activeFAQ.value = activeFAQ.value === i ? null : i
}

onMounted(async () => {
  try {
    const res = await axios.get('http://localhost:8000/api/faqs')
    faqs.value = res.data
  } catch (error) {
    console.error('Gagal memuat FAQ:', error)
  }
})
</script>

