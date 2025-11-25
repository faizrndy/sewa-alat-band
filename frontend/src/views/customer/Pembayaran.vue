<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white py-10 px-6 font-sans pt-28">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <div class="lg:col-span-3 mb-4 lg:mb-0">
         <h1 class="text-3xl font-black italic uppercase tracking-tighter">Checkout & <span class="text-rose-600">Payment</span></h1>
         <p class="text-gray-500 mt-2">Lengkapi data buat selesaikan bookingan lo.</p>
      </div>

      <div class="lg:col-span-2 space-y-6">
        <div class="bg-[#151515] p-8 rounded-3xl border border-gray-800">
          <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-3 uppercase tracking-wider">
            <span class="bg-rose-600 text-white w-8 h-8 flex items-center justify-center rounded-lg text-sm font-black">1</span>
            Data Penyewa
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
             <div>
               <label class="label-dark">Nama Lengkap</label>
               <input v-model="nama" class="input-dark" type="text" placeholder="Nama Panggung" />
             </div>
             <div>
               <label class="label-dark">WhatsApp</label>
               <input v-model="telepon" class="input-dark" type="text" placeholder="0812..." />
             </div>
             <div class="md:col-span-2">
               <label class="label-dark">Identitas (KTP/SIM)</label>
               <div class="border-2 border-dashed border-gray-700 rounded-xl p-6 text-center cursor-pointer hover:bg-gray-800 hover:border-rose-600 transition group">
                  <input type="file" @change="onIdentitas" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white hover:file:bg-rose-700"/>
                  <p class="text-xs text-gray-600 mt-2 group-hover:text-gray-400">Upload foto kartu identitas asli</p>
               </div>
             </div>
          </div>
        </div>

        <div class="bg-[#151515] p-8 rounded-3xl border border-gray-800">
          <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-3 uppercase tracking-wider">
            <span class="bg-rose-600 text-white w-8 h-8 flex items-center justify-center rounded-lg text-sm font-black">2</span>
            Pengiriman
          </h2>
          
          <div class="flex gap-4 mb-6">
            <label class="flex-1 cursor-pointer group">
              <input type="radio" value="ambil" v-model="metodePengiriman" @change="updateTarif" class="hidden peer">
              <div class="border border-gray-700 bg-black p-4 rounded-xl text-center peer-checked:border-rose-600 peer-checked:bg-rose-600/10 peer-checked:text-rose-500 transition group-hover:border-gray-500">
                <i class="fas fa-store mb-2 block text-xl"></i> Ambil Sendiri
              </div>
            </label>
            <label class="flex-1 cursor-pointer group">
              <input type="radio" value="antar" v-model="metodePengiriman" @change="updateTarif" class="hidden peer">
              <div class="border border-gray-700 bg-black p-4 rounded-xl text-center peer-checked:border-rose-600 peer-checked:bg-rose-600/10 peer-checked:text-rose-500 transition group-hover:border-gray-500">
                <i class="fas fa-truck mb-2 block text-xl"></i> Antar Kurir
              </div>
            </label>
          </div>

          <div v-show="metodePengiriman === 'antar'" class="space-y-4">
            <div id="map" class="w-full h-72 rounded-xl border border-gray-700 z-0 grayscale invert"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <div>
                  <label class="label-dark">Jarak</label>
                  <div class="input-dark bg-black text-gray-400">{{ jarak.toFixed(1) }} km</div>
               </div>
               <div>
                  <label class="label-dark">Ongkir</label>
                  <div class="input-dark bg-black text-rose-500 font-bold">Rp {{ tarifAntar.toLocaleString() }}</div>
               </div>
            </div>
            
            <textarea v-model="alamat" class="input-dark" rows="2" readonly></textarea>
            <textarea v-model="deskripsiLokasi" class="input-dark" rows="2" placeholder="Detail patokan rumah..."></textarea>
            <p v-if="jarak > 30" class="text-red-500 text-xs font-bold bg-red-900/20 p-3 rounded border border-red-900">⚠️ Kejauhan bos (>30km). Gak bisa antar.</p>
          </div>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 sticky top-28">
          <h2 class="text-xl font-black text-white mb-6 uppercase italic tracking-wider border-b border-gray-800 pb-4">Total Bayar</h2>
          
          <div class="space-y-3 text-gray-400 mb-6 border-b border-gray-800 pb-6">
             <div class="flex justify-between">
               <span>Sewa Alat</span>
               <span class="text-white font-bold">Rp {{ totalSewa.toLocaleString() }}</span>
             </div>
             <div class="flex justify-between" v-if="metodePengiriman === 'antar'">
               <span>Ongkir</span>
               <span class="text-white font-bold">Rp {{ tarifAntar.toLocaleString() }}</span>
             </div>
          </div>

          <div class="flex justify-between text-2xl font-black text-white mb-8">
            <span>Total</span>
            <span class="text-rose-500">Rp {{ totalBayar.toLocaleString() }}</span>
          </div>

          <button
             @click="kirimPembayaran"
             :disabled="loading"
             class="w-full bg-white text-black py-4 rounded-xl font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:shadow-[0_0_20px_rgba(225,29,72,0.5)] transition-all disabled:bg-gray-800 disabled:text-gray-600 cursor-pointer"
          >
             {{ loading ? "Memproses..." : "BAYAR SEKARANG" }}
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import L from "leaflet";
import Swal from "sweetalert2"; // Import SweetAlert

