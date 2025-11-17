<template>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
      <!-- Header -->
     <Navbar />
      <!-- Content -->
      <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold text-blue-700 mb-6 text-center">Syarat & Ketentuan</h1>
          <p class="text-slate-600 mb-10 text-center">
            Harap membaca dan memahami ketentuan berikut sebelum melakukan pemesanan alat musik di Kratak FC.
            Dengan menggunakan layanan kami, Anda dianggap telah menyetujui seluruh poin di bawah ini.
          </p>

          <div class="space-y-6">
            <div v-for="(term, index) in terms" :key="index" class="bg-white shadow-md rounded-2xl border border-blue-100 p-6">
              <h2 class="text-blue-600 font-semibold mb-2">
                {{ index + 1 }}. {{ term.title }}
              </h2>
              <p class="text-slate-600 leading-relaxed">
                {{ term.description }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Footer -->
      <footer class="bg-slate-900 text-white py-10 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-slate-400">
          <p>© 2025 Kratak FC. All rights reserved.</p>
        </div>
      </footer>
    </div>
  </template>

  <script setup>
  import { Music2 } from 'lucide-vue-next'

import Navbar from "../components/Navbar.vue";

  const terms = [
    {
      title: 'Persyaratan Penyewa',
      description:
        'Penyewa wajib memiliki identitas diri resmi (KTP atau SIM) dan berusia minimal 18 tahun. Data ini digunakan untuk proses verifikasi sebelum transaksi sewa dilakukan.',
    },
    {
      title: 'Ketentuan Pemesanan',
      description:
        'Pemesanan dilakukan minimal satu hari sebelum tanggal penggunaan alat. Pembatalan mendadak kurang dari 24 jam akan dikenakan biaya administrasi sebesar 20% dari total biaya sewa.',
    },
    {
      title: 'Pembayaran dan Kebijakan DP',
      description:
        'Setiap penyewaan memerlukan pembayaran DP minimal 50% dari total biaya sewa. Sisa pembayaran wajib dilunasi sebelum alat dikirim atau diambil.',
    },
    {
      title: 'Metode Pembayaran',
      description:
        'Pembayaran dapat dilakukan melalui transfer bank resmi Kratak FC atau secara tunai di lokasi. Kami tidak bertanggung jawab atas pembayaran yang dilakukan di luar jalur resmi.',
    },
    {
      title: 'Ketentuan Tambahan',
      description:
        'Penyewa bertanggung jawab atas kerusakan atau kehilangan alat selama masa sewa. Jika alat rusak karena kelalaian penyewa, biaya perbaikan akan ditanggung oleh penyewa.',
    },
  ]
  </script>
