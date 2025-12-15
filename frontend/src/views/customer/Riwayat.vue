<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white pt-24 pb-12 font-sans">
    <Navbar />

    <div class="max-w-4xl mx-auto px-6">
      
      <div class="flex items-center justify-between mb-8">
        <div>
          <h1 class="text-3xl font-black italic uppercase tracking-tighter">
            Riwayat <span class="text-rose-600">Sewa</span>
          </h1>
          <p class="text-gray-500 text-sm mt-1">Daftar gear yang pernah lo sikat.</p>
        </div>
        <button @click="fetchHistory" class="bg-[#151515] hover:bg-gray-800 text-white p-3 rounded-full transition border border-gray-700">
          <i class="fas fa-sync-alt" :class="{'animate-spin': loading}"></i>
        </button>
      </div>

      <div v-if="loading" class="space-y-4">
        <div v-for="n in 3" :key="n" class="h-32 bg-[#151515] rounded-2xl animate-pulse"></div>
      </div>

      <div v-else-if="riwayat.length === 0" class="text-center py-20 bg-[#151515] rounded-3xl border border-gray-800 border-dashed">
        <div class="text-6xl mb-4">🎸</div>
        <h3 class="text-xl font-bold text-white mb-2">Belum Ada Riwayat</h3>
        <p class="text-gray-500 mb-6">Lo belum pernah nyewa alat nih.</p>
        <router-link to="/katalog" class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-3 rounded-xl font-bold uppercase tracking-widest transition shadow-lg">
          Gas Sewa Sekarang
        </router-link>
      </div>

      <div v-else class="space-y-6">
        <div v-for="trx in riwayat" :key="trx.id" 
          class="bg-[#151515] border border-gray-800 rounded-2xl overflow-hidden hover:border-rose-600/50 transition group relative">
          
          <div class="bg-[#111] p-4 flex justify-between items-center border-b border-gray-800">
            <div>
              <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Kode TRX</span>
              <p class="font-mono text-white font-bold text-lg">#{{ trx.kode_transaksi }}</p>
            </div>
            <div class="text-right">
              <span class="block text-[10px] text-gray-500 uppercase font-bold mb-1">Status</span>
              <span :class="statusBadge(trx.status)" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border">
                {{ trx.status }}
              </span>
            </div>
          </div>

          <div class="p-6">
            <div class="flex items-center gap-2 mb-4 text-sm text-gray-400">
              <i class="fas fa-calendar-alt text-rose-600"></i>
              <span>{{ formatDate(trx.tgl_mulai) }}</span>
              <span class="text-gray-600">s/d</span>
              <span>{{ formatDate(trx.tgl_selesai) }}</span>
              <span class="ml-2 bg-gray-800 text-white px-2 py-0.5 rounded text-xs font-bold">{{ trx.lama_hari }} Hari</span>
            </div>

            <div class="space-y-3 mb-6">
              <div v-for="(item, idx) in trx.items" :key="idx" class="flex items-center gap-4">
                 <div class="w-12 h-12 bg-black rounded-lg flex items-center justify-center border border-gray-800 overflow-hidden">
                    <img :src="getImgUrl(item.alat?.gambar || 'no-image')" @error="$event.target.style.display='none'" class="w-full h-full object-cover">
                 </div>
                 <div>
                    <p class="text-white font-bold text-sm">{{ item.nama_alat }}</p>
                    <p class="text-xs text-gray-500">{{ item.jumlah }} Unit x Rp {{ Number(item.harga_sewa).toLocaleString() }}</p>
                 </div>
              </div>
            </div>

            <div class="flex justify-between items-end border-t border-gray-800 pt-4 mt-2">
               <div>
                  <p class="text-xs text-gray-500 uppercase font-bold">Total Bayar</p>
                  <p class="text-xl font-black text-rose-500">Rp {{ Number(trx.total_bayar).toLocaleString() }}</p>
               </div>

               <div v-if="trx.status === 'pending'">
                  <button @click="bayarSekarang(trx.snap_token)" 
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-bold uppercase text-xs tracking-wider shadow-lg hover:scale-105 transition">
                    Bayar Sekarang
                  </button>
               </div>
               
               <div v-else-if="['success', 'paid', 'settlement'].includes(trx.status)">
                  <button class="bg-gray-800 text-green-500 px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider cursor-default border border-green-500/20">
                    <i class="fas fa-check-circle mr-1"></i> Lunas
                  </button>
               </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import Navbar from "@/components/Navbar.vue";

const router = useRouter();
const riwayat = ref([]);
const loading = ref(true);

// 1. FETCH DATA OTOMATIS (Tanpa Input WA)
const fetchHistory = async () => {
  const token = localStorage.getItem('buyer_token');

  // Jika tidak ada token, lempar ke login
  if (!token) {
      Swal.fire({
          icon: 'warning',
          title: 'Belum Login',
          text: 'Login dulu untuk melihat riwayat sewa.',
          background: '#151515',
          color: '#fff',
          confirmButtonColor: '#e11d48'
      }).then(() => router.push('/login'));
      return;
  }

  loading.value = true;
  try {
    // Panggil API history yang baru dibuat
    const res = await axios.get('http://127.0.0.1:8000/api/buyer/history', {
        headers: { Authorization: `Bearer ${token}` }
    });

    if (res.data.success) {
        riwayat.value = res.data.data;
    }

  } catch (err) {
    console.error(err);
    // Handle Token Expired (401)
    if (err.response && err.response.status === 401) {
        localStorage.removeItem('buyer_token');
        router.push('/login');
    }
  } finally {
    loading.value = false;
  }
};

// 2. HELPER FUNCTIONS
const statusBadge = (status) => {
  if (['success', 'paid', 'settlement'].includes(status)) return 'bg-green-500/20 text-green-500 border-green-500/50';
  if (status === 'pending') return 'bg-yellow-500/20 text-yellow-500 border-yellow-500/50';
  if (['failed', 'cancelled', 'expire'].includes(status)) return 'bg-red-500/20 text-red-500 border-red-500/50';
  return 'bg-gray-500/20 text-gray-400 border-gray-500/50';
};

const formatDate = (dateString) => {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};

const getImgUrl = (path) => {
    // Logic simpel untuk gambar, sesuaikan path backend
    return path && path !== 'no-image' ? `http://127.0.0.1:8000/${path}` : 'https://placehold.co/100x100/111/FFF?text=No+Image';
};

// 3. FITUR BAYAR ULANG (Jika user close tab sebelum bayar)
const bayarSekarang = (snapToken) => {
    if (!snapToken) {
        Swal.fire({icon: 'error', title: 'Error', text: 'Token pembayaran tidak ditemukan.', background: '#151515', color: '#fff'});
        return;
    }
    
    if (window.snap) {
        window.snap.pay(snapToken, {
            onSuccess: () => { fetchHistory(); Swal.fire({icon:'success', title:'Lunas!', background:'#151515', color:'#fff'}); },
            onPending: () => { fetchHistory(); },
            onError: () => { fetchHistory(); }
        });
    } else {
        Swal.fire({icon: 'error', title: 'Midtrans Error', text: 'Script Midtrans belum dimuat.', background: '#151515', color: '#fff'});
    }
};

onMounted(() => {
  fetchHistory();
});
</script>