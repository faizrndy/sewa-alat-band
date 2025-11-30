<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white pt-28 pb-12 font-sans">
    <div class="max-w-6xl mx-auto px-6">
      <h1 class="text-3xl font-black italic uppercase tracking-tighter mb-8 flex items-center gap-3">
        🛒 Gear <span class="text-rose-600">Cart</span> 
        <span class="text-lg font-normal text-gray-500 not-italic tracking-normal">({{ cart.length }} item)</span>
      </h1>

      <div v-if="cart.length === 0" class="text-center bg-[#151515] rounded-3xl p-16 border border-gray-800 border-dashed">
        <div class="text-6xl mb-4 opacity-50">🎸</div>
        <h3 class="text-xl font-bold text-gray-300 uppercase">Keranjang Kosong</h3>
        <p v-if="!isLoggedIn" class="text-gray-500 mt-2 text-sm">Kamu belum login. Login dulu biar data tersimpan.</p>
        
        <div class="mt-6 flex justify-center gap-4">
            <router-link to="/katalog" class="bg-rose-600 text-white px-8 py-3 rounded-full font-bold uppercase tracking-wider hover:bg-rose-700 transition shadow-[0_0_15px_rgba(225,29,72,0.4)]">
            Cari Gear
            </router-link>
            <router-link v-if="!isLoggedIn" to="/login" class="border border-gray-600 text-white px-8 py-3 rounded-full font-bold uppercase tracking-wider hover:bg-gray-800 transition">
            Login Dulu
            </router-link>
        </div>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-4">
          <div v-for="(item, index) in cart" :key="item.id" class="bg-[#151515] p-4 rounded-2xl border border-gray-800 flex gap-4 items-center group hover:border-rose-600/30 transition">
            <div class="w-24 h-24 flex-shrink-0 bg-black rounded-xl overflow-hidden border border-gray-800">
               <img :src="getImgUrl(item.gambar)" class="w-full h-full object-contain opacity-80 group-hover:opacity-100 transition" />
            </div>
            <div class="flex-1">
              <h3 class="font-black text-white text-lg uppercase italic">{{ item.nama_alat }}</h3>
              <p class="text-xs text-gray-500 mt-1 font-mono">
                {{ item.tanggalMulai }} <span class="text-rose-500">➜</span> {{ item.tanggalSelesai }} 
                <span class="bg-gray-800 text-gray-300 px-2 py-0.5 rounded ml-2 font-bold text-[10px]">{{ hitungHari(item) }} HARI</span>
              </p>
              <div class="mt-2 flex items-center justify-between">
                <p class="text-sm text-gray-400">Rp {{ Number(item.harga_sewa).toLocaleString() }} x {{ item.jumlah }} unit</p>
                <p class="font-bold text-rose-500">Rp {{ hitungSubtotal(item).toLocaleString() }}</p>
              </div>
            </div>
            <button @click="hapusItem(index)" class="p-3 text-gray-600 hover:text-red-500 hover:bg-red-900/20 rounded-xl transition"><i class="fas fa-trash"></i></button>
          </div>
          
          <button @click="kosongkanKeranjang" class="text-red-500 text-xs font-bold uppercase hover:text-red-400 mt-4 pl-2 tracking-widest">Hapus Semua</button>
        </div>

        <div class="lg:col-span-1">
          <div class="bg-[#151515] rounded-3xl border border-gray-800 p-6 sticky top-28">
            <h3 class="font-bold text-white text-lg mb-6 uppercase tracking-widest border-b border-gray-800 pb-4">Ringkasan</h3>
            <div class="space-y-3 mb-6">
               <div class="flex justify-between text-gray-400 text-sm"><span>Total Item</span><span>{{ cart.length }} Item</span></div>
               <div class="flex justify-between text-xl font-black text-white border-t border-gray-800 pt-4"><span>Total</span><span class="text-rose-500">Rp {{ totalSemua.toLocaleString() }}</span></div>
            </div>
            <button @click="checkout" class="w-full bg-white text-black py-4 rounded-xl font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white hover:shadow-[0_0_20px_rgba(225,29,72,0.5)] transition-all transform hover:-translate-y-1">Lanjut Pembayaran</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Navbar from "@/components/Navbar.vue"

const cart = ref([])
const isLoggedIn = ref(false)
const router = useRouter()

onMounted(() => {
  const token = localStorage.getItem("buyer_token");
  isLoggedIn.value = !!token;

  if (!token) {
    // 👇 LOGIKA PENTING: JIKA GUEST, KERANJANG KOSONG
    cart.value = []; 
    // Optional: localStorage.removeItem('cart'); 
  } else {
    // JIKA MEMBER, AMBIL DATA
    const storedCart = JSON.parse(localStorage.getItem('cart') || '[]');
    cart.value = storedCart;
  }
})

const getImgUrl = (path) => {
  if (!path) return 'https://placehold.co/400x400/1a1a1a/FFF?text=No+Image';
  if (path.startsWith('http')) return path;
  return `http://127.0.0.1:8000/${path}`;
}

const hitungHari = (item) => {
  if (!item.tanggalMulai || !item.tanggalSelesai) return 0;
  const start = new Date(item.tanggalMulai);
  const end = new Date(item.tanggalSelesai);
  return Math.max(0, (end - start) / (1000 * 60 * 60 * 24));
}

const hitungSubtotal = (item) => item ? hitungHari(item) * item.harga_sewa * item.jumlah : 0;
const totalSemua = computed(() => cart.value.reduce((total, item) => total + hitungSubtotal(item), 0));

const hapusItem = (index) => {
  cart.value.splice(index, 1);
  localStorage.setItem('cart', JSON.stringify(cart.value));
  window.dispatchEvent(new Event('cart-updated'));
}

const kosongkanKeranjang = () => {
  if (confirm('Yakin?')) {
    cart.value = [];
    localStorage.removeItem('cart');
    window.dispatchEvent(new Event('cart-updated'));
  }
}

const checkout = () => {
  if (cart.value.length === 0) return;
  localStorage.setItem("checkout_cart", JSON.stringify(cart.value));
  localStorage.setItem("checkout_total", totalSemua.value);
  router.push("/pembayaran");
}
</script>