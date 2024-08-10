<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 p-0">
                <AdminSidebar />
            </div>
            <div class="col-md-9 col-lg-10 p-4">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
import AdminSidebar from './AdminSidebar.vue';

export default {
    name: "AdminDashboard",
    components: {
        AdminSidebar
    }
};
</script>

<style scoped>
.container-fluid {
    padding: 0;
}

.col-md-3,
.col-lg-2 {
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
}

.col-md-9,
.col-lg-10 {
    background-color: #fff;
    min-height: 100vh;
}
</style>