<template>
  <div class="container-fluid">
    <h1 class="mb-4">Manage Expenses</h1>
    <div class="float-end">
      <div class="col-md-12">
        <button @click="navigateToCreate" class="btn btn-success">Add Expense</button>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-2">
        <select v-model="selectedCategory" class="form-control">
          <option value="">All Categories</option>
          <option v-for="category in categoryStore.categories" :key="category.id" :value="category.name">
            {{ category.name }}
          </option>
        </select>
      </div>
      <div class="col-md-2">
        <input type="date" v-model="fromDate" class="form-control" placeholder="From Date">
      </div>
      <div class="col-md-2">
        <input type="date" v-model="toDate" class="form-control" placeholder="To Date">
      </div>
      <div class="col-md-3">
        <input type="number" min="0" v-model="fromAmount" class="form-control" placeholder="From Amount">
      </div>
      <div class="col-md-3">
        <input type="number" min="0" v-model="toAmount" class="form-control" placeholder="To Amount">
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Amount (€)</th>
            <th>Category</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="expense in filteredExpenses" :key="expense.id">
            <td>{{ expense.id }}</td>
            <td>{{ expense.title }}</td>
            <td>{{ expense.amount }}</td>
            <td>{{ expense.category_name }}</td>
            <td>{{ expense.created_at }}</td>
            <td>
              <button @click="editExpense(expense.id)" class="btn btn-primary btn-sm me-2">Edit</button>
              <button @click="deleteExpense(expense.id)" class="btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
          <tr v-if="!filteredExpenses.length">
            <td colspan="6" class="text-center">No Expenses Found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import { ref, computed, onMounted } from 'vue';
import { useExpenseStore } from '../../../stores/expenseStore';
import { useCategoryStore } from '../../../stores/categoryStore';
import { useRouter } from 'vue-router';

export default {
  name: "ExpenseList",
  setup() {
    const expenseStore = useExpenseStore();
    const categoryStore = useCategoryStore();
    const router = useRouter();
    const filter = ref('');
    const selectedCategory = ref('');
    const fromDate = ref('');
    const toDate = ref('');
    const fromAmount = ref('');
    const toAmount = ref('');

    onMounted(async () => {
      await expenseStore.fetchExpenses();
      await categoryStore.fetchCategories();
    });

    const navigateToCreate = () => {
      router.push('/user/expenses/create');
    };

    const editExpense = (id) => {
      router.push(`/user/expenses/${id}/edit`);
    };

    const deleteExpense = async (id) => {
      await expenseStore.deleteExpense(id);
    };

    const applyFilters = () => {

    };

    const filteredExpenses = computed(() => {
      return expenseStore.expenses.filter(expense => {
        const matchesFilter = expense.title.toLowerCase().includes(filter.value.toLowerCase()) ||
          expense.category_name.toLowerCase().includes(filter.value.toLowerCase()) ||
          expense.amount.toString().includes(filter.value) ||
          expense.created_at.includes(filter.value);

        const matchesCategory = !selectedCategory.value || expense.category_name === selectedCategory.value;
        const matchesFromDate = !fromDate.value || new Date(expense.created_at) >= new Date(fromDate.value);
        const matchesToDate = !toDate.value || new Date(expense.created_at) <= new Date(toDate.value);
        const matchesFromAmount = !fromAmount.value || parseFloat(expense.amount) >= parseFloat(fromAmount.value);
        const matchesToAmount = !toAmount.value || parseFloat(expense.amount) <= parseFloat(toAmount.value);

        return matchesFilter && matchesCategory && matchesFromDate && matchesToDate && matchesFromAmount && matchesToAmount;
      });
    });

    return {
      filter,
      filteredExpenses,
      navigateToCreate,
      editExpense,
      deleteExpense,
      selectedCategory,
      fromDate,
      toDate,
      fromAmount,
      toAmount,
      applyFilters,
      categoryStore,
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