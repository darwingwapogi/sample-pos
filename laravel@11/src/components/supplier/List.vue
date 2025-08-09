<template>
    <div class="card page-card">
        <div class="card-header d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><i class="bi bi-person-badge"></i> Suppliers List</h3>
            <button class="btn btn-primary" @click.prevent="openCreateOrUpdateDialog(false)">Create</button>
        </div>
        <div class="card-body vl-parent">
            <Loading v-model:active="pageLoading" :is-full-page="false" :color="`#1ecbe1`"/>
            <div class="d-flex justify-content-end">
                <Button label="Reset" class="p-button-secondary ms-1" icon="bi bi-x-circle" @click.prevent="resetTable()" />
                <Button label="Search" class="p-button-pri ms-1" icon="bi bi-search" @click.prevent="getSuppliersList()" />
            </div>
            <PrimeTable
                :value="suppliersList"
                :paginator="true"
                :size="'small'"
                :rows="rows"
                :rowsPerPageOptions="[10, 20, 50, 100]"
                :totalRecords="totalRecords"
                @page="onPageChange"
                @sort="onSort"
                :sortField="sortField"
                :sortOrder="sortOrder"
                :filterEventAction ="getSuppliersList"
                filterDisplay="row"
                :loading="isLoading"
                stripedRows
                class="small-font-table">
                <Column field="" header="" :sortable="false" :filter="false" :showFilterMenu="false" style="width: 3px;">
                    <template #body="slotProps">
                        <button style="border: none; background: none; color: #337daf;" title="edit" @click="editSupplier(slotProps.data)"><i class='bi bi-pencil-square'></i></button>
                    </template>
                </Column>
                <Column field="" header="" :sortable="false" :filter="false" :showFilterMenu=false>
                    <template #body="slotProps">
                        <button style="border: none; background: none; color: #337daf;" title="View" @click="viewSupplier(slotProps.data)"><i class='bi bi-eye'></i></button>
                    </template>
                </Column>
                <Column field="name" header="Name" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.name"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="contact_person" header="Contact Person" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.contact_person"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="status" header="Status" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <Select v-model="searchForm.status" 
                            :options="supplierOptions" 
                            optionLabel="label" 
                            optionValue="value" 
                            size="small" 
                            showClear 
                            fluid />
                    </template>
                </Column>
                <Column field="address" header="Full Address" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.address"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="location" header="Location" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.location"
                            size="small"
                            fluid />
                    </template>
                </Column>
            </PrimeTable>
            <div v-if="suppliersList.length === 0" class="text-center">
                <p>No record found.</p>
            </div>

            <Dialog v-model:visible="openDialog" modal header="Supplier Details" :style="{ width: '30rem' }" @hide="closeDialog">
                <SupplierRecord :supplierData="supplierData" />
            </Dialog>

            <Dialog v-model:visible="openCreateDialog" :header="dialogHeader" :style="{ width: '40rem' }">
                <p class="text-muted mb-4">Fields with asterisk (*) are required.</p>
                <form @submit.prevent="btnSubmit">
                    <div class="mb-3 row">
                        <label for="name" class="col-sm-3 col-form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="name" v-model="form.name" :class="v$.name.$error ? 'is-invalid' : ''" size="small" />
                            <span v-if="v$.name.$error" class="invalid-feedback mt-0">
                                <label>{{ v$.name.required.$message }}</label>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="contact_person" class="col-sm-3 col-form-label fw-semibold">Contact Person <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="contact_person" v-model="form.contact_person" :class="v$.contact_person.$error ? 'is-invalid' : ''" size="small" />
                            <span v-if="v$.contact_person.$error" class="invalid-feedback mt-0">
                                <label>{{ v$.contact_person.required.$message }}</label>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="status" class="col-sm-3 col-form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <Select class="form-control" id="status" v-model="form.status" :class="v$.status.$error ? 'is-invalid' : ''" :options="supplierOptions" optionLabel="label" optionValue="value" size="small" showClear fluid />
                            <span v-if="v$.status.$error" class="invalid-feedback mt-0">
                                <label>{{ v$.status.required.$message }}</label>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email" class="col-sm-3 col-form-label fw-semibold">Email</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="email" v-model="form.email" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="phone" class="col-sm-3 col-form-label fw-semibold">Phone</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="phone" v-model="form.phone" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="website" class="col-sm-3 col-form-label fw-semibold">Website</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="website" v-model="form.website" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="address" class="col-sm-3 col-form-label fw-semibold">Address</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="address" v-model="form.address" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="city" class="col-sm-3 col-form-label fw-semibold">City</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="city" v-model="form.city" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="province" class="col-sm-3 col-form-label fw-semibold">Province</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="province" v-model="form.province" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="country" class="col-sm-3 col-form-label fw-semibold">Country</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="country" v-model="form.country" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="zip_code" class="col-sm-3 col-form-label fw-semibold">Zip Code</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="zip_code" v-model="form.zip_code" size="small" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" @click="openCreateDialog = false">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </Dialog>

        </div>
    </div>
