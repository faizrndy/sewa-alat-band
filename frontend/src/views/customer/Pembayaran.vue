<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white py-10 px-6 font-sans pt-28">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <div class="lg:col-span-3 mb-2">
         <h1 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter">
           Checkout <span class="text-rose-600">&</span> Payment
         </h1>
         <p class="text-gray-500 mt-2 text-sm md:text-base">Mode Sandbox (Test) Aktif.</p>
      </div>

      <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-[#151515] p-6 md:p-8 rounded-3xl border border-gray-800">
          <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-3 uppercase tracking-wider">
            <span class="bg-rose-600 text-white w-8 h-8 flex items-center justify-center rounded-lg text-sm font-black">1</span>
            Review Gear
          </h2>
          <div class="space-y-4">
            <div v-for="item in itemsKeranjang" :key="item.id" class="flex gap-4 bg-black/40 p-4 rounded-2xl border border-gray-800/50">
              <div class="w-20 h-20 md:w-24 md:h-24 flex-shrink-0 bg-white rounded-xl overflow-hidden">
                <img 
                  :src="getImgUrl(item.gambar)" 
                  @error="$event.target.src = 'https://placehold.co/150x150/1a1a1a/FFF?text=No+Image'"
                  class="w-full h-full object-contain p-1"
                />
              </div>
              <div class="flex-1 flex flex-col justify-center">
                <h3 class="font-black text-white text-base md:text-lg uppercase italic leading-tight">{{ item.nama_alat }}</h3>
                <div class="text-xs text-gray-500 mt-1 font-mono">
                  {{ item.tanggalMulai }} <span class="text-rose-500">➜</span> {{ item.tanggalSelesai }}
                </div>
                <div class="mt-2 flex items-center justify-between">
                  <p class="text-xs text-gray-400">
                    Rp {{ Number(item.harga_sewa).toLocaleString() }} x {{ item.jumlah }} Unit
                  </p>
                  <p class="font-bold text-white">
                    Total: Rp {{ (hitungHari(item) * item.harga_sewa * item.jumlah).toLocaleString() }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-[#151515] p-6 md:p-8 rounded-3xl border border-gray-800">
          <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-3 uppercase tracking-wider">
            <span class="bg-rose-600 text-white w-8 h-8 flex items-center justify-center rounded-lg text-sm font-black">2</span>
            Data Penyewa
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
             <div>
               <label class="label-dark">Nama Lengkap</label>
               <input v-model="nama" class="input-dark" type="text" placeholder="Nama Panggung / Asli" />
             </div>
             <div>
               <label class="label-dark">WhatsApp</label>
               <input v-model="telepon" class="input-dark" type="text" placeholder="0812..." />
             </div>
             <div class="md:col-span-2">
               <label class="label-dark">Identitas (KTP/SIM)</label>
               <div class="relative border-2 border-dashed border-gray-700 bg-black/30 rounded-xl p-6 text-center cursor-pointer hover:bg-gray-800 hover:border-rose-600 transition group">
                  <input type="file" @change="onIdentitas" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"/>
                  <div v-if="!fileIdentitas" class="flex flex-col items-center text-gray-500 group-hover:text-rose-500 transition">
                    <i class="fas fa-id-card text-3xl mb-2"></i>
                    <p class="text-sm font-bold">Klik untuk upload KTP/SIM</p>
                  </div>
                  <div v-else class="flex items-center justify-center gap-3 text-green-500">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <p class="text-sm font-bold text-white">{{ fileIdentitas.name }}</p>
                  </div>
               </div>
             </div>
          </div>
        </div>

        <div class="bg-[#151515] p-6 md:p-8 rounded-3xl border border-gray-800">
          <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-3 uppercase tracking-wider">
            <span class="bg-rose-600 text-white w-8 h-8 flex items-center justify-center rounded-lg text-sm font-black">3</span>
            Pengiriman
          </h2>
          
          <div class="flex gap-4 mb-6">
            <label class="flex-1 cursor-pointer group">
              <input type="radio" value="ambil" v-model="metodePengiriman" @change="updateTarif" class="hidden peer">
              <div class="border border-gray-700 bg-black p-4 rounded-xl text-center peer-checked:border-rose-600 peer-checked:bg-rose-600/10 peer-checked:text-rose-500 transition group-hover:border-gray-500 h-full flex flex-col justify-center items-center gap-2">
                <i class="fas fa-store text-2xl"></i> 
                <span class="font-bold text-sm">Ambil Sendiri</span>
              </div>
            </label>
            <label class="flex-1 cursor-pointer group">
              <input type="radio" value="antar" v-model="metodePengiriman" @change="updateTarif" class="hidden peer">
              <div class="border border-gray-700 bg-black p-4 rounded-xl text-center peer-checked:border-rose-600 peer-checked:bg-rose-600/10 peer-checked:text-rose-500 transition group-hover:border-gray-500 h-full flex flex-col justify-center items-center gap-2">
                <i class="fas fa-truck-fast text-2xl"></i>
                <span class="font-bold text-sm">Antar Kurir</span>
              </div>
            </label>
          </div>

          <div v-show="metodePengiriman === 'antar'" class="space-y-4">
            <div id="map" class="w-full h-72 rounded-xl border border-gray-700 z-0 grayscale invert contrast-125"></div>
            <p class="text-xs text-gray-500 text-center italic">*Klik peta untuk menentukan lokasi</p>
            
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
            
            <textarea v-model="alamat" class="input-dark" rows="2" readonly placeholder="Alamat otomatis..."></textarea>
            <textarea v-model="deskripsiLokasi" class="input-dark" rows="2" placeholder="Detail patokan..."></textarea>
          </div>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 sticky top-28 shadow-2xl">
          <h2 class="text-xl font-black text-white mb-6 uppercase italic tracking-wider border-b border-gray-800 pb-4">
            Total <span class="text-rose-600">Bayar</span>
          </h2>
          
          <div class="space-y-3 mb-6">
             <div class="flex justify-between text-gray-400 text-sm">
               <span>Subtotal</span>
               <span class="text-white font-bold">Rp {{ totalSewa.toLocaleString() }}</span>
             </div>
             <div class="flex justify-between text-gray-400 text-sm" v-if="metodePengiriman === 'antar'">
               <span>Biaya Antar</span>
               <span class="text-white font-bold">Rp {{ tarifAntar.toLocaleString() }}</span>
             </div>
             <div class="border-t border-gray-800 my-2"></div>
             <div class="flex justify-between items-end">
                <span class="text-gray-300 font-bold">Total Akhir</span>
                <span class="text-2xl font-black text-rose-500">Rp {{ totalBayar.toLocaleString() }}</span>
             </div>
          </div>

          <button
             @click="kirimPembayaran"
             :disabled="loading"
             class="w-full bg-white text-black py-4 rounded-xl font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:shadow-[0_0_20px_rgba(225,29,72,0.5)] transition-all disabled:bg-gray-800 disabled:text-gray-600 cursor-pointer relative overflow-hidden group"
          >
             <span class="relative z-10 group-hover:scale-105 transition">{{ loading ? "Memproses..." : "BAYAR SEKARANG" }}</span>
          </button>

          <p class="text-[10px] text-center text-gray-600 mt-4 uppercase tracking-widest">Secured by Midtrans Sandbox</p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import L from "leaflet";
import Swal from "sweetalert2";

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

const getImgUrl = (path) => {
  if (!path) return 'https://placehold.co/400x400/1a1a1a/FFF?text=No+Image';
  if (path.startsWith('http')) return path;
  return `http://127.0.0.1:8000/${path}`;
}

const hitungHari = (item) => {
  if (!item.tanggalMulai || !item.tanggalSelesai) return 0;
  const start = new Date(item.tanggalMulai);
  const end = new Date(item.tanggalSelesai);
  const diff = (end - start) / (1000 * 60 * 60 * 24);
  return diff > 0 ? diff : 0;
}

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
  if (marker) marker.setLatLng(e.latlng);
  else marker = L.marker(e.latlng).addTo(map);
  
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
    const hari = hitungHari(item);
    return total + (hari * item.harga_sewa * item.jumlah);
  }, 0);
});
const totalBayar = computed(() => totalSewa.value + (metodePengiriman.value === "antar" ? tarifAntar.value : 0));

