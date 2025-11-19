<template>
    <div class="max-w-xl mx-auto py-12">
      <h1 class="text-3xl font-bold mb-6">Profil Saya</h1>

      <div v-if="loading" class="text-gray-500">Memuat...</div>

      <form v-else @submit.prevent="updateProfile" class="space-y-4 bg-white p-6 shadow rounded-lg">

        <div>
          <label class="font-medium">Nama Lengkap</label>
          <input
            v-model="form.nama_lengkap"
            type="text"
            class="w-full p-2 border rounded"
          />
        </div>

        <div>
          <label class="font-medium">Email</label>
          <input
            v-model="form.email"
            type="email"
            class="w-full p-2 border rounded bg-gray-100"
            disabled
          />
        </div>

        <div>
          <label class="font-medium">Nomor Telepon</label>
          <input
            v-model="form.nomor_telepon"
            type="text"
            class="w-full p-2 border rounded"
          />
        </div>

        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Simpan Perubahan
        </button>

        <p v-if="successMessage" class="text-green-600 mt-2">{{ successMessage }}</p>
        <p v-if="errorMessage" class="text-red-600 mt-2">{{ errorMessage }}</p>

      </form>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from "vue";
  import axios from "axios";

  const loading = ref(true);
  const form = ref({
    nama_lengkap: "",
    email: "",
    nomor_telepon: "",
  });

  const successMessage = ref("");
  const errorMessage = ref("");

  onMounted(async () => {
    try {
      const token = localStorage.getItem("buyer_token");

      const res = await axios.get("/api/buyer/profile", {
        headers: { Authorization: `Bearer ${token}` }
      });

      form.value = res.data.user;
    } catch (error) {
      errorMessage.value = "Gagal memuat profil";
    } finally {
      loading.value = false;
    }
  });

  const updateProfile = async () => {
    try {
      const token = localStorage.getItem("buyer_token");

      const res = await axios.put("/api/buyer/update", form.value, {
        headers: { Authorization: `Bearer ${token}` }
      });

      successMessage.value = res.data.message;
      errorMessage.value = "";
    } catch (error) {
      errorMessage.value = "Gagal memperbarui profil.";
    }
  };
  </script>

  <style scoped>
  </style>
