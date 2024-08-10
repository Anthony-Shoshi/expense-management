<template>
    <div class="sidebar">
        <h3 class="sidebar-title text-center py-3">User Menu</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <router-link to="/user" class="nav-link">Home</router-link>
            </li>
            <li class="nav-item">
                <router-link to="/user/expenses" class="nav-link">Manage Expenses</router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "UserSidebar"
};
</script>

<style scoped>
.sidebar {
    min-width: 200px;
    height: 100vh;
    background: linear-gradient(to right, #343a40, #495057);
    color: #fff;
}

.sidebar-title {
    color: #fff;
}

.nav-link {
    color: #adb5bd;
    transition: background-color 0.3s, color 0.3s;
}

.nav-link:hover {
    background-color: #6c757d;
    color: #fff;
}

.nav-link.router-link-active {
    background-color: #495057;
    color: #fff;
}
</style>