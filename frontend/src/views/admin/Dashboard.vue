<template>
  <div class="p-6">
    <h1 class="text-3xl font-black text-white italic uppercase tracking-tighter mb-8">
      Dashboard <span class="text-indigo-500">Overview</span>
    </h1>
    <p class="text-gray-400 mb-6">Selamat datang kembali, Admin.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 bg-indigo-600/10 w-32 h-32 rounded-full group-hover:bg-indigo-600/20 transition duration-500"></div>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Total Gear</p>
        <h2 class="text-4xl font-bold text-white">{{ stats.total_alat }}</h2>
      </div>

      <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 bg-green-600/10 w-32 h-32 rounded-full group-hover:bg-green-600/20 transition duration-500"></div>
        <p class="text-green-500 text-xs font-bold uppercase tracking-wider mb-1">Pendapatan</p>
        <h2 class="text-4xl font-bold text-white">{{ formatRupiah(stats.pendapatan) }}</h2>
      </div>

      <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 bg-orange-600/10 w-32 h-32 rounded-full group-hover:bg-orange-600/20 transition duration-500"></div>
        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-1">Perlu Diproses</p>
        <h2 class="text-4xl font-bold text-white">{{ stats.perlu_diproses }}</h2>
      </div>
    </div>

    <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 mb-8">
      <h3 class="text-xl font-bold mb-6 text-white">Statistik Pendapatan</h3>
      <div class="h-80 w-full relative">
        <Bar v-if="!loading && chartData" :data="chartData" :options="chartOptions" />
        
        <div v-else class="flex items-center justify-center h-full text-gray-500">
            <div class="animate-spin h-6 w-6 border-2 border-indigo-500 rounded-full border-t-transparent mr-2"></div>
            Memuat Data...
        </div>
      </div>
    </div>

    <div class="bg-[#151515] rounded-3xl border border-gray-800 overflow-hidden mb-10">
      <div class="p-6 border-b border-gray-800 flex justify-between items-center">
        <h3 class="text-xl font-bold text-white">Transaksi Terbaru</h3>
        <router-link to="/admin/transaksi" class="text-sm text-indigo-400 hover:text-indigo-300">Lihat Semua →</router-link>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-gray-400">
          <thead class="bg-[#0a0a0a] text-xs uppercase text-gray-500">
            <tr>
              <th class="px-6 py-4">Penyewa</th>
              <th class="px-6 py-4">Alat</th>
              <th class="px-6 py-4">Total</th>
              <th class="px-6 py-4">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
            <tr v-for="trx in stats.transaksi_terbaru" :key="trx.id" class="hover:bg-white/5 transition">
              <td class="px-6 py-4 font-medium text-white">
                {{ trx.user ? trx.user.name : 'User Terhapus' }}
              </td>
              <td class="px-6 py-4">
                {{ trx.alat ? trx.alat.nama_alat : 'Alat Terhapus' }}
              </td>
             <td class="px-6 py-4 text-green-400">
              {{ formatRupiah(Number(trx.total_harga)) }}
            </td>
              <td class="px-6 py-4">
                <span 
                  class="px-3 py-1 rounded-full text-xs font-bold"
                  :class="{
                    'bg-yellow-500/10 text-yellow-500': trx.status === 'pending',
                    'bg-green-500/10 text-green-500': trx.status === 'lunas',
                    'bg-red-500/10 text-red-500': trx.status === 'batal',
                    'bg-blue-500/10 text-blue-500': trx.status === 'disewa'
                  }"
                >
                  {{ trx.status.toUpperCase() }}
                </span>
              </td>
            </tr>
            <tr v-if="stats.transaksi_terbaru.length === 0">
              <td colspan="4" class="px-6 py-8 text-center text-gray-600 italic">
                Belum ada transaksi masuk.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2'; // Tambahan untuk notifikasi cantik

// Import komponen Chart.js
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js';
import { Bar } from 'vue-chartjs';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const router = useRouter();
const loading = ref(true);

// State Data (Gabungan)
const stats = ref({
    total_alat: 0,
    pendapatan: 0,
    perlu_diproses: 0,
    transaksi_terbaru: [], // Tambahan untuk tabel
    grafik_per_bulan: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] // Default dummy agar chart tidak error
});

// Format Rupiah Full
const formatRupiah = (angka) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(angka);
};

// Config Chart
const chartData = computed(() => {
  return {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
    datasets: [
      {
        label: 'Pendapatan (Rp)',
        backgroundColor: '#6366f1',
        borderRadius: 6,
        // Gunakan data dari backend, atau fallback ke dummy jika kosong
        data: stats.value.grafik_per_bulan || [0,0,0,0,0,0,0,0,0,0,0,0]
      }
    ]
  };
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false },
    tooltip: {
       callbacks: {
           label: (context) => 'Rp ' + context.raw.toLocaleString('id-ID')
       }
    }
  },
  scales: {
    y: {
      grid: { color: '#333' },
      ticks: { color: '#9ca3af' },
      beginAtZero: true
    },
    x: {
      grid: { display: false },
      ticks: { color: '#9ca3af' }
    }
  }
};

// Load Data
const loadDashboard = async () => {
    loading.value = true;
    try {
        const token = localStorage.getItem('admin_token');
        
        if (!token) {
            router.push('/admin/login');
            return;
        }

        // 🔥 FIX URL: Gunakan full path agar tidak nyasar ke localhost:3000
        const res = await axios.get('http://127.0.0.1:8000/api/admin/dashboard', {
            headers: { Authorization: `Bearer ${token}` }
        });
        
        stats.value = res.data; 
        
    } catch (e) {
        console.error("Gagal load dashboard", e);
        
        // 🔥 ERROR HANDLING: Jika token expired, tendang ke login
        if (e.response && (e.response.status === 401 || e.response.status === 403)) {
            Swal.fire('Sesi Habis', 'Silakan login kembali.', 'warning');
            localStorage.removeItem('admin_token');
            router.push('/admin/login');
        }
    } finally {
        loading.value = false;
    }
};

onMounted(loadDashboard);
</script>