const loading = ref(false);

const kirimPembayaran = async () => {
  if (!itemsKeranjang.value.length) { Swal.fire({ icon: 'warning', title: 'Keranjang Kosong', background: '#151515', color: '#fff', confirmButtonColor: '#e11d48' }); return; }
  if (!nama.value || !telepon.value) { Swal.fire({ icon: 'warning', title: 'Data Kurang', text: 'Isi nama dan WhatsApp.', background: '#151515', color: '#fff', confirmButtonColor: '#e11d48' }); return; }
  if (!fileIdentitas.value) { Swal.fire({ icon: 'warning', title: 'Identitas Wajib', text: 'Upload foto KTP/SIM asli.', background: '#151515', color: '#fff', confirmButtonColor: '#e11d48' }); return; }
  if (metodePengiriman.value === 'antar' && (!lat.value)) { Swal.fire({ icon: 'warning', title: 'Lokasi Belum Ada', text: 'Klik peta dulu.', background: '#151515', color: '#fff', confirmButtonColor: '#e11d48' }); return; }

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
    
    const snapToken = res.data.snap_token;
    if (window.snap && snapToken) {
      window.snap.pay(snapToken, {
        onSuccess: () => { 
            localStorage.removeItem("cart"); 
            window.dispatchEvent(new Event('cart-updated'));
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
        localStorage.removeItem("cart");
        window.dispatchEvent(new Event('cart-updated'));
        window.location.href = `/riwayat?telepon=${telepon.value}`;
    }
  } catch (err) {
    // TAMPILKAN ERROR DETAIL DARI BACKEND
    const errorTitle = "Server Error 😭";
    const errorMsg = err.response?.data?.error || err.response?.data?.message || err.message || "Terjadi kesalahan.";
    
    Swal.fire({
        icon: 'error',
        title: errorTitle,
        text: errorMsg, // <-- Ini yang penting!
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