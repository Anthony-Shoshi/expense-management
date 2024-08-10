import { defineStore } from 'pinia';
import axios from 'axios';

export const useExpenseStore = defineStore('expense', {
    state: () => ({
        expenses: [],
    }),
    actions: {
        async fetchExpenses() {
            try {
                const response = await axios.get('/expenses');
                this.expenses = response.data;
            } catch (error) {
                console.error('Error fetching expenses:', error);
            }
        },
        async fetchExpense(id) {
            try {
                const response = await axios.get(`/expenses/${id}`);
                return response.data;
            } catch (error) {
                console.error('Error fetching expense:', error);
            }
        },
        async getExpense(id) {
            try {
                const response = await axios.get(`/expenses/${id}`);
                return response.data;
            } catch (error) {
                console.error('Error fetching expense:', error);            
            }
        },
        async createExpense(expense) {
            try {
                const response = await axios.post('/expenses', expense);
                this.expenses.push(response.data);
                return response.data;
            } catch (error) {
                console.error('Error creating expense:', error);
            }
        },
        async updateExpense(id, expense) {
            try {
                const response = await axios.put(`/expenses/${id}`, expense);
                const index = this.expenses.findIndex(e => e.id === id);
                if (index !== -1) {
                    this.expenses[index] = response.data;
                }
                return response.data;
            } catch (error) {
                console.error('Error updating expense:', error);
            }
        },
        async deleteExpense(id) {
            try {
                await axios.delete(`/expenses/${id}`);
                this.expenses = this.expenses.filter(e => e.id !== id);
            } catch (error) {
                console.error('Error deleting expense:', error);
            }
        },
        async fetchExpensesByUserId(userId) {
            try {
                const response = await axios.get(`/expenses/user/${userId}`);
                this.expenses = response.data;
            } catch (error) {
                console.error('Error fetching expenses by user ID:', error);
            }
        },
        async fetchExpensesByCategoryId(categoryId) {
            try {
                const response = await axios.get(`/expenses/category/${categoryId}`);
                this.expenses = response.data;
            } catch (error) {
                console.error('Error fetching expenses by category ID:', error);
            }
        },
        getCurrentMonthTotal() {
            const currentMonth = new Date().getMonth();
            const currentYear = new Date().getFullYear();
            return this.expenses
                .filter(expense => {
                    const expenseDate = new Date(expense.created_at);
                    return expenseDate.getMonth() === currentMonth && expenseDate.getFullYear() === currentYear;
                })
                .reduce((total, expense) => total + parseFloat(expense.amount), 0);
        },
        getMonthlyLabels() {
            const labels = [];
            const now = new Date();
            const currentMonth = now.getMonth();
            const currentYear = now.getFullYear();

            for (let i = 0; i < 12; i++) {
                const month = (currentMonth - i + 12) % 12;
                const year = currentYear - Math.floor((currentMonth - i) / 12);
                labels.unshift(new Date(year, month).toLocaleString('default', { month: 'long' }));
            }
            return labels;
        },
        getMonthlyData() {
            const monthlyData = new Array(12).fill(0);
            const now = new Date();
            const currentMonth = now.getMonth();
            const currentYear = now.getFullYear();

            this.expenses.forEach(expense => {
                const expenseDate = new Date(expense.created_at);
                const monthDiff = (currentYear - expenseDate.getFullYear()) * 12 + (currentMonth - expenseDate.getMonth());
                if (monthDiff >= 0 && monthDiff < 12) {
                    monthlyData[11 - monthDiff] += parseFloat(expense.amount);
                }
            });

            return monthlyData;
        }
    }
});
