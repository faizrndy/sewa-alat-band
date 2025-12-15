<template>
    <div class="min-h-screen bg-[#0a0a0a] p-4 md:p-8 flex justify-center items-start print:bg-white print:p-0 print:m-0">
        
        <div class="w-full max-w-[210mm] bg-white text-black p-8 md:p-12 rounded-xl shadow-2xl print:shadow-none print:w-full print:max-w-none print:rounded-none relative overflow-hidden">
            
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03]">
                <h1 class="text-[150px] font-black -rotate-45 uppercase">KRATAK FC</h1>
            </div>

            <div class="flex justify-between items-start border-b-4 border-gray-800 pb-6 mb-8 relative z-10">
                <div>
                    <h1 class="text-4xl font-black italic uppercase tracking-tighter text-rose-600 print:text-black">KRATAK FC</h1>
                    <p class="text-sm font-bold text-gray-700 mt-2 tracking-wide">RENTAL ALAT MUSIK & STUDIO</p>
                    <div class="text-xs text-gray-500 mt-2 space-y-1">
                        <p>📍 Jl. Melodi Rock No. 123, Surakarta</p>
                        <p>📞 WhatsApp: 0812-3456-7890</p>
                        <p>🌐 www.kratakfc.com</p>
                    </div>
                </div>

                <div class="text-right">
                    <h2 class="text-3xl font-black uppercase text-gray-800 tracking-widest">INVOICE</h2>
                    <p class="text-sm font-mono text-gray-500 mt-1 font-bold">#{{ transaksi.kode_transaksi }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ formatDate(new Date()) }}</p>

                    <div class="mt-4 flex justify-end">
                        <div v-if="['success', 'paid', 'settlement'].includes(transaksi.status)" 
                             class="border-[3px] border-green-600 text-green-600 px-4 py-1 text-sm font-black uppercase tracking-widest rounded transform -rotate-12 opacity-80">
                            LUNAS / PAID
                        </div>
                        <div v-else-if="transaksi.status === 'pending'" 
                             class="border-[3px] border-yellow-600 text-yellow-600 px-4 py-1 text-sm font-black uppercase tracking-widest rounded transform -rotate-12 opacity-80">
                            BELUM LUNAS
                        </div>
                        <div v-else 
                             class="border-[3px] border-red-600 text-red-600 px-4 py-1 text-sm font-black uppercase tracking-widest rounded transform -rotate-12 opacity-80">
                            CANCELLED
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10 mb-8 relative z-10 text-sm">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">TAGIHAN KEPADA:</p>
                    <p class="font-bold text-xl uppercase">{{ transaksi.nama }}</p>
                    <p class="text-gray-600">{{ transaksi.telepon }}</p>
                    <p class="text-gray-600 mt-1 w-3/4 leading-relaxed">{{ transaksi.alamat }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">PERIODE SEWA:</p>
                    <div class="bg-gray-100 p-3 rounded-lg inline-block text-right min-w-[200px]">
                        <p class="text-gray-600"><span class="font-bold text-black">Mulai:</span> {{ formatDate(transaksi.tgl_mulai) }}</p>
                        <p class="text-gray-600"><span class="font-bold text-black">Selesai:</span> {{ formatDate(transaksi.tgl_selesai) }}</p>
                        <div class="border-t border-gray-300 my-2"></div>
                        <p class="font-bold text-rose-600 print:text-black">Durasi: {{ transaksi.lama_hari }} Hari</p>
                    </div>
                </div>
            </div>

            <div class="mb-8 relative z-10">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-800 text-white text-xs uppercase tracking-wider print:bg-gray-200 print:text-black">
                            <th class="p-4 text-left rounded-l-lg print:rounded-none">Deskripsi Alat</th>
                            <th class="p-4 text-center">Harga / Hari</th>
                            <th class="p-4 text-center">Qty</th>
                            <th class="p-4 text-center">Durasi</th>
                            <th class="p-4 text-right rounded-r-lg print:rounded-none">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        <tr v-for="(item, index) in transaksi.items" :key="index" class="border-b border-gray-100">
                            <td class="p-4 font-bold">{{ item.nama_alat }}</td>
                            <td class="p-4 text-center">Rp {{ formatUang(item.harga_sewa) }}</td>
                            <td class="p-4 text-center font-bold">{{ item.jumlah }}</td>
                            <td class="p-4 text-center">{{ transaksi.lama_hari }} Hari</td>
                            <td class="p-4 text-right font-bold">
                                Rp {{ formatUang(item.harga_sewa * item.jumlah * transaksi.lama_hari) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mb-16 relative z-10">
                <div class="w-1/2 md:w-1/3 space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal Sewa</span>
                        <span class="font-bold">Rp {{ formatUang(transaksi.total_sewa) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Ongkos Kirim</span>
                        <span class="font-bold">Rp {{ formatUang(transaksi.tarif_antar) }}</span>
                    </div>
                    <div class="border-t-2 border-gray-800 my-2"></div>
                    <div class="flex justify-between text-lg">
                        <span class="font-black uppercase">Grand Total</span>
                        <span class="font-black text-rose-600 print:text-black bg-rose-50 print:bg-transparent px-2 rounded">
                            Rp {{ formatUang(transaksi.total_bayar) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-end text-center text-xs text-gray-500 relative z-10 page-break-inside-avoid">
                <div class="text-left w-1/3">
                    <p class="mb-16 font-bold">Penyewa,</p>
                    <div class="border-b border-gray-400 w-full mb-1"></div>
                    <p class="uppercase font-bold text-gray-800">{{ transaksi.nama }}</p>
                </div>
                
                <div class="text-center w-1/3">
                    </div>

                <div class="text-right w-1/3">
                    <p class="mb-2">Jakarta, {{ formatDate(new Date()) }}</p>
                    <p class="mb-16 font-bold">Hormat Kami,</p>
                    <div class="border-b border-gray-400 w-full mb-1"></div>
                    <p class="font-bold text-gray-800 uppercase">Admin Kratak FC</p>
                </div>
            </div>

            <div class="mt-12 pt-4 border-t border-gray-200 text-center text-[10px] text-gray-400 italic">
                * Barang yang disewa menjadi tanggung jawab penyewa sepenuhnya selama masa sewa.
                <br>Kerusakan atau kehilangan akan dikenakan denda sesuai harga pasar alat.
            </div>

        </div>

        <div class="fixed bottom-8 right-8 flex flex-col gap-3 print:hidden z-50">
            <button @click="printInvoice" class="bg-rose-600 hover:bg-rose-700 text-white p-4 rounded-full shadow-2xl transition transform hover:scale-110 flex items-center gap-2 group">
                <span class="text-xl">🖨️</span> 
                <span class="font-bold text-sm hidden group-hover:inline">Cetak Sekarang</span>
            </button>
            <button @click="$router.go(-1)" class="bg-gray-700 hover:bg-gray-800 text-white p-4 rounded-full shadow-2xl transition transform hover:scale-110 group flex items-center gap-2">
                <span class="text-xl">⬅️</span>
                <span class="font-bold text-sm hidden group-hover:inline">Kembali</span>
            </button>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
// Inisialisasi struktur data kosong agar tidak error saat load awal
const transaksi = ref({
    kode_transaksi: 'LOADING...',
    nama: '-',
    telepon: '-',
    alamat: '-',
    items: [],
    tgl_mulai: null,
    tgl_selesai: null,
    lama_hari: 0,
    total_sewa: 0,
    tarif_antar: 0,
    total_bayar: 0,
    status: 'pending'
});

// Helper: Format Uang
const formatUang = (angka) => {
    return Number(angka || 0).toLocaleString('id-ID');
};

// Helper: Format Tanggal Indonesia
const formatDate = (dateString) => {
    if(!dateString) return '-';
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

// Fungsi Ambil Data
const getDetailTransaksi = async () => {
    try {
        const token = localStorage.getItem('admin_token');
        
        // Panggil API search berdasarkan Kode TRX dari URL
        const res = await axios.get(`http://127.0.0.1:8000/api/admin/transaksi?search=${route.params.kode}`, {
            headers: { Authorization: `Bearer ${token}` }
        });
        
        // Ambil data pertama dari hasil pencarian
        if(res.data.data && res.data.data.length > 0) {
             transaksi.value = res.data.data[0];
        } else if (res.data.length > 0) {
             transaksi.value = res.data[0]; // jaga-jaga struktur beda
        } else {
             alert("Data transaksi tidak ditemukan!");
        }

    } catch (err) {
        console.error("Gagal load invoice", err);
    }
};

const printInvoice = () => {
    window.print();
};

onMounted(() => {
    getDetailTransaksi();
    // Opsional: Langsung popup print saat halaman dimuat
    // setTimeout(() => window.print(), 1000); 
});
</script>

<style scoped>
/* CSS KHUSUS PRINT */
@media print {
    @page {
        margin: 0;
        size: auto; /* A4 */
    }
    body {
        background: white !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .print\:hidden {
        display: none !important;
    }
    .print\:bg-white {
        background-color: white !important;
    }
    .print\:p-0 {
        padding: 0 !important;
    }
    .print\:m-0 {
        margin: 0 !important;
    }
    .print\:text-black {
        color: black !important;
    }
    .print\:shadow-none {
        box-shadow: none !important;
    }
    .print\:w-full {
        width: 100% !important;
    }
    .print\:max-w-none {
        max-width: none !important;
    }
    .print\:rounded-none {
        border-radius: 0 !important;
    }
    .print\:bg-gray-200 {
        background-color: #e5e7eb !important;
    }
    .print\:bg-transparent {
        background-color: transparent !important;
    }
    
    /* Memastikan page break tidak memotong elemen penting */
    .page-break-inside-avoid {
        page-break-inside: avoid;
    }
}
</style>