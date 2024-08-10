<template>
    <div class="container">
        <h1 class="mb-4">Manage Users</h1>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="userStore.users.length" v-for="user in userStore.users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.username }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.user_role }}</td>
                        <td>
                            <button @click="showExpenses(user.id)" class="btn btn-info">View Expenses</button>
                        </td>
                    </tr>
                    <tr v-else>
                        <td colspan="5" class="text-center">No Data Found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { useUserStore } from '../../stores/userStore';
import { useExpenseStore } from '../../stores/expenseStore';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue';

export default {
    name: 'ViewUsers',
    setup() {
        const userStore = useUserStore();
        const expenseStore = useExpenseStore();
        const router = useRouter();

        onMounted(() => {
            userStore.fetchUsers();
        });

        const showExpenses = async (id) => {
            await expenseStore.fetchExpensesByUserId(id);
            router.push(`/admin/expenses`);
        };

        return {
            userStore,
            expenseStore,
            showExpenses
        };
    }
};
</script>

<style scoped>
.container {
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    padding: 20px;
    border-radius: 10px;
}

h1 {
    background: linear-gradient(to right, #007bff, #6610f2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.table {
    background-color: #fff;
    border-radius: 10px;
}
</style>
