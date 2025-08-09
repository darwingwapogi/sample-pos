<template>
    <button style="border: none; background: none" title="Delete" @click="handleClick()"><i class='bi bi-trash'></i></button>
</template>

<script>
import { defineComponent, toRefs } from 'vue';

export default defineComponent({
    props: {
        data: { type: [String, Object, Array] },
        callback: Function,
        initialCallback: Function,
        field: String
    },
    setup(props) {
        const { data, callback } = toRefs(props);

        const handleClick = () => {
            callback.value(data.value);
        };

        return {
            handleClick
        };
    }
});
</script>