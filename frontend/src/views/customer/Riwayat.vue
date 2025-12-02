<template>
    <div class="min-h-screen bg-[#0a0a0a] text-white py-10 px-6 font-sans pt-28">
      <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-black italic uppercase tracking-tighter mb-8 text-center">
          📜 Riwayat <span class="text-rose-600">Sewa</span>
        </h1>

        <div class="bg-[#151515] p-4 rounded-2xl border border-gray-800 flex gap-2 mb-8">
          <input
            v-model="telepon"
            type="text"
            placeholder="Masukkan No. WA..."
            class="flex-1 bg-transparent border-none outline-none px-4 py-2 text-white placeholder-gray-600 focus:ring-0"
            @keyup.enter="getRiwayat"
          />

          <button
            @click="getRiwayat"
            class="bg-rose-600 text-white px-6 py-2 rounded-xl hover:bg-rose-700 transition font-bold uppercase text-sm tracking-wider"
          >
            Cari
          </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="text-center py-10">
          <div class="animate-spin h-8 w-8 border-2 border-rose-600 rounded-full border-t-transparent mx-auto"></div>
        </div>

        <!-- Tidak ditemukan -->
        <div
          v-if="!loading && riwayat.length === 0 && sudahCari"
          class="text-center text-gray-500 py-10 bg-[#151515] rounded-2xl border border-gray-800 border-dashed"
        >
          Belum ada riwayat ditemukan.
        </div>

        <!-- List Riwayat -->
        <div
          v-for="trx in riwayat"
          :key="trx.id"
          class="bg-[#151515] rounded-3xl border border-gray-800 overflow-hidden mb-8 hover:border-gray-600 transition"
        >
          <div class="bg-black/30 px-6 py-4 flex justify-between items-center border-b border-gray-800">
            <div>
              <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Kode Booking</p>
              <p class="text-lg font-mono font-bold text-white tracking-wider">#{{ trx.kode_transaksi }}</p>
            </div>

            <span
              :class="{
                'bg-yellow-500/10 text-yellow-500 border-yellow-500/20': trx.status === 'pending',
                'bg-green-500/10 text-green-500 border-green-500/20': trx.status === 'success',
                'bg-red-500/10 text-red-500 border-red-500/20': trx.status === 'failed'
              }"
              class="px-4 py-1 border rounded-full text-[10px] font-black uppercase tracking-widest"
            >
              {{ trx.status }}
            </span>
          </div>

          <div class="p-6">
            <div class="flex flex-col md:flex-row justify-between mb-6">
              <div>
                <p class="text-xs text-gray-500 uppercase mb-1">Tanggal Order</p>
                <p class="font-medium text-gray-300">
                  {{ new Date(trx.created_at).toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                  }) }}
                </p>
              </div>

              <div class="mt-2 md:mt-0 md:text-right">
                <p class="text-xs text-gray-500 uppercase mb-1">Total Pembayaran</p>
                <p class="text-xl font-black text-rose-500">Rp {{ trx.total_bayar.toLocaleString() }}</p>
              </div>
            </div>

            <div class="bg-black/50 rounded-xl p-4 space-y-3 mb-6 border border-gray-800">
              <div
                v-for="item in trx.items"
                :key="item.id"
                class="flex justify-between items-center border-b border-gray-800 last:border-0 pb-2 last:pb-0"
              >
                <div>
                  <p class="font-bold text-gray-300">
                    {{ item.nama_alat }}
                    <span class="text-xs text-gray-500">x{{ item.jumlah }}</span>
                  </p>
                  <p class="text-[10px] text-gray-600 font-mono">
                    {{ item.tanggal_mulai }} s/d {{ item.tanggal_selesai }}
                  </p>
                </div>
                <p class="text-sm font-bold text-gray-400">
                  Rp {{ item.subtotal.toLocaleString() }}
                </p>
              </div>
            </div>

            <button
              @click="kirimWA(trx)"
              class="w-full border border-green-800 bg-green-900/10 text-green-500 hover:bg-green-900/20 py-3 rounded-xl font-bold transition flex justify-center items-center gap-2 uppercase text-sm tracking-wider"
            >
              <i class="fab fa-whatsapp"></i> Konfirmasi Admin
            </button>
          </div>
        </div>
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

  const adminWa = "6285934416582";

  onMounted(() => {
    const params = new URLSearchParams(window.location.search);
    const phone = params.get("telepon");
    if (phone) {
      telepon.value = phone;
      getRiwayat();
    }
  });

  const getRiwayat = async () => {
    if (!telepon.value) return alert("Isi nomor WA dulu");

    loading.value = true;
    sudahCari.value = true;

    try {
      // Ambil token buyer yang disimpan saat login
      const token = localStorage.getItem("buyer_token");

      if (!token) {
        alert("Anda harus login untuk melihat riwayat!");
        loading.value = false;
        return;
      }

      const res = await axios.get(
        `http://127.0.0.1:8000/api/riwayat/${telepon.value}`,
        {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: "application/json",
          }
        }
      );

      riwayat.value = res.data.data;

    } catch (err) {
      console.log("Error Riwayat:", err);

      if (err.response?.status === 401) {
        alert("Sesi habis, silakan login kembali.");
      }
    }

    loading.value = false;
  };

  const kirimWA = (trx) => {
    const pesan = `Halo Admin, konfirmasi order #${trx.kode_transaksi} atas nama ${trx.nama}. Status: ${trx.status}`;
    window.open(`https://wa.me/${adminWa}?text=${encodeURIComponent(pesan)}`, "_blank");
  };
  </script>
