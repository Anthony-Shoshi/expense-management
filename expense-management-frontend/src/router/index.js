import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/authStore';

import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import CreateCategory from '../components/categories/CreateCategory.vue';
import EditCategory from '../components/categories/EditCategory.vue';
import AdminDashboard from '../components/admin/AdminDashboard.vue';
import ManageCategories from '../components/admin/ManageCategories.vue';
import ViewUsers from '../components/admin/ViewUsers.vue';
import ViewAllExpenses from '../components/admin/ViewAllExpenses.vue';
import AdminDashboardHome from '../components/admin/AdminDashboardHome.vue';
import UserHome from '../components/user/UserHome.vue';
import ExpenseList from '../components/user/expenses/ExpenseList.vue';
import UserDashboard from '../components/user/UserDashboard.vue';
import CreateExpense from '../components/user/expenses/CreateExpense.vue';
import EditExpense from '../components/user/expenses/EditExpense.vue';

const routes = [
  { path: '/', component: Login },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  {
    path: '/admin', component: AdminDashboard, meta: { requiresAdmin: true }, children: [
      { path: '', component: AdminDashboardHome },
      { path: 'categories', component: ManageCategories },
      { path: 'categories/create', component: CreateCategory },
      { path: 'categories/:id/edit', component: EditCategory, props: true },
      { path: 'users', component: ViewUsers },
      { path: 'expenses', component: ViewAllExpenses },
    ]
  },
  {
    path: '/user', component: UserDashboard, meta: { requiresAuth: true }, children: [
      { path: '', component: UserHome },
      { path: 'expenses', component: ExpenseList },
      { path: 'expenses/create', component: CreateExpense },
      { path: 'expenses/:id/edit', component: EditExpense, props: true },
    ]
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  if (to.matched.some(record => record.meta.requiresAdmin)) {
    if (authStore.user?.user_role !== 'admin') {
      next('/login');
    } else {
      next();
    }
  } else if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!authStore.isLoggedIn) {
      next('/login');
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
