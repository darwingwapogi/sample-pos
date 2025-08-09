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
                <div class="body">
                    <slot name="modalBody"></slot>
                </div>
            </div>
        </div>
    </VueFinalModal>
</template>
<script setup>
    import { VueFinalModal } from 'vue-final-modal';

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
    min-width: 800px;
}
.confirm-modal-content .header {
    width: 100%;
    background: #fff;
    border-radius: 0.5rem;
}
.confirm-modal-content .body {
    width: 100%;
    overflow-y: auto;
    max-height: 500px;
}
.confirm-modal-content > * + *{
    margin: 0.5rem 0;
}
.confirm-modal-content h1 {
    font-size: 1.375rem;
}
</style>