<!-- 
    Author: Darwin Casanova
    Date: March 8, 2025
    Time: 4:30 PM
    Description: This is the sidebar component of the application.
    Note: The menu items are fetched from the backend and displayed in the sidebar.
          In MenuService.js, the menu items are fetched from the backend using axios.   
 -->
<template>
 <div class="sidebar">
  <div class="nav-item">
    <div v-for="item in menuItems" :key="item.label">
      <div v-if="item.dropdown">
        <button class="dropdown-btn" @click="toggleDropdown"><i :class="`bx ${item.icon}`"></i> {{ item.label }}
          <i class="bx bx-caret-down float-end"></i>
        </button>
        <div class="dropdown-container">
          <div v-for="subItem in item.subMenu" :key="subItem.label">
            <router-link class="nav-link" :to="subItem.link" exact>
              <i :class="`bx ${subItem.icon}`"></i> {{ subItem.label }}
            </router-link>
          </div>
        </div>
      </div>
      <div v-else>
        <router-link class="nav-link" :to="item.link" exact>
          <i :class="`bx ${item.icon}`"></i> {{ item.label }}
        </router-link>
      </div>
    </div>
  </div>
 </div>
</template>
<script setup>
import { onMounted, ref } from 'vue';
import menuService from '../services/menu.service.js';

const menuItems = ref([]);

const getMenuItems = () => {
  menuService.getList().then(
    (response) => {
      menuItems.value = response.data.menu;
    },
    (error) => {
      console.log(error);
    }
  );
};

const toggleDropdown = (event) => {
  const dropdownContent = event.target.nextElementSibling;
  if (dropdownContent.style.display === "block") {
    dropdownContent.style.display = "none";
  } else {
    dropdownContent.style.display = "block";
  }
};

onMounted(() => {
  getMenuItems();
});

</script>
<style scoped>
</style>
