<template>
  <div class="min-h-screen flex flex-col font-sans text-slate-800 bg-white selection:bg-rose-500 selection:text-white">

    <header class="fixed top-0 w-full z-50 transition-all duration-300 bg-black/80 backdrop-blur-md border-b border-white/10">
      <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        
        <router-link to="/" class="flex items-center gap-3 group">
          <img 
            src="/images/logo1.png" 
            alt="Logo Kratak FC" 
            class="w-10 h-10 object-contain drop-shadow-[0_0_10px_rgba(255,255,255,0.5)] group-hover:rotate-12 transition duration-300" 
          />
          <span class="text-xl font-black italic tracking-tighter text-white uppercase group-hover:text-rose-500 transition">
            Kratak <span class="text-rose-600">FC</span>
          </span>
        </router-link>

        <nav class="hidden md:flex items-center bg-white/5 px-1 py-1 rounded-full border border-white/10">
          <router-link to="/" class="nav-link" active-class="active-link">Home</router-link>
          <router-link to="/katalog" class="nav-link" active-class="active-link">List Gear</router-link>
          <router-link to="/about" class="nav-link" active-class="active-link">Tentang</router-link>
          <a href="#faq" class="nav-link">FAQ</a>
        </nav>

        <div class="flex items-center gap-4">
          
          <router-link to="/keranjang" class="relative p-2 text-gray-400 hover:text-white transition">
            <i class="fas fa-shopping-cart text-xl"></i>
            <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-rose-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-black">
              {{ cartCount }}
            </span>
          </router-link>

          <template v-if="!isLoggedIn">
            <div class="flex items-center gap-3 border-l pl-4 border-gray-700">
              <router-link to="/login" class="text-sm font-bold text-gray-300 hover:text-white transition">
                LOGIN
              </router-link>
              <router-link to="/register" class="bg-rose-600 text-white text-sm font-bold px-6 py-2.5 rounded-full hover:bg-rose-700 hover:shadow-[0_0_15px_rgba(225,29,72,0.5)] transition uppercase tracking-wide">
                DAFTAR
              </router-link>
            </div>
          </template>

          <div v-else class="relative profile-box">
            <button @click="toggleDropdown" class="flex items-center gap-2 focus:outline-none">
              <img src="https://ui-avatars.com/api/?name=User&background=1f2937&color=fff" class="w-9 h-9 rounded-full border-2 border-rose-600 shadow-lg hover:scale-105 transition" />
            </button>

            <transition name="scale">
              <div v-if="profileMenu" class="absolute right-0 mt-4 w-56 bg-[#1a1a1a] rounded-xl shadow-2xl border border-gray-800 overflow-hidden z-50">
                <div class="px-5 py-4 bg-[#222] border-b border-gray-700">
                  <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Akun Member</p>
                </div>
                <router-link to="/profile" class="px-5 py-3 text-sm text-gray-300 hover:bg-rose-600 hover:text-white transition flex items-center gap-3">
                  <i class="fas fa-user"></i> Profil Saya
                </router-link>
                <router-link to="/riwayat" class="px-5 py-3 text-sm text-gray-300 hover:bg-rose-600 hover:text-white transition flex items-center gap-3">
                  <i class="fas fa-history"></i> Riwayat Sewa
                </router-link>
                <button @click="logout" class="w-full text-left px-5 py-3 text-sm text-red-500 hover:bg-red-900/30 transition border-t border-gray-700 flex items-center gap-3">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </button>
              </div>
            </transition>
          </div>

        </div>
      </div>
    </header>

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
          <a href="#faq" class="w-full sm:w-auto px-10 py-5 bg-transparent border-2 border-white/20 text-white rounded-full font-bold text-lg hover:bg-white hover:text-black transition duration-300 uppercase tracking-wide">
            Cara Sewa
          </a>
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
            <img src="https://images.unsplash.com/photo-1519892300165-cb5542fb47c7?auto=format&fit=crop&q=80" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 group-hover:scale-110 transition duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-white font-bold text-xl uppercase italic group-hover:text-rose-500 transition">Drum Kit</h3>
            </div>
          </router-link>

          <router-link to="/katalog?kategori=Keyboard" class="group relative h-40 rounded-2xl overflow-hidden cursor-pointer">
            <img src="https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&q=80" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 group-hover:scale-110 transition duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-white font-bold text-xl uppercase italic group-hover:text-rose-500 transition">Keyboard</h3>
            </div>
          </router-link>

          <router-link to="/katalog?kategori=Sound" class="group relative h-40 rounded-2xl overflow-hidden cursor-pointer">
            <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&q=80" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 group-hover:scale-110 transition duration-500" />
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
              <img :src="product.gambar.includes('http') ? product.gambar : `${api}/storage/${product.gambar}`"
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
                  <p class="font-bold text-lg">{{ product.harga_sewa }}</p>
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

    <section class="py-24 bg-black text-white relative overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-rose-600/20 rounded-full blur-[120px]"></div>
      <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-600/20 rounded-full blur-[100px]"></div>

      <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-2 gap-16 items-center">
          <div>
            <h2 class="text-4xl md:text-5xl font-black italic uppercase tracking-tighter mb-6">
              Kenapa Anak Band <br/> <span class="text-rose-500">Pilih Kratak FC?</span>
            </h2>
            <p class="text-gray-400 text-lg leading-relaxed mb-8">
              Kami paham kebutuhan lo. Sound harus nendang, alat harus prima, dan harga harus masuk akal. Gak pake ribet.
            </p>
            
            <ul class="space-y-6">
              <li class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center text-rose-500 text-xl shrink-0">
                  <i class="fas fa-check-double"></i>
                </div>
                <div>
                  <h4 class="font-bold text-xl">Alat Terawat & Bersih</h4>
                  <p class="text-gray-500 text-sm">Selalu dibersihkan dan dicek teknisi sebelum disewa.</p>
                </div>
              </li>
              <li class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center text-rose-500 text-xl shrink-0">
                  <i class="fas fa-truck-fast"></i>
                </div>
                <div>
                  <h4 class="font-bold text-xl">Siap Antar ke Venue</h4>
                  <p class="text-gray-500 text-sm">Fokus check sound aja, biar kami yang antar jemput alat.</p>
                </div>
              </li>
              <li class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center text-rose-500 text-xl shrink-0">
                  <i class="fas fa-bolt"></i>
                </div>
                <div>
                  <h4 class="font-bold text-xl">Fast Response 24 Jam</h4>
                  <p class="text-gray-500 text-sm">Admin kami juga anak band, jadi nyambung diajak ngobrol.</p>
                </div>
              </li>
            </ul>
          </div>

          <div class="relative">
            <div class="absolute inset-0 bg-rose-500 rounded-3xl rotate-6 opacity-20"></div>
            <img src="https://images.unsplash.com/photo-1574169208507-84376144848b?q=80&w=2079&auto=format&fit=crop" class="relative rounded-3xl shadow-2xl grayscale hover:grayscale-0 transition duration-500 border border-gray-700 w-full" />
            <div class="absolute -bottom-6 -left-6 bg-white text-black p-6 rounded-2xl shadow-xl max-w-xs hidden md:block">
              <div class="flex items-center gap-2 mb-2 text-yellow-500">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              </div>
              <p class="font-bold italic">"Gitar Fender-nya enak parah, settingan ceper. Panggung jadi makin pede!"</p>
              <p class="text-xs text-gray-500 mt-2 font-bold uppercase">- Arya, Vokalis</p>
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
          <p class="mb-6">
            Rental alat musik & sound system terpercaya di Jakarta. 
            Solusi kebutuhan panggung lo.
          </p>
          <div class="flex gap-4">
            <a href="#" class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center hover:bg-rose-600 hover:text-white transition"><i class="fab fa-instagram"></i></a>
            <a href="#" class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center hover:bg-green-600 hover:text-white transition"><i class="fab fa-whatsapp"></i></a>
          </div>
        </div>

        <div>
          <h4 class="text-white font-bold uppercase tracking-widest mb-6 border-b border-gray-800 pb-2 inline-block">Menu</h4>
          <ul class="space-y-3">
            <li><router-link to="/" class="hover:text-rose-500 transition">Beranda</router-link></li>
            <li><router-link to="/katalog" class="hover:text-rose-500 transition">List Gear</router-link></li>
            <li><router-link to="/about" class="hover:text-rose-500 transition">Siapa Kami</router-link></li>
            <li><router-link to="/faq" class="hover:text-rose-500 transition">Bantuan</router-link></li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-bold uppercase tracking-widest mb-6 border-b border-gray-800 pb-2 inline-block">Alat Populer</h4>
          <ul class="space-y-3">
            <li><router-link to="/katalog" class="hover:text-rose-500 transition">Gitar Elektrik</router-link></li>
            <li><router-link to="/katalog" class="hover:text-rose-500 transition">Drum Akustik</router-link></li>
            <li><router-link to="/katalog" class="hover:text-rose-500 transition">Bass Guitar</router-link></li>
            <li><router-link to="/katalog" class="hover:text-rose-500 transition">Amplifier</router-link></li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-bold uppercase tracking-widest mb-6 border-b border-gray-800 pb-2 inline-block">Markas</h4>
          <ul class="space-y-3">
            <li class="flex gap-3"><i class="fas fa-map-marker-alt mt-1 text-rose-600"></i> Jl. Musik No. 12, Jakarta</li>
            <li class="flex gap-3"><i class="fas fa-phone mt-1 text-rose-600"></i> +62 812 3456 7890</li>
            <li class="flex gap-3"><i class="fas fa-envelope mt-1 text-rose-600"></i> booking@kratakfc.com</li>
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
/* LOGIC TETAP SAMA, TIDAK ADA YANG DIUBAH AGAR FUNGSIONALITAS TETAP JALAN */
import { ref, onMounted, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";

const router = useRouter();
const route = useRoute();
const api = axios.defaults.baseURL;

const isLoggedIn = ref(false);
const cartCount = ref(0);
const profileMenu = ref(false);

const toggleDropdown = () => { profileMenu.value = !profileMenu.value; };

onMounted(() => {
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".profile-box")) profileMenu.value = false;
  });
  isLoggedIn.value = !!localStorage.getItem("buyer_token");
});

