<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
      <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-2xl font-bold text-center mb-6">Daftar Buyer</h2>

        <div v-if="errorMessage" class="bg-red-100 text-red-700 p-3 mb-4 rounded">
          {{ errorMessage }}
        </div>

        <form @submit.prevent="registerBuyer">

          <div class="mb-4">
            <label class="block mb-1 font-medium">Nama Lengkap</label>
            <input v-model="nama_lengkap" type="text"
              class="w-full border rounded px-3 py-2" required />
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Email</label>
            <input v-model="email" type="email"
              class="w-full border rounded px-3 py-2" required />
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Nomor Telepon</label>
            <input v-model="nomor_telepon" type="text"
              class="w-full border rounded px-3 py-2" required />
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Password</label>
            <input v-model="password" type="password"
              class="w-full border rounded px-3 py-2" required />
          </div>

          <button type="submit"
            class="w-full bg-green-600 text-white py-2 rounded-lg">
            Daftar
          </button>

        </form>

        <p class="text-center mt-4 text-sm">
          Sudah punya akun?
          <router-link to="/login" class="text-blue-600 underline">Login</router-link>
        </p>

      </div>
    </div>
  </template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

const nama_lengkap = ref("");
const email = ref("");
const nomor_telepon = ref("");
const password = ref("");

const errorMessage = ref("");

const registerBuyer = async () => {
  try {
    // Ambil CSRF
    await axios.get('/sanctum/csrf-cookie', {
  withCredentials: true,
});

await axios.post(
  "/api/register",
  {
    nama_lengkap: nama_lengkap.value,
    email: email.value,
    nomor_telepon: nomor_telepon.value,
    password: password.value,
  },
  { withCredentials: true }
);


    router.push("/login");
  } catch (err) {
    console.log(err);
    errorMessage.value =
      err.response?.data?.message || "Registrasi gagal!";
  }
};


</script>
