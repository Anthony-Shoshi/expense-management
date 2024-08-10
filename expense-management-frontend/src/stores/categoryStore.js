import { defineStore } from 'pinia';
import axios from 'axios';

export const useCategoryStore = defineStore({
    id: 'category',
    state: () => ({
        categories: [],
        category: { name: '' },
    }),
    actions: {
        async fetchCategories() {
            try {
                const response = await axios.get('/categories');
                this.categories = response.data;
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },
        async createCategory(name) {
            try {
                const response = await axios.post('/categories', { name });
                this.categories.push(response.data);
                await this.fetchCategories();
            } catch (error) {
                console.error("Error creating category:", error);
            }
        },
        async updateCategory(id, name) {
            try {
                await axios.put(`/categories/${id}`, { name });
                await this.fetchCategories();
            } catch (error) {
                console.error("Error updating category:", error);
            }
        },
        async deleteCategory(id) {
            try {
                await axios.delete(`/categories/${id}`);
                await this.fetchCategories();
            } catch (error) {
                console.error("Error deleting category:", error);
            }
        },
        setCategory(name) {
            this.category.name = name;
        }
    }
});
