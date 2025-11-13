import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Katalog from '../views/Katalog.vue'
import About from '../views/About.vue'
import Terms from '../views/Terms.vue'

const routes = [
  { path: '/', name: 'Home', component: Home },

  // 🟢 Route untuk katalog utama
  { path: '/katalog', name: 'Katalog', component: Katalog },

  // 🟣 Route untuk detail alat
  {
    path: '/katalog/:id',
    name: 'DetailAlat',
    component: () => import('../views/DetailAlat.vue'),
    props: true,
  },

  { path: '/about', name: 'About', component: About },
  { path: '/terms', name: 'Terms', component: Terms },
  { path: '/keranjang', name: 'Keranjang', component: () => import('../views/Keranjang.vue') },

]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
