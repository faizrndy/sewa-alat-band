<template>
  <Navbar class="fixed top-0 left-0 w-full z-50" />

  <div class="min-h-screen bg-[#0a0a0a] text-white py-10 px-6 font-sans pt-28">
    <div class="max-w-7xl mx-auto">
      
      <div class="flex items-center gap-2 mb-8">
        <h1 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter">
           Gear <span class="text-rose-600">Cart</span>
        </h1>
        <span class="text-gray-500 text-sm font-mono mt-2">({{ cart.length }} ITEM)</span>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
          <div v-if="cart.length === 0" class="text-center py-20 bg-[#151515] rounded-3xl border border-gray-800 border-dashed">
             <p class="text-gray-500 text-xl font-bold">Keranjang Kosong 🎸</p>
             <router-link to="/katalog" class="text-rose-600 mt-4 inline-block font-bold hover:underline">Cari Gear Dulu</router-link>
          </div>

          <div v-for="(item, index) in cart" :key="item.id" class="bg-[#151515] p-4 rounded-2xl border border-gray-800 flex flex-col md:flex-row gap-6 relative group hover:border-rose-900/50 transition">
            
            <div class="w-32 h-32 bg-white rounded-xl overflow-hidden flex-shrink-0 mx-auto md:mx-0">
               <img :src="item.gambar" class="w-full h-full object-contain p-2" />
            </div>

            <div class="flex-1 text-center md:text-left">
               <h3 class="font-black text-xl italic uppercase">{{ item.nama_alat }}</h3>
               
               <div class="bg-gray-900 inline-block px-3 py-1 rounded-lg mt-2 border border-gray-700">
                  <span class="text-xs text-gray-400 font-mono">{{ tglMulai }} ➜ {{ tglSelesai }}</span>
                  <span class="ml-2 text-rose-500 font-bold text-xs bg-rose-900/20 px-2 py-0.5 rounded">{{ lamaSewa }} HARI</span>
               </div>

               <div class="mt-4 flex items-center justify-center md:justify-start gap-2">
                 <p class="text-rose-500 font-bold text-lg">Rp {{ item.harga_sewa.toLocaleString() }}</p>
                 <span class="text-gray-600 text-xs">x 1 Unit</span>
               </div>
            </div>

            <button @click="removeItem(index)" class="absolute top-4 right-4 text-gray-600 hover:text-red-500 transition">
               <i class="fas fa-trash"></i> Hapus
            </button>
          </div>
          
          <div v-if="cart.length > 0">
             <button @click="clearCart" class="text-red-500 text-sm font-bold hover:underline uppercase tracking-widest">Hapus Semua</button>
          </div>
        </div>

        <div class="lg:col-span-1" v-if="cart.length > 0">
           <div class="bg-[#151515] p-6 rounded-3xl border border-gray-800 sticky top-28">
              <h3 class="text-xl font-black italic uppercase tracking-wider mb-6 border-b border-gray-800 pb-4">Ringkasan</h3>
              
              <div class="flex justify-between mb-2 text-gray-400">
                 <span>Total Item</span>
                 <span class="text-white font-bold">{{ cart.length }} Item</span>
              </div>
              <div class="flex justify-between mb-4 text-gray-400">
                 <span>Durasi Sewa</span>
                 <span class="text-white font-bold">{{ lamaSewa }} Hari</span>
              </div>
              
              <div class="flex justify-between items-end mt-6">
                 <span class="text-lg font-bold text-white">Total Harga</span>
                 <span class="text-2xl font-black text-rose-600">Rp {{ totalHarga.toLocaleString() }}</span>
              </div>

              <router-link to="/pembayaran" class="block w-full bg-white text-black text-center py-4 rounded-xl font-black uppercase tracking-widest mt-6 hover:bg-rose-600 hover:text-white transition shadow-lg shadow-white/10 hover:shadow-rose-600/20">
                 Lanjut Pembayaran
              </router-link>
           </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import Navbar from "@/components/Navbar.vue";

const cart = ref([]);
const tglMulai = ref('');
const tglSelesai = ref('');
const lamaSewa = ref(1);

onMounted(() => {
   cart.value = JSON.parse(localStorage.getItem('keranjang')) || [];
   tglMulai.value = localStorage.getItem('sewa_tgl_mulai');
   tglSelesai.value = localStorage.getItem('sewa_tgl_selesai');

   if(tglMulai.value && tglSelesai.value) {
      const start = new Date(tglMulai.value);
      const end = new Date(tglSelesai.value);
      const diff = (end - start) / (1000 * 60 * 60 * 24);
      lamaSewa.value = diff > 0 ? diff : 1;
   }
});

const totalHarga = computed(() => {
   return cart.value.reduce((acc, item) => acc + (item.harga_sewa * lamaSewa.value), 0);
});

const removeItem = (index) => {
   cart.value.splice(index, 1);
   localStorage.setItem('keranjang', JSON.stringify(cart.value));
};

const clearCart = () => {
   cart.value = [];
   localStorage.removeItem('keranjang');
};
</script>