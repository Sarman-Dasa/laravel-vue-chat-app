<template>
    <div>
        <div class="flex flex-col justify-end h-80">
            <div ref="messagesContainer" class="p-4 overflow-y-auto max-h-fit">
                <div
                    v-for="item in messageList"
                    :key="item.id"
                    class="flex items-center mb-2"
                >
                    <div
                        v-if="item.sender_id === currentUser.id"
                        class="p-2 ml-auto text-white bg-blue-500 rounded-lg"
                    >
                        {{ item.message }}
                    </div>
                    <div v-else class="p-2 mr-auto bg-gray-200 rounded-lg">
                        {{ item.message }}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center">
            <input
                type="text"
                v-model="message"
                @keydown="sendTypingEvent"
                @keyup.enter="sendMessage"
                placeholder="Type a message..."
                class="flex-1 px-2 py-1 border rounded-lg"
            />
            <button
                @click="sendMessage"
                class="px-4 py-1 ml-2 text-white bg-blue-500 rounded-lg"
            >
                Send
            </button>
            <!-- {{ user.name }} -- -->
        </div>
        <small v-if="isFriendTyping" class="text-gray-700">
            {{ user.name }} is typing...
        </small>
    </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const props = defineProps({
    user: {
        type:Object,
        Required:true
    },
    currentUser: {
        type:Object,
        Required:true
    },
});

const messageList = ref([]);
const message = ref("");

onMounted(() => {
    axios.get(`/messages/${props.user.id}`).then((response) => {
        console.log(response.data);
        messageList.value = response.data;
    });
});


function sendMessage() {
    let input = {
        user_id: props.user.id,
        message: message.value.trim()
    }
    axios.post(`/sendMessage`,input).then((result) => {
        message.value = "";
        messageList.value.push(result.data.message);
        
    }).catch((err) => {
        console.log('err: ', err);
        
    });
}

</script>
