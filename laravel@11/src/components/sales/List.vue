<template>
    <div class="card page-card">
        <div class="card-header d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><i class="bi-cash-coin"></i> Sales Page</h3>

            <div class="btn-group">
                <Button label="Reset" class="p-button-secondary ms-1" @click.prevent="resetTable()" />
                <Button label="Filter" class="p-button-info ms-1" @click.prevent="getSalesList()" />
            </div>
        </div>
        <div class="card-body vl-parent">
            <Loading v-model:active="isLoading" :is-full-page="false" :color="`#1ecbe1`"/>

            <PrimeTable
              :value="salesList" 
              :paginator="true" 
              :size="'small'" 
              :rows="rows" 
              :rowsPerPageOptions="[10, 20, 50, 100]" 
              :totalRecords="totalRecords"
              @page="onPageChange"
              @sort="onSort"
              :filterEventAction ="getSalesList"
              filterDisplay="row"
              :loading="isLoading"
              class="small-font-table">
                <Column field="customer" header="Customer Name" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.customer"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="items_sold" header="Item Sold" :sortable="true" :filter="true" :showFilterMenu=false>
                </Column>
                <Column field="status" header="Status" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <Select 
                            v-model="searchForm.status"
                            :options="statusList"
                            placeholder="Select Status"
                            optionLabel="label" 
                            optionValue="value" 
                            size="small"
                            showClear
                            fluid
                        />
                    </template>
                </Column>
                <Column field="date_created" header="Date Created" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <DatePicker
                            v-model="searchForm.date_created"
                            placeholder="Select Date"
                            showIcon
                            iconDisplay="input"
                            showButtonBar
                            size="small"
                            fluid
                        />
                    </template>
                </Column>
                <Column field="date_sold" header="Date Sold" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <DatePicker
                            v-model="searchForm.sold_at"
                            placeholder="Select Date"
                            showIcon
                            iconDisplay="input"
                            showButtonBar
                            size="small"
                            fluid
                        />
                    </template>
                </Column>
                <Column field="amount" header="Total Amount" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.total_amount"
                            size="small"
                            fluid />
                    </template>
                </Column>
            </PrimeTable>
          <div v-if="salesList.length === 0" class="text-center">
            <p>No record found.</p>
          </div>
        </div>
    </div>
    
</template>

<script setup>
    import Loading from 'vue3-loading-overlay';
    import { ref, defineComponent, nextTick, onMounted, watch } from 'vue';
    import PrimeTable from 'primevue/datatable';
    import { Column, DatePicker, InputText, Select, Button } from 'primevue';
    import SaleService from '@/services/sales.service.js';
    import datatableUtil from '@/utils/datatableUtil.js';

    defineComponent({
        name: 'SalesPage',
        components: {
        }
    });

    let isLoading = ref(false);

    let salesList = ref([]);
    let rows = ref(10);
    let totalRecords = ref(0);
    const statusList = ref([]);

    const searchForm = ref({
        customer: null,
        status: null,
        date_created: null,
        sold_at: null,
        total_amount: null
    });

    datatableUtil.setSearchForm(searchForm.value);

    const onSort = (event) => {
        datatableUtil.onSort(event.sortField, event.sortOrder);
        getSalesList();
    };

    const onPageChange = (event) => {
        datatableUtil.onPage(event.page, event.rows);
        getSalesList();
    };

    function getSalesList() {
        isLoading.value = true;

        SaleService.getList(datatableUtil.getParams())
            .then((response) => {
                salesList.value = response.data.data; // Set the employee data
                totalRecords.value = response.data.total; // Set the total number of records
                isLoading.value = false;
            })
            .catch((error) => {
                isLoading.value = false;
            });
    };

    function getStatusList() {
        SaleService.getStatusList()
            .then((response) => {
                statusList.value = response.data;
            })
            .catch((error) => {
                //
            });
    }

    function resetTable() {
        datatableUtil.resetTable();
        getSalesList();
    }

    watch(searchForm, (newValue) => {
        datatableUtil.setSearchForm(newValue);
    }, { deep: true });

    onMounted(() => {
        getSalesList();
        getStatusList();
    });
</script>