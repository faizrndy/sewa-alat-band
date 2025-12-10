<template>
  <Navbar class="fixed top-0 left-0 w-full z-50" />

  <div class="min-h-screen bg-[#0a0a0a] text-white py-10 px-6 font-sans pt-28">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

      <div class="lg:col-span-3 mb-2">
         <h1 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter">
           Checkout <span class="text-rose-600">&</span> Payment
         </h1>
         <p class="text-gray-500 mt-2 text-sm md:text-base">Lengkapi data buat selesaikan bookingan lo.</p>
      </div>

      <div class="lg:col-span-2 space-y-6">

        <div class="bg-[#151515] p-6 md:p-8 rounded-3xl border border-gray-800">
          <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-3 uppercase tracking-wider">
            <span class="bg-rose-600 text-white w-8 h-8 flex items-center justify-center rounded-lg text-sm font-black">1</span>
            Review Gear
          </h2>

          <div v-if="itemsKeranjang.length === 0" class="text-center text-gray-500 py-4">
             Keranjang kosong. <router-link to="/katalog" class="text-rose-500 underline">Cari alat dulu!</router-link>
          </div>

          <div v-if="tglMulai && tglSelesai" class="mb-4 bg-rose-900/10 border border-rose-900/50 p-4 rounded-xl flex justify-between items-center">
             <div>
                <span class="text-xs text-rose-400 font-bold uppercase tracking-widest block mb-1">Tanggal Sewa</span>
                <span class="text-white font-bold text-lg">{{ tglMulai }} <span class="text-gray-500 text-sm">s/d</span> {{ tglSelesai }}</span>
             </div>
             <div class="bg-black/40 px-4 py-2 rounded-lg border border-rose-500/30">
                <span class="text-rose-500 font-black text-xl">{{ lamaSewa }} Hari</span>
             </div>
          </div>

          <div class="space-y-4">
            <div v-for="item in itemsKeranjang" :key="item.id" class="flex gap-4 bg-black/40 p-4 rounded-2xl border border-gray-800/50 hover:border-rose-500/30 transition">
              <div class="w-20 h-20 md:w-24 md:h-24 flex-shrink-0 bg-white rounded-xl overflow-hidden">
                <img :src="getImgUrl(item.gambar)" class="w-full h-full object-contain p-1" @error="$event.target.src = 'https://placehold.co/150x150/1a1a1a/FFF?text=No+Image'"/>
              </div>
              <div class="flex-1 flex flex-col justify-center">
                <h3 class="font-black text-white text-base md:text-lg uppercase italic leading-tight">{{ item.nama_alat }}</h3>
                <div class="mt-2 flex items-center justify-between">
                  <p class="text-xs text-gray-400">Rp {{ Number(item.harga_sewa).toLocaleString() }} x {{ item.qty || item.jumlah || 1 }} Unit</p>
                  <p class="font-bold text-white text-sm">Total: <span class="text-rose-500">Rp {{ (item.harga_sewa * (item.qty || item.jumlah || 1) * lamaSewa).toLocaleString() }}</span></p>
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
               <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
               <input v-model="nama" class="input-dark" type="text" placeholder="Nama Panggung / Asli" />
             </div>
             <div>
               <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">WhatsApp</label>
               <input v-model="telepon" class="input-dark" type="number" placeholder="0812..." />
             </div>
             <div class="md:col-span-2">
               <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Identitas (KTP/SIM)</label>
               <div class="relative border-2 border-dashed border-gray-700 bg-black/30 rounded-xl p-6 text-center cursor-pointer hover:bg-gray-800 hover:border-rose-600 transition group">
                  <input type="file" @change="onIdentitas" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"/>
                  <div v-if="!fileIdentitas" class="flex flex-col items-center text-gray-500 group-hover:text-rose-500 transition">
                    <span class="text-3xl mb-2">🪪</span>
                    <p class="text-sm font-bold">Klik untuk upload KTP/SIM</p>
                  </div>
                  <div v-else class="flex items-center justify-center gap-3 text-green-500">
                    <span class="text-2xl">✓</span>
                    <p class="text-sm font-bold text-white">{{ fileIdentitas.name }}</p>
                  </div>
               </div>
             </div>
          </div>
        </div>

        <div class="bg-[#151515] p-6 md:p-8 rounded-3xl border border-gray-800">
          <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-3 uppercase tracking-wider">
            <span class="bg-rose-600 text-white w-8 h-8 flex items-center justify-center rounded-lg text-sm font-black">3</span>
            Metode Ambil
          </h2>

          <div class="flex gap-4 mb-6">
            <label class="flex-1 cursor-pointer group">
              <input type="radio" value="ambil" v-model="metodePengiriman" @change="updateTarif" class="hidden peer">
              <div class="border border-gray-700 bg-black p-4 rounded-xl text-center peer-checked:border-rose-600 peer-checked:bg-rose-600/10 peer-checked:text-rose-500 transition group-hover:border-gray-500 h-full flex flex-col justify-center items-center gap-2">
                <span class="text-2xl">🏪</span> <span class="font-bold text-sm">Ambil Sendiri</span>
              </div>
            </label>
            <label class="flex-1 cursor-pointer group">
              <input type="radio" value="antar" v-model="metodePengiriman" @change="updateTarif" class="hidden peer">
              <div class="border border-gray-700 bg-black p-4 rounded-xl text-center peer-checked:border-rose-600 peer-checked:bg-rose-600/10 peer-checked:text-rose-500 transition group-hover:border-gray-500 h-full flex flex-col justify-center items-center gap-2">
                <span class="text-2xl">🚚</span> <span class="font-bold text-sm">Antar Kurir</span>
              </div>
            </label>
          </div>

          <div v-show="metodePengiriman === 'antar'" class="space-y-4">
            <div id="map" class="w-full h-72 rounded-xl border border-gray-700 z-0"></div>

            <p class="text-xs text-gray-500 text-center italic">*Klik peta untuk menentukan lokasi pengantaran</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <div>
                  <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Jarak</label>
                  <div class="w-full bg-black border border-gray-700 text-gray-400 px-4 py-3 rounded-xl">{{ jarak.toFixed(1) }} km</div>
               </div>
               <div>
                  <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Ongkir (Estimasi)</label>
                  <div class="w-full bg-black border border-gray-700 text-rose-500 font-bold px-4 py-3 rounded-xl">Rp {{ tarifAntar.toLocaleString() }}</div>
               </div>
            </div>

            <textarea v-model="alamat" class="input-dark" rows="2" readonly placeholder="Alamat otomatis..."></textarea>
            <textarea v-model="deskripsiLokasi" class="input-dark" rows="2" placeholder="Detail patokan (Warna pagar, dekat warung, dll)..."></textarea>
          </div>
          
          <div v-if="metodePengiriman === 'ambil'" class="text-center p-4 bg-green-900/20 border border-green-900 rounded-xl">
             <p class="text-green-500 font-bold text-sm">✅ Kamu memilih Ambil Sendiri. Silakan datang langsung ke Studio.</p>
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
               <span>Subtotal ({{ lamaSewa }} Hari)</span>
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
          <p class="text-[10px] text-center text-gray-600 mt-4 uppercase tracking-widest">Secured by Midtrans</p>
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
import { useRouter } from "vue-router";
import Navbar from "@/components/Navbar.vue";

