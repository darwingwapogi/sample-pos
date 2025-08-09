<!-- 
    Author: Darwin Casanova
    Date: March 8, 2025
    Time: 8:30 PM
    Description: This is the Customer table with its logic.
-->
<template>
    <div class="d-flex justify-content-end">
        <Button label="Reset" class="p-button-secondary ms-1" icon="bi bi-x-circle" @click.prevent="resetTable()" />
        <Button label="Search" class="p-button-pri ms-1" icon="bi bi-search" @click.prevent="getCustomerList()" />
    </div>
    <PrimeTable
        :value="customersList" 
        :paginator="true" 
        :size="'small'" 
        :rows="rows" 
        :rowsPerPageOptions="[5, 10]" 
        :totalRecords="totalRecords"
        @page="onPageChange"
        @sort="onSort"
        :filterEventAction ="getCustomerList"
        @keydown.enter="getCustomerList"
        filterDisplay="row"
        :loading="isLoading"
        class="small-font-table">
        <Column field="full_name" header="Customer Name" :sortable="true" :filter="true" :showFilterMenu=false>
            <template #filter>
                <InputText v-model="searchForm.full_name"
                    size="small"
                    fluid />
            </template>
        </Column>
        <Column field="address" header="Address" :sortable="true" :filter="false" :showFilterMenu=false>
            <template #filter>
                <InputText v-model="searchForm.address"
                    size="small"
                    fluid />
            </template>
        </Column>
        <Column field="age" header="Age" :sortable="true" :filter="false" :showFilterMenu=false></Column>
        <Column field="action" header="Action" :sortable="false" :filter="false" :showFilterMenu=false>
            <template #body="slotProps">
                <Tag v-if="isSelected(slotProps.data.id)" value="selected" severity="danger" />
                <Button 
                    v-else
                    label="Select" 
                    class="p-button-primary"
                    icon="bi bi-check2-circle"
                    @click="$emit('selectCustomer', slotProps.data)" />
            </template>
        </Column>
    </PrimeTable>
    <div v-if="customersList.length === 0" class="text-center">
        <p>No record found.</p>
    </div>
</template>

<script setup>
    import { ref, defineComponent, onMounted, watch, computed } from 'vue';
    import PrimeTable from 'primevue/datatable';
    import { Column, DatePicker, InputText, Select, Button, Tag } from 'primevue';
    import datatableUtil from '@/utils/datatableUtil.js';
    import CustomerService from '@/services/customer.service.js';

    defineComponent({
        name: 'CustomerTable'
    });

    let isLoading = ref(false);

    let customersList = ref([]);
    let rows = ref(5);
    let totalRecords = ref(0);

    const searchForm = ref({
        full_name: null,
        address: null,
    });

    datatableUtil.setSearchForm(searchForm.value);
    datatableUtil.sortField = 'full_name';

    const onSort = (event) => {
        datatableUtil.onSort(event.sortField, event.sortOrder);
        getCustomerList();
    };

    const onPageChange = (event) => {
        datatableUtil.onPage(event.page, event.rows);
        getCustomerList();
    };

    function resetTable() {
        datatableUtil.resetTable();
        getCustomerList();
    }

    watch(searchForm, (newValue) => {
        datatableUtil.setSearchForm(newValue);
    }, { deep: true });

    function getCustomerList() {
        CustomerService.getList(datatableUtil.getParams())
            .then((response) => {
                customersList.value = response.data;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    const isSelected = computed(() => (id) => {
        return props.customerData.id === id;
    });

    onMounted(() => {
        getCustomerList();
    });

    const props = defineProps({
        SelectCustomer: {
            type: Function,
            required: false,
            default: () => {}
        },
        customerData: {
            type: Object,
            required: false,
            default: {}
        }
    });

    defineEmits(['selectCustomer']);
</script>
<style scoped>
@import '/resources/css/primeTableCts.css';
</style>