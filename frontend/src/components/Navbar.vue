<template>
  <header class="fixed top-0 w-full z-50 bg-black/95 backdrop-blur-md border-b border-white/10 transition-all duration-300 shadow-2xl">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
      
      <div class="flex items-center gap-3 z-50">
        <div class="md:hidden">
          <router-link v-if="isHome" to="/" class="flex items-center gap-2 group">
            <img src="/images/logo1.png" class="w-8 h-8 object-contain" />
            <span class="text-lg font-black italic tracking-tighter text-white uppercase">Kratak<span class="text-rose-600">FC</span></span>
          </router-link>
          <button v-else @click="goBack" class="flex items-center gap-2 text-gray-300 hover:text-white transition">
            <i class="fas fa-arrow-left"></i>
            <span class="text-sm font-bold uppercase tracking-widest">Kembali</span>
          </button>
        </div>

        <router-link to="/" class="hidden md:flex items-center gap-3 group">
          <img src="/images/logo1.png" class="w-10 h-10 object-contain drop-shadow group-hover:rotate-12 transition duration-300" />
          <span class="text-xl font-black italic tracking-tighter text-white uppercase group-hover:text-rose-500 transition">Kratak <span class="text-rose-600">FC</span></span>
        </router-link>
      </div>

      <nav class="hidden md:flex items-center bg-white/5 px-1 py-1 rounded-full border border-white/10 shadow-inner">
        <router-link to="/" class="nav-link" active-class="active-link">Home</router-link>
        <router-link to="/katalog" class="nav-link" active-class="active-link">List Gear</router-link>
        <router-link to="/about" class="nav-link" active-class="active-link">Tentang</router-link>
        <router-link to="/faq" class="nav-link" active-class="active-link">FAQ</router-link>
      </nav>

      <div class="flex items-center gap-3 md:gap-4 z-50">
        
        <router-link to="/keranjang" class="relative group p-2">
          <div class="text-gray-300 group-hover:text-white transition transform group-hover:scale-110">
            <i class="fas fa-shopping-cart text-xl"></i>
          </div>
          <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-rose-600 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-black shadow-sm animate-pulse">
            {{ cartCount }}
          </span>
        </router-link>

        <div class="hidden md:block">
          <template v-if="!isLoggedIn">
            <div class="flex items-center gap-3 border-l pl-4 border-gray-700">
              <router-link to="/login" class="text-sm font-bold text-gray-300 hover:text-white transition uppercase">LOGIN</router-link>
              <router-link to="/register" class="bg-rose-600 text-white text-sm font-bold px-6 py-2.5 rounded-full hover:bg-rose-700 transition uppercase shadow-lg shadow-rose-900/20">DAFTAR</router-link>
            </div>
          </template>
          
          <template v-else>
            <div class="relative profile-box pl-4 border-l border-gray-700">
              <button @click="toggleDropdown" class="flex items-center gap-2 focus:outline-none group">
                <img :src="`https://ui-avatars.com/api/?name=${userName}&background=1f2937&color=fff`" class="w-10 h-10 rounded-full border-2 border-gray-600 group-hover:border-rose-600 shadow-lg transition" />
              </button>
              
              <transition name="scale">
                <div v-if="profileMenu" class="absolute right-0 mt-4 w-56 bg-[#1a1a1a] rounded-xl shadow-2xl border border-gray-800 overflow-hidden z-[60]">
                  <div class="px-5 py-4 bg-[#222] border-b border-gray-700">
                    <p class="text-xs text-gray-400 uppercase font-bold">Halo, {{ userName }}</p>
                  </div>
                  <router-link to="/profile" class="block px-5 py-3 text-sm text-gray-300 hover:bg-rose-600 hover:text-white transition">
                    <i class="fas fa-user mr-2"></i> Profil Saya
                  </router-link>
                  <router-link to="/riwayat" class="block px-5 py-3 text-sm text-gray-300 hover:bg-rose-600 hover:text-white transition">
                    <i class="fas fa-history mr-2"></i> Riwayat Sewa
                  </router-link>
                  <button @click="logout" class="w-full text-left px-5 py-3 text-sm text-red-500 hover:bg-red-900/30 transition border-t border-gray-700 font-bold">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                  </button>
                </div>
              </transition>
            </div>
          </template>
        </div>

        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white p-1 focus:outline-none ml-1">
          <i v-if="mobileMenuOpen" class="fas fa-times text-2xl"></i>
          <i v-else class="fas fa-bars text-2xl"></i>
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
            <router-link to="/login" @click="mobileMenuOpen = false" class="w-full text-center py-3 rounded-xl border border-gray-700 text-white font-bold uppercase hover:bg-gray-800 transition">Login</router-link>
            <router-link to="/register" @click="mobileMenuOpen = false" class="w-full text-center py-3 rounded-xl bg-rose-600 text-white font-bold uppercase hover:bg-rose-700 transition">Daftar</router-link>
        </template>
        <template v-else>
            <div class="flex items-center gap-3 mb-2 px-2">
               <img :src="`https://ui-avatars.com/api/?name=${userName}&background=1f2937&color=fff`" class="w-10 h-10 rounded-full border border-rose-600" />
               <div><p class="text-white font-bold">{{ userName }}</p><p class="text-xs text-gray-500">Member Aktif</p></div>
            </div>
            <router-link to="/profile" @click="mobileMenuOpen = false" class="mobile-sublink">Profil Saya</router-link>
            <router-link to="/riwayat" @click="mobileMenuOpen = false" class="mobile-sublink">Riwayat Sewa</router-link>
            <button @click="logout" class="w-full text-left py-2 text-red-500 font-bold uppercase text-sm mt-2 ml-4">Logout</button>
        </template>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, onMounted, watch, computed, onUnmounted } from "vue";
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

// --- FUNGSI UPDATE STATE (SIMPEL & KONSISTEN) ---
const updateState = () => {
  // Cek Token (Bisa token atau buyer_token)
  const token = localStorage.getItem("buyer_token") || localStorage.getItem("token");
  const userDataStr = localStorage.getItem("user_data");
  
  isLoggedIn.value = !!token;

  if (token && userDataStr) {
      try {
          const userData = JSON.parse(userDataStr);
          userName.value = userData.name || userData.nama || "Member";
      } catch (e) {
          console.error("Gagal parse user data");
      }
  }

  // LOGIC CART: Konsisten pakai key 'keranjang'
  // Tidak perlu key user-specific yang ribet, localstorage browser sudah private per user device
  const cart = JSON.parse(localStorage.getItem("keranjang") || '[]');
  cartCount.value = cart.length;
};

// --- LIFECYCLE ---
onMounted(() => {
  // Close dropdown on outside click
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".profile-box")) profileMenu.value = false;
  });

  // Initial Load
  updateState(); 

  // Listener Custom Event (PENTING BUAT REALTIME CART)
  window.addEventListener('cart-updated', updateState);
});

onUnmounted(() => {
  window.removeEventListener('cart-updated', updateState);
});

// Watch Route Change (Tutup menu mobile kalau pindah halaman)
watch(() => route.fullPath, () => {
    updateState();
    mobileMenuOpen.value = false;
    profileMenu.value = false;
});

// --- LOGOUT ---
const logout = () => {
  // Hapus semua data sesi
  localStorage.removeItem("buyer_token");
  localStorage.removeItem("token");
  localStorage.removeItem("user_data");
  // Opsional: Hapus keranjang saat logout?
  // localStorage.removeItem("keranjang"); 
  
  updateState(); 
  router.push("/login");
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