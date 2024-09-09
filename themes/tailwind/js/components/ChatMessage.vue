<template>
    <div>
        <div class="flex flex-col justify-end h-80">
            <div class="flex items-center justify-between p-2">
                <div class="flex items-center">
                    <h1 class="text-lg font-semibold mr-2">{{ user.name }}</h1>
                    <span
                        :class="isUserOnline ? 'bg-green-500' : 'bg-gray-400'"
                        class="inline-block h-2 w-2 rounded-full"
                    ></span>
                </div>
                <SendSheduleMessage :user="user" />
            </div>

            <div
                ref="messagesContainer"
                class="p-4 overflow-y-auto max-h-fit custom-scrollbar"
            >
                <div
                    v-for="item in messageList"
                    :key="item.id"
                    class="flex items-center mb-2"
                >
                    <div
                        v-if="item.sender_id === currentUser.id"
                        class="relative ml-auto p-2 rounded-lg message"
                        :class="{
                            'pt-4': item.is_edited,
                            'bg-gray-800': editingMessageId !== item.id,
                        }"
                    >
                        <div v-if="editingMessageId !== item.id">
                            <span class="text-white">{{ item.message }}</span>
                            <span
                                v-if="item.is_edited"
                                class="absolute bottom-2 right-2 edited-message"
                                >Edited</span
                            >
                            <button
                                v-if="isEditableMessage(item.created_at)"
                                @click="editMessage(item.id, item.message)"
                                class="right-0 top-0 mt-2 ml-1 text-gray-500 hover:text-gray-800 editBtn"
                            >
                                <svg
                                    fill="#FFFFFF"
                                    version="1.1"
                                    id="Capa_1"
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="10px"
                                    height="10px"
                                    viewBox="0 0 528.899 528.899"
                                    xml:space="preserve"
                                >
                                    <g>
                                        <path
                                            d="M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981
                                        c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611
                                        C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069
                                        L27.473,390.597L0.3,512.69z"
                                        />
                                    </g>
                                </svg>
                            </button>
                            <button
                                class="right-0 top-0 ml-1 editBtn"
                                @click="deleteMessage(item.id)"
                            >
                                <svg
                                    fill="#FA5252"
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 16 16"
                                    width="16px"
                                    height="16px"
                                >
                                    <path
                                        d="M2,3v1h1v8.5C3,13.327,3.673,14,4.5,14h6c0.827,0,1.5-0.673,1.5-1.5V4h1V3H2z M6,12H5V5h1V12z M8,12H7V5h1V12z M10,12H9V5h1V12z"
                                    />
                                    <path
                                        style="
                                            fill: none;
                                            stroke: #000000;
                                            stroke-linecap: square;
                                            stroke-miterlimit: 10;
                                        "
                                        d="M9.5,3.5V2.497C9.5,1.946,9.054,1.5,8.503,1.5H6.497C5.946,1.5,5.5,1.946,5.5,2.497V3.5"
                                    />
                                </svg>
                            </button>
                        </div>
                        <input
                            v-if="editingMessageId === item.id"
                            type="text"
                            v-model="updatedMessage"
                            @keyup.enter="updateMessage"
                            @blur="cancelEdit"
                            class="flex-1 px-2 py-1 border rounded-lg text-dark"
                        />
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
        </div>
        <small v-if="isTyping" class="text-gray-700">
            {{ user.name }} is typing...
        </small>
    </div>
</template>

<script setup>
import axios from "axios";
import { nextTick, onMounted, ref, watch } from "vue";
import SendSheduleMessage from "./SendSheduleMessage.vue";
import { messaging, getToken } from "../firebase";
import { initFlowbite } from "flowbite";
const props = defineProps({
    user: {
        type: Object,
        Required: true,
    },
    currentUser: {
        type: Object,
        Required: true,
    },
});

const messageList = ref([]);
const message = ref("");
const isTyping = ref(false);
const messagesContainer = ref(null);
const isTypingTimer = ref(null);
const isUserOnline = ref(false);
const editingMessageId = ref(null);
const updatedMessage = ref("");

const sendTypingEvent = () => {
    Echo.private(`chat.${props.user.id}`).whisper("typing", {
        userID: props.currentUser.id,
    });
};

watch(
    messageList,
    () => {
        nextTick(() => {
            messagesContainer.value.scrollTo({
                top: messagesContainer.value.scrollHeight,
                behavior: "smooth",
            });
        });
    },
    { deep: true }
);

