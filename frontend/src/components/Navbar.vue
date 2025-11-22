<template>
  <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

      <!-- LOGO -->
      <div class="flex items-center gap-2">
        <img src="/images/logo1.png" alt="Kratak FC Logo" class="w-8 h-8" />
        <span class="text-slate-900 font-semibold text-lg">Kratak FC</span>
      </div>

      <!-- MENU UTAMA -->
      <nav class="hidden md:flex items-center gap-8">
        <router-link to="/" class="hover:text-blue-600">Home</router-link>
        <router-link to="/katalog" class="hover:text-blue-600">Katalog</router-link>
        <router-link to="/about" class="hover:text-blue-600">About</router-link>
        <a href="#faq" class="hover:text-blue-600">FAQ</a>
        <router-link to="/terms" class="hover:text-blue-600">Syarat & Ketentuan</router-link>
      </nav>

      <!-- BAGIAN KANAN -->
      <div class="flex items-center gap-4">

        <!-- 🔵 BELUM LOGIN -->
        <template v-if="!isLoggedIn">
          <router-link to="/login" class="text-blue-600 font-semibold hover:text-blue-700">
            Login
          </router-link>

          <router-link
            to="/register"
            class="bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700"
          >
            Register
          </router-link>
        </template>

        <!-- 🟢 SUDAH LOGIN -->
        <template v-else>

          <!-- Keranjang -->
          <router-link to="/keranjang" class="relative text-2xl hover:opacity-80">
            🛒
            <span
              v-if="cartCount > 0"
              class="absolute -top-2 -right-3 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full"
            >
              {{ cartCount }}
            </span>
          </router-link>

          <!-- Avatar Profil -->
          <div class="profile-box relative select-none">
            <img
              @click="toggleDropdown"
              src="https://ui-avatars.com/api/?name=User"
              class="w-9 h-9 rounded-full border cursor-pointer hover:ring-2 hover:ring-blue-400"
            />

            <!-- 🔽 Dropdown Profil -->
            <div
              v-if="profileMenu"
              class="absolute right-0 mt-2 w-40 bg-white shadow-lg border rounded-lg py-2 z-50"
            >
              <router-link
                to="/profile"
                class="block px-4 py-2 hover:bg-slate-100"
              >
                Detail Profil
              </router-link>

              <button
                @click="logout"
                class="w-full text-left px-4 py-2 hover:bg-slate-100 text-red-600"
              >
                Logout
              </button>
            </div>
          </div>

        </template>

      </div>

    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

/* ====================== STATE ====================== */
const isLoggedIn = ref(false)
const cartCount = ref(0)
const profileMenu = ref(false)

/* ====================== DROPDOWN PROFIL ====================== */
const toggleDropdown = () => {
  profileMenu.value = !profileMenu.value;
};

/* Tutup dropdown saat klik di luar */
onMounted(() => {
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".profile-box")) {
      profileMenu.value = false;
    }
  });
});

/* ====================== CEK LOGIN ====================== */
onMounted(() => {
  isLoggedIn.value = !!localStorage.getItem("buyer_token");
});

/* AUTO UPDATE NAVBAR SAAT PINDAH HALAMAN */
watch(
  () => route.fullPath,
  () => {
    isLoggedIn.value = !!localStorage.getItem("buyer_token");
  }
);

/* ====================== LOGOUT ====================== */
const logout = async () => {
  const token = localStorage.getItem("buyer_token");

  try {
    await fetch("/api/buyer/logout", {
      method: "POST",
      headers: {
        "Authorization": `Bearer ${token}`,
        "Content-Type": "application/json"
      }
    });
  } catch (e) {
    console.error("Logout error:", e);
  }

  localStorage.removeItem("buyer_token");

  isLoggedIn.value = false;
  profileMenu.value = false;

  // Tetap di halaman HOME
  router.replace("/");
};
</script>