<template>
    <div class="min-h-screen bg-[#050505] flex items-center justify-center p-6">
      <div class="w-full max-w-md bg-[#111] border border-gray-800 rounded-3xl p-8">
        <h2 class="text-xl text-white font-bold mb-4">Verifikasi OTP</h2>

        <p class="text-gray-400 text-sm mb-4">
          Masukkan kode OTP yang dikirim ke email: <span class="text-white">{{ email }}</span>
        </p>

        <form @submit.prevent="verifyOtp">
          <input
            v-model="otp"
            type="text"
            maxlength="6"
            placeholder="Masukkan OTP"
            class="input-dark mb-4"
            required
          />

          <button class="w-full bg-rose-600 py-3 text-white rounded-xl font-bold">
            Verifikasi
          </button>
        </form>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import axios from "axios";
  import Swal from "sweetalert2";

  const route = useRoute();
  const router = useRouter();

  const email = route.query.email; // Email dikirim dari register.vue
  const otp = ref("");

  const verifyOtp = async () => {
    try {
      const res = await axios.post("/api/verify-otp", {
        email: email,
        otp: otp.value
      });

      Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: "OTP berhasil diverifikasi!",
        background: "#151515",
        color: "#fff"
      });

      router.push("/login");
    }
    catch (err) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: err.response?.data?.message || "OTP salah atau kadaluarsa",
        background: "#151515",
        color: "#fff"
      });
    }
  };
  </script>

  <style scoped>
  .input-dark {
    @apply w-full bg-[#050505] border border-gray-700 text-white px-4 py-3 rounded-xl;
  }
  </style>
