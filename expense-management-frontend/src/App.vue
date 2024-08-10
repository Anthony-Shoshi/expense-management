<template>
  <div>
    <navigation v-if="isLoggedIn" />
    <main class="container-fluid">
      <router-view />
    </main>
  </div>
</template>

<script>
import { computed } from 'vue';
import { useAuthStore } from './stores/authStore';
import Navigation from './components/Navigation.vue';

export default {
  name: "App",
  components: {
    Navigation
  },
  setup() {
    const authStore = useAuthStore();
    const isLoggedIn = computed(() => authStore.isLoggedIn);

    return { isLoggedIn };
  }
};
</script>

<style>
/* Your global styles */
</style>
