<template>
  <Loading v-model:active="isLoading" :is-full-page="true" :color="`#1ecbe1`"/>
  <SideBarVue/>
  <NavBarVue/>
  <div class="mainContent">
    <div class="container">
      <router-view></router-view>
    </div>
  </div>
</template>

<script setup>
    import Loading from 'vue3-loading-overlay';
    import NavBarVue from './layouts/NavBar.vue';
    import SideBarVue from './layouts/SideBar.vue';
    import { ref, onMounted, defineComponent, inject } from 'vue';

    defineComponent(
      'Loading',
      'NavBarVue',
      'SideBarVue'
    )

    let isLoading = ref(true);

    onMounted(() => {
      console.log("App Mounted! " + inject('message'))
      
      setTimeout(() => {
        isLoading.value = false;
      }, 1000)
    })
    
</script>
<style scoped>
</style>