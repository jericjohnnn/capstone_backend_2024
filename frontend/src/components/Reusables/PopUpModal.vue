<template>
  <TransitionRoot as="template" :show="open">
    <Dialog class="relative z-50" @close="handleClose">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div
          class="fixed  inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        />
      </TransitionChild>

      <div class="fixed inset-0 z-30 w-screen overflow-y-auto">
        <div
          class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
        >
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel
              class=" relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-1 sm:w-full sm:max-w-lg"
            >
              <div class=" bg-white px-4 pb-4  sm:p-6 sm:pb-2">
                <div class=" justify-center sm:flex sm:items-start">
                  <div class=" mt-2 text-center grow sm:mt-0 sm:text-left">
                    <DialogTitle
                      as="h3"
                      class=" text-base text-center font-semibold text-gray-900"
                      ><slot class="" name="modalTitle"></slot></DialogTitle
                    >
                    <div class="mt-2 ">
                      <slot class="" name="mainContent"></slot>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
              >
                <button
                  type="button"
                  @click="emitMainButton"
                  class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto"
                >
                <slot name="mainButton"></slot>
                </button>
                <button
                  type="button"
                  @click="emitCancelButton"
                  class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                  ref="cancelButtonRef"
                >
                <slot name="cancelButton"></slot>
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, watch } from 'vue'
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'


const open = ref(false)

const emit = defineEmits(['openValue', 'cancelButtonValue', 'mainButtonValue'])

const props = defineProps({
  toggleModal: {
    type: Boolean,
    required: true,
  }
})

const handleClose = () => {
  open.value = false
  emit('openValue', false)
}

const emitMainButton = () => {
  console.log("main")
  emit('mainButtonValue')
}

const emitCancelButton = () => {
  console.log("cancel")
  emit('cancelButtonValue')
}

watch(
  () => props.toggleModal,
  (newVal) => {
    open.value = newVal
    if (!newVal) {
      emit('openValue', false)
    }
  }
)
</script>
<style>
button:disabled {
  background-color: #d3d3d3;
  color: #888888;
  cursor: not-allowed;
  opacity: 0.6;
  pointer-events: none;
}

button:disabled:hover {
  background-color: #d3d3d3;
  color: #888888;
}
</style>
