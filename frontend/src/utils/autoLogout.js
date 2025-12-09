const MAX_IDLE_TIME = 10 * 60 * 1000 // 10 menit

export function startAutoLogout(router) {
  // simpan aktivitas terakhir
  const updateActivity = () => {
    localStorage.setItem('lastActivity', Date.now())
  }

  // dengarkan aktivitas user
  window.addEventListener('mousemove', updateActivity)
  window.addEventListener('keydown', updateActivity)
  window.addEventListener('click', updateActivity)

  // cek setiap 30 detik
  setInterval(() => {
    const last = localStorage.getItem('lastActivity')
    const token = localStorage.getItem('buyer_token')

    if (!last || !token) return

    if (Date.now() - last > MAX_IDLE_TIME) {
      localStorage.clear()
      alert('Sesi anda telah berakhir. Silakan login kembali.')
      router.push('/login')
    }
  }, 30000)
}
