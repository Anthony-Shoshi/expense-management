<template>
  <nav class="navbar navbar-expand-lg bg-nav">
    <div class="container-fluid">
      <router-link class="navbar-brand fw-bold" :to="adminOrUserDashboard">Expenso</router-link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li v-if="!isLoggedIn" class="nav-item">
            <router-link class="nav-link" to="/login">Login</router-link>
          </li>
          <li v-if="isLoggedIn" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle"></i> <span>{{ userName }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <router-link class="dropdown-item" :to="adminOrUserDashboard">Dashboard</router-link>
              </li>
              <li>
                <button type="button" class="dropdown-item" @click="logout">Logout</button>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { useAuthStore } from '../stores/authStore';
import { computed } from 'vue';
import { useRouter } from 'vue-router';

export default {
  name: "Navigation",
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();
    
    const isLoggedIn = computed(() => authStore.isLoggedIn);
    const userName = computed(() => authStore.user ? authStore.user.username : '');
    
    const adminOrUserDashboard = computed(() => {
      return authStore.user && authStore.user.user_role === 'admin' ? '/admin' : '/user';
    });

    const logout = () => {
      authStore.logout();
      router.push("/login");
    };

    return {
      isLoggedIn,
      userName,
      adminOrUserDashboard,
      logout
    };
  }
};
</script>

<style scoped>
  .bg-nav {
    background: linear-gradient(to right, #6610f2, #007bff);
  }
</style>
