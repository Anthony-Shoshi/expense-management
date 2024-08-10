<template>
  <div class="container">
    <h1 class="mb-4">Edit Expense</h1>
    <form @submit.prevent="submitForm" class="bg-light p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="title" class="form-label">Title:</label>
        <input id="title" v-model="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="amount" class="form-label">Amount:</label>
        <input id="amount" step="0.01" min="0" type="number" v-model="amount" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="category" class="form-label">Category:</label>
        <select id="category" v-model="selectedCategory" class="form-control" required>
          <option value="" disabled>Select a category</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
        </select>
      </div>
      <div class="text-start">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useExpenseStore } from '../../../stores/expenseStore';
import { useCategoryStore } from '../../../stores/categoryStore';

export default {
  name: 'EditExpense',
  setup() {
    const expenseStore = useExpenseStore();
    const categoryStore = useCategoryStore();
    const router = useRouter();
    const route = useRoute();

    const title = ref('');
    const amount = ref('');
    const selectedCategory = ref('');
    const categories = ref([]);

    onMounted(async () => {
      await categoryStore.fetchCategories();
      categories.value = categoryStore.categories;

      const expenseId = route.params.id;
      try {
        const expense = await expenseStore.getExpense(expenseId);
        title.value = expense.title;
        amount.value = expense.amount;
        selectedCategory.value = expense.category_id;
      } catch (error) {
        console.error('Error fetching expense:', error);
      }
    });

    const submitForm = async () => {
      const expenseId = route.params.id;
      const updatedExpense = {
        title: title.value,
        amount: amount.value,
        category_id: selectedCategory.value
      };

      try {
        await expenseStore.updateExpense(expenseId, updatedExpense);
        router.push('/user/expenses');
      } catch (error) {
        console.error('Error updating expense:', error);
      }
    };

    return {
      title,
      amount,
      selectedCategory,
      categories,
      submitForm
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
</style>
