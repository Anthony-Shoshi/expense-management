<template>
  <div class="container">
    <h1 class="mb-4">Edit Category</h1>
    <form @submit.prevent="submitForm" class="bg-light p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input id="name" v-model="category.name" class="form-control" required>
      </div>
      <div class="text-start">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</template>

<script>
import { useCategoryStore } from '../../stores/categoryStore';
import { onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';

export default {
  name: 'EditCategory',
  setup() {
    const categoryStore = useCategoryStore();
    const router = useRouter();
    const route = useRoute();
    const categoryId = route.params.id;

    onMounted(() => {
      const category = categoryStore.categories.find(category => category.id == categoryId);
      if (category) {
        categoryStore.setCategory(category.name);
      }
    });

    const submitForm = async () => {
      await categoryStore.updateCategory(categoryId, categoryStore.category.name);
      categoryStore.setCategory('');
      router.push('/admin/categories');
    };

    return {
      category: categoryStore.category,
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
