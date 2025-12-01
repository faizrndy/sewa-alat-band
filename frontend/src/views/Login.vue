<template>
  <div class="min-h-screen bg-[#050505] flex items-center justify-center p-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-rose-600/10 blur-[120px] rounded-full"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-600/10 blur-[100px] rounded-full"></div>

    <div class="w-full max-w-md bg-[#111] border border-gray-800 rounded-3xl p-8 shadow-2xl relative z-10">
      <div class="text-center mb-8">
        <img src="/images/logo1.png" class="w-16 h-16 mx-auto mb-4 drop-shadow-lg" />
        <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">Login <span class="text-rose-600">Member</span></h2>
        <p class="text-gray-500 text-sm mt-2">Masuk buat atur bookingan lo.</p>
      </div>

      <form @submit.prevent="login" class="space-y-6">
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Email</label>
          <input v-model="form.email" type="email" placeholder="email@lo.com" class="input-dark" required />
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block">Password</label>
          <input v-model="form.password" type="password" placeholder="••••••••" class="input-dark" required />
        </div>

        <button type="submit" :disabled="loading" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 rounded-xl uppercase tracking-widest shadow-lg shadow-rose-900/20 transition disabled:opacity-50">
          {{ loading ? 'Loading...' : 'Gass Masuk' }}
        </button>
      </form>

      <div class="mt-8 text-center border-t border-gray-800 pt-6">
        <p class="text-sm text-gray-500">
          Belum punya akun? 
          <router-link to="/register" class="text-white font-bold hover:text-rose-500 transition">Daftar dulu sini</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2' 

const router = useRouter()
const form = ref({ email: '', password: '' })
const loading = ref(false)

const login = async () => {
  loading.value = true
  try {
    const res = await axios.post('/api/login', form.value)
    
    // 1. Simpan Token
    localStorage.setItem('buyer_token', res.data.token)
    
    // 2. Simpan Data User (PENTING BUAT KUNCI KERANJANG)
    if (res.data.user) {
        localStorage.setItem('user_data', JSON.stringify(res.data.user));
        
        // --- LOGIC PINDAHKAN KERANJANG TAMU KE USER ---
        const guestCart = JSON.parse(localStorage.getItem('cart_guest') || '[]');
        if (guestCart.length > 0) {
            const userKey = `cart_${res.data.user.id}`;
            // Gabungkan atau timpa? Di sini kita timpa saja biar simpel
            // Atau logic gabung: [...userCart, ...guestCart]
            localStorage.setItem(userKey, JSON.stringify(guestCart));
            localStorage.removeItem('cart_guest'); // Kosongkan tamu
        }
    }

    window.dispatchEvent(new Event('cart-updated'));

    Swal.fire({
      icon: 'success',
      title: 'Welcome Back!',
      text: 'Siap guncang panggung lagi?',
      background: '#151515', color: '#fff', 
      iconColor: '#e11d48', confirmButtonColor: '#e11d48',
      timer: 1500, showConfirmButton: false
    }).then(() => {
       if(res.data.user.role === 'admin') {
           window.location.href = '/admin/dashboard';
       } else {
           router.push('/');
       }
    })

  } catch (err) { 
    Swal.fire({
      icon: 'error', title: 'Gagal Masuk',
      text: err.response?.data?.message || 'Email atau password salah bro.',
      background: '#151515', color: '#fff', confirmButtonColor: '#e11d48'
    });
  } 
  finally { loading.value = false }
}
</script>

<style scoped>
.input-dark { 
  @apply w-full bg-[#050505] border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition placeholder-gray-700; 
}
</style>