<template>
    <div class="card page-card">
        <div class="card-header d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><i class="bi-card-checklist"></i> Item Types List</h3>
            <button class="btn btn-primary" @click.prevent="openCreateOrUpdateDialog(false)">Create</button>
        </div>
        <div class="card-body vl-parent">
            <Loading v-model:active="pageLoading" :is-full-page="false" :color="`#1ecbe1`"/>
            <div class="d-flex justify-content-end">
                <Button label="Reset" class="p-button-secondary ms-1" icon="bi bi-x-circle" @click.prevent="resetTable()" />
                <Button label="Search" class="p-button-pri ms-1" icon="bi bi-search" @click.prevent="getItemTypesList()" />
            </div>
            <PrimeTable
                :value="itemTypesList"
                lazy
                :paginator="true"
                :size="'small'"
                :rows="rows"
                :rowsPerPageOptions="[10, 20, 50, 100]"
                :totalRecords="totalRecords"
                @page="onPageChange"
                @sort="onSort"
                :sortField="sortField"
                :sortOrder="sortOrder"
                @keydown.enter="getItemTypesList"
                filterDisplay="row"
                :loading="isLoading"
                stripedRows
                class="small-font-table">
                <Column field="" header="" :sortable="false" :filter="false" :showFilterMenu="false" style="width: 3px;">
                    <template #body="slotProps">
                        <button style="border: none; background: none; color: #337daf;" title="edit" @click="editItemType(slotProps.data)"><i class='bi bi-pencil-square'></i></button>
                    </template>
                </Column>
                <Column field="name" header="Name" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.name"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="description" header="Description" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.description"
                            size="small"
                            fluid />
                    </template>
                </Column>
            </PrimeTable>

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
                        <label for="description" class="col-sm-3 col-form-label fw-semibold">Description</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="description" v-model="form.description" size="small" />
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
    import { onMounted, ref, reactive, defineComponent, watch } from 'vue';
    import PrimeTable from 'primevue/datatable';
    import { Column, Dialog, InputText, Select, Button } from 'primevue';
    import useVuelidate from '@vuelidate/core';
    import { required } from '@vuelidate/validators';
    import ItemTypeService from '@/services/item-type.service';
    import posSwal from '@/utils/alertUtil';
    import datatableUtil from '@/utils/datatableUtil.js';
    import _ from 'underscore';

    defineComponent({
        name: 'ItemTypesPage'
    });

    let isLoading = ref(false);
    let pageLoading = ref(false);
    let rows = ref(10);
    let sortField = ref('name');
    let sortOrder = ref(datatableUtil.getSortOrder());
    let totalRecords = ref(0);
    let openCreateDialog = ref(false);
    let dialogHeader = ref(null);
    let itemTypesList = ref([]);
    let isUpdate = ref(false);
    let existingData = reactive({});

    const form = reactive({
        name: '',
        description: ''
    });

    const rules = {
        name: { required }
    };

    const v$ = useVuelidate(rules, form);

    function resetForm() {
        // Remove the 'id' property if it exists
        if ('id' in form) {
            delete form.id;
        }

        // Reset all other properties to null
        Object.keys(form).forEach(key => {
            form[key] = null;
        });
    }

    function openCreateOrUpdateDialog(isEditing) {
        openCreateDialog.value = true;

        if (isEditing) {
            dialogHeader.value = 'Edit Item Type'
            isUpdate.value = true;
        } else {
            dialogHeader.value = 'Add Item Type'
            resetForm()
        }
    }
    
    const searchForm = ref({
        name: null,
        description: null,
    });

    datatableUtil.setSortField(sortField.value);
    datatableUtil.setSearchForm(searchForm.value);

    const onSort = (event) => {
        datatableUtil.onSort(event.sortField, event.sortOrder);
        getItemTypesList();
    };

    const onPageChange = (event) => {
        datatableUtil.onPage(event.page, event.rows);
        getItemTypesList();
    };

    function resetTable() {
        datatableUtil.resetTable();
        getItemTypesList();
    }

    function getItemTypesList() {
        isLoading.value = true;
        ItemTypeService.getList(datatableUtil.getParams())
            .then((response) => {
                isLoading.value = false;
                const data = response.data;
                itemTypesList.value = data.data;
                totalRecords.value = data.total;
            })
            .catch((error) => {
                isLoading.value = false;
            });
    }

    function deleteItemType(data) {
        posSwal.fire({
            title: "Remove Item Type?",        
            html: `Are you sure that you want to remove the selected item type <b>(${data.name})</b>?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#337daf",
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            closeOnConfirm: true,
            closeOnCancel: true,
            draggable: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    
                    ItemTypeService.delete(data.id)
                        .then((response) => {
                            const data = response.data
                            let success = data.success;

                            if (success) {
                                posSwal.fire("Item Type Removed!", "Item Type successfully removed.", "success");
                            }
                        }).catch(error => {
                            posSwal.fire("Server Error!", "Deletion not successful. Kindly retry", "error");
                        });
                }
        });
    }

    function editItemType(data) {
        isUpdate.value = true;
        openCreateOrUpdateDialog(true);
        form.id = data.id;
        form.name = data.name;
        form.description = data.description;
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

            Object.keys(form).forEach(key => {
                if (form[key] !== existingData[key]) {
                    formData[key] = form[key];
                }
            });
        } else {
            formData = form;
        }
        
        ItemTypeService.save(formData)
            .then((response) => {
                openCreateDialog.value = false;

                const data = response.data;
                
                if (data.success) {
                    posSwal.fire({title: "Success!",
                        text: data.message,
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#48C9B0",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                    getItemTypesList();
                    pageLoading.value = false;
                    resetForm();
                }
            })
            .catch(error => {
                pageLoading.value = false;
                openCreateDialog.value = false;
                posSwal.fire({
                    title: "Error!",
                    text: error.response?.data?.message || "An error occurred while saving the item type.",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#E74C3C",
                    confirmButtonText: "Close",
                    closeOnConfirm: true,
                });
            });
    }
    
    // watchers here...
    watch(searchForm, (newValue) => {
        datatableUtil.setSearchForm(newValue);
    }, { deep: true });

    onMounted(() => {
        getItemTypesList();
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