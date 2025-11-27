<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white font-sans md:pl-64 p-8">
    <h1 class="text-3xl font-bold mb-8">Daftar Transaksi</h1>

    <div class="bg-[#151515] rounded-3xl border border-gray-800 overflow-hidden shadow-xl">
      <table class="w-full text-left">
        <thead class="bg-[#222] text-gray-400 uppercase text-xs font-bold">
          <tr>
            <th class="px-6 py-4">Kode TRX</th>
            <th class="px-6 py-4">Penyewa</th>
            <th class="px-6 py-4">Total</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Pembayaran</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
          <tr v-for="trx in transactions" :key="trx.id" class="hover:bg-white/5 transition">
            <td class="px-6 py-4 font-mono text-indigo-400 font-bold">
              #{{ trx.kode_transaksi }}
              <div class="text-xs text-gray-500 mt-1">{{ new Date(trx.created_at).toLocaleDateString() }}</div>
            </td>
            <td class="px-6 py-4">
              <p class="font-bold">{{ trx.nama }}</p>
              <p class="text-xs text-gray-500">{{ trx.telepon }}</p>
            </td>
            <td class="px-6 py-4 font-bold">Rp {{ Number(trx.total_bayar).toLocaleString() }}</td>
            <td class="px-6 py-4">
              <span :class="statusBadge(trx.status)" class="px-3 py-1 rounded-full text-xs font-bold uppercase">
                {{ trx.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-xs">
              <span v-if="trx.snap_token" class="text-green-500 flex items-center gap-1"><i class="fas fa-check-circle"></i> Midtrans</span>
              <span v-else class="text-gray-500">Manual/Belum</span>
            </td>
            <td class="px-6 py-4 text-right">
              <select 
                @change="updateStatus(trx.id, $event.target.value)" 
                class="bg-black border border-gray-700 text-white text-xs px-3 py-2 rounded-lg focus:outline-none focus:border-indigo-500"
              >
                <option value="" disabled selected>Ubah Status</option>
                <option value="pending">⏳ Pending</option>
                <option value="success">✅ Success (Lunas)</option>
                <option value="failed">❌ Failed (Batal)</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="loading" class="text-center py-10"><div class="animate-spin h-8 w-8 border-2 border-indigo-600 rounded-full border-t-transparent mx-auto"></div></div>
      <div v-if="!loading && transactions.length === 0" class="text-center py-10 text-gray-500">Belum ada transaksi masuk.</div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const transactions = ref([]);
const loading = ref(true);

// Helper Warna Badge
const statusBadge = (status) => {
  if (status === 'success') return 'bg-green-500/20 text-green-500';
  if (status === 'pending') return 'bg-yellow-500/20 text-yellow-500';
  return 'bg-red-500/20 text-red-500';
};

// 1. Ambil Semua Data
const getTransactions = async () => {
  loading.value = true;
  try {
    // Pastikan backend punya endpoint ini (lihat catatan di bawah)
    const res = await axios.get('/api/admin/transaksi'); 
    transactions.value = res.data.data;
  } catch (e) {
    console.error("Gagal load transaksi:", e);
  } finally {
    loading.value = false;
  }
};

// 2. Update Status
const updateStatus = async (id, newStatus) => {
  try {
    await axios.patch(`/api/admin/transaksi/${id}/status`, { status: newStatus });
    
    Swal.fire({
      icon: 'success', 
      title: 'Status Diubah!', 
      toast: true, 
      position: 'top-end', 
      showConfirmButton: false, 
      timer: 2000, 
      background: '#111', color: '#fff' 
    });
    
    getTransactions(); // Refresh data
  } catch (e) {
    Swal.fire('Gagal', 'Tidak bisa mengubah status', 'error');
  }
};

onMounted(getTransactions);
</script>