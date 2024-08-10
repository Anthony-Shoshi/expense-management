<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Welcome to Your Dashboard</h2>
                        <h5 class="text-primary">Total Expenses for {{ currentMonthName }}: {{
                            formattedCurrentMonthTotal }}</h5>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header">Monthly Expenses Chart</div>
                                <div class="card-body">
                                    <canvas id="expensesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3 shadow-sm h-100">
                                <div class="card-header">Last Five Expenses</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Amount (€)</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="expense in lastFiveExpenses" :key="expense.id">
                                                    <td>{{ expense.title }}</td>
                                                    <td>{{ expense.amount.toFixed(2) }}</td>
                                                    <td>{{ new Date(expense.created_at).toLocaleDateString() }}</td>
                                                </tr>
                                                <tr v-if="!lastFiveExpenses.length">
                                                    <td colspan="3" class="text-center">No Expenses Found</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useExpenseStore } from '../../stores/expenseStore';

export default {
    name: "UserHome",
    setup() {
        const expenseStore = useExpenseStore();
        const currentMonthTotal = ref(0);
        const lastFiveExpenses = ref([]);
        const currentMonthName = ref(new Date().toLocaleString('default', { month: 'long' }));
        const chartData = ref({
            labels: [],
            datasets: []
        });

        onMounted(async () => {
            await expenseStore.fetchExpenses();
            currentMonthTotal.value = expenseStore.getCurrentMonthTotal();
            lastFiveExpenses.value = expenseStore.expenses.slice(-5).reverse();
            currentMonthName.value = new Date().toLocaleString('default', { month: 'long' });

            chartData.value.labels = expenseStore.getMonthlyLabels();
            chartData.value.datasets = [{
                label: 'Monthly Expenses',
                data: expenseStore.getMonthlyData(),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: false
            }];

            renderChart();
        });

        const renderChart = () => {
            const ctx = document.getElementById('expensesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.value.labels,
                    datasets: chartData.value.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        };

        const formattedCurrentMonthTotal = computed(() => {
            return `€${currentMonthTotal.value.toFixed(2)}`;
        });

        return {
            currentMonthTotal,
            lastFiveExpenses,
            currentMonthName,
            formattedCurrentMonthTotal
        };
    }
};
</script>

<style scoped>
.container-fluid {
    padding: 20px;
}

.card {
    background-color: #fff;
    border-radius: 10px;
}

.card-header {
    background: linear-gradient(to right, #007bff, #6610f2);
    color: #fff;
    font-weight: bold;
}

.card-title {
    font-size: 1.5rem;
    font-weight: bold;
}

.table {
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.text-center {
    text-align: center;
}
</style>