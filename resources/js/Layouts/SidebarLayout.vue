<template>
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" :class="{ 'toggled': isCollapsed }"
        id="accordionSidebar">

        <Link class="sidebar-brand d-flex align-items-center justify-content-center" :href="'/tasks'">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa-solid fa-music"></i>
        </div>
        <div class="sidebar-brand-text mx-0">ADMIN PANEL <sup><small>Content Management</small></sup></div>
        </Link>

        <hr class="sidebar-divider my-0">

        <li class="nav-item" :class="{ 'active': ['Task'].includes($page.component) }">
            <Link class="nav-link" :href="'/tasks'">
            <i class="fas fa-layer-group"></i>
            <span>Tasks</span></Link>
        </li>

        <hr class="sidebar-divider">

        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle" @click.prevent="toggleSidebar"></button>
        </div>



    </ul>
</template>

<script>
import { Link } from "@inertiajs/vue3";

export default {
    data() {
        return {
            isCollapsed: false,
        };
    },
    components: {
        Link
    },
    methods: {
        toggleSidebar() {
            this.isCollapsed = !this.isCollapsed;
        },
    },
};
</script>
