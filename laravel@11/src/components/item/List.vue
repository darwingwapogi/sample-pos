<template>
    <div class="card page-card">
        <div class="card-header d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><i class="bi bi-box-seam-fill"></i> Items List</h3>
            <button class="btn btn-primary" @click.prevent="openCreateOrUpdateDialog(false)">Create</button>
        </div>
        <div class="card-body vl-parent">
            <Loading v-model:active="pageLoading" :is-full-page="false" :color="`#1ecbe1`"/>
            <div class="d-flex justify-content-end">
                <Button label="Reset" class="p-button-secondary ms-1" icon="bi bi-x-circle" @click.prevent="resetTable()" />
                <Button label="Search" class="p-button-pri ms-1" icon="bi bi-search" @click.prevent="getItemsList()" />
            </div>
            <PrimeTable
                :value="itemsList"
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
                @keydown.enter="getItemsList"
                filterDisplay="row"
                :loading="isLoading"
                stripedRows
                class="small-font-table">
                <Column field="" header="" :sortable="false" :filter="false" :showFilterMenu="false" style="width: 3px;">
                    <template #body="slotProps">
                        <button style="border: none; background: none; color: #337daf;" title="edit" @click="editItem(slotProps.data)"><i class='bi bi-pencil-square'></i></button>
                    </template>
                </Column>
                <Column field="" header="" :sortable="false" :filter="false" :showFilterMenu="false" style="width: 3px;">
                    <template #body="slotProps">
                        <button style="border: none; background: none; color: #337daf;" title="View" @click="viewItem(slotProps.data)"><i class='bi bi-eye'></i></button>
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
                        <Select v-model="searchForm.item_type"
                            :options="itemTypeOptions" optionLabel="label" optionValue="value"
                            size="small" showClear fluid />
                    </template>
                </Column>
                <Column field="category" header="Category" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <Select v-model="searchForm.category"
                            :options="categoryOptions" optionLabel="label" optionValue="value"
                            size="small" showClear fluid />
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
                <Column field="size" header="Size" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.size"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="color" header="Color" :sortable="true" :filter="true" :showFilterMenu=false>
                      <template #filter>
                        <InputText v-model="searchForm.color"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="supplier" header="Supplier" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputText v-model="searchForm.supplier"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="final_selling_price" header="Price" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputNumber v-model="searchForm.final_selling_price"
                            :useGrouping="false"
                            :minFractionDigits="2"
                            :maxFractionDigits="5"
                            size="small"
                            fluid />
                    </template>
                </Column>
                <Column field="stock_quantity" header="Total Stocks" :sortable="true" :filter="true" :showFilterMenu=false>
                    <template #filter>
                        <InputNumber v-model="searchForm.stock_quantity"
                            :useGrouping="false"
                            :minFractionDigits="2"
                            :maxFractionDigits="5"
                            size="small"
                            fluid />
                    </template>
                </Column>
            </PrimeTable>
            <div v-if="itemsList.length === 0" class="text-center">
                <p>No record found.</p>
            </div>

            <Dialog v-model:visible="openDialog" modal header="Item Details" :style="{ width: '30rem' }" @hide="closeDialog">
                <ItemRecord :itemData="itemData" />
            </Dialog>

            <Dialog v-model:visible="openCreateDialog" :header="dialogHeader" :style="{ width: '40rem' }" class="vl-parent">
                <Loading v-model:active="isCalculateSellingPriceLoading" :is-full-page="false" :color="`#1ecbe1`" loader="bars"/>
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
                            <textarea class="form-control" id="description" v-model="form.description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="item_type_id" class="col-sm-3 col-form-label fw-semibold">Item Type  <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <Select class="form-control" v-model="form.item_type_id" id="item_type_id" :options="itemTypeOptions" optionLabel="label" optionValue="value" placeholder="Select Item Type" size="small" :class="{'is-invalid': v$.item_type_id.$error}" showClear fluid />
                            <span v-if="v$.item_type_id.$error" class="invalid-feedback mt-0">
                                <label>{{ v$.item_type_id.required.$message }}</label>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="category_id" class="col-sm-3 col-form-label fw-semibold">Category  <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <Select class="form-control" v-model="form.category_id" id="category_id" :options="categoryOptions" optionLabel="label" optionValue="value" placeholder="Select Category" size="small" :class="v$.category_id.$error ? 'is-invalid' : ''" showClear fluid />
                            <span v-if="v$.category_id.$error" class="invalid-feedback mt-0">
                                <label>{{ v$.category_id.required.$message }}</label>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="brand" class="col-sm-3 col-form-label fw-semibold">Brand</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="brand" v-model="form.brand" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="model" class="col-sm-3 col-form-label fw-semibold">Model</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="model" v-model="form.model" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="supplier_id" class="col-sm-3 col-form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                        <div class="col-sm-9" :class="{'text-danger' : v$.supplier_id.$error}">
                            <AutoComplete v-model="form.supplier_id" id="supplier_id" dropdown :suggestions="supplierOptions" placeholder="Select a supplier" optionLabel="name" @complete="getSupplierList" size="small" fluid />
                            {{ v$.supplier_id.$error ? v$.supplier_id.required.$message : '' }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="status_id" class="col-sm-3 col-form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <Select class="form-control" v-model="form.status_id" id="status_id" :options="itemStatusOptions" optionLabel="label" optionValue="value" placeholder="Select Status" size="small" :class="v$.status_id.$error ? 'is-invalid' : ''" showClear fluid />
                            <span v-if="v$.status_id.$error" class="invalid-feedback mt-0">
                                <label>{{ v$.status_id.required.$message }}</label>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="barcode" class="col-sm-3 col-form-label fw-semibold">Barcode</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="barcode" v-model="form.barcode" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="size" class="col-sm-3 col-form-label fw-semibold">Size</label>
                        <div class="col-sm-9">
                            <InputText class="form-control" id="size" v-model="form.size" size="small" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="color" class="col-sm-3 col-form-label fw-semibold">Color</label>
                        <div class="col-sm-9">
                            <InputText id="color" v-model="form.color" size="small" fluid />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="stock_quantity" class="col-sm-3 col-form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
                        <div class="col-sm-9" :class="{'text-danger' : v$.stock_quantity.$error}">
                            <InputNumber id="stock_quantity" v-model="form.stock_quantity" size="small" fluid />
                            {{ v$.stock_quantity.$error ? v$.stock_quantity.required.$message : '' }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="unit_id" class="col-sm-3 col-form-label fw-semibold">Unit <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <Select class="form-control" v-model="form.unit_id" id="unit_id" :options="unitOptions" optionLabel="label" optionValue="value" placeholder="Select Unit" :class="v$.unit_id.$error ? 'is-invalid' : ''" size="small" showClear fluid />
                            <span v-if="v$.unit_id.$error" class="invalid-feedback mt-0">
                                <label>{{ v$.unit_id.required.$message }}</label>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="reorder_level" class="col-sm-3 col-form-label fw-semibold">Reorder Level <span class="text-danger">*</span></label>
                        <div class="col-sm-9" :class="{'text-danger' : v$.reorder_level.$error}">
                            <InputNumber id="reorder_level" v-model="form.reorder_level" size="small" fluid />
                            {{ v$.reorder_level.$error ? v$.reorder_level.required.$message : '' }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="is_vat_enabled" class="col-sm-3 col-form-label fw-semibold">VAT Enabled</label>
                        <div class="col-sm-9">
                            <Checkbox v-model="form.is_vat_enabled" class="small" binary /> {{ form.is_vat_enabled ? 'Yes' : 'No' }}
                        </div>
                    </div>
                    <div class="mb-4">
                        <h5 class="fw-bold">Pricing Details</h5>

                        <div class="mb-3 row">
                            <label for="cost_price" class="col-sm-3 col-form-label fw-semibold">Cost Price <span class="text-danger">*</span></label>
                            <div class="col-sm-9" :class="{'text-danger' : v$.cost_price.$error}">
                                <InputNumber id="cost_price" v-model="form.cost_price" mode="decimal" showButtons :min="0" :max="1000000" :minFractionDigits="2" :maxFractionDigits="5" size="small" fluid />
                                {{ v$.cost_price.$error ? v$.cost_price.required.$message : '' }}
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="whole_sale_price" class="col-sm-3 col-form-label fw-semibold">Wholesale Price</label>
                            <div class="col-sm-9">
                                <InputNumber id="whole_sale_price" v-model="form.whole_sale_price" mode="decimal" showButtons :min="0" :max="1000000" :minFractionDigits="2" :maxFractionDigits="5" size="small" fluid />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label fw-semibold">Markup <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <IconField>
                                            <InputIcon class="bi bi-percent" />
                                            <InputNumber v-model="form.markup" placeholder="Percentage" mode="decimal" showButtons :min="0" :max="100" :minFractionDigits="2" :maxFractionDigits="5" size="small" fluid />
                                        </IconField>
                                    </div>
                                    <div class="col-sm-6">
                                        <IconField>
                                            <InputNumber v-model="form.markup_amount" placeholder="Amount" mode="decimal" showButtons :min="0" :max="1000000" :minFractionDigits="2" :maxFractionDigits="5" size="small" fluid />
                                        </IconField>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label fw-semibold">Discount</label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <IconField>
                                            <InputIcon class="bi bi-percent" />
                                            <InputNumber v-model="form.percentage_discount" placeholder="Percentage" mode="decimal" showButtons :min="0" :max="100" :minFractionDigits="2" :maxFractionDigits="5" size="small" fluid />
                                        </IconField>
                                    </div>
                                    <div class="col-sm-6">
                                        <IconField>
                                            <InputNumber v-model="form.discount_amount" placeholder="Amount" mode="decimal" showButtons :min="0" :max="1000000" :minFractionDigits="2" :maxFractionDigits="5" size="small" fluid />
                                        </IconField>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold">Price Summary</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Selling Price</label>
                                    <Chip :label="numberUtil.toPhpCurrency(sellingPrice)" class="bg-light text-dark w-100" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Discounted Price</label>
                                    <Chip :label="numberUtil.toPhpCurrency(discountedPrice)" class="bg-light text-dark w-100" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">VAT Amount</label>
                                    <Chip :label="numberUtil.toPhpCurrency(vatAmount)" class="bg-light text-dark w-100" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">VAT Percentage</label>
                                    <Chip label="12%" class="bg-light text-dark w-100" />
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Final Selling Price</label>
                                    <Chip :label="numberUtil.toPhpCurrency(finalSellingPrice)" class="bg-light text-dark w-100" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="expiry_date" class="col-sm-3 col-form-label fw-semibold">Expiry Date</label>
                        <div class="col-sm-9">
                            <DatePicker v-model="form.expiry_date" id="expiry_date" placeholder="Select Date" showIcon iconDisplay="input" size="small" showButtonBar fluid />
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
    import {ref, defineComponent, onMounted, watch, reactive} from 'vue';
    import PrimeTable from 'primevue/datatable';
    import { Column, Dialog, InputText, Select, Button, DatePicker, AutoComplete, Checkbox, InputNumber, IconField, InputIcon, Chip } from 'primevue';
    import datatableUtil from '@/utils/datatableUtil.js';
    import ItemService from '@/services/item.service.js';
    import useVuelidate from '@vuelidate/core';
    import { required } from '@vuelidate/validators';
    import ItemRecord from './itemRecord.vue';
    import DbConfigService from '@/services/db-config.service.js';
    import posSwal from '@/utils/alertUtil.js';
    import numberUtil from '@/utils/numberUtil.js';
    import _ from 'underscore';

    defineComponent({
        name: 'ItemsPage'
    });

    let isLoading = ref(false);
    let pageLoading = ref(false);
    let isCalculateSellingPriceLoading = ref(false);

    let itemsList = ref([]);
    let rows = ref(10);
    let sortField = ref('name');
    let sortOrder = ref(datatableUtil.getSortOrder());
    let totalRecords = ref(0);
    let openDialog = ref(false);
    let openCreateDialog = ref(false);
    const itemData = ref({});
    let dialogHeader = ref(null);
    const itemTypeOptions = ref([]);
    const categoryOptions = ref([]);
    const unitOptions = ref([]);
    const supplierOptions = ref([]);
    const itemStatusOptions = ref([]);
    const sellingPrice = ref(0);
    const discountedPrice = ref(0);
    const vatAmount = ref(0);
    const finalSellingPrice = ref(0);
    let isUpdate = ref(false);
    let existingData = reactive({});

    const form = reactive({
        name: null,
        brand: null,
        model: null,
        description: null,
        stock_quantity: null,
        barcode: null,
        size: null,
        color: null,
        reorder_level: null,
        cost_price: 0,
        markup: 0,
        markup_amount: 0,
        is_vat_enabled: false,
        whole_sale_price: 0,
        discount_amount: 0,
        percentage_discount: 0,
        category_id: null,
        unit_id: null,
        item_type_id: null,
        supplier_id: null,
        status_id: null,
        expiry_date: null
    });

    const markupRequired = (value) => {
        return form.markup !== null || form.markup_amount !== null || 'Either markup or markup amount is required.';
    };

    const rules = {
        name: { required },
        category_id: { required },
        item_type_id: { required },
        status_id: { required },
        supplier_id: { required },
        unit_id: { required },
        cost_price: { required },
        markup: { required: markupRequired },
        markup_amount: { required: markupRequired },
        stock_quantity: { required },
        reorder_level: { required },
    };

    const v$ = useVuelidate(rules, form);

    function resetForm() {
        if (_.has(form, 'id')) {
            delete form.id;
        }

        Object.keys(form).forEach(key => {
            form[key] = null;
        });

        form.cost_price = 0;
        form.markup = 0;
        form.markup_amount = 0;
        form.whole_sale_price = 0;
        form.discount_amount = 0;
        form.percentage_discount = 0;
    }

    function openCreateOrUpdateDialog(isEditing) {
        openCreateDialog.value = true;

        if (isEditing) {
            dialogHeader.value = 'Edit Item'
            isUpdate.value = true;
        } else {
            dialogHeader.value = 'Add Item'
            resetForm()
        }
    }

    const searchForm = ref({
        supplier: null,
        category: null,
        unit: null,
        item_type: null,
        status: null,
        brand: null,
        model: null,
        name: null,
        cost_price: null,
        final_selling_price: null,
        whole_sale_price: null,
    });

    datatableUtil.setSortField(sortField.value);
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
                isLoading.value = false;
                const data = response.data;
                itemsList.value = data.data;
                totalRecords.value = data.total;
            })
            .catch((error) => {
                isLoading.value = false;
            });
    }

    function getUnitsList() {
        DbConfigService.getUnitList().then(
            (response) => {
                unitOptions.value = response.data;
            }
        );
    }

    function getItemTypesList() {
        DbConfigService.getItemTypeList().then(
            (response) => {
                itemTypeOptions.value = response.data;
            }
        );
    }

    function getCategoryList() {
        DbConfigService.getCategoryList().then(
            (response) => {
                categoryOptions.value = response.data;
            }
        );
    }

    function getSupplierList() {
        DbConfigService.getSupplierList().then(
            (response) => {
                supplierOptions.value = response.data.map((item) => ({
                    id: item.value,
                    name: item.label
                }));
            }
        );
    }

    function getItemStatusList() {
        DbConfigService.getItemStatusList().then(
            (response) => {
                itemStatusOptions.value = response.data;
            }
        );
    }

    function viewItem(item) {
        openDialog.value = true
        itemData.value = item;
    }

    function closeDialog() {
        openDialog.value = false;
        itemData.value = {};
    }

    function getOptionValue(field, optionData) {
        for (const item of optionData) {
            if (item.label === field) {
                return item.value;
            }
        }
    }

    function editItem(data) {
        isUpdate.value = true;
        openCreateOrUpdateDialog(true);
        form.id = data.id;
        form.name = data.name;
        form.brand = data.brand;
        form.model = data.model;
        form.description = data.description;
        form.stock_quantity = parseInt(data.stock_quantity.match(/\d+/)[0]);
        form.barcode = data.barcode;
        form.size = data.size;
        form.color = data.color;
        form.reorder_level = data.reorder_level;
        form.cost_price = data.cost_price;
        form.markup = data.markup;
        form.markup_amount = data.markup_amount;
        form.is_vat_enabled = data.is_vat_enabled;
        form.whole_sale_price = data.whole_sale_price;
        form.discount_amount = data.discount_amount;
        form.percentage_discount = data.percentage_discount;
        form.category_id = getOptionValue(data.category, categoryOptions.value);
        form.unit_id = getOptionValue(data.unit, unitOptions.value);
        form.item_type_id = getOptionValue(data.item_type, itemTypeOptions.value);
        form.supplier_id = data.supplier;
        form.status_id = getOptionValue(data.status, itemStatusOptions.value);
        form.expiry_date = data.expiry_date;

        // Store the original data from the database to track changes in field values
        existingData = data;
    }

    function btnSubmit() {
        v$.value.$touch();

        if (v$.value.$pending || v$.value.$invalid) {
            return;
        }

        pageLoading.value = true;
        form.supplier_id = form.supplier_id.id;
        form.is_vat_enabled = form.is_vat_enabled ?? false;
        let formData = {};

        if (isUpdate.value) {
            formData.id = form.id;
            formData.cost_price = form.cost_price;
            formData.markup = form.markup;
            formData.markup_amount = form.markup_amount;
            formData.percentage_discount = form.percentage_discount;
            formData.discount_amount = form.discount_amount;
            formData.is_vat_enabled = form.is_vat_enabled;

            Object.keys(form).forEach(key => {
                if (form[key] !== existingData[key]) {
                    formData[key] = form[key];
                }
            });
            
        } else {
            formData = form;
        }

        ItemService.save(formData)
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
                    getItemsList();
                    pageLoading.value = false;
                    resetForm();
                }
            })
            .catch(error => {
                pageLoading.value = false;
                posSwal.fire({
                    title: "Error!",
                    text: error.response?.data?.message || "An error occurred while saving the Item.",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#E74C3C",
                    confirmButtonText: "Close",
                    closeOnConfirm: true,
                });
            });
    }

    // watchers here...
    watch(() => form.markup, (newValue, oldValue) => {
        if (newValue !== oldValue) {
            form.markup_amount = 0;
            computeFinalSellingPrice();
        }
    });

    watch(() => form.markup_amount, (newValue, oldValue) => {
        if (newValue !== oldValue) {
            form.markup = 0;
            computeFinalSellingPrice();
        }
    });

    watch(() => form.is_vat_enabled, (newValue) => {
        if (newValue) {
            computeFinalSellingPrice();
        }
    });

    watch(() => form.percentage_discount, (newValue, oldValue) => {
        if (newValue !== oldValue) {
            form.discount_amount = 0;
            computeFinalSellingPrice();
        }
    });

    watch(() => form.discount_amount, (newValue, oldValue) => {
        if (newValue !== oldValue) {
            form.percentage_discount = 0;
            computeFinalSellingPrice();
        }
    });

    watch(() => form.cost_price, (newValue) => {
        if (newValue) {
            computeFinalSellingPrice();
        }
    });

    function computeFinalSellingPrice() {
        if (
            !(form.cost_price &&
              (form.markup || form.markup_amount)
            )
        ) return;

        isCalculateSellingPriceLoading.value = true;

        ItemService.computeFinalSellingPrice(form)
            .then((response) => {
                const data = response.data;
                sellingPrice.value = data.final_selling_price;
                discountedPrice.value = data.discounted_price;
                vatAmount.value = data.vat_amount;
                finalSellingPrice.value = data.final_selling_price;
            })
            .finally(() => {
                isCalculateSellingPriceLoading.value = false;
            });
    }

    onMounted(() => {
        getItemsList();
        getUnitsList();
        getItemTypesList();
        getCategoryList();
        getItemStatusList();

        //validate on mounted
        v$.value.$touch();
    });
</script>
<style scoped>
@import '/resources/css/primeTableCts.css';
</style>
