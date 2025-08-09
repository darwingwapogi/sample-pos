<!--
    Author: Darwin Casanova
    Date: April 2, 2025
    Time: 8:30 PM
    Description: Main POS system component that dynamically loads a child component
                 based on the provided name. Utilizes defineAsyncComponent for
                 asynchronous loading. Supports various store types such as
                 Hardware, IcePlant, DepartmentStore, etc.
-->
<template>
    <component :is="dynamicComponent" />
</template>

<script setup>
import { ref, computed } from 'vue';
import Hardware from './Hardware.vue';

const dynamicComponentName = ref('Hardware');

const dynamicComponent = computed(() => {
    if (dynamicComponentName.value === 'Hardware') {
        return Hardware;
    }
    return null;
});
</script>