const nama = ref("");
const telepon = ref("");
const deskripsiLokasi = ref("");
const fileIdentitas = ref(null);
const onIdentitas = (e) => { fileIdentitas.value = e.target.files[0]; };

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
  const a = Math.sin(dLat / 2) ** 2 + Math.cos((lat1 * Math.PI) / 180) * Math.cos((lat2 * Math.PI) / 180) * Math.sin(dLon / 2) ** 2;
  return 2 * R * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

async function pilihLokasi(e) {
  lat.value = e.latlng.lat;
  lon.value = e.latlng.lng;
  marker.setLatLng(e.latlng);
  jarak.value = hitungJarak(tokoLat, tokoLon, lat.value, lon.value);
  updateTarif();
  try {
    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat.value}&lon=${lon.value}&format=json`);
    const data = await res.json();
    alamat.value = data.display_name || "Alamat tidak ditemukan";
  } catch(e) {}
}

onMounted(() => {
  if(document.getElementById('map')) {
      map = L.map("map").setView([tokoLat, tokoLon], 13);
      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);
      marker = L.marker([tokoLat, tokoLon]).addTo(map);
      map.on("click", pilihLokasi);
  }
});

const metodePengiriman = ref("ambil");
const tarifAntar = ref(0);
function updateTarif() {
  if (metodePengiriman.value !== "antar") { tarifAntar.value = 0; return; }
  if (jarak.value <= 10) { tarifAntar.value = 0; } 
  else {
    const lebih = jarak.value - 10;
    const blok = Math.ceil(lebih / 5);
    tarifAntar.value = blok * 10000;
  }
}

const itemsKeranjang = ref(JSON.parse(localStorage.getItem("cart") || "[]"));
const totalSewa = computed(() => {
  return itemsKeranjang.value.reduce((total, item) => {
    if (!item.tanggalMulai || !item.tanggalSelesai) return total;
    const start = new Date(item.tanggalMulai);
    const end = new Date(item.tanggalSelesai);
    const ms = end - start;
    if (isNaN(ms) || ms <= 0) return total;
    const days = ms / (1000 * 60 * 60 * 24);
    return total + days * item.harga_sewa * item.jumlah;
  }, 0);
});
const totalBayar = computed(() => totalSewa.value + (metodePengiriman.value === "antar" ? tarifAntar.value : 0));

const loading = ref(false);

const kirimPembayaran = async () => {
  // 1. Validasi Frontend
  if (!itemsKeranjang.value.length) {
    Swal.fire({ icon: 'warning', title: 'Keranjang Kosong', text: 'Pilih alat dulu ya!', background: '#151515', color: '#fff', confirmButtonColor: '#e11d48' });
    return;
  }
  if (!nama.value || !telepon.value) {
    Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Isi nama dan WhatsApp dulu.', background: '#151515', color: '#fff', confirmButtonColor: '#e11d48' });
    return;
  }
  if (!fileIdentitas.value) {
    Swal.fire({ icon: 'warning', title: 'Identitas Wajib', text: 'Upload foto KTP/SIM asli ya.', background: '#151515', color: '#fff', confirmButtonColor: '#e11d48' });
    return;
  }
  if (metodePengiriman.value === 'antar' && (!lat.value)) {
    Swal.fire({ icon: 'warning', title: 'Lokasi Belum Dipilih', text: 'Klik peta untuk atur titik antar.', background: '#151515', color: '#fff', confirmButtonColor: '#e11d48' });
    return;
  }

  loading.value = true;
  try {
    const form = new FormData();
    form.append("nama", nama.value);
    form.append("telepon", telepon.value);
    form.append("alamat", alamat.value || '-');
    form.append("deskripsi_lokasi", deskripsiLokasi.value || '-');
    form.append("lat", lat.value || 0);
    form.append("lon", lon.value || 0);
    form.append("jarak_km", jarak.value);
    form.append("metode_pengiriman", metodePengiriman.value);
    form.append("tarif_antar", tarifAntar.value);
    form.append("total_sewa", totalSewa.value);
    form.append("total_bayar", totalBayar.value);
    form.append("identitas", fileIdentitas.value);
    form.append("items", JSON.stringify(itemsKeranjang.value));

    const res = await axios.post("/api/transaksi", form, { 
        headers: { "Content-Type": "multipart/form-data" } 
    });
    
    // Midtrans Logic
    const snapToken = res.data.snap_token;
    if (window.snap && snapToken) {
      window.snap.pay(snapToken, {
        onSuccess: () => { 
            localStorage.removeItem("cart"); 
            Swal.fire({ icon: 'success', title: 'Pembayaran Berhasil!', background: '#151515', color: '#fff' }).then(() => {
                window.location.href = `/riwayat?telepon=${telepon.value}`; 
            });
        },
        onPending: () => { 
            Swal.fire({ icon: 'info', title: 'Menunggu Pembayaran', text: 'Selesaikan pembayaran lo ya.', background: '#151515', color: '#fff' }).then(() => {
                window.location.href = `/riwayat?telepon=${telepon.value}`; 
            });
        },
        onError: () => Swal.fire({ icon: 'error', title: 'Pembayaran Gagal', background: '#151515', color: '#fff' }),
        onClose: () => Swal.fire({ icon: 'question', title: 'Batal Bayar?', text: 'Lo bisa bayar nanti di menu Riwayat.', background: '#151515', color: '#fff' })
      });
    } else {
        // Fallback jika tanpa Midtrans
        localStorage.removeItem("cart");
        Swal.fire({ icon: 'success', title: 'Order Masuk!', text: 'Tunggu konfirmasi admin ya.', background: '#151515', color: '#fff' }).then(() => {
            window.location.href = `/riwayat?telepon=${telepon.value}`;
        });
    }
  } catch (err) {
    console.error(err);
    // TAMPILKAN PESAN ERROR ASLI DARI LARAVEL
    const errorMsg = err.response?.data?.message || err.message || "Terjadi kesalahan server.";
    Swal.fire({
        icon: 'error',
        title: 'Gagal Memproses',
        text: errorMsg, // <-- Ini akan memberitahu kita kenapa server error
        background: '#151515',
        color: '#fff',
        confirmButtonColor: '#e11d48'
    });
  }
  loading.value = false;
};
</script>

<style scoped>
.label-dark { @apply block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2; }
.input-dark { @apply w-full bg-black border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition placeholder-gray-700; }
</style>