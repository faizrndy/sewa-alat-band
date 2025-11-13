<template>
    <div class="max-w-6xl mx-auto px-6 py-16">
      <!-- Tombol Kembali -->
      <router-link to="/katalog" class="text-blue-600 hover:underline mb-6 inline-block">
        ← Kembali ke Katalog
      </router-link>

      <div v-if="alat" class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white rounded-3xl shadow-xl p-8">
        <!-- Gambar -->
        <div class="flex justify-center">
          <img
            :src="`http://127.0.0.1:8000/${alat.gambar}`"
            :alt="alat.nama_alat"
            class="rounded-2xl shadow-lg max-h-96 object-contain"
          />
        </div>

        <!-- Detail -->
        <div>
          <h1 class="text-3xl font-bold text-slate-900 mb-3">{{ alat.nama_alat }}</h1>
          <p class="text-gray-600 mb-2"><strong>Kategori:</strong> {{ alat.kategori }}</p>
          <p class="text-gray-600 mb-2">
            <strong>Status:</strong>
            <span :class="alat.status === 'Tersedia' ? 'text-green-600' : 'text-red-500'">
              {{ alat.status }}
            </span>
          </p>
          <p class="text-blue-600 font-semibold text-lg mb-4">
            Rp {{ Number(alat.harga_sewa).toLocaleString() }} / hari
          </p>

          <!-- Deskripsi -->
          <div class="mb-8">
            <h3 class="font-semibold text-gray-800 mb-2">Deskripsi:</h3>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
              {{ alat.deskripsi || 'Belum ada deskripsi untuk alat ini.' }}
            </p>
          </div>

          <!-- Form Sewa -->
          <div class="border-t pt-6 mt-6">
            <h3 class="font-semibold text-gray-800 mb-4">Form Sewa</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-sm text-gray-600 mb-1">Tanggal Sewa</label>
                <input type="date" v-model="tanggalMulai" class="w-full border rounded-xl px-4 py-2" />
              </div>
              <div>
                <label class="block text-sm text-gray-600 mb-1">Tanggal Selesai</label>
                <input type="date" v-model="tanggalSelesai" class="w-full border rounded-xl px-4 py-2" />
              </div>
            </div>

            <div class="mb-4">
              <label class="block text-sm text-gray-600 mb-1">Jumlah Alat</label>
              <input
                type="number"
                v-model.number="jumlah"
                min="1"
                class="w-full border rounded-xl px-4 py-2"
              />
            </div>

            <!-- Kalkulasi Otomatis -->
            <div v-if="lamaSewa > 0" class="mt-4 p-4 bg-slate-50 rounded-xl border">
              <p class="text-gray-700">
                Lama sewa: <strong>{{ lamaSewa }}</strong> hari
              </p>
              <p class="text-gray-700">
                Total biaya: <strong>Rp {{ totalBiaya.toLocaleString() }}</strong>
              </p>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-wrap gap-4 mt-6">
              <button
                @click="sewaLangsung"
                class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition"
              >
                🟩 Sewa Sekarang
              </button>
              <button
                @click="tambahKeranjang"
                class="border border-slate-300 px-6 py-3 rounded-xl hover:bg-slate-100 transition"
              >
                🛒 Masukkan Keranjang
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-20 text-gray-500 text-lg">
        Memuat detail alat...
      </div>
    </div>
  </template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const alat = ref(null)
const tanggalMulai = ref('')
const tanggalSelesai = ref('')
const jumlah = ref(1)

/* ==========================================================
   🔹 FETCH DATA DETAIL PRODUK
   ========================================================== */
const getAlat = async () => {
  try {
    const res = await axios.get(`http://127.0.0.1:8000/api/alat-band/${route.params.id}`)
    alat.value = res.data
  } catch (err) {
    console.error('Gagal ambil detail alat:', err)
  }
}
onMounted(getAlat)

/* ==========================================================
   🔹 HITUNG LAMA SEWA & TOTAL BIAYA
   ========================================================== */
const lamaSewa = computed(() => {
  if (!tanggalMulai.value || !tanggalSelesai.value) return 0
  const start = new Date(tanggalMulai.value)
  const end = new Date(tanggalSelesai.value)
  const diff = (end - start) / (1000 * 60 * 60 * 24)
  return diff > 0 ? diff : 0
})

const totalBiaya = computed(() => {
  if (!alat.value) return 0
  return lamaSewa.value * alat.value.harga_sewa * jumlah.value
})

/* ==========================================================
   🔹 AKSI: SEWA LANGSUNG (DEMO)
   ========================================================== */
const sewaLangsung = () => {
  if (lamaSewa.value <= 0) {
    alert('⚠️ Silakan pilih tanggal sewa dan tanggal selesai dengan benar.')
    return
  }
  alert(
    `Sewa berhasil!\n\nAlat: ${alat.value.nama_alat}\nDurasi: ${lamaSewa.value} hari\nTotal: Rp ${totalBiaya.value.toLocaleString()}`
  )
}

/* ==========================================================
   🔹 AKSI: TAMBAH KE KERANJANG
   ========================================================== */
const tambahKeranjang = () => {
  if (!alat.value) return

  const cart = JSON.parse(localStorage.getItem('cart') || '[]')

  // Cek apakah item sudah ada di keranjang
  const existing = cart.find((item) => item.id === alat.value.id)

  if (existing) {
    existing.jumlah += jumlah.value
  } else {
    cart.push({
      id: alat.value.id,
      nama_alat: alat.value.nama_alat,
      gambar: alat.value.gambar,
      harga_sewa: alat.value.harga_sewa,
      jumlah: jumlah.value,
      tanggalMulai: tanggalMulai.value,
      tanggalSelesai: tanggalSelesai.value,
    })
  }

  // Simpan kembali ke localStorage
  localStorage.setItem('cart', JSON.stringify(cart))

  // 🔔 Event untuk update badge di icon keranjang (Langkah 3)
  window.dispatchEvent(new Event('cart-updated'))

  alert(`✅ ${alat.value.nama_alat} berhasil ditambahkan ke keranjang!`)
}
</script>
