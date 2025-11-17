<template>
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

        <!-- LOGO -->
        <div class="flex items-center gap-2">
          <Music2 class="w-8 h-8 text-blue-600" />
          <span class="text-slate-900 font-semibold text-lg">Kratak FC</span>
        </div>

        <!-- MENU KIRI -->
        <nav class="hidden md:flex items-center gap-8">
          <router-link to="/" class="hover:text-blue-600">Home</router-link>
          <router-link to="/katalog" class="hover:text-blue-600">Katalog</router-link>
          <router-link to="/about" class="hover:text-blue-600">About</router-link>
          <router-link to="/#faq" class="hover:text-blue-600">FAQ</router-link>
          <router-link to="/terms" class="hover:text-blue-600">Syarat & Ketentuan</router-link>
        </nav>

        <!-- MENU KANAN -->
        <div class="flex items-center gap-4">

          <!-- 🔵 MODE GUEST -->
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

          <!-- 🟢 MODE LOGIN -->
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

            <!-- Avatar + Dropdown -->
            <div class="relative select-none profile-box">
              <img
                @click="toggleDropdown"
                src="https://ui-avatars.com/api/?name=User"
                class="w-9 h-9 rounded-full border cursor-pointer hover:ring-2 hover:ring-blue-400"
              />

              <div
                v-if="profileMenu"
                class="absolute right-0 mt-2 w-40 bg-white shadow-lg border rounded-lg py-2"
              >
                <router-link to="/profile" class="block px-4 py-2 hover:bg-slate-100">
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
  import { ref, onMounted } from "vue";
  import { useRouter } from "vue-router";
  import axios from "axios";
  import { Music2 } from "lucide-vue-next";

  const router = useRouter();
  const isLoggedIn = ref(false);
  const cartCount = ref(0);

  /* Dropdown Profil */
  const profileMenu = ref(false);
  const toggleDropdown = () => {
    profileMenu.value = !profileMenu.value;
  };

  /* Tutup dropdown ketika klik di luar */
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".profile-box")) {
      profileMenu.value = false;
    }
  });

  /* Cek Login */
  onMounted(() => {
    isLoggedIn.value = !!localStorage.getItem("buyer_token");
  });

  /* Logout */
  const logout = async () => {
    const token = localStorage.getItem("buyer_token");

    try {
      await axios.post("/api/buyer/logout", {}, {
        headers: { Authorization: `Bearer ${token}` },
      });
    } catch {}

    localStorage.removeItem("buyer_token");
    isLoggedIn.value = false;
    profileMenu.value = false;

    router.push("/");
  };
  </script>

  <style scoped>
  /* Fade dropdown */
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.2s;
  }
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  </style>
