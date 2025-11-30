<template>
  <div class="min-h-screen bg-[#050505] flex items-center justify-center p-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-rose-600/10 blur-[120px] rounded-full"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-600/10 blur-[100px] rounded-full"></div>

    <div class="w-full max-w-md bg-[#111] border border-gray-800 rounded-3xl p-8 shadow-2xl relative z-10">
      <div class="text-center mb-8">
        <img src="/images/logo1.png" class="w-16 h-16 mx-auto mb-4 drop-shadow-lg" />
        <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">Join <span class="text-rose-600">Member</span></h2>
        <p class="text-gray-500 text-sm mt-2">Bikin akun biar gampang sewa alat.</p>
      </div>

      <form @submit.prevent="register" class="space-y-5">
        <div>
          <label class="label-dark">Nama Lengkap</label>
          <input v-model="form.name" type="text" placeholder="Nama Panggung Lo" class="input-dark" required />
        </div>

        <div>
          <label class="label-dark">Email</label>
          <input v-model="form.email" type="email" placeholder="email@lo.com" class="input-dark" required />
        </div>

        <div>
          <label class="label-dark">Nomor Telepon</label>
          <input v-model="form.phone" type="tel" placeholder="0812xxxx" class="input-dark" required />
        </div>

        <div>
          <label class="label-dark">Password</label>
          <input v-model="form.password" type="password" placeholder="••••••••" class="input-dark" required />
        </div>

        <div>
          <label class="label-dark">Konfirmasi Password</label>
          <input v-model="form.password_confirmation" type="password" placeholder="••••••••" class="input-dark" required />
        </div>

        <button type="submit" :disabled="loading" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 rounded-xl uppercase tracking-widest shadow-lg shadow-rose-900/20 transition">
          {{ loading ? 'Mendaftar...' : 'DAFTAR SEKARANG' }}
        </button>
      </form>

      <div class="mt-8 text-center border-t border-gray-800 pt-6">
        <p class="text-sm text-gray-500">
          Udah punya akun? 
          <RouterLink to="/login" class="text-white font-bold hover:text-rose-500 transition">Login aja</RouterLink>
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
const form = ref({ name: '', email: '', phone: '', password: '', password_confirmation: '' })
const loading = ref(false)

const register = async () => {
  loading.value = true
  try {
    // MENGIRIM DATA SESUAI PERMINTAAN CONTROLLER
    await axios.post('/api/register', {
      nama_lengkap: form.value.name,       // Backend minta 'nama_lengkap'
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
      nomor_telepon: form.value.phone      // Backend minta 'nomor_telepon'
    })
    
    // Hapus keranjang lama biar bersih
    localStorage.removeItem('cart');
    window.dispatchEvent(new Event('cart-updated'));

    // SweetAlert Sukses
    Swal.fire({
      icon: 'success',
      title: 'Berhasil Gabung!',
      text: 'Akun lo udah jadi. Silakan login.',
      background: '#151515',
      color: '#fff',
      iconColor: '#e11d48',
      confirmButtonColor: '#e11d48'
    }).then(() => {
      router.push('/login')
    })

  } catch (err) {
    const msg = err.response?.data?.message || 'Gagal mendaftar.'
    // SweetAlert Error
    Swal.fire({
      icon: 'error',
      title: 'Waduh...',
      text: msg,
      background: '#151515',
      color: '#fff',
      confirmButtonColor: '#e11d48'
    });
  } finally { loading.value = false }
}
</script>

<style scoped>
.label-dark { @apply text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 block; }
.input-dark { @apply w-full bg-[#050505] border border-gray-700 text-white px-4 py-3 rounded-xl focus:border-rose-600 focus:ring-1 focus:ring-rose-600 outline-none transition placeholder-gray-700; }
</style>