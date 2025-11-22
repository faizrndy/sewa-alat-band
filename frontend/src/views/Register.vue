<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
      <div class="text-center mb-6">
        <img src="/images/logo1.png" alt="Kratak FC" class="w-20 h-20 mx-auto mb-4" />
        <h2 class="text-2xl font-bold text-slate-900">Daftar Buyer</h2>
      </div>

      <form @submit.prevent="register" class="space-y-6">
        <!-- Nama Lengkap -->
        <div>
          <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            placeholder="John Doe"
            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            required
          />
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            placeholder="email@example.com"
            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            required
          />
        </div>

        <!-- Nomor Telepon -->
        <div>
          <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
          <input
            id="phone"
            v-model="form.phone"
            type="tel"
            placeholder="+628123456789"
            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            required
          />
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            placeholder="••••••••"
            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            required
          />
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            placeholder="••••••••"
            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            required
          />
        </div>

        <!-- Error Message -->
        <div v-if="error" class="p-3 bg-red-100 text-red-700 rounded-lg text-sm">
          {{ error }}
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="loading"
          class="w-full px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition disabled:opacity-75 disabled:cursor-not-allowed"
        >
          <span v-if="loading">
            <i class="fas fa-spinner animate-spin mr-2"></i> Mendaftar...
          </span>
          <span v-else>Daftar</span>
        </button>
      </form>

      <!-- Link ke Login -->
      <div class="mt-6 text-center">
        <p class="text-sm text-slate-600">
          Sudah punya akun? 
          <RouterLink to="/login" class="font-medium text-blue-600 hover:text-blue-700">Login</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { register: authRegister } = useAuth()

const form = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: ''
})
const loading = ref(false)
const error = ref('')

const register = async () => {
  loading.value = true
  error.value = ''
  try {
    await authRegister(form.name, form.email, form.password, form.phone)
    router.push('/login')
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal mendaftar. Coba lagi.'
  } finally {
    loading.value = false
  }
}
</script>