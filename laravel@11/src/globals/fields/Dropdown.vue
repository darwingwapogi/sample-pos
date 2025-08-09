<template>
    <div class="form-group" :class="{'input-error': val[field.name] && val[field.name].$error}">
        <label>{{ field.label }} <span class="asterisk" v-if="isRequired">*</span></label>
        <v-select :options="field.options" :value="modelValue" @input="updateValue" :placeholder="field.placeholder ? field.placeholder : ''"/>
        <label v-if="val[field.name] && val[field.name].$error" class="error">
            {{ val[field.name].$errors[0].$message}}
        </label>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const props = defineProps({
    modelValue: {
        type: [String, Object],
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
</script>