<template>
    <div class="min-h-screen bg-[#050505] flex items-center justify-center p-6 relative overflow-hidden">

      <div class="w-full max-w-md bg-[#111] border border-gray-800 rounded-3xl p-8 shadow-2xl relative z-10">
        <div class="text-center mb-8">
          <img src="/images/logo1.png" class="w-16 h-16 mx-auto mb-4 drop-shadow-lg" />
          <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">
            Login <span class="text-rose-600">Member</span>
          </h2>
        </div>

        <form @submit.prevent="login" class="space-y-6">

          <!-- EMAIL -->
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Email</label>
            <input v-model="form.email" type="email" class="input-dark" required />
          </div>

          <!-- PASSWORD -->
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Password</label>
            <input v-model="form.password" type="password" class="input-dark" required />
          </div>

          <!-- CAPTCHA -->
          <div class="flex justify-center">
            <div class="g-recaptcha" :data-sitekey="siteKey"></div>
          </div>

          <!-- SUBMIT -->
          <button type="submit"
            :disabled="loading"
            class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 rounded-xl uppercase">
            {{ loading ? 'Loading...' : 'Gass Masuk' }}
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
const siteKey = "6LdD7CAsAAAAAImeZwOtcVEbvZzrTlMoJ6rTVhOT"  // Ubah ke site key kamu

const form = ref({
  email: '',
  password: '',
  captcha_token: ''
})

// Render captcha setelah script Google ready
onMounted(() => {
  if (window.grecaptcha) renderCaptcha()
  else {
    window.vueRecaptchaInit = renderCaptcha
  }
})

function renderCaptcha() {
  const el = document.querySelector('.g-recaptcha')
  if (el) {
    grecaptcha.render(el, {
      sitekey: siteKey
    })
  }
}

const login = async () => {
  loading.value = true

  try {
    form.value.captcha_token = grecaptcha.getResponse()

    if (!form.value.captcha_token) {
      Swal.fire({
        icon: 'warning',
        title: 'Captcha belum diisi!',
        background: '#151515', color: '#fff'
      })
      loading.value = false
      return
    }

    const res = await axios.post('/api/login', form.value)

    localStorage.setItem('buyer_token', res.data.token)
    localStorage.setItem('user_data', JSON.stringify(res.data.user))

    Swal.fire({
      icon: 'success',
      title: 'Welcome Back!',
      timer: 1500,
      showConfirmButton: false,
      background: '#151515',
      color: '#fff'
    }).then(() => {
      if (res.data.user.role === 'admin') window.location.href = '/admin/dashboard'
      else router.push('/')
    })

  } catch (err) {
    Swal.fire({
      icon: 'error',
      title: 'Gagal Masuk',
      text: err.response?.data?.message || 'Email atau password salah bro.',
      background: '#151515',
      color: '#fff'
    })
  }

  loading.value = false
}
</script>

  <style scoped>
  .input-dark {
    @apply w-full bg-[#050505] border border-gray-700 text-white px-4 py-3 rounded-xl;
  }
  </style>
