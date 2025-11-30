<template>
  <div>
    <h1 class="text-3xl font-bold mb-8">Dashboard Overview</h1>
    <p class="text-gray-400 mb-6">Selamat datang kembali, Admin.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 bg-indigo-600/10 w-32 h-32 rounded-full group-hover:bg-indigo-600/20 transition duration-500"></div>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Total Alat</p>
        <h2 class="text-4xl font-bold text-white">{{ stats.total_alat }}</h2>
      </div>

      <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 bg-green-600/10 w-32 h-32 rounded-full group-hover:bg-green-600/20 transition duration-500"></div>
        <p class="text-green-500 text-xs font-bold uppercase tracking-wider mb-1">Pendapatan</p>
        <h2 class="text-4xl font-bold text-white">Rp {{ formatJuta(stats.pendapatan) }}</h2>
      </div>

      <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 bg-orange-600/10 w-32 h-32 rounded-full group-hover:bg-orange-600/20 transition duration-500"></div>
        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-1">Perlu Diproses</p>
        <h2 class="text-4xl font-bold text-white">{{ stats.perlu_diproses }}</h2>
      </div>
    </div>

    <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 mb-8">
      <h3 class="text-xl font-bold mb-6">Statistik Pendapatan Tahun Ini</h3>
      <div class="h-80 w-full relative">
        <Bar v-if="!loading && chartData" :data="chartData" :options="chartOptions" />
        
        <div v-else class="flex items-center justify-center h-full text-gray-500">
            <div class="animate-spin h-6 w-6 border-2 border-indigo-500 rounded-full border-t-transparent mr-2"></div>
            Memuat Grafik...
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
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

// Registrasi modul Chart
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

// State Data
const stats = ref({
    total_alat: 0,
    pendapatan: 0,
    perlu_diproses: 0,
    grafik_per_bulan: [] 
});

const loading = ref(true);

// Format Angka Juta (Rp 15jt)
const formatJuta = (num) => {
    if (!num) return '0';
    if (num >= 1000000) return (num / 1000000).toFixed(1).replace('.0', '') + 'jt';
    return (num / 1000).toFixed(0) + 'rb';
};

// Data untuk Grafik
const chartData = computed(() => {
  return {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
    datasets: [
      {
        label: 'Pendapatan (Rp)',
        backgroundColor: '#6366f1', // Warna Ungu
        borderRadius: 6,
        data: stats.value.grafik_per_bulan // Data dari Backend
      }
    ]
  };
});

// Opsi Tampilan Grafik
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false }, // Sembunyikan legenda
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

// Load Data dari API
const loadDashboard = async () => {
    loading.value = true;
    try {
        const token = localStorage.getItem('admin_token');
        const res = await axios.get('/api/admin/dashboard', {
            headers: { Authorization: `Bearer ${token}` }
        });
        
        // Simpan data dari backend ke state
        stats.value = res.data; 
        
    } catch (e) {
        console.error("Gagal load dashboard", e);
    } finally {
        loading.value = false;
    }
};

onMounted(loadDashboard);
</script>