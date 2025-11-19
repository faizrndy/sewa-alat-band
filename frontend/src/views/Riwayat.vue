<template>
    <div class="max-w-4xl mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6">📜 Riwayat Pemesanan</h1>

      <!-- Input Nomor Telepon -->
      <div class="flex gap-3 mb-8">
        <input
          v-model="telepon"
          type="text"
          class="input flex-1"
          placeholder="Masukkan nomor telepon yang dipakai saat pemesanan"
        />
        <button
          @click="getRiwayat"
          class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700"
        >
          Cari
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-gray-500 text-center py-10">
        Memuat data...
      </div>

      <!-- Jika Tidak Ada Data -->
      <div
        v-if="!loading && riwayat.length === 0 && sudahCari"
        class="text-center text-gray-500 py-10"
      >
        Tidak ada riwayat ditemukan untuk nomor tersebut.
      </div>

      <!-- LIST RIWAYAT -->
      <div
        v-for="trx in riwayat"
        :key="trx.id"
        class="bg-white shadow p-5 rounded-2xl mb-6"
      >
        <div class="flex justify-between items-center mb-3">
          <h2 class="text-xl font-semibold">{{ trx.kode_transaksi }}</h2>

          <span
            :class="{
              'text-yellow-600 bg-yellow-100 px-3 py-1 rounded-xl': trx.status === 'pending',
              'text-green-700 bg-green-100 px-3 py-1 rounded-xl': trx.status === 'success',
              'text-red-700 bg-red-100 px-3 py-1 rounded-xl': trx.status === 'failed'
            }"
          >
            {{ trx.status.toUpperCase() }}
          </span>
        </div>

        <p class="text-gray-700 text-sm">
          Tanggal: {{ new Date(trx.created_at).toLocaleString() }}
        </p>

        <p class="text-gray-700 text-sm mt-1">
          Total Bayar:
          <strong class="text-green-600">Rp {{ trx.total_bayar.toLocaleString() }}</strong>
        </p>

        <!-- DETAIL ITEM -->
        <div class="mt-4 border-t pt-4">
          <h3 class="font-semibold mb-2">Detail Alat:</h3>

          <div
            v-for="item in trx.items"
            :key="item.id"
            class="mb-3 p-3 bg-slate-50 rounded-xl"
          >
            <p class="font-medium">{{ item.nama_alat }}</p>
            <p class="text-sm text-gray-600">
              {{ item.tanggal_mulai }} → {{ item.tanggal_selesai }}
            </p>
            <p class="text-sm text-gray-600">
              {{ item.jumlah }} alat × Rp {{ item.harga_sewa.toLocaleString() }}
            </p>
            <p class="text-sm font-semibold">
              Subtotal: Rp {{ item.subtotal.toLocaleString() }}
            </p>
          </div>
        </div>

        <!-- LOKASI -->
        <div class="mt-4 border-t pt-4">
          <p class="text-sm text-gray-700">
            📍 <strong>Alamat:</strong> {{ trx.alamat }}
          </p>
          <p class="text-sm text-gray-700">
            🚚 <strong>Metode Pengiriman:</strong> {{ trx.metode_pengiriman }}
          </p>
        </div>

        <!-- TOMBOL WHATSAPP -->
        <button
          @click="kirimWA(trx)"
          class="mt-5 w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700"
        >
          Kirim ke WhatsApp Admin
        </button>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from "vue";
  import axios from "axios";

  const telepon = ref("");
  const riwayat = ref([]);
  const loading = ref(false);
  const sudahCari = ref(false);

  // Nomor WA Admin (Ganti dengan punya kamu)
  const adminWa = "6285934416582"; // contoh: 628xxxxxx

  // Ambil nomor telepon dari URL (?telepon=)
  onMounted(() => {
    const params = new URLSearchParams(window.location.search);
    const phone = params.get("telepon");
    if (phone) {
      telepon.value = phone;
      getRiwayat();
    }
  });

  const getRiwayat = async () => {
    if (!telepon.value) {
      alert("Masukkan nomor telepon dulu.");
      return;
    }

    loading.value = true;
    sudahCari.value = true;

    try {
      const res = await axios.get(
        `http://127.0.0.1:8000/api/riwayat/${telepon.value}`
      );

      riwayat.value = res.data.data;
    } catch (err) {
      console.error(err);
      alert("Gagal mengambil riwayat.");
    }

    loading.value = false;
  };

  // ======================
  //  KIRIM KE WHATSAPP
  // ======================
  const kirimWA = (trx) => {
    const pesan = `
  Halo Admin, saya ingin menyewa alat:

  Kode Transaksi: ${trx.kode_transaksi}
  Nama: ${trx.nama}
  Telepon: ${trx.telepon}
  Alamat: ${trx.alamat}
  Metode Pengiriman: ${trx.metode_pengiriman}

  Total Pembayaran: Rp ${trx.total_bayar.toLocaleString()}
  Status: ${trx.status.toUpperCase()}

  Detail Alat:
  ${trx.items
    .map(
      (i) =>
        `- ${i.nama_alat} (${i.tanggal_mulai} → ${i.tanggal_selesai}) x ${
          i.jumlah
        } → Rp ${i.subtotal.toLocaleString()}`
    )
    .join("\n")}
  `;

    const url = `https://wa.me/${adminWa}?text=${encodeURIComponent(pesan)}`;
    window.open(url, "_blank");
  };
  </script>

  <style scoped>
  .input {
    @apply border px-4 py-2 rounded-xl w-full;
  }
  </style>