const router = useRouter();

// STATE FORM
const nama = ref("");
const telepon = ref("");
const deskripsiLokasi = ref("");
const alamat = ref("");
const fileIdentitas = ref(null);

// STATE TANGGAL
const tglMulai = ref("");
const tglSelesai = ref("");
const lamaSewa = ref(0);

// STATE PETA
const lat = ref(null);
const lon = ref(null);
const jarak = ref(0);
let map;
let marker;
const tokoLat = -7.568; 
const tokoLon = 110.829; 

// CART LOGIC
const itemsKeranjang = ref(JSON.parse(localStorage.getItem("keranjang") || "[]"));

// ============================================
// 1. AUTO FILL PROFILE
// ============================================
const getProfile = async () => {
  // Ambil Token (Coba kedua key)
  const token = localStorage.getItem("token") || localStorage.getItem("buyer_token");
  
  if (!token) {
      console.log("Guest Mode - No Token");
      return; 
  }

  try {
    const res = await axios.get("http://127.0.0.1:8000/api/buyer/profile", {
      headers: { Authorization: `Bearer ${token}` }
    });
    
    const u = res.data.data || res.data.user || res.data;
    if (u) {
        nama.value = u.nama_lengkap || u.name || u.nama || nama.value;
        telepon.value = u.nomor_telepon || u.telepon || u.phone || telepon.value;
    }
  } catch (e) {
    // JIKA ERROR 401 (Unauthorized), Hapus Token Rusak
    if (e.response && e.response.status === 401) {
        console.warn("Token expired saat load profile. Bersihkan storage.");
        localStorage.removeItem("token");
        localStorage.removeItem("buyer_token");
    }
  }
};

