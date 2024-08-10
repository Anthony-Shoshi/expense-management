import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export const useAuthStore = defineStore('auth', () => {
    const isLoggedIn = ref(false);
    const user = ref(null);
    const jwt = ref(null);
    const router = useRouter();

    const loginSuccess = (token, userData) => {
        isLoggedIn.value = true;
        user.value = userData;
        jwt.value = token;
        localStorage.setItem('jwt', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        if (userData.user_role === 'admin') {
            router.push('/admin');
        } else {
            router.push('/user');
        }
    };

    const logout = () => {
        isLoggedIn.value = false;
        user.value = null;
        jwt.value = null;
        localStorage.removeItem('jwt');
        delete axios.defaults.headers.common['Authorization'];
        router.push('/login');
    };

    const login = async (username, password) => {
        try {
            const response = await axios.post("/login", { username, password });
            if (response.status === 200) {
                loginSuccess(response.data.jwt, response.data.user);
            } else {
                throw new Error(response.data.message || "Login failed.");
            }
        } catch (error) {
            throw error;
        }
    };

    const register = async (username, email, password) => {
        try {
            const response = await axios.post("/register", { username, email, password });
            if (response.status === 200) {
                loginSuccess(response.data.jwt, response.data.user);
            } else {
                throw new Error(response.data.message || "Registration failed.");
            }
        } catch (error) {
            throw error;
        }
    };

    return {
        isLoggedIn,
        user,
        jwt,
        loginSuccess,
        logout,
        login,
        register,
    };
});
