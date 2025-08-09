<template>
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <td width="30%"><strong>Name:</strong></td>
                <td width="70%">{{ itemData.name }}</td>
            </tr>
            <tr>
                <td><strong>Description:</strong></td>
                <td>{{ itemData.description }}</td>
            </tr>
            <tr>
                <td><strong>Type:</strong></td>
                <td>{{ itemData.item_type }}</td>
            </tr>
            <tr>
                <td><strong>Category:</strong></td>
                <td>{{ itemData.category }}</td>
            </tr>
            <tr>
                <td><strong>Brand:</strong></td>
                <td>{{ itemData.brand }}</td>
            </tr>
            <tr>
                <td><strong>Model:</strong></td>
                <td>{{ itemData.model }}</td>
            </tr>
            <tr>
                <td><strong>Supplier:</strong></td>
                <td>{{ itemData.supplier }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>{{ itemData.status }}</td>
            </tr>
            <tr>
                <td><strong>SKU:</strong></td>
                <td>{{ itemData.sku }}</td>
            </tr>
            <tr>
                <td><strong>Barcode:</strong></td>
                <td>{{ itemData.barcode }}</td>
            </tr>
            <tr>
                <td><strong>Size:</strong></td>
                <td>{{ itemData.size }}</td>
            </tr>
            <tr>
                <td><strong>Color:</strong></td>
                <td>{{ itemData.color }}</td>
            </tr>
            <tr>
                <td><strong>Stock Quantity:</strong></td>
                <td>{{ itemData.stock_quantity }}</td>
            </tr>
            <tr>
                <td><strong>Unit:</strong></td>
                <td>{{ itemData.unit }}</td>
            </tr>
            <tr>
                <td><strong>Reorder Level:</strong></td>
                <td>{{ itemData.reorder_level }}</td>
            </tr>
            <tr>
                <td><strong>Cost Price:</strong></td>
                <td>{{ itemData.cost_price }}</td>
            </tr>
            <tr>
                <td><strong>Selling Price:</strong></td>
                <td>{{ itemData.selling_price }}</td>
            </tr>
            <tr>
                <td><strong>Wholesale Price:</strong></td>
                <td>{{ itemData.whole_sale_price }}</td>
            </tr>
            <tr>
                <td><strong>Markup (%):</strong></td>
                <td>{{ itemData.markup + "%" }}</td>
            </tr>
            <tr>
                <td><strong>Markup Amount:</strong></td>
                <td>{{ itemData.markup_amount }}</td>
            </tr>
            <tr>
                <td><strong>Discount Amount:</strong></td>
                <td>{{ itemData.discount_amount }}</td>
            </tr>
            <tr>
                <td><strong>Percentage Discount:</strong></td>
                <td>{{ itemData.percentage_discount }}</td>
            </tr>
            <tr>
                <td><strong>Vatable:</strong></td>
                <td>{{ itemData.is_vat_enabled ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <td><strong>VAT Amount:</strong></td>
                <td>{{ itemData.vat_amount }}</td>
            </tr>
            <tr>
                <td><strong>VAT Percentage:</strong></td>
                <td>{{ itemData.vat_percentage }}</td>
            </tr>
            <tr>
                <td><strong>Expiry Date:</strong></td>
                <td>{{ itemData.expiry_date }}</td>
            </tr>
        </tbody>
    </table>
</template>
<script setup>
import { ref, defineComponent, defineProps } from 'vue';

    defineComponent({
        name: 'ItemRecordTable'
    });

    const props = defineProps({
        itemData: {
            type: Object,
            required: true,
            default: {
                name: '',
                description: '',
                item_type: '',
                category: '',
                brand: '',
                model: '',
                supplier: '',
                status: '',
                sku: '',
                barcode: '',
                size: '',
                color: '',
                stock_quantity: '',
                unit: '',
                reorder_level: 0,
                cost_price: 0,
                selling_price: 0,
                whole_sale_price: 0,
                markup: 0,
                markup_amount: 0,
                discount_amount: 0,
                percentage_discount: 0,
                is_vat_enabled: false,
                vat_amount: 0,
                vat_percentage: 0,
                expiry_date: ''
            }
        }
    });

    const itemData = ref(props.itemData);
</script>