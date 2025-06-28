<template>
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <p class="ribbon">
            <span class="text"><i :class="page_icon"></i>TASK APP {{ }}</span>
        </p>
        <!-- <h2 class="page-title"><i class="fas fa-users-cog"></i> User Management <i class="fas fa-level-down-alt"></i></h2> -->

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" @click.prevent="toggleSidebar">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
                <!-- Dropdown - Messages -->
                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                    aria-labelledby="searchDropdown">
                    <form class="form-inline mr-auto w-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

          

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="me-2 d-none d-lg-inline text-gray-600 small">
                        {{
                Array.from($page.props.auth.user.name)[0] +
                ". " + $page.props.auth.user.name.split(" ").splice(-1)
            }}
                    </span>
                    <img class="img-profile rounded-circle" :src="$page.props.auth.user.profile_photo_url">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                    <Link class="dropdown-item d-flex align-items-center" :href="route('profile.show')">
                    <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                    Profile
                    </Link>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item" @click.prevent="logout">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                        Logout
                    </button>
                </div>
            </li>


        </ul>

    </nav>
</template>

<script>
import { Link } from "@inertiajs/vue3";

export default {
    props: {
        page_title: String,
        page_icon: String
    },
    data() {
        return {
            isCollapsed: false,
        };
    },
    components: {
        Link
    },
    methods: {
        logout() {
            this.$swal({
                text: "Are you sure you want to logout?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#512da8",
                cancelButtonText: 'No <i class="bi bi-hand-thumbs-down"></i>',
                confirmButtonText: '<i class="bi bi-hand-thumbs-up"></i> Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    sessionStorage.clear();
                    sessionStorage.setItem('logout', 1);
                    this.$inertia.post(route("logout"));
                }
            });
        },
        toggleSidebar() {
            const sidebar = document.getElementById('accordionSidebar');
            if (sidebar) {
                sidebar.classList.toggle('toggled');
                this.isCollapsed = !this.isCollapsed;
            }
        },
    },
    created() {
        if (sessionStorage.getItem('logout') == 0) {
            location.reload();
            sessionStorage.setItem('logout', 1);
        }
    }
}
</script>
