<!-- 
    Author: Darwin Casanova
    Date: March 8, 2025
    Time: 8:30 PM
    Description: This is the Item table with its logic.
-->
<template>
    <div class="d-flex justify-content-end">
        <Button label="Reset" class="p-button-secondary ms-1" icon="bi bi-x-circle" @click.prevent="resetTable()" />
        <Button label="Search" class="p-button-pri ms-1" icon="bi bi-search" @click.prevent="getItemsList()" />
    </div>
    <PrimeTable
        v-model:selection="selectedRows"
        :value="itemsList" 
        :paginator="true" 
        :size="'small'" 
        :rows="rows" 
        :rowsPerPageOptions="[10, 20, 50, 100]" 
        :totalRecords="totalRecords"
        @page="onPageChange"
        @sort="onSort"
        :sortField="sortField"
        :sortOrder="sortOrder"
        :filterEventAction ="getItemsList"
        @keydown.enter="getItemsList"
        filterDisplay="row"
        :loading="isLoading"
        selectionMode="multiple"
        dataKey="id"
        :metaKeySelection="false"
        class="small-font-table">
        <Column field="" header="" :sortable="false" :filter="false" :showFilterMenu=false>
            <template #body="slotProps">
                <button style="border: none; background: none; color: #337daf;" title="View" @click="$emit('viewItem', slotProps.data)"><i class='bi bi-eye'></i></button>
            </template>
        </Column>
        <Column field="" header="" :sortable="false" :filter="false" :showFilterMenu=false>
            <template #body="slotProps">
                <InputNumber v-model="slotProps.data.quantity"
                    size="small"
                    value="1"
                    @input.prevent="onChangeQuantity(slotProps.data)"
                    fluid />
                <Chip v-if="slotProps.data.stock_warning != null" :label="slotProps.data.stock_warning" style="color: red;" />
            </template>
        </Column>
        <Column field="name" header="Name" :sortable="true" :filter="true" :showFilterMenu=false>
            <template #filter>
                <InputText v-model="searchForm.name"
                    size="small"
                    fluid />
            </template>
        </Column>
        <Column field="item_type" header="Type" :sortable="true" :filter="true" :showFilterMenu=false>
            <template #filter>
                <InputText v-model="searchForm.item_type"
                    size="small"
                    fluid />
            </template>
        </Column>
        <Column field="category" header="Category" :sortable="true" :filter="true" :showFilterMenu=false>
            <template #filter>
                <InputText v-model="searchForm.category"
                    size="small"
                    fluid />
            </template>
        </Column>
        <Column field="brand" header="Brand" :sortable="true" :filter="true" :showFilterMenu=false>
            <template #filter>
                <InputText v-model="searchForm.brand"
                    size="small"
                    fluid />
            </template>
        </Column>
        <Column field="model" header="Model" :sortable="true" :filter="true" :showFilterMenu=false>
            <template #filter>
                <InputText v-model="searchForm.model"
                    size="small"
                    fluid />
            </template>
        </Column>
        <Column field="size" header="Size" :sortable="true" :filter="true" :showFilterMenu=false></Column>
        <Column field="color" header="Color" :sortable="true" :filter="true" :showFilterMenu=false></Column>
        <Column field="supplier" header="Supplier" :sortable="true" :filter="true" :showFilterMenu=false>
            <template #filter>
                <InputText v-model="searchForm.supplier"
                    size="small"
                    fluid />
            </template>
        </Column>
        <Column field="final_selling_price" header="Price" :sortable="true" :filter="true" :showFilterMenu=false>
            <template #filter>
                <InputText v-model="searchForm.final_selling_price"
                    size="small"
                    fluid />
            </template>
        </Column>
    </PrimeTable>
    <div v-if="itemsList.length === 0" class="text-center">
        <p>No record found.</p>
    </div>
</template>

<script setup>
    import { ref, defineComponent, onMounted, watch, computed, defineEmits } from 'vue';
    import PrimeTable from 'primevue/datatable';
    import { Column, DatePicker, InputText, Select, Button, InputNumber } from 'primevue';
    import datatableUtil from '@/utils/datatableUtil.js';
    import ItemService from '@/services/item.service.js';
    import Chip from 'primevue/chip';

    defineComponent({
        name: 'ItemTable'
    });

    let isLoading = ref(false);

    let itemsList = ref([]);
    let rows = ref(10);
    let sortField = ref('name');
    let sortOrder = ref(datatableUtil.getSortOrder());
    let totalRecords = ref(0);
    const selectedRows = ref([]);

    const searchForm = ref({
        supplier: null,
        category: null,
        unit: null,
        item_type: null,
        status: null,
        brand: null,
        model: null,
        name: null,
        final_selling_price: null,
    });

    datatableUtil.sortField = sortField.value;
    datatableUtil.sortOrder = sortOrder.value;
    datatableUtil.setSearchForm(searchForm.value);

    const onSort = (event) => {
        datatableUtil.onSort(event.sortField, event.sortOrder);
        getItemsList();
    };

    const onPageChange = (event) => {
        datatableUtil.onPage(event.page, event.rows);
        getItemsList();
    };

    function resetTable() {
        datatableUtil.resetTable();
        getItemsList();
    }

    watch(searchForm, (newValue) => {
        datatableUtil.setSearchForm(newValue);
    }, { deep: true });

    function getItemsList() {
        isLoading.value = true;
        ItemService.getList(datatableUtil.getParams())
            .then((response) => {
                const data = response.data;
                itemsList.value = data.data;
                totalRecords.value = data.total;
                isLoading.value = false;
            })
            .catch((error) => {
                isLoading.value = false;
            });
    }

    function onChangeQuantity(item) {
        const value = event.target.value;
        if (value > item.stock_quantity) {
            item.stock_warning = "Only " + item.stock_quantity + " items available in stock.";
        } else if (
            value < 1
        ) {
            item.stock_warning = "Quantity must be greater than 0.";
        } else if (value == null || value == undefined || isNaN(value) || value == Infinity || value == -Infinity || value == -1) {
            item.stock_warning = null;
            item.quantity = 1;
        } else {
            item.stock_warning = null;
        }
    }

    const emit = defineEmits(['viewItem', 'selectRows']);

    onMounted(() => {
        getItemsList();

        watch(selectedRows, (newValue) => {
            emit('selectRows', newValue);
        }, { deep: true });
    });
</script>
<style scoped>
@import '/resources/css/primeTableCts.css';
</style>