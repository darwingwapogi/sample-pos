<template>
    <div class="card page-card">
        <div class="card-header d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><i class="bi-speedometer"></i> Dashboard</h3>
        </div>
        <div class="card-body vl-parent">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card p-3">
                        <p>Sales / Expenses</p>
                        <Chart type="bar" :data="chartData" :options="chartOptions" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card p-3">
                        <p>Stocks</p>
                        <Chart type="bar" :data="userChartData" :options="userChartOptions" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card p-3">
                        <p>Active Users / Total Users</p>
                        <Chart type="pie" :data="userActivityChartData" :options="userActivityChartOptions" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from 'primevue/chart';

export default {
    name: 'Dashboard',
    components: {
        Chart,
    },
    data() {
        return {
            chartData: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [
                    {
                        label: 'Sales',
                        backgroundColor: '#42A5F5',
                        data: [65, 59, 80, 81, 56, 55, 40],
                    },
                    {
                        label: 'Expenses',
                        backgroundColor: '#FFA726',
                        data: [28, 48, 40, 19, 86, 27, 90],
                    },
                ],
            },
            chartOptions: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
            },
            userChartData: {
                labels: ['Cement', 'Hollow Block', 'PBC'],
                datasets: [
                    {
                        label: 'Stocks',
                        backgroundColor: ['#42A5F5', '#66BB6A', '#FFA726'],
                        data: [300, 150, 100],
                    },
                ],
            },
            userChartOptions: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
            },
            userActivityChartData: {
                labels: ['Active Users', 'Inactive Users'],
                datasets: [
                    {
                        data: [120, 80], // Example data
                        backgroundColor: ['#66BB6A', '#FF7043'],
                        hoverBackgroundColor: ['#81C784', '#FF8A65'],
                    },
                ],
            },
            userActivityChartOptions: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
            },
        };
    },
};
</script>

<style scoped>
</style>