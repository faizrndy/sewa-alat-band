<template>
  <div class="min-h-screen flex flex-col font-sans text-slate-800 bg-white selection:bg-rose-500 selection:text-white">

    <Navbar />

    <section class="relative min-h-screen flex items-center justify-center pt-20 bg-black overflow-hidden">
      <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-40" />
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/80 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-black via-transparent to-black"></div>
      </div>

      <div class="relative z-10 max-w-5xl mx-auto px-6 text-center mt-10">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 backdrop-blur-md mb-8 animate-fade-in-down">
          <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
          <span class="text-gray-300 text-xs font-bold tracking-widest uppercase">Rental Alat Musik & Sound System #1</span>
        </div>

        <h1 class="text-5xl md:text-8xl font-black text-white leading-none tracking-tighter mb-6 drop-shadow-2xl">
          GUNCANG <br />
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-orange-500">PANGGUNG</span> KAMU
        </h1>
        
        <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed font-light">
          Sewa alat band profesional, sound system, dan lighting untuk gigs, latihan, atau event sekolah. 
          <span class="text-white font-medium">Kualitas studio, harga anak band.</span>
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-5">
          <router-link to="/katalog" class="w-full sm:w-auto px-10 py-5 bg-rose-600 text-white rounded-full font-black text-lg shadow-[0_0_20px_rgba(225,29,72,0.6)] hover:bg-rose-700 hover:scale-105 transition duration-300 uppercase tracking-wide flex items-center justify-center gap-2">
            <i class="fas fa-guitar"></i> Cari Gear
          </router-link>
          <router-link to="/faq" class="w-full sm:w-auto px-10 py-5 bg-transparent border-2 border-white/20 text-white rounded-full font-bold text-lg hover:bg-white hover:text-black transition duration-300 uppercase tracking-wide">
            Cara Sewa
          </router-link>
        </div>
      </div>

      <div class="absolute bottom-0 w-full overflow-hidden py-4 bg-rose-600/10 border-t border-rose-600/20 backdrop-blur-sm">
        <div class="whitespace-nowrap animate-marquee text-white/20 font-black text-4xl uppercase tracking-widest">
          GITAR • BASS • DRUM • KEYBOARD • SOUND SYSTEM • MICROPHONE • AMPLIFIER • LIGHTING •
          GITAR • BASS • DRUM • KEYBOARD • SOUND SYSTEM • MICROPHONE • AMPLIFIER • LIGHTING •
        </div>
      </div>
    </section>

    <section class="py-20 bg-[#0f0f0f] border-b border-gray-800">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <router-link to="/katalog?kategori=Gitar" class="group relative h-40 rounded-2xl overflow-hidden cursor-pointer">
              <img 
                src="https://images.unsplash.com/photo-1525201548942-d8732f6617a0?q=80&w=1000&auto=format&fit=crop" 
                class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 group-hover:scale-110 transition duration-500" 
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
              <div class="absolute bottom-4 left-4">
                <h3 class="text-white font-bold text-xl uppercase italic group-hover:text-rose-500 transition">Gitar & Bass</h3>
              </div>
            </router-link>

          <router-link to="/katalog?kategori=Drum" class="group relative h-40 rounded-2xl overflow-hidden cursor-pointer">
            <img src="https://images.unsplash.com/photo-1519892300165-cb5542fb47c7?q=80&w=1000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 group-hover:scale-110 transition duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-white font-bold text-xl uppercase italic group-hover:text-rose-500 transition">Drum Kit</h3>
            </div>
          </router-link>

          <router-link to="/katalog?kategori=Keyboard" class="group relative h-40 rounded-2xl overflow-hidden cursor-pointer">
            <img src="https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?q=80&w=1000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 group-hover:scale-110 transition duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-white font-bold text-xl uppercase italic group-hover:text-rose-500 transition">Keyboard</h3>
            </div>
          </router-link>

          <router-link to="/katalog?kategori=Sound" class="group relative h-40 rounded-2xl overflow-hidden cursor-pointer">
            <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=1000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 group-hover:scale-110 transition duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-white font-bold text-xl uppercase italic group-hover:text-rose-500 transition">Sound System</h3>
            </div>
          </router-link>
        </div>
      </div>
    </section>

    <section class="py-24 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
          <div>
            <h2 class="text-4xl font-black text-black italic uppercase tracking-tighter">
              Gear <span class="text-rose-600">Terpanas</span>
            </h2>
            <p class="text-gray-500 mt-2 font-medium">Alat yang paling sering disikat anak band minggu ini.</p>
          </div>
          <router-link to="/katalog" class="group flex items-center gap-2 text-black font-bold uppercase tracking-wider hover:text-rose-600 transition">
            Lihat Semua Gear <i class="fas fa-arrow-right group-hover:translate-x-2 transition"></i>
          </router-link>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="product in randomProducts" :key="product.id" class="group bg-gray-50 rounded-3xl p-4 transition hover:bg-black hover:text-white duration-300">
            <div class="relative h-60 rounded-2xl overflow-hidden bg-white mb-6 border border-gray-200 group-hover:border-gray-700">
              <img :src="getImgUrl(product.gambar)"
                @error="$event.target.src = 'https://placehold.co/400x400?text=No+Image'"
                class="w-full h-full object-contain p-4 group-hover:scale-110 transition duration-500" />
              
              <div class="absolute top-3 right-3">
                 <span v-if="product.status === 'Tersedia'" class="w-3 h-3 rounded-full bg-green-500 block shadow-[0_0_10px_#22c55e]"></span>
                 <span v-else class="w-3 h-3 rounded-full bg-red-500 block shadow-[0_0_10px_#ef4444]"></span>
              </div>
            </div>

            <div>
              <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 group-hover:text-rose-500">{{ product.kategori }}</div>
              <h3 class="text-xl font-black mb-3 leading-tight line-clamp-2">{{ product.nama_alat }}</h3>
              
              <div class="flex items-center justify-between border-t border-gray-200 group-hover:border-gray-700 pt-4 mt-2">
                <div>
                  <p class="font-bold text-lg">{{ Number(product.harga_sewa).toLocaleString() }}</p>
                  <p class="text-[10px] text-gray-400 uppercase font-bold">Per 24 Jam</p>
                </div>
                <router-link :to="`/katalog/${product.id}`" class="w-10 h-10 rounded-full bg-black text-white group-hover:bg-rose-600 flex items-center justify-center transition hover:rotate-90">
                  <i class="fas fa-plus"></i>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="bg-[#050505] text-gray-400 py-16 border-t border-gray-900 font-mono text-sm">
      <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-12">
        <div class="col-span-1 md:col-span-1">
          <div class="flex items-center gap-3 mb-6">
            <img src="/images/logo1.png" class="w-8 h-8 opacity-80 grayscale hover:grayscale-0 transition" />
            <span class="text-xl font-black text-white italic">KRATAK FC</span>
          </div>
          <p class="mb-6">Rental alat musik & sound system terpercaya di Jakarta.</p>
        </div>
        <div>
          <h4 class="text-white font-bold uppercase tracking-widest mb-6 border-b border-gray-800 pb-2 inline-block">Menu</h4>
          <ul class="space-y-3">
            <li><router-link to="/" class="hover:text-rose-500 transition">Beranda</router-link></li>
            <li><router-link to="/katalog" class="hover:text-rose-500 transition">List Gear</router-link></li>
          </ul>
        </div>
      </div>
      <div class="max-w-7xl mx-auto px-6 mt-16 pt-8 border-t border-gray-900 text-center text-xs">
        &copy; 2025 Kratak FC. Keep Rocking! 🤘
      </div>
    </footer>

  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Navbar from "@/components/Navbar.vue";

const randomProducts = ref([]);

// FIXED: Hapus '/storage' karena gambar ada di public biasa
const getImgUrl = (path) => {
  if (!path) return 'https://placehold.co/400x400?text=No+Image';
  if (path.startsWith('http')) return path;
  return `http://127.0.0.1:8000/${path}`; 
}

const loadProducts = async () => {
  try {
    const res = await axios.get("/api/alat-band");
    randomProducts.value = [...res.data].sort(() => Math.random() - 0.5).slice(0, 4);
  } catch (e) {
    console.error("Gagal load produk home", e);
  }
};

onMounted(() => {
  loadProducts();
});
</script>

<style scoped>
@keyframes marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
.animate-marquee {
  display: inline-block;
  animation: marquee 20s linear infinite;
}
</style>