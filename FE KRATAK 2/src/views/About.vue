<template>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
      <!-- Header -->
      <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-lg border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
          <div class="flex items-center gap-2">
            <Music2 class="w-7 h-7 text-blue-600" />
            <span class="text-slate-900 font-semibold text-lg">Kratak FC</span>
          </div>
  
          <nav class="hidden md:flex items-center gap-8">
            <router-link to="/" class="text-slate-600 hover:text-blue-600">Beranda</router-link>
            <router-link to="/katalog" class="text-slate-600 hover:text-blue-600">Katalog</router-link>
            <router-link to="/about" class="text-slate-600 hover:text-blue-600 transition-colors">
  About
</router-link>

            <a href="#faq" class="text-slate-600 hover:text-blue-600">FAQ</a>
            <router-link to="/terms" class="text-slate-600 hover:text-blue-600 transition-colors">
  Syarat & Ketentuan
</router-link>

          </nav>
  
          <Button class="bg-blue-600 hover:bg-green-500">contact us</Button>
        </div>
      </header>
  
      <!-- Hero -->
      <section class="bg-gradient-to-br from-blue-400 via-blue-500 to-indigo-500 text-white py-20 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAi...')]"></div>
        <div class="relative max-w-3xl mx-auto px-6">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">Tentang Kratak FC</h1>
          <p class="text-blue-100 text-lg">
            Partner terpercaya dalam penyewaan alat musik dan kebutuhan band sejak 2015.
          </p>
        </div>
      </section>
  
      <!-- Siapa Kami -->
      <section class="py-20">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
          <div>
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Siapa Kami</h2>
            <p class="text-slate-600 mb-4 leading-relaxed">
              Kratak FC adalah platform penyewaan alat band yang berbasis di Jakarta, hadir untuk musisi, event organizer,
              dan pencinta musik yang membutuhkan alat profesional dengan harga terjangkau.
            </p>
            <p class="text-slate-600 leading-relaxed">
              Kami menyediakan berbagai alat band berkualitas — dari gitar, drum, bass, keyboard, hingga sound system —
              lengkap dengan layanan pengantaran dan instalasi di tempat Anda.
            </p>
          </div>
  
          <div class="relative">
            <div class="rounded-2xl overflow-hidden shadow-xl border border-blue-100">
              <img src="https://source.unsplash.com/600x400/?band,rehearsal" alt="Tentang Kratak FC" class="object-cover w-full h-full" />
            </div>
            <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-blue-100 rounded-2xl -z-10"></div>
          </div>
        </div>
      </section>
  
      <!-- Kenapa Memilih Kami -->
      <section class="py-20 bg-blue-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
          <h2 class="text-3xl font-bold text-slate-900 mb-10">Kenapa Memilih Kratak FC?</h2>
  
          <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="(item, i) in reasons" :key="i" class="p-6 bg-white rounded-2xl shadow hover:shadow-lg transition">
              <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center bg-blue-100 rounded-full">
                <component :is="item.icon" class="w-6 h-6 text-blue-600" />
              </div>
              <h3 class="font-semibold text-slate-900 mb-2">{{ item.title }}</h3>
              <p class="text-slate-600 text-sm leading-relaxed">{{ item.desc }}</p>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Kontak & Lokasi -->
      <section class="py-20 bg-gradient-to-br from-blue-100 via-blue-50 to-white">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
          <div>
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Hubungi Kami</h2>
            <p class="text-slate-600 mb-6">
              Punya pertanyaan atau ingin melakukan pemesanan langsung?  
              Tim kami siap membantu Anda 24/7.
            </p>
  
            <form class="space-y-4">
              <input type="text" placeholder="Nama Lengkap" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none" />
              <input type="email" placeholder="Email Anda" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none" />
              <textarea placeholder="Pesan Anda" rows="4" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
              <Button class="bg-blue-600 hover:bg-blue-700">Kirim Pesan</Button>
            </form>
          </div>
  
          <div class="h-[400px] rounded-2xl overflow-hidden border border-blue-100 shadow">
            <iframe
              class="w-full h-full"
              style="border:0"
              loading="lazy"
              allowfullscreen
              referrerpolicy="no-referrer-when-downgrade"
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.626795054266!2d106.81428687475266!3d-6.180548660614109!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5cc39ab0d2f%3A0x5fbcbdabf8ea5f6c!2sJakarta!5e0!3m2!1sid!2sid!4v1696952476555!5m2!1sid!2sid"
            ></iframe>
          </div>
        </div>
      </section>
  
      <!-- Footer -->
      <footer class="bg-slate-900 text-white py-10">
        <div class="text-center text-slate-400 text-sm">
          © 2025 Kratak FC. All rights reserved.
        </div>
      </footer>
    </div>
  </template>
  
  <script setup>
  import { Music2, Star, Check, Package, Shield } from 'lucide-vue-next'
  import Button from '../components/ui/button.vue'
  
  const reasons = [
    { icon: Star, title: 'Kualitas Premium', desc: 'Semua alat musik selalu dalam kondisi terbaik dengan perawatan rutin.' },
    { icon: Package, title: 'Pengiriman Cepat', desc: 'Tersedia layanan antar dan jemput alat langsung ke lokasi acara.' },
    { icon: Shield, title: 'Keamanan Terjamin', desc: 'Setiap penyewaan dilindungi oleh sistem keamanan dan jaminan alat.' },
    { icon: Check, title: 'Harga Transparan', desc: 'Tidak ada biaya tersembunyi, semua biaya ditampilkan dengan jelas.' },
  ]
  </script>
  