</template>
<script setup>
    import Loading from 'vue3-loading-overlay';
    import { ref, defineComponent, onMounted, watch, reactive } from 'vue';
    import PrimeTable from 'primevue/datatable';
    import { Column, Dialog, InputText, Select, Button } from 'primevue';
    import datatableUtil from '@/utils/datatableUtil.js';
    import SupplierService from '@/services/supplier.service.js';
    import useVuelidate from '@vuelidate/core';
    import { required } from '@vuelidate/validators';
    import SupplierRecord from './supplierRecord.vue';
    import posSwal from '@/utils/alertUtil.js';
    import _ from 'underscore';

    defineComponent({
        name: 'SuppliersPage'
    });

    let isLoading = ref(false);
    let pageLoading = ref(false);

    let suppliersList = ref([]);
    let rows = ref(10);
    let sortField = ref('name');
    let sortOrder = ref(datatableUtil.getSortOrder());
    let totalRecords = ref(0);
    let openDialog = ref(false);
    let openCreateDialog = ref(false);
    const supplierData = ref({});
    let dialogHeader = ref(null);
    let supplierOptions = ref([]);
    let isUpdate = ref(false);
    let existingData = reactive({});

    const form = reactive({
        name: null,
        contact_person: null,
        status: null,
        email: null,
        phone: null,
        website: null,
        address: null,
        city: null,
        province: null,
        country: null,
        zip_code: null
    });

    const rules = {
        name: { required },
        contact_person: { required },
        status: { required },
    };

    const v$ = useVuelidate(rules, form);

    function resetForm() {
        if (_.has(form, 'id')) {
            delete form.id;
        }

        Object.keys(form).forEach(key => {
            form[key] = null;
        });
    }

    function openCreateOrUpdateDialog(isEditing) {
        openCreateDialog.value = true;

        if (isEditing) {
            dialogHeader.value = 'Edit Supplier'
            isUpdate.value = true;
        } else {
            dialogHeader.value = 'Add Supplier'
            resetForm()
        }
    }

    const searchForm = ref({
        name: null,
        contact_person: null,
        status: null,
        email: null,
        phone: null,
        website: null,
    });

    datatableUtil.sortField = sortField.value;
    datatableUtil.sortOrder = sortOrder.value;
    datatableUtil.setSearchForm(searchForm.value);

    const onSort = (event) => {
        datatableUtil.onSort(event.sortField, event.sortOrder);
        getSuppliersList();
    };

    const onPageChange = (event) => {
        datatableUtil.onPage(event.page, event.rows);
        getSuppliersList();
    };

    function resetTable() {
        datatableUtil.resetTable();
        getSuppliersList();
    }

    watch(searchForm, (newValue) => {
        datatableUtil.setSearchForm(newValue);
    }, { deep: true });

    function getSupplierStatusList() {
        SupplierService.getSupplierStatusList()
            .then((response) => {
                supplierOptions.value = response.data;
            })
            .catch((error) => {
                //
            });
    }

    function getSuppliersList() {
        isLoading.value = true;
        SupplierService.getList(datatableUtil.getParams())
            .then((response) => {
                isLoading.value = false;
                const data = response.data;
                suppliersList.value = data.data;
                totalRecords.value = data.total;
            })
            .catch((error) => {
                isLoading.value = false;
            });
    }

    function viewSupplier(item) {
        openDialog.value = true
        supplierData.value = item;
    }

    function closeDialog() {
        openDialog.value = false;
        supplierData.value = {};
    }

    function getOptionValue(field, optionData) {
        for (const item of optionData) {
            if (item.label === field) {
                return item.value;
            }
        }
    }

    function editSupplier(data) {
        isUpdate.value = true;
        openCreateOrUpdateDialog(true);
        form.id = data.id;
        form.name = data.name;
        form.contact_person = data.contact_person;
        form.status = getOptionValue(data.status, supplierOptions.value);
        form.email = data.email;
        form.phone = data.phone;
        form.website = data.website;
        form.address = data.address;
        form.city = data.city;
        form.province = data.province;
        form.country = data.country;
        form.zip_code = data.zip_code;

        // Store the original data from the database to track changes in field values
        existingData = data;
    }


    function btnSubmit() {
        v$.value.$touch();

        if (v$.value.$pending || v$.value.$invalid) {
            return;
        }

        pageLoading.value = true;
        let formData = {};

        if (isUpdate.value) {
            formData.id = form.id;
            formData.name = form.name;
            formData.contact_person = form.contact_person;
            formData.status = form.status;

            Object.keys(form).forEach(key => {
                if (form[key] !== existingData[key]) {
                    formData[key] = form[key];
                }
            });
            
        } else {
            formData = form;
        }

        SupplierService.save(formData)
            .then((response) => {
                openCreateDialog.value = false;

                const data = response.data
                if (data.success) {
                    posSwal.fire({title: "Success!",
                        text: data.message,
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#48C9B0",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                    pageLoading.value = false;
                    resetForm();
                }
            })
            .catch(error => {
                pageLoading.value = false;
                openCreateDialog.value = false;
                posSwal.fire({
                    title: "Error!",
                    text: error.response?.data?.message || "An error occurred while saving the supplier.",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#E74C3C",
                    confirmButtonText: "Close",
                    closeOnConfirm: true,
                });
            });
    }

    onMounted(() => {
        getSuppliersList();
        getSupplierStatusList();

        //validate on mounted
        v$.value.$touch();
    });
</script>