const onIdentitas = e => fileIdentitas.value = e.target.files[0];
const getImgUrl = (path) => path?.startsWith("http") ? path : `http://localhost:8000/${path}`;

// UPDATE ONGKIR
const metodePengiriman = ref("ambil");
const tarifAntar = ref(0);

function updateTarif() {
  if (metodePengiriman.value !== "antar") {
    tarifAntar.value = 0;
    return;
  }
  const km = jarak.value;
  const ratePerKm = 2500;
  const minOngkir = 10000;
  const hitung = Math.ceil(km * ratePerKm);
  tarifAntar.value = hitung < minOngkir ? minOngkir : hitung;
}

// LOGIC LAINNYA
const hitungHariGlobal = () => {
  tglMulai.value = localStorage.getItem('sewa_tgl_mulai');
  tglSelesai.value = localStorage.getItem('sewa_tgl_selesai');

  if (tglMulai.value && tglSelesai.value) {
    const start = new Date(tglMulai.value);
    const end = new Date(tglSelesai.value);
    const diff = (end - start) / (1000 * 60 * 60 * 24);
    lamaSewa.value = diff > 0 ? diff : 1; 
  } else {
    lamaSewa.value = 1; 
  }
};

onMounted(() => {
  getProfile();
  hitungHariGlobal();

  if(!tglMulai.value || !tglSelesai.value) {
     Swal.fire({ icon: 'warning', title: 'Tanggal Kosong', text: 'Pilih tanggal dulu di Katalog', background: '#151515', color: '#fff' })
       .then(() => router.push('/katalog'));
  }

  setTimeout(() => {
      if (document.getElementById("map")) {
        map = L.map("map").setView([tokoLat, tokoLon], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);
        L.marker([tokoLat, tokoLon]).addTo(map).bindPopup("Toko Kami");
        L.circle([tokoLat, tokoLon], { radius: 25000, color: "#e11d48", fillColor: "#e11d48", fillOpacity: 0.1 }).addTo(map);
        map.on("click", pilihLokasi);
      }
  }, 500);
});

