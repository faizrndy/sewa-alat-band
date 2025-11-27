<template>
  <div class="min-h-screen bg-[#0a0a0a] pt-28 px-4 flex justify-center items-start">
    <div class="bg-[#151515] w-full max-w-lg rounded-3xl border border-gray-800 p-8 shadow-2xl">
      
      <div class="text-center mb-8">
        <div class="w-20 h-20 bg-gray-800 text-rose-500 rounded-full mx-auto flex items-center justify-center text-3xl font-bold mb-4 border-2 border-rose-600 shadow-[0_0_20px_rgba(225,29,72,0.3)]">
          {{ form.nama_lengkap ? form.nama_lengkap.charAt(0) : 'U' }}
        </div>
        <h1 class="text-2xl font-black text-white uppercase italic tracking-wider">Profil <span class="text-rose-600">Member</span></h1>
      </div>

      <div v-if="loading" class="text-center py-4 text-gray-500">Loading...</div>

      <form v-else @submit.prevent="updateProfile" class="space-y-6">
        <div>
          <label class="label-dark">Nama Lengkap</label>
          <input v-model="form.nama_lengkap" type="text" class="input-dark" />
        </div>

        <div>
          <label class="label-dark">Email</label>
          <input v-model="form.email" type="email" class="input-dark opacity-50 cursor-not-allowed" disabled />
        </div>

        <div>
          <label class="label-dark">Nomor Telepon</label>
          <input v-model="form.nomor_telepon" type="text" class="input-dark" />
        </div>

        <button type="submit" class="w-full bg-white text-black py-3 rounded-xl font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition shadow-lg mt-4">
          Simpan Data
        </button>

        <div v-if="successMessage" class="bg-green-900/20 border border-green-800 text-green-400 p-3 rounded-xl text-center text-sm font-bold">
          {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="bg-red-900/20 border border-red-800 text-red-400 p-3 rounded-xl text-center text-sm font-bold">
          {{ errorMessage }}
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const loading = ref(true);
const form = ref({ nama_lengkap: "", email: "", nomor_telepon: "" });
const successMessage = ref("");
const errorMessage = ref("");

onMounted(async () => {
  try {
    const token = localStorage.getItem("buyer_token");
    const res = await axios.get("/api/buyer/profile", { headers: { Authorization: `Bearer ${token}` } });
    form.value = res.data.user;
  } catch (error) { errorMessage.value = "Gagal memuat profil"; } 
  finally { loading.value = false; }
});

const updateProfile = async () => {
  try {
    const token = localStorage.getItem("buyer_token");
    const res = await axios.put("/api/buyer/update", form.value, { headers: { Authorization: `Bearer ${token}` } });
    successMessage.value = res.data.message;
    errorMessage.value = "";
    setTimeout(() => successMessage.value = "", 3000);
  } catch (error) { errorMessage.value = "Gagal update profil."; }
};
</script>

<style scoped>
.label-dark { @apply block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2; }
.input-dark { @apply w-full bg-black border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition; }
</style>