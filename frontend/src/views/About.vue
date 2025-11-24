<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white pt-20">
    <Navbar />

    <section class="bg-[#111] py-20 text-center relative overflow-hidden border-b border-gray-800">
      <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
      <div class="relative max-w-3xl mx-auto px-6 z-10">
        <h1 class="text-4xl md:text-6xl font-black italic uppercase tracking-tighter mb-4">
          Tentang <span class="text-rose-600">Kratak FC</span>
        </h1>
        <p class="text-gray-400 text-lg">
          Markas sewa alat musik paling solid se-Jakarta sejak 2015.
        </p>
      </div>
    </section>

    <section class="py-20">
      <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
        <div>
          <h2 class="text-3xl font-bold uppercase italic mb-4">Siapa Kami?</h2>
          <p class="text-gray-400 mb-4 leading-relaxed">
            Kratak FC hadir buat lo yang butuh gear panggung sadis tapi budget tipis. Kami bukan cuma tempat sewa, kami partner gigs lo.
          </p>
          <p class="text-gray-400 leading-relaxed">
            Mulai dari gitar, bass, drum, sampai sound system ribuan watt, semua ready stock dan dirawat pake hati.
          </p>
        </div>
        <div class="relative">
          <img src="/images/logo1.png" class="w-full max-w-xs mx-auto drop-shadow-[0_0_30px_rgba(225,29,72,0.3)] animate-pulse" />
        </div>
      </div>
    </section>

    <section class="py-20 bg-[#111] border-y border-gray-800">
      <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-10 uppercase italic">Kenapa Harus Kami?</h2>
        <div class="grid md:grid-cols-4 gap-8">
          <div class="p-6 bg-black rounded-2xl border border-gray-800">
            <i class="fas fa-star text-3xl text-rose-600 mb-4"></i>
            <h3 class="font-bold text-white mb-2">Gear Sultan</h3>
            <p class="text-gray-500 text-sm">Alat original & terawat.</p>
          </div>
          <div class="p-6 bg-black rounded-2xl border border-gray-800">
            <i class="fas fa-truck text-3xl text-rose-600 mb-4"></i>
            <h3 class="font-bold text-white mb-2">Antar Jemput</h3>
            <p class="text-gray-500 text-sm">Lo duduk manis, alat sampai.</p>
          </div>
          <div class="p-6 bg-black rounded-2xl border border-gray-800">
             <i class="fas fa-shield-alt text-3xl text-rose-600 mb-4"></i>
            <h3 class="font-bold text-white mb-2">Aman 100%</h3>
            <p class="text-gray-500 text-sm">Data & transaksi terjamin.</p>
          </div>
          <div class="p-6 bg-black rounded-2xl border border-gray-800">
             <i class="fas fa-tag text-3xl text-rose-600 mb-4"></i>
            <h3 class="font-bold text-white mb-2">Harga Teman</h3>
            <p class="text-gray-500 text-sm">Murah tapi gak murahan.</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import Navbar from "@/components/Navbar.vue";
</script>