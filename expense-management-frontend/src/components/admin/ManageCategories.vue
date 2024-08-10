<template>
  <div class="container">
    <h1 class="mb-4">Manage Categories</h1>
    <div class="text-end mb-3">
      <button @click="navigateToCreate" class="btn btn-success">Create New Category</button>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="categoryStore.categories.length" v-for="category in categoryStore.categories" :key="category.id">
            <td>{{ category.id }}</td>
            <td>{{ category.name }}</td>
            <td>
              <button @click="navigateToEdit(category.id)" class="btn btn-primary btn-sm me-2">Edit</button>
              <button @click="deleteCategory(category.id)" class="btn btn-danger btn-sm me-2">Delete</button>
              <button @click="showExpenses(category.id)" class="btn btn-info">View Expenses</button>
            </td>
          </tr>
          <tr v-else>
            <td colspan="3" class="text-center">No Data Found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { useCategoryStore } from '../../stores/categoryStore';
import { useExpenseStore } from '../../stores/expenseStore';
import { useRouter } from 'vue-router';

export default {
  name: 'ManageCategories',
  setup() {
    const categoryStore = useCategoryStore();
    const expenseStore = useExpenseStore();
    const router = useRouter();

    categoryStore.fetchCategories();

    const navigateToCreate = () => {
      router.push('/admin/categories/create');
    };

    const navigateToEdit = (id) => {
      router.push(`/admin/categories/${id}/edit`);
    };

    const deleteCategory = async (id) => {
      await categoryStore.deleteCategory(id);
      categoryStore.fetchCategories();
    };
    
    const showExpenses = async (id) => {
      await expenseStore.fetchExpensesByCategoryId(id);  
      router.push(`/admin/expenses`);  
    };

    return {
      categoryStore,
      expenseStore,
      navigateToCreate,
      navigateToEdit,
      deleteCategory,
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
