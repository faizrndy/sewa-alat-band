<template>
  <header class="fixed top-0 w-full z-50 bg-black/80 backdrop-blur-md border-b border-white/10 transition-all duration-300">
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
        <router-link to="/faq" class="nav-link" active-class="active-link">FAQ</router-link>
      </nav>

      <div class="flex items-center gap-4">
        
        <router-link to="/keranjang" class="relative p-2 text-gray-400 hover:text-white transition group">
          <i class="fas fa-shopping-cart text-xl group-hover:text-rose-500 transition"></i>
          <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-rose-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-black">
            {{ cartCount }}
          </span>
        </router-link>

        <template v-if="!isLoggedIn">
          <div class="flex items-center gap-3 border-l pl-4 border-gray-700">
            <router-link to="/login" class="text-sm font-bold text-gray-300 hover:text-white transition">LOGIN</router-link>
            <router-link to="/register" class="bg-rose-600 text-white text-sm font-bold px-6 py-2.5 rounded-full hover:bg-rose-700 hover:shadow-[0_0_15px_rgba(225,29,72,0.5)] transition uppercase tracking-wide">
              DAFTAR
            </router-link>
          </div>
        </template>

        <div v-else class="relative profile-box">
          <button @click="toggleDropdown" class="flex items-center gap-2 focus:outline-none">
            <img :src="`https://ui-avatars.com/api/?name=User&background=1f2937&color=fff`" class="w-9 h-9 rounded-full border-2 border-rose-600 shadow-lg hover:scale-105 transition" />
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
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";

const router = useRouter();
const route = useRoute();
const isLoggedIn = ref(false);
const cartCount = ref(0);
const profileMenu = ref(false);

const toggleDropdown = () => { profileMenu.value = !profileMenu.value; };

const updateState = () => {
  isLoggedIn.value = !!localStorage.getItem("buyer_token");
  const cart = JSON.parse(localStorage.getItem('cart') || '[]');
  cartCount.value = cart.length;
};

onMounted(() => {
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".profile-box")) profileMenu.value = false;
  });
  updateState();
  window.addEventListener('cart-updated', updateState);
});

watch(() => route.fullPath, updateState);

const logout = async () => {
  try {
    const token = localStorage.getItem("buyer_token");
    await axios.post("/api/buyer/logout", {}, { headers: { Authorization: `Bearer ${token}` } });
  } catch (e) {}
  localStorage.removeItem("buyer_token");
  updateState();
  profileMenu.value = false;
  router.push("/");
};
</script>

<style scoped>
/* Styling Pill Navigation */
.nav-link { 
  @apply px-5 py-2 text-sm font-bold text-gray-400 rounded-full hover:text-white transition-all duration-300 uppercase tracking-wide; 
}
.active-link { 
  @apply text-white bg-white/10 shadow-inner; 
}

/* Animasi Dropdown */
.scale-enter-active, .scale-leave-active { transition: all 0.2s ease; }
.scale-enter-from, .scale-leave-to { opacity: 0; transform: scale(0.95); }
</style>