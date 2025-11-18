<template>
    <div class="max-w-5xl mx-auto p-6 space-y-8">

      <h1 class="text-3xl font-bold">💳 Pembayaran & Data Penyewa</h1>

      <!-- DATA DIRI -->
      <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-4">🧍 Data Diri Penyewa</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="font-medium">Nama Lengkap</label>
            <input v-model="nama" class="input" type="text" placeholder="Nama kamu" />
          </div>

          <div>
            <label class="font-medium">Nomor Telepon (WA)</label>
            <input v-model="telepon" class="input" type="text" placeholder="08xxxxxxxx" />
          </div>
        </div>

        <div class="mt-4">
          <label class="font-medium">📎 Upload Identitas (KTP/KTM/SIM)</label>
          <input type="file" class="input mt-2" @change="onIdentitas" />
        </div>
      </div>

      <!-- MAP -->
      <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-4">📍 Lokasi Pengiriman</h2>

        <p class="text-sm text-gray-500">Klik pada map untuk memilih lokasi rumah.</p>

        <div id="map" class="w-full h-80 rounded-xl my-4"></div>

        <label class="font-medium">Alamat (otomatis)</label>
        <textarea class="input" rows="3" v-model="alamat" readonly></textarea>

        <label class="font-medium mt-4 block">Deskripsi Rumah</label>
        <textarea
          class="input"
          rows="3"
          v-model="deskripsiLokasi"
          placeholder="Rumah cat biru, pagar putih, dekat gang Melati"
        ></textarea>

        <p class="font-semibold text-gray-700 mt-4">
          📏 Jarak ke toko:
          <span class="text-blue-600">{{ jarak.toFixed(1) }} km</span>
        </p>

        <p v-if="jarak > 30" class="text-red-500 font-semibold">
          ❌ Di luar radius 30 km — tidak bisa menyewa.
        </p>
      </div>

      <!-- PENGIRIMAN -->
      <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-4">🚚 Metode Pengiriman</h2>

        <select class="input mb-4" v-model="metodePengiriman" @change="updateTarif">
          <option value="ambil">Ambil di Toko</option>
          <option value="antar">Antar ke Lokasi</option>
        </select>

        <p v-if="metodePengiriman === 'antar'">
          Tarif Antar:
          <span class="text-blue-600 font-bold">
            Rp {{ tarifAntar.toLocaleString() }}
          </span>
        </p>
      </div>

      <!-- RINGKASAN -->
      <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-4">🧾 Ringkasan Pembayaran</h2>

        <p>Total Sewa: <strong>Rp {{ totalSewa.toLocaleString() }}</strong></p>

        <p v-if="metodePengiriman === 'antar'">
          Tarif Antar: <strong>Rp {{ tarifAntar.toLocaleString() }}</strong>
        </p>

        <p class="text-xl mt-3">
          Total Bayar:
          <strong class="text-green-600">Rp {{ totalBayar.toLocaleString() }}</strong>
        </p>
      </div>

      <!-- BUTTON -->
      <button
        @click="kirimPembayaran"
        :disabled="loading"
        class="w-full bg-blue-600 text-white py-3 rounded-xl text-lg font-semibold hover:bg-blue-700 disabled:opacity-50"
      >
        {{ loading ? "Mengirim..." : "Bayar Sekarang" }}
      </button>
    </div>
  </template>

  <script setup>
  import { ref, onMounted, computed } from "vue";
  import axios from "axios";
  import L from "leaflet";

  /* ---------------- DATA DIRI ---------------- */
  const nama = ref("");
  const telepon = ref("");
  const deskripsiLokasi = ref("");

  /* ---------------- FILE ---------------- */
  const fileIdentitas = ref(null);
  const onIdentitas = (e) => {
    fileIdentitas.value = e.target.files[0];
  };

  /* ---------------- MAP ---------------- */
  const alamat = ref("");
  const lat = ref(null);
  const lon = ref(null);
  const jarak = ref(0);

  let map;
  let marker;

  const tokoLat = -7.568;
  const tokoLon = 110.829;

  function hitungJarak(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = ((lat2 - lat1) * Math.PI) / 180;
    const dLon = ((lon2 - lon1) * Math.PI) / 180;

    const a =
      Math.sin(dLat / 2) ** 2 +
      Math.cos((lat1 * Math.PI) / 180) *
        Math.cos((lat2 * Math.PI) / 180) *
        Math.sin(dLon / 2) ** 2;

    return 2 * R * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  }

  async function pilihLokasi(e) {
    lat.value = e.latlng.lat;
    lon.value = e.latlng.lng;
    marker.setLatLng(e.latlng);

    jarak.value = hitungJarak(tokoLat, tokoLon, lat.value, lon.value);
    updateTarif();

    const res = await fetch(
      `https://nominatim.openstreetmap.org/reverse?lat=${lat.value}&lon=${lon.value}&format=json`
    );
    const data = await res.json();
    alamat.value = data.display_name || "Alamat tidak ditemukan";
  }

  onMounted(() => {
    map = L.map("map").setView([tokoLat, tokoLon], 14);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);
    marker = L.marker([tokoLat, tokoLon]).addTo(map);
    map.on("click", pilihLokasi);
  });

  /* ---------------- PENGIRIMAN ---------------- */
  const metodePengiriman = ref("ambil");
  const tarifAntar = ref(0);

  function updateTarif() {
    if (metodePengiriman.value !== "antar") {
      tarifAntar.value = 0;
      return;
    }

    if (jarak.value <= 10) {
      tarifAntar.value = 0;
    } else {
      const lebih = jarak.value - 10;
      const blok = Math.ceil(lebih / 5);
      tarifAntar.value = blok * 10000;
    }
  }

  /* ---------------- KERANJANG ---------------- */
  const itemsKeranjang = ref(JSON.parse(localStorage.getItem("cart") || "[]"));

  const totalSewa = computed(() => {
    return itemsKeranjang.value.reduce((total, item) => {
      // kalau tanggal kosong, jangan ikut dihitung
      if (!item.tanggalMulai || !item.tanggalSelesai) return total;

      const start = new Date(item.tanggalMulai);
      const end = new Date(item.tanggalSelesai);
      const ms = end - start;
      if (isNaN(ms) || ms <= 0) return total;

      const days = ms / (1000 * 60 * 60 * 24);
      return total + days * item.harga_sewa * item.jumlah;
    }, 0);
  });

  const totalBayar = computed(() => {
    return (
      totalSewa.value +
      (metodePengiriman.value === "antar" ? tarifAntar.value : 0)
    );
  });

  /* ---------------- SEND KE BACKEND + SNAP ---------------- */
  const loading = ref(false);

  const kirimPembayaran = async () => {
  // CEK KERANJANG
  if (!itemsKeranjang.value.length) {
    alert("Keranjang masih kosong.");
    return;
  }

  // CEK DATA DIRI
  if (!nama.value || !telepon.value) {
    alert("Nama dan nomor telepon wajib diisi.");
    return;
  }

  // CEK FILE IDENTITAS
  if (!fileIdentitas.value) {
    alert("Silakan upload foto identitas.");
    return;
  }

  // CEK MAP
  if (!lat.value || !lon.value) {
    alert("Silakan klik lokasi rumah pada map.");
    return;
  }

  // CEK RADIUS
  if (jarak.value > 30) {
    alert("Radius melebihi 30 km, tidak bisa menyewa.");
    return;
  }

  // CEK TANGGAL ITEM
  for (const item of itemsKeranjang.value) {
    if (!item.tanggalMulai || !item.tanggalSelesai) {
      alert(`Tanggal sewa untuk alat "${item.nama_alat}" belum diisi.`);
      return;
    }
  }

  loading.value = true;

  try {
    const form = new FormData();

    form.append("nama", nama.value);
    form.append("telepon", telepon.value);
    form.append("alamat", alamat.value);
    form.append("deskripsi_lokasi", deskripsiLokasi.value);

    form.append("lat", lat.value);
    form.append("lon", lon.value);
    form.append("jarak_km", jarak.value);

    form.append("metode_pengiriman", metodePengiriman.value);
    form.append("tarif_antar", tarifAntar.value);

    form.append("total_sewa", totalSewa.value);
    form.append("total_bayar", totalBayar.value);

    form.append("identitas", fileIdentitas.value);

    form.append("items", JSON.stringify(itemsKeranjang.value));

    const res = await axios.post(
      "http://127.0.0.1:8000/api/transaksi",
      form,
      {
        headers: { "Content-Type": "multipart/form-data" },
      }
    );

    const snapToken = res.data.snap_token;
    const kodeTransaksi = res.data.kode_transaksi;

    // --- JIKA SNAP ADA (online) ---
    if (window.snap && snapToken) {
      window.snap.pay(snapToken, {
        onSuccess: (result) => {
          alert("Pembayaran berhasil!");

          // kosongkan keranjang
          localStorage.removeItem("cart");

          // redirect ke riwayat
          window.location.href = `/riwayat?telepon=${telepon.value}`;
        },
        onPending: () => {
          alert("Menunggu pembayaran...");

          // tetap masuk riwayat karena statusnya pending
          window.location.href = `/riwayat?telepon=${telepon.value}`;
        },
        onError: () => {
          alert("Pembayaran gagal.");
        },
        onClose: () => {
          alert("Pembayaran ditutup.");
        },
      });

      return;
    }

    // --- TANPA SNAP (fallback) ---
    alert("Transaksi berhasil dibuat!");

    localStorage.removeItem("cart");
    window.location.href = `/riwayat?telepon=${telepon.value}`;

  } catch (err) {
    console.error(err);
    alert("Gagal mengirim transaksi.");
  }

  loading.value = false;
};

  </script>

  <style scoped>
  .input {
    @apply w-full border px-4 py-2 rounded-xl;
  }
  </style>
