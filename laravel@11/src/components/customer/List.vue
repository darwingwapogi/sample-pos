<template>
    <div class="card page-card">
        <div class="card-header d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><i class="bi bi-people"></i> Customer List</h3>
            <button class="btn btn-primary" @click.prevent="openCreateOrUpdateModal(false)">Create</button>
        </div>
        <div class="card-body vl-parent">
            <Loading v-model:active="isLoading" :is-full-page="false" :color="`#1ecbe1`"/>

            <PrimeTable
              :value="customerList" 
              :paginator="true" 
              :size="'small'" 
              :rows="rows" 
              :totalRecords="totalRecords"
              @page="onPageChange"
              @sort="onSort"
              :filterEventAction ="getCustomerList"
              filterDisplay="row"
              stripedRows
              class="small-font-table">
                <Column field="fname" header="First Name" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.fname"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="mname" header="Middle Name" :sortable="true" :filter="true" :showFilterMenu=false>
                </Column>
                <Column field="lname" header="Last Name" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.lname"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="gender" header="Gender" :sortable="true" :filter="true" :showFilterMenu=false>
                </Column>
                <Column field="birth_date" header="Birthdate" :sortable="true" :filter="true" :showFilterMenu=false>
                </Column>
                <Column field="address" header="Address" :sortable="true" :filter="true" :showFilterMenu=false>
                </Column>
                <Column field="email" header="Email" :sortable="true" :filter="true" :showFilterMenu=false>
                </Column>
                <Column field="primary_contact" header="Primary Contact" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.primary_contact"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="contact" header="Contact" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.contact"
                            size="small"
                            fluid />
                    </template>
                </Column>
            </PrimeTable>
            <ModalContainer v-model="openModal">
                <template #modalBody>
                    <MainPanel :configs="panelConfigs" :form="form" :val="v$"/>
                </template>
            </ModalContainer>
          <div v-if="customerList.length === 0" class="text-center">
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
    import CustomerService from '@/services/customer.service';
    import datatableUtil from '@/utils/datatableUtil.js';
    import ModalContainer from '@/globals/modals/ModalContainer.vue';
    import MainPanel from '@/globals/MainPanel.vue';

    defineComponent({
        name: 'CustomersPage',
    })

    let isLoading = ref(false);

    let customerList = ref([]);
    let rows = ref(10);
    let totalRecords = ref(0);
    let openModal = ref(false);

    const panelConfigs = {
        panel_header: {
            title: ''
        },
        panel_body: {
            fields: [
                [
                    {
                        name: 'fname',
                        label: 'First Name',
                        placeholder: 'Enter First Name',
                        required: true
                    },
                    {
                        name: 'lname',
                        label: 'Last Name',
                        placeholder: 'Enter Last Name',
                        required: true
                    },
                    {
                        name: 'mname',
                        label: 'Middle Name',
                        placeholder: 'Enter Middle Name',
                    },
                    {
                        name: 'gender',
                        label: 'Gender',
                        placeholder: 'Enter Gender',
                        required: true,
                        type: 'dropdown',
                    },
                    {
                        name: 'birth_date',
                        label: 'Birthdate',
                        placeholder: 'Enter Birthdate',
                    },
                    {
                        name: 'address',
                        label: 'Address',
                        placeholder: 'Enter Address',
                    },
                    {
                        name: 'email',
                        label: 'Email',
                        placeholder: 'Enter Email',
                    },
                    {
                        name: 'primary_contact',
                        label: 'Primary Contact',
                        placeholder: 'Enter Primary Contact'
                    },
                    {
                        name: 'contact',
                        label: 'Contact',
                        placeholder: 'Enter Contact'
                    },
                ],
            ]
        },
        panel_footer: {
            buttons: [
                {
                    label: 'Save',
                    class: 'btn btn-primary',
                    click: btnSubmit
                },
                {
                    label: 'Cancel',
                    class: 'btn btn-secondary',
                    click: () => {
                        openModal.value = false;
                    }
                }
            ]
        }
    };

    let searchForm = ref({
        fname: null,
        lname: null,
        email: null,
        primary_contact: null,
        contact: null
    });

    datatableUtil.setSearchForm(searchForm.value);

    const onSort = (event) => {
        datatableUtil.onSort(event.sortField, event.sortOrder);
        getCustomerList();
    };

    const onPageChange = (event) => {
        datatableUtil.onPage(event.page, event.rows);
        getCustomerList();
    };

    function getCustomerList() {
        isLoading.value = true;

        CustomerService.getList(datatableUtil.getParams())
            
            .then((response) => {
                customerList.value = response.data;
                totalRecords.value = response.data.total;
                isLoading.value = false;
            })
            .catch((error) => {
                console.error('Error fetching customer list:', error);
                isLoading.value = false;
            });
    };

    function resetTable() {
        datatableUtil.resetTable();
        getSalesList();
    }

    watch(searchForm, (newValue) => {
        datatableUtil.setSearchForm(newValue);
    }, { deep: true });

    function btnSubmit(customerData) {
        isLoading.value = true;

        CustomerService.save(customerData)
            .then((response) => {
                getCustomerList();
                isLoading.value = false;
            })
            .catch((error) => {
                console.error('Error creating customer:', error);
                isLoading.value = false;
            });
    }
    
    function openCreateOrUpdateModal(isEditing) {
        openModal.value = true;

        if (isEditing) {
            panelConfigs.panel_header.title = 'Edit Category'
        } else {
            panelConfigs.panel_header.title = 'Add Category'
            resetForm()
        }
    }

    onMounted(() => {
        getCustomerList();
    });
</script>
<style scoped>
.btn-primary {
    background-color: #337daf;
}
a.btn.btn-sm {
    background-color: #337daf;
}
</style>