// Helpers Peta
function hitungJarak(lat1, lon1, lat2, lon2) {
  const R = 6371;
  const dLat = ((lat2 - lat1) * Math.PI) / 180;
  const dLon = ((lon2 - lon1) * Math.PI) / 180;
  const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
  return 2 * R * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

async function pilihLokasi(e) {
  const klikLat = e.latlng.lat;
  const klikLon = e.latlng.lng;
  const jarakKlik = hitungJarak(tokoLat, tokoLon, klikLat, klikLon);

  if (jarakKlik > 25) {
    Swal.fire({ icon: "warning", title: "Kejauhan Bos!", text: "Max 25km ya.", background: "#151515", color: "#fff" });
    return;
  }
  lat.value = klikLat; lon.value = klikLon; jarak.value = jarakKlik;
  if (marker) marker.setLatLng(e.latlng); else marker = L.marker(e.latlng).addTo(map);
  updateTarif();
  try {
    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat.value}&lon=${lon.value}&format=json`);
    const data = await res.json();
    alamat.value = data.display_name || "Alamat terpilih";
  } catch (err) { alamat.value = "Alamat terpilih"; }
}

const totalSewa = computed(() => itemsKeranjang.value.reduce((total, item) => total + (item.harga_sewa * (item.qty || item.jumlah || 1) * lamaSewa.value), 0));
const totalBayar = computed(() => totalSewa.value + tarifAntar.value);
const loading = ref(false);

// 🔥🔥🔥 SOLUSI FINAL AUTH 401 & ALAMAT
const kirimPembayaran = async () => {
  // 1. Ambil Token dengan Benar (Cek Prioritas)
  // Kalau ada token, kita pakai. Kalau tidak ada, STOP.
  const token = localStorage.getItem("token") || localStorage.getItem("buyer_token");

  if (!token) {
    return Swal.fire({ 
        icon: "error", 
        title: "Kamu Belum Login", 
        text: "Silakan login ulang untuk melanjutkan pembayaran.", 
        background: "#151515", 
        color: "#fff",
        confirmButtonColor: "#e11d48"
    }).then(() => router.push('/login'));
  }

  // 2. Validasi Data
  if (!nama.value || !telepon.value || !fileIdentitas.value) {
    return Swal.fire({ icon: "warning", title: "Data Kurang", text: "Lengkapi Nama, WA, dan Upload KTP.", background: "#151515", color: "#fff" });
  }

  // Jika Antar, wajib pilih lokasi
  if (metodePengiriman.value === "antar" && !lat.value) {
    return Swal.fire({ icon: "warning", title: "Pilih Lokasi", text: "Klik peta untuk lokasi pengantaran.", background: "#151515", color: "#fff" });
  }
  
  loading.value = true;
  try {
    const form = new FormData();
    form.append("nama", nama.value);
    form.append("telepon", telepon.value);
    
    // LOGIC ALAMAT: Jika Ambil Sendiri -> Kirim Strip (-)
    // Jika Antar -> Kirim Alamat dari Peta
    const alamatFinal = metodePengiriman.value === 'ambil' ? '-' : (alamat.value || '-');
    form.append("alamat", alamatFinal);
    
    form.append("deskripsi_lokasi", deskripsiLokasi.value || "-");
    form.append("lat", lat.value || 0); form.append("lon", lon.value || 0);
    form.append("jarak_km", jarak.value);
    form.append("metode_pengiriman", metodePengiriman.value);
    form.append("tarif_antar", tarifAntar.value);
    form.append("total_sewa", totalSewa.value);
    form.append("total_bayar", totalBayar.value);
    form.append("identitas", fileIdentitas.value);
    form.append("tgl_mulai", tglMulai.value);
    form.append("tgl_selesai", tglSelesai.value);
    
    const itemsToSend = itemsKeranjang.value.map(item => ({
        id: item.id, qty: item.qty || item.jumlah || 1, harga_sewa: item.harga_sewa, nama_alat: item.nama_alat
    }));
    form.append("items", JSON.stringify(itemsToSend));

    // Kirim Header Authorization
    const headers = { 
        "Content-Type": "multipart/form-data",
        "Authorization": `Bearer ${token}` 
    };

    const res = await axios.post("http://127.0.0.1:8000/api/transaksi", form, { headers });
    
    if (window.snap && res.data.snap_token) {
       window.snap.pay(res.data.snap_token, {
         onSuccess: () => {
             localStorage.removeItem("keranjang"); localStorage.removeItem("sewa_tgl_mulai"); localStorage.removeItem("sewa_tgl_selesai");
             window.location.href = `/riwayat?telepon=${telepon.value}`;
         },
         onPending: () => { window.location.href = `/riwayat?telepon=${telepon.value}`; }
       });
    }
  } catch (err) {
    console.error("Error Detail:", err); // Cek console kalau masih error

    // Tangkap Error 401 (Token Salah/Expired)
    if (err.response && (err.response.status === 401 || err.response.status === 419)) {
        Swal.fire({ 
            icon: "error", 
            title: "Sesi Habis", 
            text: "Token kadaluwarsa. Login ulang yuk!", 
            background: "#151515", 
            color: "#fff",
            confirmButtonColor: "#e11d48"
        }).then(() => {
            // BERSIHKAN TOKEN SAMPAH AGAR TIDAK LOOPING ERROR
            localStorage.removeItem("token");
            localStorage.removeItem("buyer_token");
            router.push('/login');
        });
    } else {
        Swal.fire({ icon: "error", title: "Error", text: err.response?.data?.message || "Gagal memproses", background: "#151515", color: "#fff" });
    }
  } finally { loading.value = false; }
};
</script>

<style scoped>
.input-dark {
  @apply w-full bg-black border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition placeholder-gray-600;
}
</style>