<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white pt-28 pb-12 font-sans">
    <Navbar />
    
    <div v-if="loading" class="text-center py-20">
       <div class="animate-spin h-10 w-10 border-4 border-rose-600 rounded-full border-t-transparent mx-auto"></div>
    </div>

    <div class="max-w-6xl mx-auto px-6" v-else-if="alat">
      <router-link to="/katalog" class="inline-flex items-center gap-2 text-gray-500 hover:text-white mb-8 transition font-bold uppercase text-sm tracking-wider">
        <i class="fas fa-arrow-left"></i> Kembali ke List Gear
      </router-link>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div class="bg-[#151515] border border-gray-800 rounded-3xl p-10 flex items-center justify-center relative group">
          <div class="absolute inset-0 bg-rose-600/5 opacity-0 group-hover:opacity-100 transition duration-500 blur-3xl rounded-full"></div>
          <img 
            :src="getImgUrl(alat.gambar)" 
            @error="$event.target.src = 'https://placehold.co/600x400/1a1a1a/FFF?text=Gambar+Rusak'"
            class="w-full max-h-[500px] object-contain relative z-10 drop-shadow-2xl" 
          />
        </div>

        <div>
          <div class="flex items-center gap-3 mb-4">
             <span class="bg-rose-600 text-white text-[10px] font-black px-3 py-1 uppercase rounded-sm tracking-widest">{{ alat.kategori }}</span>
             <span :class="alat.status === 'Tersedia' ? 'text-green-500' : 'text-red-500'" class="text-sm font-bold uppercase tracking-wide flex items-center gap-1">
               <span class="w-2 h-2 rounded-full bg-current"></span> {{ alat.status }}
             </span>
          </div>

          <h1 class="text-4xl md:text-5xl font-black italic uppercase leading-none mb-6">{{ alat.nama_alat }}</h1>

          <div class="bg-[#151515] p-6 rounded-2xl border border-gray-800 mb-8">
            <p class="text-gray-400 text-sm font-bold uppercase mb-1">Harga Sewa</p>
            <p class="text-3xl font-bold text-white">Rp {{ Number(alat.harga_sewa).toLocaleString() }} <span class="text-sm text-gray-500 font-normal">/ 24 jam</span></p>
          </div>

          <h3 class="text-lg font-bold uppercase tracking-wide mb-3 text-gray-300">Deskripsi Gear</h3>
          <p class="text-gray-500 leading-relaxed mb-8">{{ alat.deskripsi || 'Deskripsi belum tersedia.' }}</p>

          <div class="bg-[#151515] p-8 rounded-3xl border border-gray-800">
            <h3 class="font-bold text-xl text-white mb-6 uppercase italic">Booking Sekarang</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label class="label-dark">Mulai</label>
                <input type="date" v-model="tanggalMulai" class="input-dark color-scheme-dark" />
              </div>
              <div>
                <label class="label-dark">Selesai</label>
                <input type="date" v-model="tanggalSelesai" class="input-dark color-scheme-dark" />
              </div>
            </div>

            <div class="mb-6">
              <label class="label-dark">Jumlah Unit</label>
              <input type="number" v-model="jumlah" min="1" class="input-dark" />
            </div>

            <div v-if="lamaSewa > 0" class="flex justify-between items-center bg-black/50 p-4 rounded-xl mb-6 border border-gray-800">
               <span class="text-gray-400 text-sm">{{ lamaSewa }} Hari x {{ jumlah }} Unit</span>
               <span class="text-xl font-bold text-rose-500">Total: Rp {{ totalBiaya.toLocaleString() }}</span>
            </div>

            <button @click="tambahKeranjang" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-black py-4 rounded-xl uppercase tracking-widest shadow-[0_0_20px_rgba(225,29,72,0.4)] transition hover:scale-[1.02]">
              <i class="fas fa-plus mr-2"></i> Masukkan Keranjang
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import Navbar from "@/components/Navbar.vue"
import Swal from 'sweetalert2'

const route = useRoute()
const router = useRouter()
const alat = ref(null)
const loading = ref(true)
const tanggalMulai = ref('')
const tanggalSelesai = ref('')
const jumlah = ref(1)

