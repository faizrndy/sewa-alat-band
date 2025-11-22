<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
      <div class="text-center mb-6">
        <img src="/images/logo1.png" alt="Kratak FC" class="w-20 h-20 mx-auto mb-4" />
        <h2 class="text-2xl font-bold text-slate-900">Login Buyer</h2>
      </div>

      <form @submit.prevent="login" class="space-y-6">
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

        <!-- Error Message -->
        <div v-if="error" class="p-3 bg-red-100 text-red-700 rounded-lg text-sm">
          {{ error }}
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="loading"
          class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition disabled:opacity-75 disabled:cursor-not-allowed"
        >
          <span v-if="loading">
            <i class="fas fa-spinner animate-spin mr-2"></i> Memproses...
          </span>
          <span v-else>Login</span>
        </button>
      </form>

      <!-- Link ke Register -->
      <div class="mt-6 text-center">
        <p class="text-sm text-slate-600">
          Belum punya akun? 
          <RouterLink to="/register" class="font-medium text-blue-600 hover:text-blue-700">Daftar sekarang</RouterLink>
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
const { login: authLogin } = useAuth()

const form = ref({ email: '', password: '' })
const loading = ref(false)
const error = ref('')

const login = async () => {
  loading.value = true
  error.value = ''
  try {
    await authLogin(form.email, form.password)
    router.push('/dashboard')
  } catch (err) {
    error.value = 'Email atau password salah'
  } finally {
    loading.value = false
  }
}
</script>