onMounted(() => {
    initFlowbite();
    axios.get(`/messages/${props.user.id}`).then((response) => {
        messageList.value = response.data;
    });

    if (Notification.permission !== 'granted' || props.currentUser?.device_token === null) {
        getdeviceToken();
    }

    Echo.private(`chat.${props.currentUser.id}`)
        .listen(".MessageEvent", ({ message, type }) => {
            switch (type) {
                case "sent":
                    // Listen when new message
                    messageList.value.push(message);
                    break;

                case "updated":
                    // Listen when updated message
                    const updatedIndex = messageList.value.findIndex(
                        (msg) => msg.id === message.id
                    );
                    if (updatedIndex !== -1) {
                        messageList.value[updatedIndex] = message;
                    }
                    break;

                case "deleted":
                    // Listen when deleted message
                    const deletedIndex = messageList.value.findIndex(
                        (msg) => msg.id === message.id
                    );
                    if (deletedIndex !== -1) {
                        messageList.value.splice(deletedIndex, 1);
                    }
                    break;

                default:
                    break;
            }
        })
        .listenForWhisper("typing", (response) => {
            isTyping.value = response.userID === props.user.id;

            if (isTypingTimer.value) {
                clearTimeout(isTypingTimer.value);
            }

            isTypingTimer.value = setTimeout(() => {
                isTyping.value = false;
            }, 1000);
        });

    Echo.join(`presence.chat`)
        .here((users) => {
            isUserOnline.value = users.some(
                (user) => user.id === props.user.id
            );
        })
        .joining((user) => {
            if (user.id === props.user.id) isUserOnline.value = true;
        })
        .leaving((user) => {
            if (user.id === props.user.id) isUserOnline.value = false;
        });
});

function sendMessage() {
    let input = {
        user_id: props.user.id,
        message: message.value.trim(),
    };
    axios
        .post(`/sendMessage`, input)
        .then((result) => {
            message.value = "";
            messageList.value.push(result.data.message);
        })
        .catch((err) => {
            console.log("err: ", err.response.data.message);
        });
}

function editMessage(messageId, currentMessage) {
    editingMessageId.value = messageId;
    updatedMessage.value = currentMessage;
}

function cancelEdit() {
    editingMessageId.value = null;
    updatedMessage.value = "";
}

function updateMessage() {
    if (editingMessageId.value) {
        const messageToUpdate = messageList.value.find(
            (item) => item.id === editingMessageId.value
        );
        if (messageToUpdate) {
            axios
                .put(`/updateMessage/${editingMessageId.value}`, {
                    message: updatedMessage.value,
                })
                .then((response) => {
                    messageToUpdate.message = response.data.message.message;
                    messageToUpdate.is_edited = true;
                    cancelEdit();
                })
                .catch((error) => {
                    console.error("Error updating message:", error);
                });
        }
    }
}

const isEditableMessage = (sentAt) => {
    const FIVE_MINUTES = 5 * 60 * 1000; // 5 minutes in milliseconds
    const now = new Date().getTime();
    return now - new Date(sentAt).getTime() < FIVE_MINUTES;
};

function deleteMessage(id) {
    const index = messageList.value.findIndex((item) => item.id === id);
    if (index !== -1) {
        axios
            .delete(`/deleteMessage/${id}`)
            .then((response) => {
                messageList.value.splice(index, 1);
            })
            .catch((error) => {
                console.error("Error updating message:", error);
            });
    }
}

async function getdeviceToken() {
    const permission = await Notification.requestPermission();
    if (permission === "granted") {
        console.log("Notification permission granted.");

        // Get FCM token
        const vapidKey = import.meta.env.VITE_VAPID_KEY;
        // const messaging = getMessaging();
        getToken(messaging, { vapidKey: vapidKey })
            .then((currentToken) => {
                if (currentToken) {
                    console.log("currentToken: ", currentToken);
                    axios
                        .post("/saveToken", { token: currentToken })
                        .then((result) => {
                            console.log("Token saved successfully.", result);
                        })
                        .catch((err) => {
                            console.log("No registration token available.");
                        });
                } else {
                    // Show permission request UI
                    console.log(
                        "No registration token available. Request permission to generate one."
                    );
                }
            })
            .catch((err) => {
                console.log("An error occurred while retrieving token. ", err);
            });
    }
}
</script>
<style>
.message {
    position: relative;
    overflow: hidden;
}

.message .editBtn {
    opacity: 0;
    display: none;
    transition: opacity 0.3s ease-in-out;
}

.message:hover .editBtn {
    opacity: 1;
    display: inline-block;
}

.message .edited-message {
    position: absolute;
    bottom: 25px;
    right: 0.5rem;
    color: gray;
    font-size: 12px;
}

/* Custom scrollbar for the div with the class 'custom-scrollbar' */
.custom-scrollbar::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 10px;
    border: 2px solid #f1f1f1;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
