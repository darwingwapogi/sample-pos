<template>
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ supplierData.name }}</td>
            </tr>
            <tr>
                <td><strong>Contact Person:</strong></td>
                <td>{{ supplierData.contact_person }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>{{ supplierData.status }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ supplierData.email }}</td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td>{{ supplierData.phone }}</td>
            </tr>
            <tr>
                <td><strong>Website:</strong></td>
                <td>{{ supplierData.website }}</td>
            </tr>
            <tr>
                <td><strong>Address:</strong></td>
                <td>{{ supplierData.address }}</td>
            </tr>
            <tr>
                <td><strong>City:</strong></td>
                <td>{{ supplierData.city }}</td>
            </tr>
            <tr>
                <td><strong>Province:</strong></td>
                <td>{{ supplierData.province }}</td>
            </tr>
            <tr>
                <td><strong>Country:</strong></td>
                <td>{{ supplierData.country }}</td>
            </tr>
            <tr>
                <td><strong>Zip Code:</strong></td>
                <td>{{ supplierData.zip_code }}</td>
            </tr>
        </tbody>
    </table>
</template>
<script setup>
import { ref, defineComponent, defineProps } from 'vue';

defineComponent({
    name: 'SupplierRecordTable'
});

const props = defineProps({
    supplierData: {
        type: Object,
        required: true,
        default: {
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
        }
    }
});

const supplierData = ref(props.supplierData);
</script>