watch(() => route.fullPath, () => {
  isLoggedIn.value = !!localStorage.getItem("buyer_token");
  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  cartCount.value = cart.length;
});

const logout = async () => {
  const token = localStorage.getItem("buyer_token");
  try {
    await axios.post("/api/buyer/logout", {}, { headers: { Authorization: `Bearer ${token}` } });
  } catch (e) {}
  localStorage.removeItem("buyer_token");
  isLoggedIn.value = false;
  profileMenu.value = false;
  router.replace("/");
};

const randomProducts = ref([]);
const loadProducts = async () => {
  try {
    const res = await axios.get("/api/alat-band");
    randomProducts.value = [...res.data].sort(() => Math.random() - 0.5).slice(0, 4);
  } catch (e) {
    randomProducts.value = [
      { id: 1, nama_alat: 'Gitar Fender Stratocaster', kategori: 'Gitar', harga_sewa: 'Rp 150.000', gambar: 'default.jpg', status: 'Tersedia' },
      { id: 2, nama_alat: 'Drum Yamaha Stage Custom', kategori: 'Drum', harga_sewa: 'Rp 350.000', gambar: 'default.jpg', status: 'Disewa' },
      { id: 3, nama_alat: 'Bass Ibanez SR300', kategori: 'Bass', harga_sewa: 'Rp 175.000', gambar: 'default.jpg', status: 'Tersedia' },
      { id: 4, nama_alat: 'Korg Kronos 2', kategori: 'Keyboard', harga_sewa: 'Rp 500.000', gambar: 'default.jpg', status: 'Tersedia' },
    ];
  }
};

onMounted(() => {
  loadProducts();
  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  cartCount.value = cart.length;
});
</script>

<style scoped>
/* CUSTOM FONT & ANIMATION */
.nav-link {
  @apply px-5 py-2 text-sm font-bold text-gray-400 rounded-full hover:text-white transition-all duration-300 uppercase tracking-wide;
}
.active-link {
  @apply text-white bg-white/10 shadow-inner;
}
.scale-enter-active, .scale-leave-active { transition: all 0.2s ease; }
.scale-enter-from, .scale-leave-to { opacity: 0; transform: scale(0.95); }

/* ANIMASI MARQUEE (RUNNING TEXT) */
@keyframes marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
.animate-marquee {
  display: inline-block;
  animation: marquee 20s linear infinite;
}
</style>