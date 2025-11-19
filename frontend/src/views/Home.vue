<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-b from-slate-50 to-white text-slate-900">

      <!-- ================= NAVBAR ================= -->
      <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

    <!-- LOGO -->
    <div class="flex items-center gap-2">
      <Music2 class="w-8 h-8 text-blue-600" />
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

      <!-- ================= HERO ================= -->
      <section class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800">
        <div class="text-center py-24 md:py-32 max-w-4xl mx-auto px-6">
          <Badge class="mb-6 bg-white/20 text-white border-white/30">✨ Sewa Alat Band Profesional</Badge>
          <h1 class="text-white text-4xl font-bold mb-4">Wujudkan Impian Bermusik Anda</h1>
          <p class="text-blue-100 mb-8">Sewa alat musik profesional untuk konser & latihan.</p>

          <router-link to="/katalog">
            <Button size="lg" class="bg-blue-600 text-white hover:bg-blue-700 shadow-lg">
              <Search class="w-5 h-5 mr-2" /> Lihat Katalog
            </Button>
          </router-link>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-slate-50 to-transparent"></div>
      </section>

      <!-- ================= FITUR ================= -->
      <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8 text-center">
          <div v-for="(f, i) in features" :key="i" class="flex flex-col items-center">
            <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center mb-3">
              <component :is="f.icon" class="w-7 h-7 text-blue-600" />
            </div>
            <h3 class="font-semibold text-slate-900 mb-1">{{ f.title }}</h3>
            <p class="text-slate-600 text-sm max-w-[200px]">{{ f.desc }}</p>
          </div>
        </div>
      </section>

      <!-- ================= PRODUK ================= -->
      <section class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

          <div class="text-center mb-12">
            <Badge class="mb-4 bg-blue-100 text-blue-700">🎵 Pilihan Terbaik</Badge>
            <h2 class="text-3xl font-bold mb-3">Pilihan Alat Band Kami</h2>
          </div>

          <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <Card
              v-for="product in randomProducts"
              :key="product.id"
              class="border border-slate-200 hover:shadow-lg transition rounded-xl overflow-hidden">

              <div class="aspect-square relative">
                <img :src="`${api}/storage/${product.gambar}`"
                  class="w-full h-full object-cover" />

                <div v-if="product.status === 'tersedia'" class="absolute top-3 left-3">
                  <Badge class="bg-green-500 text-white">Tersedia</Badge>
                </div>

                <div class="absolute top-3 right-3">
                  <Badge class="bg-white/90 text-slate-800">{{ product.kategori }}</Badge>
                </div>
              </div>

              <div class="p-5">
                <h3 class="text-slate-900 font-semibold mb-2">{{ product.nama_alat }}</h3>
                <p class="text-blue-600 font-medium">{{ product.harga_sewa }}</p>
                <p class="text-xs text-slate-500 mb-4">/hari</p>
                <Button class="w-full bg-blue-600 hover:bg-blue-700 text-white">
                  Detail <ArrowRight class="w-4 h-4 ml-1" />
                </Button>
              </div>
            </Card>
          </div>

        </div>
      </section>

      <!-- ================= REVIEW ================= -->
      <section id="review" class="py-20 bg-[#0f1b4c] text-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
          <h2 class="text-3xl font-bold mb-2">Review Pelanggan</h2>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 justify-items-center mt-12">
            <div v-for="(r, i) in reviews" :key="i"
              class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/10 hover:bg-white/20 transition w-full max-w-[320px]">

              <img :src="r.avatar" class="rounded-xl mb-4" />

              <p class="font-semibold text-white text-lg">{{ r.name }}</p>
              <p class="text-sm text-white/70">{{ r.role }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- ================= FAQ ================= -->
      <section id="faq" class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-6">

          <h2 class="text-center text-3xl font-bold mb-4">FAQ</h2>

          <div v-if="faqs.length" class="space-y-4 mt-12">
            <div v-for="(faq, index) in faqs" :key="index"
              class="border border-slate-200 rounded-lg overflow-hidden">

              <button @click="toggleFAQ(index)"
                class="w-full flex justify-between px-5 py-4 bg-slate-50 hover:bg-slate-100">
                <span class="font-medium">{{ faq.question }}</span>
                <span>{{ activeFAQ === index ? '-' : '+' }}</span>
              </button>

              <transition name="fade">
                <div v-if="activeFAQ === index"
                  class="px-5 py-4 bg-white text-slate-600 border-t">
                  {{ faq.answer }}
                </div>
              </transition>

            </div>
          </div>

        </div>
      </section>

      <!-- FOOTER -->
      <footer class="bg-slate-900 text-white py-10 mt-auto">
        <div class="text-center text-slate-400 text-sm">
          © 2025 Kratak FC. All rights reserved.
        </div>
      </footer>

    </div>
  </template>


<script setup>
/* IMPORT */
import { ref, onMounted, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";
import { Music2, Search, Star, Package, Check, ArrowRight } from "lucide-vue-next";

import Button from "../components/ui/button.vue";
import Badge from "../components/ui/badge.vue";
import Card from "../components/ui/card.vue";

/* ROUTING */
const router = useRouter();
const route = useRoute();
const api = axios.defaults.baseURL;

/* ====================== STATE ====================== */
const isLoggedIn = ref(false);
const cartCount = ref(0);
const profileMenu = ref(false);

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
    await axios.post("/api/buyer/logout", {}, {
      headers: { Authorization: `Bearer ${token}` }
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


/* ====================== FITUR ====================== */
const features = [
  { icon: Check, title: "Kualitas Terjamin", desc: "Alat musik terawat dan siap digunakan." },
  { icon: Star, title: "Rating 4.9/5", desc: "Dipercaya oleh ratusan musisi." },
  { icon: Package, title: "Pengiriman Cepat", desc: "Antar-jemput alat langsung ke lokasi." }
];

/* ====================== PRODUK ====================== */
const randomProducts = ref([]);

const loadProducts = async () => {
  try {
    const res = await axios.get("/api/alat-band");
    randomProducts.value = [...res.data]
      .sort(() => Math.random() - 0.5)
      .slice(0, 4);
  } catch (e) {
    console.error("Gagal memuat produk:", e);
  }
};

/* ====================== REVIEW ====================== */
const reviews = ref([]);

const loadReviews = async () => {
  try {
    const res = await axios.get("/api/reviews");

    reviews.value = res.data.map((r) => ({
      name: r.nama_pelanggan ?? "Anonim",
      avatar: `${api}/${r.gambar}`,
      role: "Pelanggan"
    }));
  } catch (e) {
    console.error("Gagal memuat review:", e);
  }
};

/* ====================== FAQ ====================== */
const faqs = ref([]);
const activeFAQ = ref(null);

const toggleFAQ = (i) => {
  activeFAQ.value = activeFAQ.value === i ? null : i;
};

const loadFAQ = async () => {
  try {
    const res = await axios.get("/api/faqs");
    faqs.value = res.data;
  } catch (e) {
    console.error("Gagal memuat FAQ:", e);
  }
};

/* ====================== LOAD DATA SAAT MOUNT ====================== */
onMounted(() => {
  loadProducts();
  loadReviews();
  loadFAQ();
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
