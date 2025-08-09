<template>
    <form @submit.prevent="$event.preventDefault()">
        <div class="card fixed-width">
            <div class="card-header">
                {{ configs.panel_header.title }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div v-for="field in configs.panel_body.fields.flat()" :key="field.name" class="col-sm-6">
                        <Text v-if="!field.type || field.type === 'text'" v-model="form[field.name]" :field="field" :val="val" />
                        <Dropdown v-else-if="field.type === 'dropdown'" v-model="form[field.name]" :field="field" :val="val" />
                        <Datepicker v-else-if="field.type === 'datepicker'" v-model="form[field.name]" :field="field" :val="val" />
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button
                    v-for="button in configs.panel_footer.buttons"
                    :key="button.label"
                    :class="[button.class, 'ms-2']"
                    @click="button.click"
                    tabindex="-1"
                >
                    {{ button.label }}
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import { defineComponent, ref, toRefs } from 'vue';
import Text from '../globals/fields/Text.vue';
import Dropdown from '../globals/fields/Dropdown.vue';
import Datepicker from '../globals/fields/Datepicker.vue';

export default defineComponent({
    components: {
        Text,
        Dropdown,
        Datepicker
    },
    name: 'MainPanel',
    props: {
        configs: {
            type: Object,
            required: true
        },
        form: {
            type: Object,
            required: false
        },
        val: {
            type: Object,
            default: () => ({})
        }
    },
    setup(props) {
        const { configs, form } = toRefs(props);

        return {
            configs,
            form
        };
    }
});
</script>

<style scoped>
.fixed-width {
    min-width: min-content;
    max-width: 780px;
    width: 100%;
    margin: 0 auto;
}
</style>