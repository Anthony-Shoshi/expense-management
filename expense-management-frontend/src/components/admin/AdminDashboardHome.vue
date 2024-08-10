<template>
    <div class="container">
        <h2>Welcome to the Admin Dashboard</h2>
        <p class="mb-4">Manage your categories and users efficiently from here. Click on the cards below to
            navigate to the respective sections.</p>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-cat mb-3" @click="navigateToCategories">
                    <div class="card-body">
                        <h5 class="card-title">Total Categories</h5>
                        <p class="card-text">{{ categoryStore.categories.length }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-user mb-3" @click="navigateToUsers">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text">{{ userStore.users.length }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminSidebar from './AdminSidebar.vue';
import { useCategoryStore } from '../../stores/categoryStore';
import { useUserStore } from '../../stores/userStore';
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';

export default {
    name: "AdminDashboard",
    components: {
        AdminSidebar
    },
    setup() {
        const categoryStore = useCategoryStore();
        const userStore = useUserStore();
        const router = useRouter();

        onMounted(() => {
            categoryStore.fetchCategories();
            userStore.fetchUsers();
        });

        const navigateToCategories = () => {
            router.push('/admin/categories');
        };

        const navigateToUsers = () => {
            router.push('/admin/users');
        };

        return {
            categoryStore,
            userStore,
            navigateToCategories,
            navigateToUsers
        };
    }
};
</script>

<style scoped>
.card {
    cursor: pointer;
    transition: transform 0.2s;
}

.card:hover {
    transform: scale(1.05);
}

.bg-cat {
    background: linear-gradient(to right, #63aca2, #03947c);
    ;
}

.bg-user {
    background: linear-gradient(to right, #e2aa32, #f28910);
    ;
}
</style>
