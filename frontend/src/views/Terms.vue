<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white pt-24 pb-12 font-sans selection:bg-rose-500 selection:text-white">
    <Navbar />

    <div class="max-w-4xl mx-auto px-6 relative z-10">
      
      <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-black italic uppercase tracking-tighter mb-6">
          Syarat & <span class="text-rose-600">Ketentuan</span>
        </h1>
        <p class="text-gray-400 text-lg max-w-2xl mx-auto leading-relaxed">
          Harap baca aturan main di Kratak FC sebelum sewa gear. 
          Biar sama-sama enak, gigs lancar, alat aman.
        </p>
      </div>

      <div class="space-y-6">
        <div 
          v-for="(term, index) in terms" 
          :key="index" 
          class="group bg-[#151515] p-8 rounded-3xl border border-gray-800 hover:border-rose-600/50 transition-all duration-300 hover:shadow-[0_0_20px_rgba(225,29,72,0.1)]"
        >
          <div class="flex items-start gap-6">
            <div class="w-12 h-12 flex-shrink-0 bg-gray-800 text-rose-500 rounded-xl flex items-center justify-center text-xl font-black group-hover:bg-rose-600 group-hover:text-white transition duration-300">
              {{ index + 1 }}
            </div>

            <div>
              <h2 class="text-xl font-bold text-white mb-3 uppercase tracking-wide group-hover:text-rose-500 transition">
                {{ term.title }}
              </h2>
              <p class="text-gray-400 leading-relaxed text-sm md:text-base">
                {{ term.description }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-12 p-6 bg-rose-600/10 border border-rose-600/30 rounded-2xl text-center">
        <p class="text-rose-200 text-sm font-medium">
          <i class="fas fa-info-circle mr-2"></i> Dengan melakukan transaksi sewa, Anda dianggap telah menyetujui seluruh poin di atas.
        </p>
      </div>

    </div>

    <footer class="mt-20 border-t border-gray-900 pt-10 text-center">
      <p class="text-gray-600 text-sm">© 2025 Kratak FC. Keep Rocking!</p>
    </footer>
  </div>
</template>

<script setup>
import Navbar from "@/components/Navbar.vue";

const terms = [
  {
    title: 'Persyaratan Penyewa',
    description:
      'Penyewa wajib memiliki identitas diri resmi (KTP atau SIM) dan berusia minimal 18 tahun. Dokumen asli wajib dibawa saat pengambilan alat sebagai jaminan keamanan.',
  },
  {
    title: 'Ketentuan Pemesanan',
    description:
      'Booking minimal dilakukan 1 hari sebelum tanggal penggunaan (H-1). Pembatalan mendadak kurang dari 24 jam akan dikenakan biaya administrasi sebesar 20% dari total sewa.',
  },
  {
    title: 'Kebijakan Pembayaran',
    description:
      'Sewa alat wajib lunas sebelum alat dibawa keluar dari studio atau sebelum dikirim oleh kurir kami. Kami menerima pembayaran Cash, Transfer Bank, dan QRIS.',
  },
  {
    title: 'Tanggung Jawab Kerusakan',
    description:
      'Penyewa bertanggung jawab penuh atas alat selama masa sewa. Kerusakan akibat kelalaian (jatuh, tersiram air, pecah) wajib diganti sesuai harga sparepart/service. Kerusakan teknis alami (senar putus wajar) ditanggung kami.',
  },
  {
    title: 'Keterlambatan Pengembalian',
    description:
      'Pengembalian yang melebihi durasi sewa akan dikenakan biaya overtime. Keterlambatan lebih dari 3 jam dihitung sebagai perpanjangan sewa 1 hari penuh.',
  },
]
</script>