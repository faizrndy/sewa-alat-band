<template>
    <div class="max-w-6xl mx-auto px-6 py-16">
      <!-- Header -->
      <h1 class="text-3xl font-bold text-slate-900 mb-8">🛒 Keranjang Sewa</h1>

      <!-- Kosong -->
      <div v-if="cart.length === 0" class="text-center text-gray-500 py-20">
        <p>Keranjang masih kosong 😢</p>
        <router-link
          to="/katalog"
          class="mt-4 inline-block text-blue-600 hover:underline"
        >
          ← Kembali ke Katalog
        </router-link>
      </div>

      <!-- Daftar Barang -->
      <div v-else class="space-y-6">
        <div
          v-for="(item, index) in cart"
          :key="item.id"
          class="flex flex-col md:flex-row items-center gap-6 bg-white border border-gray-200 rounded-2xl shadow-md p-6"
        >
          <!-- Gambar -->
          <img
            :src="`http://127.0.0.1:8000/${item.gambar}`"
            alt="alat"
            class="w-32 h-32 object-cover rounded-xl border"
          />

          <!-- Detail Barang -->
          <div class="flex-1">
            <h2 class="text-lg font-semibold text-slate-900">
              {{ item.nama_alat }}
            </h2>
            <p class="text-sm text-gray-500 mb-2">
              {{ item.tanggalMulai }} → {{ item.tanggalSelesai }}
            </p>

            <p class="text-blue-600 font-medium">
              Rp {{ Number(item.harga_sewa).toLocaleString() }} / hari
            </p>

            <p class="text-gray-700 mt-1">
              Lama sewa: <strong>{{ hitungHari(item) }}</strong> hari
            </p>

            <p class="text-gray-700">
              Jumlah: <strong>{{ item.jumlah }}</strong>
            </p>

            <p class="text-green-700 mt-2 font-semibold">
              Total: Rp {{ hitungSubtotal(item).toLocaleString() }}
            </p>
          </div>

          <!-- Aksi -->
          <div class="flex flex-col gap-2">
            <button
              @click="hapusItem(index)"
              class="text-red-500 hover:text-red-700 border border-red-300 px-3 py-1 rounded-lg text-sm"
            >
              🗑 Hapus
            </button>
          </div>
        </div>
      </div>

      <!-- Total dan Tombol Checkout -->
      <div v-if="cart.length > 0" class="mt-10 border-t pt-6">
        <div class="flex flex-col sm:flex-row justify-between items-center">
          <p class="text-xl font-semibold text-slate-800">
            Total Keseluruhan:
            <span class="text-blue-600">
              Rp {{ totalSemua.toLocaleString() }}
            </span>
          </p>

          <div class="mt-4 sm:mt-0 flex gap-4">
            <button
              @click="kosongkanKeranjang"
              class="border border-gray-300 px-5 py-2 rounded-lg hover:bg-gray-100 transition"
            >
              🧹 Kosongkan
            </button>
            <button
              @click="checkout"
              class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
            >
              💳 Checkout
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, computed, onMounted } from 'vue'

  const cart = ref([])

  // Ambil data dari localStorage saat halaman dibuka
  onMounted(() => {
    const storedCart = JSON.parse(localStorage.getItem('cart') || '[]')
    cart.value = storedCart
  })

  // Hitung lama hari sewa per-item
  const hitungHari = (item) => {
    if (!item.tanggalMulai || !item.tanggalSelesai) return 0
    const start = new Date(item.tanggalMulai)
    const end = new Date(item.tanggalSelesai)
    const diff = (end - start) / (1000 * 60 * 60 * 24)
    return diff > 0 ? diff : 0
  }

  // Hitung subtotal per-item
  const hitungSubtotal = (item) => {
    return hitungHari(item) * item.harga_sewa * item.jumlah
  }

  // Hitung total semua item
  const totalSemua = computed(() => {
    return cart.value.reduce((total, item) => total + hitungSubtotal(item), 0)
  })

  // Hapus item tunggal
  const hapusItem = (index) => {
    cart.value.splice(index, 1)
    localStorage.setItem('cart', JSON.stringify(cart.value))
    window.dispatchEvent(new Event('cart-updated'))
  }

  // Kosongkan keranjang
  const kosongkanKeranjang = () => {
    if (confirm('Yakin ingin mengosongkan keranjang?')) {
      cart.value = []
      localStorage.removeItem('cart')
      window.dispatchEvent(new Event('cart-updated'))
    }
  }

  // Checkout dummy
  const checkout = () => {
    alert(`💳 Checkout berhasil!\nTotal: Rp ${totalSemua.value.toLocaleString()}`)
    cart.value = []
    localStorage.removeItem('cart')
    window.dispatchEvent(new Event('cart-updated'))
  }
  </script>

  <style scoped>
  /* (opsional, kalau mau styling tambahan) */
  </style>
