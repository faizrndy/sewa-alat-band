<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white pt-28 pb-10">
    <Navbar />
    <div class="max-w-3xl mx-auto px-6">
      <h1 class="text-4xl font-black italic text-center mb-10 uppercase tracking-tighter">
        Sering <span class="text-rose-600">Ditanyain</span>
      </h1>

      <div class="space-y-4">
        <div v-for="(faq, i) in faqs" :key="i" class="bg-[#151515] border border-gray-800 rounded-2xl overflow-hidden">
          <button @click="active = active === i ? null : i" class="w-full px-6 py-4 text-left font-bold flex justify-between items-center hover:bg-gray-800 transition">
            {{ faq.q }}
            <i :class="`fas fa-chevron-down transition ${active === i ? 'rotate-180 text-rose-600' : ''}`"></i>
          </button>
          <div v-if="active === i" class="px-6 pb-6 text-gray-400 text-sm leading-relaxed border-t border-gray-800 pt-4">
            {{ faq.a }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Navbar from "@/components/Navbar.vue";

const active = ref(null)
const faqs = [
  { q: 'Cara sewa gimana bang?', a: 'Pilih alat di katalog, masukin keranjang, checkout, terus upload KTP. Beres!' },
  { q: 'Bisa antar ke lokasi?', a: 'Bisa banget. Tinggal pilih opsi "Antar Kurir" pas checkout, nanti ongkir diitung otomatis.' },
  { q: 'Kalo rusak gimana?', a: 'Kerusakan wajar (senar putus dll) kami tanggung. Tapi kalo jatoh/pecah karena kelalaian, lo yang ganti ya bro.' }
]
</script>