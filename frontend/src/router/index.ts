import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: () => import('../layouts/DefaultLayout.vue'),
      children: [
        {
          path: '',
          name: 'Dashboard',
          component: () => import('../views/dashboard/DashboardView.vue'),
        },
        {
          path: 'produtos',
          name: 'Products',
          component: () => import('../views/products/ProductsView.vue'),
        },
        {
          path: 'vendas',
          name: 'Sales',
          component: () => import('../views/sales/SalesView.vue'),
        },
      ],
    },
  ],
});

export default router;
