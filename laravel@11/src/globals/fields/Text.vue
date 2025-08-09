<template>
    <div class="form-group" :class="{'input-error': val[field.name] && val[field.name].$error}">
        <label>{{ field.label }} <span class="asterisk" v-if="isRequired">*</span></label>
        <input type="text" :value="modelValue" @input="updateValue($event.target.value)" class="form-control" :placeholder="field.placeholder ? field.placeholder : ''" :disabled="disabled"/>
        <label v-if="val[field.name] && val[field.name].$error" class="error">
            {{ val[field.name].$errors[0].$message}}
        </label>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';

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

const disabled = computed(() => {
    return props.field.disabled === true;
});

const updateValue = (value) => {
    emit('update:modelValue', value);
};
</script>