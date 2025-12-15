<template>
  <div class="min-h-screen bg-[#050505] flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-[#111] border border-gray-800 rounded-3xl p-8 shadow-2xl relative z-10">
      
      <div class="text-center mb-8">
        <img src="/images/logo1.png" class="w-16 h-16 mx-auto mb-4 drop-shadow-lg" onerror="this.style.display='none'" />
        <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">
          Login <span class="text-rose-600">Member</span>
        </h2>
        <p class="text-gray-500 text-sm mt-2">Masuk buat atur bookingan lo.</p>
      </div>

      <form @submit.prevent="login" class="space-y-6">
        
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Email</label>
          <input 
            v-model="form.email" 
            type="email" 
            placeholder="email@lo.com" 
            class="input-dark" 
            required 
          />
        </div>

        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Password</label>
          <input 
            v-model="form.password" 
            type="password" 
            placeholder="••••••••" 
            class="input-dark" 
            required 
          />
        </div>

        <div class="flex justify-center my-4">
           <div id="recaptcha-box"></div>
        </div>

        <button 
          type="submit" 
          :disabled="loading" 
          class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 rounded-xl uppercase tracking-widest shadow-lg shadow-rose-900/20 transition disabled:opacity-50 disabled:cursor-not-allowed">
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

// Kunci ReCaptcha (SKD)
const siteKey = '6LfscxcsAAAAAO07vUyB0-C5RguuVUA9IV-_nn4x'

const form = ref({
  email: '',
  password: '',
  'g-recaptcha-response': ''
})

let widgetId = null

// Render reCAPTCHA saat halaman dimuat
onMounted(() => {
  if (window.grecaptcha) renderCaptcha()
  else window.vueRecaptchaInit = renderCaptcha
})

function renderCaptcha() {
  try {
    widgetId = grecaptcha.render('recaptcha-box', {
      sitekey: siteKey,
      callback: (token) => {
        form.value['g-recaptcha-response'] = token
      },
      'expired-callback': () => {
        form.value['g-recaptcha-response'] = ''
      }
    })
  } catch (e) {
    console.error("Captcha error:", e)
  }
}

// LOGIN LOGIC (SKD Version - Security Aware)
const login = async () => {
  // 1. Validasi Captcha
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

    // Simpan Token
    const token = res.data.token || res.data.access_token
    localStorage.setItem('buyer_token', token)
    
    const user = res.data.user || res.data.data
    if(user) {
        localStorage.setItem('user_data', JSON.stringify(user))
    }
    
    localStorage.setItem('lastActivity', Date.now())

    Swal.fire({
      icon: 'success',
      title: 'Login berhasil!',
      timer: 1500,
      showConfirmButton: false,
      background: '#151515',
      color: '#fff'
    }).then(() => {
      // Redirect Logic
      if(user && user.role === 'admin') {
           window.location.href = '/admin/dashboard';
      } else {
           router.push('/')
      }
    })

  } catch (err) {
    // ✅ RATE LIMIT HANDLING (Fitur Temanmu)
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
        text: err.response?.data?.message || 'Email atau password salah.',
        background: '#151515',
        color: '#fff'
      })
    }

    // Reset captcha bila gagal
    if(widgetId !== null) grecaptcha.reset(widgetId)
    form.value['g-recaptcha-response'] = ''
  }

  loading.value = false
}
</script>

<style scoped>
.input-dark {
  @apply w-full bg-[#050505] border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-rose-500 transition;
}
</style>