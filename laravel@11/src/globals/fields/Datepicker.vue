<template>
    <div class="form-group" :class="{'input-error': val[field.name] && val[field.name].$error}">
        <label>{{ field.label }} <span class="asterisk" v-if="isRequired">*</span></label>
        <DatePicker 
            :model-value="modelValue" 
            @update:model-value="updateValue" 
            :enable-time-picker="false" 
            auto-apply
        />
        <label v-if="val[field.name] && val[field.name].$error" class="error">
            {{ val[field.name].$errors[0].$message}}
        </label>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed, onMounted } from 'vue';
import DatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import _ from 'underscore';

const props = defineProps({
    modelValue: {
        type: String,
        required: true
    },
    field: {
        type: Object,
        required: true
    },
    behaviour: {
        type: Array,
        default: () => []
    },
    val: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['update:modelValue']);

const isRequired = computed(() => {
    return props.field.required === true;
});

const updateValue = (value) => {
    emit('update:modelValue', value);
};

onMounted(() => {
    if (_.isEmpty(props.modelValue)) {
        emit('update:modelValue', new Date().toISOString().substr(0, 10));
    }
})
</script>