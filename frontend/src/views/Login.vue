<template>
    <div class="min-h-screen bg-[#050505] flex items-center justify-center p-6">
      <div class="w-full max-w-md bg-[#111] border border-gray-800 rounded-3xl p-8 shadow-2xl">

        <!-- HEADER -->
        <div class="text-center mb-8">
          <img src="/images/logo1.png" class="w-16 h-16 mx-auto mb-4" />
          <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">
            Login <span class="text-rose-600">Member</span>
          </h2>
        </div>

        <!-- FORM -->
        <form @submit.prevent="login" class="space-y-6">

          <!-- EMAIL -->
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">
              Email
            </label>
            <input
              v-model="form.email"
              type="email"
              class="input-dark"
              placeholder="email@example.com"
              required
            />
          </div>

          <!-- PASSWORD -->
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">
              Password
            </label>
            <input
              v-model="form.password"
              type="password"
              class="input-dark"
              placeholder="••••••••"
              required
            />
          </div>

          <!-- CAPTCHA -->
          <div class="flex justify-center">
            <div id="recaptcha-box"></div>
          </div>

          <!-- SUBMIT -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 rounded-xl transition disabled:opacity-60">
            {{ loading ? 'Memproses...' : 'Gass Masuk' }}
          </button>

        </form>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRouter } from 'vue-router'
  import axios from 'axios'
  import Swal from 'sweetalert2'

  const router = useRouter()
  const loading = ref(false)

  const siteKey = '6LfscxcsAAAAAO07vUyB0-C5RguuVUA9IV-_nn4x'

  const form = ref({
    email: '',
    password: '',
    'g-recaptcha-response': ''
  })

  let widgetId = null

  // Render reCAPTCHA
  onMounted(() => {
    if (window.grecaptcha) renderCaptcha()
    else window.vueRecaptchaInit = renderCaptcha
  })

  function renderCaptcha() {
    widgetId = grecaptcha.render('recaptcha-box', {
      sitekey: siteKey,
      callback: (token) => {
        form.value['g-recaptcha-response'] = token
      },
      'expired-callback': () => {
        form.value['g-recaptcha-response'] = ''
      }
    })
  }

  // LOGIN
  const login = async () => {
    // Validasi captcha di frontend
    if (!form.value['g-recaptcha-response']) {
      Swal.fire({
        icon: 'warning',
        title: 'Captcha belum diisi!',
        text: 'Silakan centang captcha terlebih dahulu.',
        background: '#151515',
        color: '#fff'
      })
      return
    }

    loading.value = true

    try {
      const res = await axios.post(
        'http://127.0.0.1:8000/api/login',
        form.value
      )

      // Simpan token
      localStorage.setItem('buyer_token', res.data.token)
      localStorage.setItem('user_data', JSON.stringify(res.data.user))
      localStorage.setItem('lastActivity', Date.now())


      Swal.fire({
        icon: 'success',
        title: 'Login berhasil!',
        timer: 1500,
        showConfirmButton: false,
        background: '#151515',
        color: '#fff'
      }).then(() => {
        router.push('/')
      })

    } catch (err) {

      // ✅ RATE LIMIT (ANTI BRUTE FORCE)
      if (err.response?.status === 429) {
        Swal.fire({
          icon: 'warning',
          title: 'Terlalu Banyak Percobaan',
          text: 'Anda terlalu sering mencoba login. Silakan tunggu beberapa menit.',
          background: '#151515',
          color: '#fff'
        })
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Gagal Masuk',
          text: err.response?.data?.message || 'Login gagal',
          background: '#151515',
          color: '#fff'
        })
      }

      // Reset captcha bila gagal
      grecaptcha.reset(widgetId)
      form.value['g-recaptcha-response'] = ''
    }

    loading.value = false
  }
  </script>

  <style scoped>
  .input-dark {
    @apply w-full bg-[#050505] border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-rose-500;
  }
  </style>
