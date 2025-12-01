<template>
  <header class="fixed top-0 w-full z-50 bg-black/95 backdrop-blur-md border-b border-white/10 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
      
      <div class="flex items-center gap-3 z-50">
        <div class="md:hidden">
          <router-link v-if="isHome" to="/" class="flex items-center gap-2 group">
            <img src="/images/logo1.png" class="w-8 h-8 object-contain" />
            <span class="text-lg font-black italic tracking-tighter text-white uppercase">Kratak<span class="text-rose-600">FC</span></span>
          </router-link>
          <button v-else @click="goBack" class="flex items-center gap-2 text-gray-300 hover:text-white transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            <span class="text-sm font-bold uppercase tracking-widest">Kembali</span>
          </button>
        </div>
        <router-link to="/" class="hidden md:flex items-center gap-3 group">
          <img src="/images/logo1.png" class="w-10 h-10 object-contain drop-shadow group-hover:rotate-12 transition duration-300" />
          <span class="text-xl font-black italic tracking-tighter text-white uppercase group-hover:text-rose-500 transition">Kratak <span class="text-rose-600">FC</span></span>
        </router-link>
      </div>

      <nav class="hidden md:flex items-center bg-white/5 px-1 py-1 rounded-full border border-white/10">
        <router-link to="/" class="nav-link" active-class="active-link">Home</router-link>
        <router-link to="/katalog" class="nav-link" active-class="active-link">List Gear</router-link>
        <router-link to="/about" class="nav-link" active-class="active-link">Tentang</router-link>
        <router-link to="/faq" class="nav-link" active-class="active-link">FAQ</router-link>
      </nav>

      <div class="flex items-center gap-3 md:gap-4 z-50">
        
        <router-link to="/keranjang" class="relative group p-2">
          <div class="text-gray-300 group-hover:text-white transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 md:w-7 md:h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
          </div>
          <span v-if="cartCount > 0" class="absolute top-0 right-0 bg-rose-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-black shadow-sm transform scale-100 group-hover:scale-110 transition">
            {{ cartCount }}
          </span>
        </router-link>

        <div class="hidden md:block">
          <template v-if="!isLoggedIn">
            <div class="flex items-center gap-3 border-l pl-4 border-gray-700">
              <router-link to="/login" class="text-sm font-bold text-gray-300 hover:text-white transition uppercase">LOGIN</router-link>
              <router-link to="/register" class="bg-rose-600 text-white text-sm font-bold px-6 py-2.5 rounded-full hover:bg-rose-700 transition uppercase">DAFTAR</router-link>
            </div>
          </template>
          <template v-else>
            <div class="relative profile-box pl-4 border-l border-gray-700">
              <button @click="toggleDropdown" class="flex items-center gap-2 focus:outline-none">
                <img :src="`https://ui-avatars.com/api/?name=${userName}&background=1f2937&color=fff`" class="w-10 h-10 rounded-full border-2 border-rose-600 shadow-lg hover:scale-105 transition" />
              </button>
              <transition name="scale">
                <div v-if="profileMenu" class="absolute right-0 mt-4 w-56 bg-[#1a1a1a] rounded-xl shadow-2xl border border-gray-800 overflow-hidden z-[60]">
                  <div class="px-5 py-4 bg-[#222] border-b border-gray-700"><p class="text-xs text-gray-400 uppercase font-bold">Halo, {{ userName }}</p></div>
                  <router-link to="/profile" class="block px-5 py-3 text-sm text-gray-300 hover:bg-rose-600 hover:text-white transition">Profil Saya</router-link>
                  <router-link to="/riwayat" class="block px-5 py-3 text-sm text-gray-300 hover:bg-rose-600 hover:text-white transition">Riwayat Sewa</router-link>
                  <button @click="logout" class="w-full text-left px-5 py-3 text-sm text-red-500 hover:bg-red-900/30 transition border-t border-gray-700">Logout</button>
                </div>
              </transition>
            </div>
          </template>
        </div>

        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white p-1 focus:outline-none ml-1">
          <svg v-if="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
        </button>

      </div>
    </div>

    <transition name="slide-fade">
      <div v-if="mobileMenuOpen" class="md:hidden absolute top-20 left-0 w-full bg-[#111] border-b border-gray-800 shadow-2xl p-6 flex flex-col gap-4 max-h-[80vh] overflow-y-auto">
        <router-link to="/" @click="mobileMenuOpen = false" class="mobile-link">Home</router-link>
        <router-link to="/katalog" @click="mobileMenuOpen = false" class="mobile-link">List Gear</router-link>
        <router-link to="/about" @click="mobileMenuOpen = false" class="mobile-link">Tentang</router-link>
        <router-link to="/faq" @click="mobileMenuOpen = false" class="mobile-link">FAQ</router-link>
        <div class="border-t border-gray-800 my-2"></div>
        <template v-if="!isLoggedIn">
           <router-link to="/login" @click="mobileMenuOpen = false" class="w-full text-center py-3 rounded-xl border border-gray-700 text-white font-bold uppercase">Login</router-link>
           <router-link to="/register" @click="mobileMenuOpen = false" class="w-full text-center py-3 rounded-xl bg-rose-600 text-white font-bold uppercase">Daftar</router-link>
        </template>
        <template v-else>
           <div class="flex items-center gap-3 mb-2">
              <img :src="`https://ui-avatars.com/api/?name=${userName}&background=1f2937&color=fff`" class="w-10 h-10 rounded-full border border-rose-600" />
              <div><p class="text-white font-bold">{{ userName }}</p><p class="text-xs text-gray-500">Member Aktif</p></div>
           </div>
           <router-link to="/profile" @click="mobileMenuOpen = false" class="mobile-sublink">Profil Saya</router-link>
           <router-link to="/riwayat" @click="mobileMenuOpen = false" class="mobile-sublink">Riwayat Sewa</router-link>
           <button @click="logout" class="w-full text-left py-2 text-red-500 font-bold uppercase text-sm mt-2">Logout</button>
        </template>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";

