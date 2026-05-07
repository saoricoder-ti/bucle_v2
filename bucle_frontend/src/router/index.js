import { createRouter, createWebHistory } from 'vue-router';
import DashboardPrincipal from '../views/DashboardPrincipal.vue';

const routes = [
  { path: '/', redirect: '/dashboard' },
  { path: '/dashboard', name: 'Dashboard', component: DashboardPrincipal }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;