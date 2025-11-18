<template>
    <div class="max-w-6xl mx-auto px-6 py-16">

      <!-- Tombol Kembali -->
      <router-link to="/katalog" class="text-blue-600 hover:underline mb-6 inline-block">
        ← Kembali ke Katalog
      </router-link>

      <!-- KONTEN -->
      <div v-if="alat" class="grid grid-cols-1 md:grid-cols-2 gap-12 bg-white rounded-3xl shadow-xl p-10">

        <!-- FOTO PRODUK -->
        <div class="flex justify-center items-center">
          <div class="bg-gray-50 border rounded-2xl p-6 shadow-inner w-full h-[400px] flex justify-center items-center">
            <img
              :src="alat.gambar"
              :alt="alat.nama_alat"
              class="max-h-full object-contain"
            />
          </div>
        </div>

        <!-- DETAIL PRODUK -->
        <div>
          <h1 class="text-3xl font-bold text-slate-900 mb-4">{{ alat.nama_alat }}</h1>

          <div class="space-y-1 text-gray-700">
            <p><strong>Kategori:</strong> {{ alat.kategori }}</p>

            <p>
              <strong>Status:</strong>
              <span :class="alat.status === 'Tersedia' ? 'text-green-600' : 'text-red-500'">
                {{ alat.status }}
              </span>
            </p>

            <p class="text-blue-600 font-semibold text-xl pt-2">
              Rp {{ Number(alat.harga_sewa).toLocaleString() }} / hari
            </p>
          </div>

          <!-- DESKRIPSI -->
          <div class="mt-6">
            <h3 class="font-semibold text-gray-800 mb-2">Deskripsi:</h3>

            <p class="text-gray-700 leading-relaxed whitespace-pre-line break-words max-w-xl">
              {{ alat.deskripsi || 'Belum ada deskripsi untuk alat ini.' }}
            </p>
          </div>

          <!-- FORM SEWA -->
          <div class="border-t mt-8 pt-8">

            <h3 class="font-semibold text-gray-800 mb-4">Form Sewa</h3>

            <!-- Tanggal -->
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

            <!-- Jumlah -->
            <div class="mb-6">
              <label class="block text-sm text-gray-600 mb-1">Jumlah Alat</label>
              <input
                type="number"
                v-model.number="jumlah"
                min="1"
                class="w-full border rounded-xl px-4 py-2"
              />
            </div>

            <!-- Kalkulasi -->
            <div v-if="lamaSewa > 0" class="mb-6 p-4 bg-slate-50 rounded-xl border">
              <p class="text-gray-700">Durasi: <strong>{{ lamaSewa }}</strong> hari</p>
              <p class="text-gray-700">
                Total Harga:
                <strong>Rp {{ totalBiaya.toLocaleString() }}</strong>
              </p>
            </div>

            <!-- Tombol -->
            <div class="flex flex-wrap gap-4">
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

      <!-- LOADING -->
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

  /* --- FETCH DETAIL PRODUK --- */
  const getAlat = async () => {
    try {
      const res = await axios.get(`http://127.0.0.1:8000/api/alat-band/${route.params.id}`)
      alat.value = res.data
    } catch (err) {
      console.error('Gagal ambil detail alat:', err)
    }
  }
  onMounted(getAlat)

  /* --- HITUNG LAMA SEWA --- */
  const lamaSewa = computed(() => {
    if (!tanggalMulai.value || !tanggalSelesai.value) return 0
    const start = new Date(tanggalMulai.value)
    const end = new Date(tanggalSelesai.value)
    const diff = (end - start) / (1000 * 60 * 60 * 24)
    return diff > 0 ? diff : 0
  })

  /* --- TOTAL BIAYA --- */
  const totalBiaya = computed(() => {
    if (!alat.value) return 0
    return lamaSewa.value * alat.value.harga_sewa * jumlah.value
  })

  /* --- AKSI SEWA --- */
  const sewaLangsung = () => {
    if (lamaSewa.value <= 0) {
      alert('⚠️ Silakan pilih tanggal sewa dan tanggal selesai dengan benar.')
      return
    }

    alert(
      `Sewa berhasil!\n\nAlat: ${alat.value.nama_alat}\nDurasi: ${lamaSewa.value} hari\nTotal: Rp ${totalBiaya.value.toLocaleString()}`
    )
  }

  /* --- TAMBAH KE KERANJANG (FIX) --- */
  const tambahKeranjang = () => {

    // VALIDASI WAJIB
    if (!tanggalMulai.value || !tanggalSelesai.value) {
      alert("⚠️ Harap pilih tanggal mulai & tanggal selesai.");
      return;
    }

    if (lamaSewa.value <= 0) {
      alert("⚠️ Tanggal selesai harus lebih besar dari tanggal mulai.");
      return;
    }

    if (!alat.value) return;

    const cart = JSON.parse(localStorage.getItem('cart') || '[]')

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

    localStorage.setItem('cart', JSON.stringify(cart))
    window.dispatchEvent(new Event('cart-updated'))

    alert(`✅ ${alat.value.nama_alat} berhasil ditambahkan ke keranjang!`)
  }
  </script>
