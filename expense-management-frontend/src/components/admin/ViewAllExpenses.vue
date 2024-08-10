<template>
    <div class="container">
        <h1 class="mb-4">All Expenses</h1>
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="user-select" class="form-label">Select User</label>
                <select id="user-select" class="form-select" v-model="selectedUser" @change="fetchExpenses">
                    <option value="">All Users</option>
                    <option v-for="user in userStore.users" :key="user.id" :value="user.id">
                        {{ user.username }}
                    </option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="category-select" class="form-label">Select Category</label>
                <select id="category-select" class="form-select" v-model="selectedCategory" @change="fetchExpenses">
                    <option value="">All Categories</option>
                    <option v-for="category in categoryStore.categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Amount (€)</th>
                        <th>Category</th>
                        <th>Username</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="expenseStore.expenses.length" v-for="expense in expenseStore.expenses" :key="expense.id">
                        <td>{{ expense.id }}</td>
                        <td>{{ expense.title }}</td>
                        <td>{{ expense.amount }}</td>
                        <td>{{ expense.category_name }}</td>
                        <td>{{ expense.username }}</td>
                        <td>{{ expense.created_at }}</td>
                    </tr>
                    <tr v-else>
                        <td colspan="6" class="text-center">No Data Found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { useExpenseStore } from '../../stores/expenseStore';
import { useUserStore } from '../../stores/userStore';
import { useCategoryStore } from '../../stores/categoryStore';
import { onMounted, ref } from 'vue';

export default {
    name: 'ViewAllExpenses',
    setup() {
        const expenseStore = useExpenseStore();
        const userStore = useUserStore();
        const categoryStore = useCategoryStore();
        const selectedUser = ref('');
        const selectedCategory = ref('');

        onMounted(() => {
            expenseStore.expenses = [];
            userStore.fetchUsers();
            categoryStore.fetchCategories();
        });

        const fetchExpenses = () => {
            if (selectedUser.value) {
                expenseStore.fetchExpensesByUserId(selectedUser.value);
            } else if (selectedCategory.value) {
                expenseStore.fetchExpensesByCategoryId(selectedCategory.value);
            } else {
                expenseStore.expenses = [];
                console.error('No user or category selected.');
            }
        };

        return {
            expenseStore,
            userStore,
            categoryStore,
            selectedUser,
            selectedCategory,
            fetchExpenses
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