const router = useRouter();
const route = useRoute();
const isLoggedIn = ref(false);
const cartCount = ref(0);
const profileMenu = ref(false);
const mobileMenuOpen = ref(false);
const userName = ref("User");

const toggleDropdown = () => { profileMenu.value = !profileMenu.value; };
const isHome = computed(() => route.path === '/');
const goBack = () => { router.go(-1); };

// --- LOGIC KERANJANG CERDAS (ID USER) ---
const updateState = () => {
  const token = localStorage.getItem("buyer_token");
  const userDataStr = localStorage.getItem("user_data");
  
  isLoggedIn.value = !!token;

  let cartKey = 'cart_guest'; // Default Tamu

  if (token && userDataStr) {
      const userData = JSON.parse(userDataStr);
      if (userData.id) {
          cartKey = `cart_${userData.id}`; // Keranjang Spesifik User
          userName.value = userData.name || "Member";
      }
  }

  // Ambil Data Keranjang
  const cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
  cartCount.value = cart.length;
};

onMounted(() => {
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".profile-box")) profileMenu.value = false;
  });
  updateState(); 
  window.addEventListener('cart-updated', updateState);
});

watch(() => route.fullPath, () => {
    updateState();
    mobileMenuOpen.value = false;
});

const logout = async () => {
  try {
    const token = localStorage.getItem("buyer_token");
    if(token) await axios.post("/api/buyer/logout", {}, { headers: { Authorization: `Bearer ${token}` } });
  } catch (e) {}
  
  // HAPUS SESI TAPI JANGAN HAPUS KERANJANG USER
  localStorage.removeItem("buyer_token");
  localStorage.removeItem("user_data");
  
  updateState(); 
  profileMenu.value = false;
  mobileMenuOpen.value = false;
  router.push("/");
};
</script>

<style scoped>
.nav-link { @apply px-5 py-2 text-sm font-bold text-gray-400 rounded-full hover:text-white transition-all duration-300 uppercase tracking-wide; }
.active-link { @apply text-white bg-white/10 shadow-inner; }
.mobile-link { @apply text-lg font-black text-gray-300 hover:text-white hover:pl-2 transition-all duration-300 block uppercase italic; }
.mobile-sublink { @apply text-sm font-bold text-gray-400 hover:text-rose-500 py-2 block border-l-2 border-gray-800 pl-4 hover:border-rose-600 transition-all; }
.scale-enter-active, .scale-leave-active { transition: all 0.2s ease; }
.scale-enter-from, .scale-leave-to { opacity: 0; transform: scale(0.95); }
.slide-fade-enter-active { transition: all 0.3s ease-out; }
.slide-fade-leave-active { transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1); }
.slide-fade-enter-from, .slide-fade-leave-to { transform: translateY(-20px); opacity: 0; }
</style>