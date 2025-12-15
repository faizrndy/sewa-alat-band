<template>
  <div>
    <h1 class="text-3xl font-bold mb-8">Daftar Transaksi</h1>

    <div class="bg-[#151515] rounded-3xl border border-gray-800 overflow-hidden shadow-xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-[#222] text-gray-400 uppercase text-xs font-bold">
            <tr>
              <th class="px-6 py-4">Kode TRX</th>
              <th class="px-6 py-4">Penyewa</th>
              <th class="px-6 py-4">Total</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4">Pembayaran</th>
              <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
            <tr v-for="trx in transactions" :key="trx.id" class="hover:bg-white/5 transition">
              <td class="px-6 py-4 font-mono text-indigo-400 font-bold whitespace-nowrap">
                #{{ trx.kode_transaksi }}
                <div class="text-xs text-gray-500 mt-1 font-sans">
                  {{ new Date(trx.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                </div>
              </td>

              <td class="px-6 py-4">
                <p class="font-bold text-white">{{ trx.nama }}</p>
                <p class="text-xs text-gray-500">{{ trx.telepon }}</p>
              </td>

              <td class="px-6 py-4 font-bold text-rose-500 whitespace-nowrap">
                Rp {{ Number(trx.total_bayar).toLocaleString() }}
              </td>

              <td class="px-6 py-4">
                <span :class="statusBadge(trx.status)" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                  {{ trx.status }}
                </span>
              </td>

              <td class="px-6 py-4 text-xs font-bold">
                <span v-if="trx.snap_token" class="text-green-500 flex items-center gap-1">
                  Midtrans
                </span>
                <span v-else class="text-gray-500 flex items-center gap-1">
                  Manual
                </span>
              </td>

              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                  
                  <select 
                    @change="updateStatus(trx.id, $event.target.value)" 
                    class="bg-black border border-gray-700 text-white text-xs px-2 py-2 rounded-lg focus:outline-none focus:border-indigo-500 cursor-pointer"
                    :value="trx.status"
                  >
                    <option value="pending">⏳ Pending</option>
                    <option value="success">✅ Success</option>
                    <option value="failed">❌ Failed</option>
                    <option value="cancelled">🚫 Cancelled</option>
                  </select>

                  <router-link 
                    :to="{ name: 'admin.transaksi.invoice', params: { kode: trx.kode_transaksi } }" 
                    target="_blank"
                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition shadow-lg flex items-center gap-1 text-xs font-bold"
                    title="Cetak Kwitansi"
                  >
                    🖨️
                  </router-link>

                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin h-8 w-8 border-2 border-indigo-600 rounded-full border-t-transparent mx-auto"></div>
        <p class="text-gray-500 text-xs mt-2">Memuat data...</p>
      </div>

      <div v-if="!loading && transactions.length === 0" class="text-center py-12 text-gray-500">
        Belum ada transaksi masuk.
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const transactions = ref([]);
const loading = ref(true);

const statusBadge = (status) => {
  if (['success', 'paid', 'settlement'].includes(status)) return 'bg-green-500/10 text-green-500 border border-green-500/20';
  if (status === 'pending') return 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20';
  if (['failed', 'cancelled', 'expired'].includes(status)) return 'bg-red-500/10 text-red-500 border border-red-500/20';
  return 'bg-gray-500/10 text-gray-500 border border-gray-500/20';
};

const getTransactions = async () => {
  loading.value = true;
  try {
    const token = localStorage.getItem('admin_token');
    const res = await axios.get('/api/admin/transaksi', {
        headers: { Authorization: `Bearer ${token}` }
    }); 
    transactions.value = res.data || []; 
  } catch (e) {
    console.error("Gagal load transaksi:", e);
    transactions.value = [];
  } finally {
    loading.value = false;
  }
};

const updateStatus = async (id, newStatus) => {
  try {
    const token = localStorage.getItem('admin_token');
    await axios.patch(`/api/admin/transaksi/${id}/status`, 
        { status: newStatus }, 
        { headers: { Authorization: `Bearer ${token}` } }
    );

    const Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
      background: '#151515', color: '#fff', iconColor: '#22c55e'
    });
    Toast.fire({ icon: 'success', title: 'Status diperbarui!' });
    
    getTransactions(); 
  } catch (e) {
    Swal.fire({
        icon: 'error', 
        title: 'Gagal', 
        text: 'Gagal update status', 
        background: '#111', color: '#fff'
    });
  }
};

onMounted(getTransactions);
</script>