// Helper Image URL
const getImgUrl = (path) => {
  if (!path) return 'https://placehold.co/600x400/1a1a1a/FFF?text=No+Image';
  if (path.startsWith('http')) return path;
  return `http://127.0.0.1:8000/${path}`;
}

// Fetch Data Alat
const getAlat = async () => {
  try {
    const res = await axios.get(`/api/alat-band/${route.params.id}`)
    alat.value = res.data
  } catch (err) { console.error(err) } 
  finally { loading.value = false }
}
onMounted(getAlat)

// Hitung Lama Sewa
const lamaSewa = computed(() => {
  if (!tanggalMulai.value || !tanggalSelesai.value) return 0
  const start = new Date(tanggalMulai.value)
  const end = new Date(tanggalSelesai.value)
  const diff = (end - start) / (1000 * 60 * 60 * 24)
  return diff > 0 ? diff : 0
})

// Hitung Total Biaya
const totalBiaya = computed(() => alat.value ? lamaSewa.value * alat.value.harga_sewa * jumlah.value : 0)

// Fungsi Tambah Keranjang
const tambahKeranjang = async () => { 
  // 1. CEK LOGIN
  const token = localStorage.getItem("buyer_token");
  if (!token) {
    Swal.fire({ icon: 'info', title: 'Login Dulu Yuk!', text: 'Kamu harus login sebelum sewa alat.', background: '#151515', color: '#fff', confirmButtonText: 'Login Sekarang' }).then((result) => {
      if (result.isConfirmed) router.push('/login');
    });
    return;
  }

  // 2. VALIDASI INPUT WAJIB
  if (!tanggalMulai.value || !tanggalSelesai.value) { 
    Swal.fire({ icon: 'warning', title: 'Pilih tanggal sewa dulu!', background: '#151515', color: '#fff' });
    return; 
  }
  if (lamaSewa.value <= 0) { 
    Swal.fire({ icon: 'error', title: 'Tanggal selesai salah!', background: '#151515', color: '#fff' });
    return; 
  }
  if (jumlah.value <= 0) {
    Swal.fire({ icon: 'warning', title: 'Jumlah harus lebih dari 0!', background: '#151515', color: '#fff' });
    return; 
  }

  // 3. 🔥 CEK KETERSEDIAAN ALAT DENGAN API 🔥
  try {
      const checkRes = await axios.post('/api/alat-band/check-availability', {
          alat_id: alat.value.id,
          tanggal_mulai: tanggalMulai.value,
          tanggal_selesai: tanggalSelesai.value,
          // FIX: Gunakan 'jumlah_diminta' agar sesuai validasi Laravel
          jumlah_diminta: jumlah.value, 
      });

      if (!checkRes.data.available) {
          Swal.fire({ 
              icon: 'error', 
              title: 'Stok Tidak Cukup!', 
              text: checkRes.data.message, 
              background: '#151515', color: '#fff' 
          });
          return;
      }
      
  } catch (err) {
      const msg = err.response?.data?.message || 'Terjadi kesalahan saat mengecek stok.';
      Swal.fire({ icon: 'error', title: 'Gagal Validasi', text: msg, background: '#151515', color: '#fff' });
      return;
  }
  
  // 4. JIKA LOLOS VALIDASI, BARU SIMPAN KE KERANJANG
  const cart = JSON.parse(localStorage.getItem('cart') || '[]')
  
  // Cek apakah item yang sama persis sudah ada
  const existingItem = cart.find(item => 
      item.id === alat.value.id && 
      item.tanggalMulai === tanggalMulai.value && 
      item.tanggalSelesai === tanggalSelesai.value
  );
  
  if (existingItem) { 
      existingItem.jumlah += jumlah.value; 
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
  
  Swal.fire({ icon: 'success', title: 'Sip! Gear masuk keranjang 🤘', background: '#151515', color: '#fff', timer: 1500, showConfirmButton: false });
}
</script>

<style scoped>
.label-dark { @apply block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2; }
.input-dark { @apply w-full bg-[#0a0a0a] border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition; }
.color-scheme-dark { color-scheme: dark; }
</style>