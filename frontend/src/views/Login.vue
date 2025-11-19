<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
      <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Login Buyer</h2>

        <div v-if="errorMessage" class="bg-red-100 text-red-700 p-3 mb-4 rounded">
          {{ errorMessage }}
        </div>

        <form @submit.prevent="login">
          <div class="mb-4">
            <label class="block mb-1 font-medium">Email</label>
            <input
              v-model="email"
              type="email"
              class="w-full border rounded px-3 py-2"
              placeholder="email@example.com"
              required
            />
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Password</label>
            <input
              v-model="password"
              type="password"
              class="w-full border rounded px-3 py-2"
              placeholder="******"
              required
            />
          </div>

          <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Login
          </button>
        </form>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from "vue";
  import axios from "axios";
  import { useRouter } from "vue-router";

  const email = ref("");
  const password = ref("");
  const errorMessage = ref("");
  const router = useRouter();

  const login = async () => {
    try {
      const res = await axios.post("/api/login", {
        email: email.value,
        password: password.value,
      });

      // SIMPAN TOKEN
      localStorage.setItem("buyer_token", res.data.token);

      router.push("/");
    } catch (error) {
      errorMessage.value =
        error.response?.data?.message || "Login gagal!";
    }
  };
  </script>

  <style scoped></style>
