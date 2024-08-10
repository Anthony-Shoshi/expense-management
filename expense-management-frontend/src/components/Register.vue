<template>
  <section class="vh-100" style="background-color: #f8f9fa;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card shadow-2-strong" style="border-radius: 1rem;">
            <div class="card-body p-5">
              <img src="/img/logo.png" alt="logo" class="img-fluid mx-auto mb-4 d-block" width="80" height="80">
              <h3 class="mb-4 text-center">Sign Up</h3>
              <form @submit.prevent="submitForm">
                <div v-if="errorMessage" class="alert alert-danger" role="alert">
                  {{ errorMessage }}
                </div>
                <div class="form-outline mb-4">
                  <label for="inputUsername" class="form-label float-start">Username</label>
                  <input id="inputUsername" type="text" class="form-control form-control-lg" v-model="username"
                    required />
                </div>
                <div class="form-outline mb-4">
                  <label for="inputEmail" class="form-label float-start">Email</label>
                  <input id="inputEmail" type="email" class="form-control form-control-lg" v-model="email" required />
                </div>
                <div class="form-outline mb-4">
                  <label for="inputPassword" class="form-label float-start">Password</label>
                  <input id="inputPassword" type="password" class="form-control form-control-lg" v-model="password"
                    required />
                </div>
                <button class="btn btn-primary btn-lg btn-block" type="submit">Sign Up</button>
              </form>
              <div class="mt-3">
                <p>Already have an account? <router-link to="/login" class="text-primary">Login</router-link></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { useAuthStore } from '../stores/authStore';

export default {
  name: "Register",
  data() {
    return {
      username: "",
      email: "",
      password: "",
      errorMessage: "",
    };
  },
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  },
  methods: {
    async submitForm() {
      try {
        await this.authStore.register(this.username, this.email, this.password);
        this.$router.push("/user");
      } catch (error) {
        this.errorMessage = error.response.data.errorMessage || "An error occurred. Please try again.";
      }
    },
  },
};
</script>

<style scoped>
.vh-100 {
  height: 100vh;
}
</style>
