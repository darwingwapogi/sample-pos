<template>
    <VueFinalModal
        class="confirm-modal"
        content-class="confirm-modal-content"
        overlay-transition="vfm-fade"
        content-transition="vfm-fade"
        teleport-to="body"
    >
        <div class="confirm-modal">
            <div class="confirm-modal-content">
                <div class="header flex-column">
                    <span class="header-text">{{modalTitle}}</span>
                    <span class="modal-icon float-end"><i :class="icon"></i></span>
                </div>
                <hr>
                <div class="body">
                    <slot name="modalBody"/>
                </div>
                <hr>
                <div class="flex-column">
                    <div v-if="type === 'warning'" class="float-end">
                        <button class="btn-cancel" @click="$emit('cancel')">
                            Cancel
                        </button>
                        <button class="btn-confirm ml-1" @click="$emit('confirm')">
                            Confirm
                        </button>
                    </div>
                    <div v-else class="float-end">
                        <button class="btn-confirm" @click="$emit('ok')">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </VueFinalModal>
</template>
<script setup>
    import { computed, toRefs } from 'vue';
    import { VueFinalModal } from 'vue-final-modal';

    const props = defineProps({
        modalTitle: String,
        type: {
            type: String,
            default: ''
        }
    });
    
    defineEmits(['confirm', 'cancel', 'ok']);

    const { type } = toRefs(props);

    const icon = computed(() => {
        let cssClass = '';

        if (type.value === 'success') {
            cssClass = 'bx bx-check-circle text-success';
        } else if (type.value === 'error') {
            cssClass = 'bx bx-error text-danger';
        } else if (type.value === 'warning') {
            cssClass = 'bx bx-error text-warning';
        } else if (type.value === 'info') {
            cssClass = 'bx bx-info-circle';
        }

        return cssClass;
    });

</script>
<style scoped>
.confirm-modal {
    display: flex;
    justify-content: center;
    align-items: center;
}
.confirm-modal-content {
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 0.5rem;
    margin-top: 100px;
    border: solid black 1px;
    min-width: 600px;
    padding: 10px;
}
.confirm-modal-content .header {
    width: 100%;
    background: #fff;
    border-radius: 0.5rem;
}
.confirm-modal-content .body {
    width: 100%;
    padding-left: 5px;
    padding-right: 5px;
}
.confirm-modal-content > * + *{
    margin: 0.5rem 0;
}
.confirm-modal-content h1 {
    font-size: 1.375rem;
}
.confirm-modal-content button.btn-confirm {
    margin: 0.25rem 0 0 auto;
    padding: 0 8px;
    border: 1px solid;
    border-radius: 0.5rem;
    background-color: green;
    color: white;
    min-width: 70px;
    height: 40px;
}
.confirm-modal-content button.btn-confirm:hover {
    background-color: lightseagreen;
    color: black;
}
.confirm-modal-content button.btn-cancel {
    margin: 0.25rem 0 0 auto;
    padding: 0 8px;
    border: 1px solid;
    border-radius: 0.5rem;
    background-color: rgb(225, 225, 225);
    color: rgb(0, 0, 0);
    min-width: 70px;
    height: 40px;
}
.confirm-modal-content button.btn-cancel:hover {
    background-color: lightcoral;
    color: black;
}
span.header-text {
    font-weight: bold;
    font-size: 1.5rem;
}
span.modal-icon {
    font-size: 1.5rem;
    color: red;
}
</style>