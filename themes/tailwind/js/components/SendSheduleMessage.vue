<template>
    <button
        type="button"
        class="inline-flex justify-center rounded-md bg-gray-600 px-3 py-1 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 sm:ml-3 sm:w-auto"
        @click="open = !open "
    >
        Shedule Message 
    </button>
    <TransitionRoot as="template" :show="open">
    <Dialog class="relative z-10" @close="open = false">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
              <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-white sm:mx-0 sm:h-10 sm:w-10">
                     <svg fill="#40C057" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" width="64px" height="64px"><g><path d="M31,2C15.009,2,2,15.009,2,31s13.009,29,29,29s29-13.009,29-29S46.991,2,31,2z M31,58C16.112,58,4,45.888,4,31S16.112,4,31,4s27,12.112,27,27S45.888,58,31,58z"/><path d="M31,18c0.552,0,1-0.448,1-1v-2c0-0.552-0.448-1-1-1s-1,0.448-1,1v2C30,17.552,30.448,18,31,18z"/><path d="M15,30c-0.552,0-1,0.448-1,1s0.448,1,1,1h2c0.552,0,1-0.448,1-1s-0.448-1-1-1H15z"/><path d="M48,31c0-0.552-0.448-1-1-1h-2c-0.552,0-1,0.448-1,1s0.448,1,1,1h2C47.552,32,48,31.552,48,31z"/><path d="M30,45v2c0,0.552,0.448,1,1,1s1-0.448,1-1v-2c0-0.552-0.448-1-1-1S30,44.448,30,45z"/><path d="M35.87,18.947c0.123,0.049,0.25,0.073,0.374,0.073c0.396,0,0.771-0.237,0.928-0.626l0.749-1.854c0.207-0.512-0.041-1.095-0.553-1.302c-0.512-0.206-1.095,0.041-1.302,0.553l-0.749,1.854C35.11,18.157,35.358,18.74,35.87,18.947z"/><path d="M45.46,37.921c0.123,0.049,0.25,0.073,0.374,0.073c0.396,0,0.771-0.237,0.928-0.626c0.207-0.512-0.041-1.095-0.553-1.302l-1.854-0.749c-0.512-0.206-1.095,0.042-1.302,0.553c-0.207,0.512,0.041,1.095,0.553,1.302L45.46,37.921z"/><path d="M40.006,20.234c-0.384,0.397-0.373,1.03,0.024,1.414c0.194,0.188,0.444,0.281,0.695,0.281c0.262,0,0.523-0.102,0.719-0.305l1.39-1.438c0.384-0.397,0.373-1.03-0.024-1.414c-0.397-0.384-1.03-0.374-1.414,0.024L40.006,20.234z"/><path d="M41.766,40.006c-0.398-0.384-1.03-0.374-1.414,0.024c-0.152,0.157-0.224,0.354-0.253,0.555c-0.17,0.369-0.11,0.817,0.194,1.122l1.313,1.313c0.195,0.195,0.451,0.293,0.707,0.293s0.512-0.098,0.707-0.293c0.037-0.037,0.054-0.085,0.084-0.126c0.04-0.031,0.088-0.047,0.124-0.085c0.384-0.397,0.373-1.03-0.024-1.414L41.766,40.006z"/><path d="M45.21,23.579l-1.827,0.813c-0.504,0.225-0.731,0.816-0.507,1.32c0.166,0.373,0.531,0.594,0.914,0.594c0.136,0,0.274-0.028,0.406-0.087l1.827-0.813c0.504-0.225,0.731-0.816,0.507-1.32C46.305,23.581,45.715,23.355,45.21,23.579z"/><path d="M38.5,39.5c0.256,0,0.512-0.098,0.707-0.293c0.391-0.391,0.391-1.023,0-1.414l-6.282-6.282C32.969,31.347,33,31.178,33,31c0-0.737-0.405-1.375-1-1.722V21c0-0.552-0.448-1-1-1s-1,0.448-1,1v8.278c-0.595,0.347-1,0.985-1,1.722c0,1.103,0.897,2,2,2c0.178,0,0.347-0.031,0.511-0.075l6.282,6.282C37.988,39.402,38.244,39.5,38.5,39.5z"/><path d="M45.2,12.906c-0.434-0.341-1.062-0.267-1.404,0.168c-0.341,0.434-0.266,1.062,0.168,1.404C49.071,18.492,52,24.514,52,31c0,11.58-9.42,21-21,21s-21-9.42-21-21s9.42-21,21-21c1.028,0,2.06,0.075,3.065,0.222c0.548,0.081,1.054-0.298,1.134-0.844c0.08-0.547-0.298-1.055-0.844-1.134C33.253,8.082,32.125,8,31,8C18.318,8,8,18.318,8,31s10.318,23,23,23s23-10.318,23-23C54,23.896,50.792,17.301,45.2,12.906z"/><path d="M38.238,11.281c0.783,0.287,1.561,0.627,2.311,1.011c0.146,0.074,0.301,0.109,0.454,0.109c0.364,0,0.715-0.199,0.892-0.545c0.251-0.492,0.056-1.094-0.436-1.346c-0.822-0.42-1.674-0.792-2.533-1.107c-0.517-0.189-1.093,0.077-1.283,0.595C37.454,10.516,37.72,11.09,38.238,11.281z"/></g></svg>
                  </div>
                <div class="sm:flex sm:items-start">
           
                  <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                    <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">Send Shedule Message</DialogTitle>
                    <div class="mt-2">
                        <textarea
                            class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            rows="4"
                            v-model="message"
                            placeholder="Enter your text here..."
                        ></textarea>
                        
                        <input type="datetime-local" :min="minDateTime" v-model="dateTime" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />

                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" class="inline-flex w-full justify-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 sm:ml-3 sm:w-auto" @click="sendSheduleMessage">Send</button>
                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="clearValue" ref="cancelButtonRef">Cancel</button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    user: {
        type: Object,
        Required: true,
    }
});

const open = ref(false);
const message = ref('');
const dateTime = ref();
const now = new Date();
const minDateTime = ref(now.toISOString().slice(0, 16));
console.log('minDateTime: ', minDateTime);
function sendSheduleMessage() {
    let input = {
        user_id: props.user.id,
        message: message.value.trim(),
        send_at: dateTime.value
    };
    axios
        .post(`/sendScheduleMessage`, input)
        .then((result) => {
            clearValue();
        })
        .catch((err) => {
            console.log("err: ", err.response.data.message);
            console.log("call");
        });
}

function clearValue() {
  open.value = false;
  dateTime.value = null;
  message.value = "";
}
</script>
