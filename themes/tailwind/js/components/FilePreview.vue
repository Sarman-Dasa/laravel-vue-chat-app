<template>
    <fwb-modal v-if="showModal && files?.length" @close="closeModal">
        <template #header>
            <div class="flex items-center text-lg">Selected Image</div>
        </template>
        <template #body>
            <div class="grid grid-cols-3 gap-4">
                <img
                    v-for="(file, index) in files"
                    :key="index"
                    :src="getPreviewUrl(file)"
                    alt="Image preview"
                    class="w-full h-32 object-cover rounded-lg"
                />
            </div>
        </template>
        <template #footer>
            <div class="flex justify-between">
                <fwb-button @click="closeModal" color="alternative">
                    Cancel
                </fwb-button>
                <fwb-button @click="emits('addMoreImage')" color="alternative">
                    Add new File
                </fwb-button>
                <fwb-button @click="emits('send')" color="green">
                    Update
                </fwb-button>
            </div>
        </template>
    </fwb-modal>
</template>

<script setup>
import { FwbButton, FwbModal, FwbTextarea } from "flowbite-vue";

const props = defineProps({
    showModal: {
        type: Boolean,
        Required: true,
    },
    files: {
        type: Array,
        Required:true,
    }
});

const emits = defineEmits(['closeModel','send','addMoreImage']);


function getPreviewUrl(file) {
    return URL.createObjectURL(file)
}

function closeModal() {
    props.showModal = false;
    emits('closeModal');
}
</script>