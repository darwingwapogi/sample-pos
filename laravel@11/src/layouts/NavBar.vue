<!-- 
    Author: Darwin Casanova
    Date: March 8, 2025
    Time: 4:30 PM
    Description: This is the navigation bar component of the application.
 -->
<template>
    <nav class="navbar fixed-top navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Company Name</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <li class="nav-item">
                    </li>
                </ul>
                <div class="d-flex">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                                <span class="user">{{ user.username }} <i class='bi bi-person-fill'></i></span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown" style="right: 0; left: auto;">
                                <li><a class="dropdown-item" href="#">My Profile</a></li>
                                <li><a class="dropdown-item" href="#">My Activities</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" @click="logout">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>
<script setup>
    import axios from 'axios';
    import { ref, onBeforeMount } from 'vue';
    import authService from '../services/auth.service.js';

    let user = ref({});

    function getAuthenticatedUser() {
        authService.getCurrentUser().then(
            (res) => {
                user.value = res.data;
            }
        )
    }

    function logout() {
        
        authService.postLogout().then(
            (response) => {
                console.log(response)
                window.location.reload()
                window.location.href = "/login"
                axios.defaults.headers.common['Authorization'] = '';
                window.localStorage.removeItem('jwt-token');
            }
        )
    }

    onBeforeMount(() => {
        getAuthenticatedUser()
    })

</script>
<style scoped>
.navbar {
    background-color: #337daf;
}

.user {
    color: rgb(252, 252, 252);
    font-size: 1.2rem;
    font-weight: 500;
}
</style>