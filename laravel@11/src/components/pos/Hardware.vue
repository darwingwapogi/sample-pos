<!-- 
    Author: Darwin Casanova
    Date: March 8, 2025
    Time: 8:30 PM
    Description: This is the component for the Hardware store POS system.
-->
<template>
    <div class="card page-card">
        <Loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" />
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="bi-postcard"></i> Point of Sales Page</h3>
        </div>
        <div class="card-body">
            <Accordion value="1" expandIcon="bi bi-plus-circle-dotted" collapseIcon="bi bi-dash-circle-dotted">
                <!-- <AccordionPanel value="0">
                    <AccordionHeader>
                        <Chip label="Customer" />
                    </AccordionHeader>
                    <AccordionContent>
                        <CustomerTable @selectCustomer="selectCustomer" :customerData="selectedCustomer"/>
                    </AccordionContent>
                </AccordionPanel> -->
                <AccordionPanel value="1">
                    <AccordionHeader>
                        <Chip label="Item" />
                    </AccordionHeader>
                    <AccordionContent>
                        <ItemTable @selectRows="selectRows" @viewItem="viewItem"/>
                    </AccordionContent>
                </AccordionPanel>
            </Accordion>
            <Fieldset legend="Transaction Details" class="mt-3">
                <div class="row">
                    <div class="content d-flex justify-content-end mb-3">
                        <button class="btn btn-primary" @click.prevent="createTransaction()">Finalize</button>
                    </div>
                </div>
                <Splitter style="min-height: 300px" layout="vertical">
                    <SplitterPanel class="flex items-center justify-center p-1" :size="25" :minSize="10">
                        
                        <Splitter layout="horizontal">
                            <SplitterPanel :size="33" :minSize="10">
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <h6 class="mb-1">Customer</h6>
                                        <p class="mb-0">
                                            <span v-if="selectedCustomer.full_name">{{ selectedCustomer.full_name }}</span>
                                            <span v-else>No customer selected</span>
                                        </p>
                                    </div>
                                    <!-- <div class="list-group-item">
                                        <h6 class="mb-1">Transaction Type</h6>
                                        <Select
                                            v-model="form.transaction_type_id"
                                            :options="transactionTypeList"
                                            placeholder="Select Transaction Type"
                                            optionLabel="label" 
                                            optionValue="value" 
                                            size="small"
                                            showClear
                                            fluid
                                            class="mb-0"
                                        />
                                    </div> -->
                                </div>
                            </SplitterPanel>
                            <SplitterPanel :size="33" :minSize="10">
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <h6 class="mb-1">Cash</h6>
                                        <inputText 
                                            id="cash" 
                                            v-model="form.amount_paid" 
                                            class="form-control" 
                                            :class="v$.amount_paid.$error ? 'is-invalid' : ''" 
                                            type="number"
                                            size="small"
                                        />
                                        <span v-if="v$.amount_paid.$error" class="invalid-feedback mt-0">
                                            <label>{{ v$.amount_paid.required.$message }}</label>
                                        </span>
                                    </div>
                                </div>
                            </SplitterPanel>
                            <SplitterPanel :size="34" :minSize="10">
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <td width="30%" class="fw-semibold">No. of Items</td>
                                            <td>{{ form.items.length }}</td>
                                        </tr>
                                        <tr>
                                            <td width="30%" class="fw-semibold">CASH</td>
                                            <td>
                                                {{ numberUtil.toPhpCurrency(form.amount_paid) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="30%" class="fw-semibold">TOTAL</td>
                                            <td>{{ calculateGrandTotal }}</td>
                                        </tr>
                                        <template v-if="form.discount_amount > 0">
                                            <tr>
                                                <td width="30%" class="fw-semibold">DISCOUNT</td>
                                                <td>{{ numberUtil.toPhpCurrency(form.discount_amount) }}</td>
                                            </tr>
                                            <tr>
                                                <td width="30%" class="fw-semibold">Discounted Total</td>
                                                <td></td>
                                            </tr>
                                        </template>
                                        <tr>
                                            <td width="30%" class="fw-bold text-lg">CHANGE</td>
                                            <td>{{ paymentChange }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </SplitterPanel>
                        </Splitter>
                    </SplitterPanel>
                    <SplitterPanel class="flex items-center justify-center" :size="75">
                        <table class="table table-bordered table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th width="1px"></th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Discount (%)</th>
                                    <th>Discount (&#8369;)</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in selectedItem" :key="index">
                                    <td width="1px">
                                        <button style="border: none; background: none; color: red;" title="Delete" @click="removeItem(index)"><i class='bi bi-trash'></i></button>
                                    </td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.item_type }}</td>
                                    <td>{{ item.brand }}</td>
                                    <td>{{ item.model }}</td>
                                    <td width="10px">
                                        <InputText v-model="item.quantity" type="number" style="width: 100px;" min="1" :max="item.stock_quantity" @input.prevent="onChangeQuantity(item)" />
                                        <Chip v-if="item.stock_warning != null" :label="item.stock_warning" style="color: red;" />
                                    </td>
                                    <td>{{ numberUtil.toPhpCurrency(item.final_selling_price) }}</td>
                                    <td width="10px">
                                        <InputText v-model="item.percentage_discount" type="number" style="width: 100px;" min="0" @input.prevent="onChangeDiscInP(item)" />
                                    </td>
                                    <td width="10px">
                                        <InputText v-model="item.discount_amount" type="number" style="width: 100px;" min="0" @input.prevent="onChangeDiscInA(item)" />
                                    </td>
                                    <td>{{ numberUtil.toPhpCurrency(item.final_selling_price * item.quantity) }}</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td width="1px"></td>
                                    <td colspan="8">Total</td>
                                    <td>{{ calculateGrandTotal }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </SplitterPanel>
                </Splitter>
            </Fieldset>
            <Dialog v-model:visible="openModal" modal header="Item Details" :style="{ width: '25rem' }" @hide="closeModal">
                <itemRecord :itemData="itemData" />
                <div class="flex justify-end gap-2">
                    <Button type="button" label="Cancel" severity="secondary" @click="closeModal"></Button>
                </div>
            </Dialog>
        </div>
    </div>
</template>
<script setup>
    import Loading from 'vue3-loading-overlay';
    import { ref, defineComponent, nextTick, onMounted, watch, computed } from 'vue';
    import { Column, DatePicker, InputText, Select, Button, Fieldset, Accordion, AccordionPanel, AccordionHeader, AccordionContent, Splitter, SplitterPanel, IftaLabel, Dialog } from 'primevue';
    import PosService from '@/services/pos.service.js';
    import CustomerTable from './tables/CustomerTable.vue';
    import ItemTable from './tables/ItemTable.vue';
    import Chip from 'primevue/chip';
    import numberUtil from '@/utils/numberUtil.js';
    import _ from 'underscore';
    import useVuelidate from '@vuelidate/core';
    import { required } from '@vuelidate/validators';
    import posSwal from '@/utils/alertUtil.js';
    import itemRecord from '../item/itemRecord.vue';

    defineComponent({
        name: 'PointOfSalesPage'
    });

    const isLoading = ref(false);
    const openModal = ref(false);
    const itemData = ref({});

    //default to walk-in customer
    const selectedCustomer = ref({
        id: 1,
        full_name: 'Walk-in',
    });

    const selectedItem = ref([]);
    let form = ref({
        customer_id: selectedCustomer.value.id,
        total_amount: null,
        method_id: 1,
        amount_paid: null,
        discount_amount: null,
        percentage_discount: null,
        transaction_type_id: 1,
        transaction_id: null,
        notes: null,
        due_date: null,
        items: [],
    });

    const rules = {
        customer_id: { required },
        total_amount: { required },
        amount_paid: { required },
        items: { 
            required, 
            $each: {
                quantity: {
                    required,
                    isValidQuantity: (value) => value > 0 || 'Quantity must be greater than 0',
                },
                total: {
                    required,
                    isValidTotal: (value) => value > 0 || 'Total must be greater than 0',
                },
            },
        },
        method_id: { required },
        transaction_type_id: { required },
    };

    const v$ = useVuelidate(rules, form);

    function selectCustomer(customer) {
        form.value.customer_id = customer.id;
        selectedCustomer.value = customer;
    }

    function selectRows(data) {
        const sanitizedArray = [];

        if (data.length > 0) {
            for (const item of data) {
                sanitizedArray.push({
                    id: item.id,    
                    name: item.name,
                    item_type: item.item_type,
                    brand: item.brand,
                    model: item.model,
                    is_vat_enabled: item.is_vat_enabled,
                    quantity: item.quantity ?? 1,
                    cost_price: parseFloat(item.cost_price.replace(/[^\d.-]/g, '')),
                    selling_price: parseFloat(item.selling_price.replace(/[^\d.-]/g, '')),
                    percentage_discount: parseFloat(item.percentage_discount.replace(/[^\d.-]/g, '')),
                    discount_amount: parseFloat(item.discount_amount.replace(/[^\d.-]/g, '')),
                    final_selling_price: parseFloat(item.final_selling_price.replace(/[^\d.-]/g, '')),
                    stock_quantity: parseFloat(item.stock_quantity.replace(/[^\d.-]/g, '')),
                    stock_warning: null,
                });
            }
            selectedItem.value = sanitizedArray;
        } else {
            selectedItem.value = [];
        }
        
    }
    
    function removeItem(index) {
        selectedItem.value.splice(index, 1);
        form.value.items = [...selectedItem.value];
    }

    function resetForm() {
        form.value = {
            customer_id: selectedCustomer.value.id,
            total_amount: null,
            method_id: 1,
            amount_paid: null,
            discount_amount: null,
            percentage_discount: null,
            transaction_type_id: 1,
            transaction_id: null,
            notes: null,
            due_date: null,
            items: [],
        };
        selectedItem.value = [];
    }

    const calculateGrandTotal = computed(() => {
        let grandTotal = 0;

        if (selectedItem.value.length === 0) {
            return numberUtil.toPhpCurrency(0);
        }

        selectedItem.value.forEach(item => {
            grandTotal += item.final_selling_price * item.quantity;
        });
        
        return numberUtil.toPhpCurrency(grandTotal);
    });

    const paymentChange = computed(() => {

        if (!_.isEmpty(form.value.amount_paid) && form.value.amount_paid > 0
            && (!_.isNull(form.value.total_amount) && form.value.total_amount > 0)
        ) {
            return numberUtil.toPhpCurrency(form.value.amount_paid - form.value.total_amount);
        }
        
        return numberUtil.toPhpCurrency(0);
    });

    function createTransaction() {
        if (form.value.items.length === 0) {
            posSwal.fire("Warning!", "Please select at least one item.", "warning");
            return;
        }
        
        v$.value.$touch();
        
        if (v$.value.$pending || v$.value.$invalid) {
            return;
        }

        if (form.value.amount_paid < form.value.total_amount) {
            posSwal.fire("Warning!", "Cash amount must be greater than or equal to the total amount.", "warning");
            return;
        }

        isLoading.value = true;

        PosService.processSale(form.value)
            .then((response) => {
                const data = response.data;
                isLoading.value = false;
                if (data.success) {
                    posSwal.fire("Success!", "Sale transaction created successfully.", "success");
                    resetForm();
                }
            })
            .catch((error) => {
                isLoading.value = false;
                posSwal.fire("Error!", "Something went wrong!", "error");
            });
    }

    function viewItem(item) {
        openModal.value = true
        itemData.value = item;
    }

    function closeModal() {
        openModal.value = false;
        itemData.value = {};
    }

    const isItemSelected = computed(() => (id) => {
        return selectedItem.value.some(item => item.id === id);
    });

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

    function onChangeDiscInP(item) {
        const value = event.target.value;
        item.discount_amount = 0;
        
        if (item.percentage_discount < 0 || item.percentage_discount > 100 || value == null || value == undefined || isNaN(value) || value == Infinity || value == -Infinity || value == -1) {
            posSwal.fire("Warning!", "Discount percentage must be between 0 and 100.", "warning");
            item.percentage_discount = 0;
        }

        const params = {
                selling_price: item.selling_price,
                discount_amount: item.discount_amount,
                percentage_discount: item.percentage_discount,
                is_vat_enabled: item.is_vat_enabled,
            }

        PosService.recomputeFinalSellingPrice(params)
            .then((response) => {
                item.final_selling_price = response.data.final_selling_price
            });
    }

    function onChangeDiscInA(item) {
        const value = event.target.value;
        item.percentage_discount = 0;

        if (item.discount_amount < 0 || value == null || value == undefined || isNaN(value) || value == Infinity || value == -Infinity || value == -1) {
            posSwal.fire("Warning!", "Discount amount must be greater than or equal to 0.", "warning");
            item.discount_amount = 0;
        }

        const params = {
                selling_price: item.selling_price,
                discount_amount: item.discount_amount,
                percentage_discount: item.percentage_discount,
                is_vat_enabled: item.is_vat_enabled,
            }

        PosService.recomputeFinalSellingPrice(params)
            .then((response) => {
                item.final_selling_price = response.data.final_selling_price
            });
    }

    // Watchers here...
    watch(selectedItem.value, (newValue) => {
        let totalAmount = 0

        newValue.forEach(item => {
            totalAmount += item.final_selling_price * item.quantity;
        });
        
        form.value.total_amount = totalAmount;
        form.value.items = [...newValue];
    });

</script>
<style scoped>
.list-group .list-group-item {
    height: 90px;
}
</style>