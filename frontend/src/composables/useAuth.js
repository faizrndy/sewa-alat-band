// src/composables/useAuth.js
import { ref } from 'vue'
import axios from 'axios'

const user = ref(null)
const token = ref(localStorage.getItem('buyer_token'))

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
})

// Intercept token
api.interceptors.request.use(config => {
  if (token.value) {
    config.headers.Authorization = `Bearer ${token.value}`
  }
  return config
})

export function useAuth() {
  const login = async (email, password) => {
    try {
      const response = await api.post('/buyer/login', { email, password })
      user.value = response.data.user
      token.value = response.data.token
      localStorage.setItem('buyer_token', token.value)
      return { success: true }
    } catch (error) {
      const message = error.response?.data?.message || 'Login gagal'
      return { success: false, message }
    }
  }

  const register = async (name, email, password, phone) => {
    try {
      const response = await api.post('/buyer/register', { name, email, password, phone })
      user.value = response.data.user
      token.value = response.data.token
      localStorage.setItem('buyer_token', token.value)
      return { success: true }
    } catch (error) {
      const message = error.response?.data?.message || 'Register gagal'
      return { success: false, message }
    }
  }

  const logout = () => {
    user.value = null
    token.value = null
    localStorage.removeItem('buyer_token')
  }

  const fetchUser = async () => {
    try {
      const response = await api.get('/buyer/me')
      user.value = response.data
      return response.data
    } catch (error) {
      logout()
      throw error
    }
  }

  return {
    user,
    token,
    login,
    register,
    logout,
    fetchUser
  }
}