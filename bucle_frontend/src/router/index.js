import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  { path: '/', redirect: '/workspace' },
  { path: '/workspace', name: 'workspace-home', component: () => {} }, // placeholder
  { path: '/workspace/:categoryName/:subCategoryName?', name: 'workspace', component: () => {} }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;