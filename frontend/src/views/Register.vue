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
    await axios.post('/api/register', {
      nama_lengkap: form.value.name,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
      nomor_telepon: form.value.phone 
    })
    
    // 👇 PERBAIKAN DISINI: HAPUS KERANJANG SISA (Jaga-jaga)
    localStorage.removeItem('cart');
    window.dispatchEvent(new Event('cart-updated'));

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