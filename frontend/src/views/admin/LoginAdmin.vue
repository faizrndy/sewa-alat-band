<template>
    <div class="min-h-screen bg-[#050505] flex items-center justify-center p-6 relative overflow-hidden">
      <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-900/20 blur-[150px] rounded-full"></div>
      <div class="absolute bottom-0 right-0 w-96 h-96 bg-rose-900/20 blur-[150px] rounded-full"></div>

      <div class="w-full max-w-md bg-[#111] border border-gray-800 rounded-3xl p-10 shadow-2xl relative z-10">
        <div class="text-center mb-10">
          <h1 class="text-3xl font-black text-white uppercase tracking-tighter italic">
            Admin <span class="text-indigo-500">Panel</span>
          </h1>
          <p class="text-gray-500 text-sm mt-2">Login untuk mengelola Kratak FC</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Email Admin</label>
            <input v-model="form.email" type="email" class="input-dark" placeholder="admin@kratak.com" required />
          </div>

          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Password</label>
            <input v-model="form.password" type="password" class="input-dark" placeholder="••••••••" required />
          </div>

          <div class="flex justify-center my-4">
             <div id="recaptcha-box-admin"></div>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl uppercase tracking-widest shadow-lg shadow-indigo-900/20 transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ loading ? 'Memproses...' : 'Masuk Dashboard' }}
          </button>
        </form>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  import Swal from 'sweetalert2';

  const router = useRouter();
  const loading = ref(false);

  // 🔥 KEY TESTING GOOGLE (Sama dengan Customer & .env)
  const siteKey = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';

  const form = ref({ 
      email: '', 
      password: '',
      'g-recaptcha-response': '' // Field Token Captcha
  });

  let widgetId = null;

  // Render reCAPTCHA
  onMounted(() => {
    if (window.grecaptcha) renderCaptcha();
    else window.vueRecaptchaInit = renderCaptcha;
  });

  function renderCaptcha() {
    try {
        if(document.getElementById('recaptcha-box-admin')) {
            widgetId = grecaptcha.render('recaptcha-box-admin', {
                sitekey: siteKey,
                callback: (token) => {
                    form.value['g-recaptcha-response'] = token;
                },
                'expired-callback': () => {
                    form.value['g-recaptcha-response'] = '';
                }
            });
        }
    } catch (e) {
        console.error("Captcha Admin Error:", e);
    }
  }

  const handleLogin = async () => {
    // 1. Validasi Captcha
    if (!form.value['g-recaptcha-response']) {
        Swal.fire({ icon: 'warning', title: 'Captcha Kosong', text: 'Centang dulu bro!', background: '#111', color: '#fff' });
        return;
    }

    loading.value = true;
    try {
      // Kirim Data Login + Captcha
      const res = await axios.post('http://127.0.0.1:8000/api/login', form.value);

      // Pastikan user admin
      if (res.data.user.role !== 'admin') {
        throw new Error('Anda bukan Admin! Akses ditolak.');
      }

      // Bersihkan sesi buyer jika ada
      localStorage.removeItem('buyer_token');
      localStorage.removeItem('user_data');

      // Simpan sesi admin
      localStorage.setItem('admin_token', res.data.token);
      localStorage.setItem('user_role', 'admin');

      Swal.fire({
        icon: 'success',
        title: 'Welcome Admin!',
        showConfirmButton: false,
        timer: 1500,
        background: '#111',
        color: '#fff',
      });

      router.push('/admin/dashboard');

    } catch (err) {
      const msg = err.response?.data?.message || err.message || 'Login Gagal';
      Swal.fire({
        icon: 'error',
        title: 'Akses Ditolak',
        text: msg,
        background: '#111',
        color: '#fff'
      });
      
      // Reset Captcha jika gagal
      if(widgetId !== null) grecaptcha.reset(widgetId);
      form.value['g-recaptcha-response'] = '';
      
    } finally {
      loading.value = false;
    }
  };
  </script>

  <style scoped>
  .input-dark {
    @apply w-full bg-[#050505] border border-gray-700 text-white px-4 py-3 rounded-xl
    focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition placeholder-gray-700;
  }
  </style>