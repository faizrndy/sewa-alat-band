<template>
    <div class="min-h-screen bg-[#050505] flex items-center justify-center p-6">

      <div class="w-full max-w-md bg-[#111] border border-gray-800 rounded-3xl p-8 shadow-2xl">

        <h2 class="text-3xl font-black text-white text-center mb-6">
          Daftar <span class="text-rose-600">Member</span>
        </h2>

        <form @submit.prevent="sendOtp" class="space-y-4">

          <div>
            <label class="label-dark">Nama Lengkap</label>
            <input v-model="form.name" class="input-dark" required />
          </div>

          <div>
            <label class="label-dark">Email</label>
            <input v-model="form.email" type="email" class="input-dark" required />
          </div>

          <div>
            <label class="label-dark">Nomor Telepon</label>
            <input v-model="form.phone" type="tel" class="input-dark" required />
          </div>

          <div>
            <label class="label-dark">Password</label>
            <input v-model="form.password" type="password" class="input-dark" required />
          </div>

          <button class="w-full bg-rose-600 py-3 rounded-xl font-bold text-white">
            {{ loading ? 'Mengirim OTP...' : 'DAFTAR' }}
          </button>

        </form>

      </div>

    </div>
  </template>

  <script setup>
  import { ref } from "vue"
  import Swal from "sweetalert2"
  import axios from "axios"
  import { useRouter } from "vue-router"

  const router = useRouter()
  const loading = ref(false)

  const form = ref({
    name: "",
    email: "",
    phone: "",
    password: ""
  })

  const sendOtp = async () => {
    loading.value = true

    try {
      await axios.post("/api/register", {
        nama_lengkap: form.value.name,
        email: form.value.email,
        nomor_telepon: form.value.phone,
        password: form.value.password
      })

      Swal.fire({
        icon: "success",
        title: "OTP terkirim!",
        text: "Cek email kamu.",
        background: "#151515",
        color: "#fff"
      })

      router.push({
        name: "verify-otp",
        query: { email: form.value.email }
      })

    } catch (err) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: err.response?.data?.message || "Terjadi kesalahan",
        background: "#151515",
        color: "#fff"
      })
    }

    loading.value = false
  }
  </script>

  <style scoped>
  .label-dark { @apply text-xs font-bold text-gray-500 uppercase mb-1 block; }
  .input-dark { @apply w-full bg-[#050505] border border-gray-700 text-white px-4 py-3 rounded-xl; }
  </style>
