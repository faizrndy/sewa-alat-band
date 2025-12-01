<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white pt-28 pb-12 font-sans">
    <div class="max-w-7xl mx-auto px-6">
      
      <div class="flex items-center gap-3 mb-8">
        <i class="fas fa-cart-shopping text-3xl text-rose-600"></i>
        <h1 class="text-3xl font-black italic uppercase tracking-tighter">
          Gear <span class="text-rose-600">Cart</span> 
          <span class="text-sm font-normal text-gray-500 ml-2">({{ cartItems.length }} Item)</span>
        </h1>
      </div>

      <div v-if="cartItems.length === 0" class="flex flex-col items-center justify-center py-20 bg-[#151515] rounded-3xl border border-gray-800 border-dashed">
        <i class="fas fa-guitar text-6xl text-gray-700 mb-4"></i>
        <h2 class="text-xl font-bold text-gray-400 mb-2 uppercase">Keranjang Kosong</h2>
        <router-link to="/katalog" class="mt-4 bg-rose-600 text-white px-8 py-3 rounded-full font-bold uppercase tracking-widest hover:bg-rose-700 transition shadow-[0_0_15px_rgba(225,29,72,0.4)]">
          Cari Gear
        </router-link>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-4">
          <div v-for="(item, index) in cartItems" :key="index" class="bg-[#151515] p-4 rounded-2xl border border-gray-800 flex gap-4 hover:border-gray-700 transition group relative overflow-hidden">
            
            <div class="w-24 h-24 bg-white rounded-xl flex-shrink-0 p-2">
              <img :src="getImgUrl(item.gambar)" class="w-full h-full object-contain" />
            </div>

            <div class="flex-1 flex flex-col justify-between">
              <div>
                <h3 class="font-black text-lg uppercase italic leading-none mb-1">{{ item.nama_alat }}</h3>
                <div class="text-xs text-gray-500 font-mono flex items-center gap-2">
                   <span>{{ item.tanggalMulai }}</span> 
                   <i class="fas fa-arrow-right text-[10px] text-rose-500"></i> 
                   <span>{{ item.tanggalSelesai }}</span>
                   <span class="bg-gray-800 px-2 py-0.5 rounded text-[10px] text-white">{{ hitungHari(item) }} Hari</span>
                </div>
              </div>

              <div class="flex items-end justify-between mt-2">
                <p class="text-rose-500 font-bold">Rp {{ Number(item.harga_sewa).toLocaleString() }} <span class="text-xs text-gray-500 font-normal">x {{ item.jumlah }} Unit</span></p>
                
                <button @click="hapusItem(index)" class="text-gray-600 hover:text-red-500 transition p-2">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>

          <button @click="hapusSemua" class="text-xs text-red-500 font-bold uppercase hover:underline">Hapus Semua</button>
        </div>

        <div class="lg:col-span-1">
          <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 sticky top-28">
            <h3 class="font-black text-xl uppercase italic mb-6">Ringkasan</h3>
            
            <div class="space-y-3 text-sm mb-6">
              <div class="flex justify-between text-gray-400">
                <span>Total Item</span>
                <span class="text-white font-bold">{{ cartItems.length }} Item</span>
              </div>
              <div class="flex justify-between text-gray-400">
                <span>Total Harga</span>
                <span class="text-rose-500 font-bold text-lg">Rp {{ grandTotal.toLocaleString() }}</span>
              </div>
            </div>

            <router-link to="/pembayaran" class="block w-full bg-white text-black text-center py-4 rounded-xl font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:shadow-[0_0_20px_rgba(225,29,72,0.5)]">
              Lanjut Pembayaran
            </router-link>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Swal from 'sweetalert2';

const cartItems = ref([]);

// --- 1. LOGIC PENGAMBILAN CART YANG BENAR ---
const getCartKey = () => {
    const token = localStorage.getItem("buyer_token");
    const userDataStr = localStorage.getItem("user_data");

    if (token && userDataStr) {
        const userData = JSON.parse(userDataStr);
        if (userData.id) {
            return `cart_${userData.id}`; // Ambil punya user (cart_15)
        }
    }
    return 'cart_guest'; // Fallback
};

const loadCart = () => {
    const key = getCartKey();
    cartItems.value = JSON.parse(localStorage.getItem(key) || '[]');
};

onMounted(() => {
    loadCart();
});
// --------------------------------------------

const getImgUrl = (path) => {
  if (!path) return 'https://placehold.co/150x150/1a1a1a/FFF?text=No+Image';
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

const grandTotal = computed(() => {
  return cartItems.value.reduce((total, item) => {
    const hari = hitungHari(item);
    return total + (hari * item.harga_sewa * item.jumlah);
  }, 0);
});

// --- UPDATE CART ---
const updateLocalStorage = () => {
    const key = getCartKey();
    localStorage.setItem(key, JSON.stringify(cartItems.value));
    window.dispatchEvent(new Event('cart-updated')); // Biar navbar ikut update
};

const hapusItem = (index) => {
    Swal.fire({
        title: 'Hapus?',
        text: "Yakin mau hapus gear ini?",
        icon: 'warning',
        showCancelButton: true,
        background: '#151515', color: '#fff',
        confirmButtonColor: '#e11d48',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            cartItems.value.splice(index, 1);
            updateLocalStorage();
            Swal.fire({ icon: 'success', title: 'Terhapus!', background: '#151515', color: '#fff', timer: 1000, showConfirmButton: false });
        }
    });
};

const hapusSemua = () => {
    cartItems.value = [];
    updateLocalStorage();
};
</script>