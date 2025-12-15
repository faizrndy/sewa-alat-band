<template>
    <div class="min-h-screen bg-[#050505] flex items-center justify-center p-6">
      <div class="w-full max-w-md bg-[#111] border border-gray-800 rounded-3xl p-8 shadow-2xl">

        <h2 class="text-3xl font-black text-white text-center mb-6">
          Daftar <span class="text-rose-600">Member</span>
        </h2>

        <form @submit.prevent="sendOtp" class="space-y-4">

          <!-- NAMA -->
          <div>
            <label class="label-dark">Nama Lengkap</label>
            <input v-model="form.name" class="input-dark" required />
          </div>

          <!-- EMAIL -->
          <div>
            <label class="label-dark">Email</label>
            <input v-model="form.email" type="email" class="input-dark" required />
          </div>

          <!-- PHONE -->
          <div>
            <label class="label-dark">Nomor Telepon</label>
            <input v-model="form.phone" type="tel" class="input-dark" required />
          </div>

          <!-- PASSWORD -->
          <div>
            <label class="label-dark">Password</label>
            <input
              v-model="form.password"
              type="password"
              class="input-dark"
              required
            />

            <!-- BAR STRENGTH -->
            <div class="w-full h-2 bg-gray-700 rounded-full mt-2 overflow-hidden">
              <div
                class="h-full transition-all duration-300"
                :class="strengthColor"
                :style="{ width: passwordStrength + '%' }"
              ></div>
            </div>

            <p
              class="text-xs mt-1 font-semibold flex items-center gap-1"
              :class="strengthColor.replace('bg', 'text')"
            >
              {{ strengthIcon }}
              Password {{ strengthText }}
            </p>
          </div>

          <!-- KONFIRMASI PASSWORD -->
          <div>
            <label class="label-dark">Konfirmasi Password</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              class="input-dark"
              required
            />

            <p
              v-if="form.password_confirmation"
              class="text-xs mt-1 font-semibold"
              :class="passwordMatch ? 'text-green-500' : 'text-red-500'"
            >
              {{ passwordMatch ? '✅ Password cocok' : '❌ Password tidak cocok' }}
            </p>
          </div>

          <!-- SUBMIT -->
          <button
            class="w-full bg-rose-600 py-3 rounded-xl font-bold text-white disabled:opacity-60"
            :disabled="loading"
          >
            {{ loading ? 'Mengirim OTP...' : 'DAFTAR' }}
          </button>

        </form>
      </div>
    </div>
  </template>


<script setup>
    import { ref, computed } from "vue"
    import Swal from "sweetalert2"
    import axios from "axios"
    import { useRouter } from "vue-router"

    const router = useRouter()
    const loading = ref(false)

    const form = ref({
      name: "",
      email: "",
      phone: "",
      password: "",
      password_confirmation: ""
    })

    /* =============================
       PASSWORD STRENGTH
       ============================= */
    const passwordStrength = computed(() => {
      const pwd = form.value.password
      if (pwd.length === 0) return 0
      if (pwd.length < 6) return 25
      if (pwd.length < 8) return 50
      if (!/[A-Z]/.test(pwd) || !/[0-9]/.test(pwd)) return 75
      return 100
    })

    const strengthText = computed(() => {
      if (passwordStrength.value < 50) return "Lemah"
      if (passwordStrength.value < 100) return "Sedang"
      return "Kuat"
    })

    const strengthColor = computed(() => {
      if (passwordStrength.value < 50) return "bg-red-500"
      if (passwordStrength.value < 100) return "bg-yellow-500"
      return "bg-green-500"
    })

    const strengthIcon = computed(() => {
      if (passwordStrength.value < 50) return "❌"
      if (passwordStrength.value < 100) return "⚠️"
      return "✅"
    })

    /* =============================
       PASSWORD MATCH
       ============================= */
    const passwordMatch = computed(() => {
      return (
        form.value.password &&
        form.value.password === form.value.password_confirmation
      )
    })

    /* =============================
       REGISTER + OTP
       ============================= */
    const sendOtp = async () => {

      // ❌ Password lemah
      if (passwordStrength.value < 100) {
        Swal.fire({
          icon: "warning",
          title: "Password belum aman",
          text: "Gunakan password kuat (minimal 8 karakter, huruf besar & angka).",
          background: "#151515",
          color: "#fff"
        })
        return
      }

      // ❌ Password tidak cocok
      if (!passwordMatch.value) {
        Swal.fire({
          icon: "error",
          title: "Password tidak cocok",
          text: "Pastikan password dan konfirmasi password sama.",
          background: "#151515",
          color: "#fff"
        })
        return
      }

      loading.value = true

      try {
        await axios.post("/api/register", {
          nama_lengkap: form.value.name,
          email: form.value.email,
          nomor_telepon: form.value.phone,
          password: form.value.password,
          password_confirmation: form.value.password_confirmation
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
  .label-dark {
    @apply text-xs font-bold text-gray-500 uppercase mb-1 block;
  }
  .input-dark {
    @apply w-full bg-[#050505] border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-rose-500;
  }
  </style>
