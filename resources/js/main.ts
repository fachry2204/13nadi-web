import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import './index.css'
import './App.css'
import { clearSession, getToken, validateSession } from './api'

const Blank = { template: '<span hidden></span>' }
const routes = [
  { path: '/', name: 'home', component: Blank },
  { path: '/rilis', name: 'releases', component: Blank },
  { path: '/berita', name: 'news-list', component: Blank },
  { path: '/foto', name: 'photos', component: Blank },
  { path: '/video', name: 'videos', component: Blank },
  { path: '/kolaborasi', name: 'collaboration', component: Blank },
  { path: '/tentang', name: 'about', component: Blank },
  { path: '/rilisan/:slug', name: 'release', component: Blank },
  { path: '/artis/:slug', name: 'artist', component: Blank },
  { path: '/berita/:slug', name: 'news', component: Blank },
  { path: '/privacy', name: 'privacy', component: Blank },
  { path: '/terms', name: 'terms', component: Blank },
  { path: '/nadiku/login', name: 'login', component: Blank, meta: { guestOnly: true } },
  { path: '/nadiku/slider/tambah', name: 'slider-create', component: Blank, meta: { requiresAuth: true } },
  { path: '/nadiku/slider/:id/edit', name: 'slider-edit', component: Blank, meta: { requiresAuth: true } },
  { path: '/nadiku/:module(tentang|rilisan|artis|berita|foto|video)/tambah', name: 'content-create', component: Blank, meta: { requiresAuth: true } },
  { path: '/nadiku/:module(tentang|rilisan|artis|berita|foto|video)/:id/edit', name: 'content-edit', component: Blank, meta: { requiresAuth: true } },
  { path: '/nadiku/:module?', name: 'admin', component: Blank, meta: { requiresAuth: true } },
  { path: '/404', name: '404', component: Blank },
  { path: '/:pathMatch(.*)*', redirect: '/404' },
]
const router = createRouter({ history: createWebHistory(), routes, scrollBehavior:()=>({top:0}) })
router.beforeEach(async (to) => {
  if (to.meta.requiresAuth) {
    if (!getToken()) return { name: 'login', query: { redirect: to.fullPath } }
    try { await validateSession() } catch (error) { if (error instanceof Error && error.message === 'UNAUTHENTICATED') { clearSession(); return { name: 'login', query: { redirect: to.fullPath } } } }
  }
  if (to.meta.guestOnly && getToken()) {
    try { await validateSession(); return { name: 'admin' } }
    catch (error) { if (error instanceof Error && error.message === 'UNAUTHENTICATED') clearSession() }
  }
})
createApp(App).use(router).mount('#root')
