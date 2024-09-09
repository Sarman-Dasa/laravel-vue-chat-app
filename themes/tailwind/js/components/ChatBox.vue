<template>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-1/4 bg-white border-r border-gray-300">
            <!-- Sidebar Header -->
            <header
                class="p-4 border-b border-gray-300 flex justify-between items-center bg-indigo-600 text-white"
            >
                <h1 class="text-2xl font-semibold">Chat Web</h1>
                <div class="relative"></div>
            </header>

            <!-- Contact List -->
            <div class="overflow-y-auto h-[calc(100vh-300px)] p-3 mb-9 pb-20">
                <div
                    v-for="(contact, index) in contacts"
                    :key="index"
                    class="flex items-center mb-4 cursor-pointer hover:bg-gray-100 p-2 rounded-md"
                    @click="getUserMessages(contact)"
                >
                    <div class="w-12 h-12 bg-gray-300 rounded-full mr-3">
                        <img
                            src="/user-pic.png"
                            alt="User Avatar"
                            class="w-12 h-12 rounded-full"
                        />
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold">
                            {{ contact.name }}
                        </h2>
                        <p class="text-gray-600">{{ contact.message }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="flex-1" v-if="chatUser">
            <!-- Chat Header -->
            <!-- <header class="bg-white p-4 text-gray-700"> -->
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <h1 class="text-lg font-semibold mr-2">
                        {{ chatUser.name }}
                    </h1>
                    <span
                        :class="isUserOnline ? 'bg-green-500' : 'bg-gray-400'"
                        class="inline-block h-2 w-2 rounded-full"
                    ></span>
                </div>
                <SendSheduleMessage :user="user" />
            </div>
            <!-- </header> -->

            <!-- Chat Messages -->
            <div
                class="h-[calc(100vh-400px)] overflow-y-auto p-4"
                ref="messagesContainer"
            >
                <div
                    v-for="(message, index) in messageList"
                    :key="index"
                    class="flex mb-4"
                    :class="{
                        'justify-end': message.sender_id === currentUser.id,
                    }"
                >
                    <div
                        v-if="message.sender_id !== currentUser.id"
                        class="w-9 h-9 rounded-full flex items-center justify-center mr-2"
                    >
                        <img
                            src="/user-pic.png"
                            alt="User Avatar"
                            class="w-8 h-8 rounded-full"
                        />
                    </div>

                    <div class="flex items-start gap-2.5">
                        <div
                            v-if="message.message"
                            class="flex flex-col w-full max-w-[calc(100vh-50px)] leading-1.5 p-4 border-gray-200 rounded-e-xl rounded-es-xl dark:bg-gray-700"
                            :class="
                                message.sender_id === currentUser.id
                                    ? 'bg-slate-100 text-white'
                                    : 'bg-gray-300 text-gray-700'
                            "
                        >
                            <div class="text-end space-x-2 rtl:space-x-reverse">
                                <span
                                    class="text-sm font-normal text-gray-500 dark:text-gray-400"
                                    >{{ message.timeAgo }}</span
                                >
                            </div>
                            <p
                                class="text-sm font-normal text-gray-900 dark:text-white"
                            >
                                {{ message.message }}
                            </p>
                            <span
                                v-if="message.is_edited"
                                class="text-sm font-normal text-end text-gray-500 dark:text-gray-400"
                                >Edited</span
                            >
                        </div>
                        <div
                            v-if="
                                message.attachments &&
                                message.attachments.length
                            "
                            class="block mt-2"
                        >
                            <div
                                v-for="file in message.attachments"
                                :key="file"
                                class="mt-1"
                            >
                                <div class="flex items-start gap-2.5">
                                    <div class="flex flex-col gap-1">
                                        <div
                                            class="flex flex-col w-full max-w-[326px] leading-1.5 p-4 border-gray-200 dark:bg-gray-700"
                                        >
                                            <div
                                                class="flex items-center space-x-2 rtl:space-x-reverse mb-2"
                                            >
                                                <span
                                                    class="text-sm font-normal text-gray-500 dark:text-gray-400"
                                                    >{{ file.timeAgo }}</span
                                                >
                                            </div>
                                            <!-- <p class="text-sm font-normal text-gray-900 dark:text-white">This is the new office</p> -->
                                            <div
                                                class="group relative my-2.5"
                                                v-if="!file.is_audio_file"
                                            >
                                                <div
                                                    class="absolute w-full h-full bg-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center"
                                                >
                                                    <button
                                                        data-tooltip-target="download-image"
                                                        @click="
                                                            downloadFile(
                                                                file.file_path,
                                                                file.file_name
                                                            )
                                                        "
                                                        class="inline-flex items-center justify-center rounded-full h-10 w-10 bg-white/30 hover:bg-white/50 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50"
                                                    >
                                                        <svg
                                                            class="w-5 h-5 text-white"
                                                            aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 16 18"
                                                        >
                                                            <path
                                                                stroke="currentColor"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"
                                                            />
                                                        </svg>
                                                    </button>

                                                    <!-- delete start -->
                                                    <button
                                                    v-if="message.sender_id === currentUser.id"
                                                        data-tooltip-target="download-image"
                                                        @click="
                                                            deleteFile(message.id,file.id )
                                                        "
                                                        class="inline-flex items-center justify-center rounded-full ml-2 h-10 w-10 bg-white/30 hover:bg-white/50 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50"
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
                                                    <!-- delete end -->
                                                    <div
                                                        id="download-image"
                                                        role="tooltip"
                                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                                                    >
                                                        Download image
                                                        <div
                                                            class="tooltip-arrow"
                                                            data-popper-arrow
                                                        ></div>
                                                    </div>
                                                </div>
                                                <img
                                                    :src="file.file_path"
                                                    class="rounded-lg"
                                                />
                                            </div>
                                            <div v-else>
                                                <audio
                                                    :src="file.file_path"
                                                    controls
                                                    class="ml-2 max-w-xs"
                                                ></audio>
                                            </div>
                                            <!-- <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Delivered</span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Dropdown Trigger -->
                        <button
                            v-if="
                                message.message &&
                                message.sender_id === currentUser.id
                            "
                            class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 dark:focus:ring-gray-600"
                            type="button"
                            @click="toggleDropdown(index)"
                        >
                            <svg
                                class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 4 15"
                            >
                                <path
                                    d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"
                                />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            v-if="activeDropdown === index"
                            class="z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-40 dark:bg-gray-700 dark:divide-gray-600"
                        >
                            <ul
                                class="py-2 text-sm text-gray-700 dark:text-gray-200 hover:cursor-pointer"
                                aria-labelledby="dropdownMenuIconButton"
                            >
                                <li>
                                    <span
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        v-if="
                                            isEditableMessage(
                                                message.created_at
                                            )
                                        "
                                        @click="
                                            editMessage(
                                                message.id,
                                                message.message
                                            )
                                        "
                                    >
                                        Edit
                                    </span>
                                </li>
                                <li>
                                    <span
                                        @click="copyMessage(message.message)"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >
                                        Copy
                                    </span>
                                </li>
                                <li>
                                    <span
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        @click="deleteMessage(message.id)"
                                    >
                                        Delete
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <footer
                class="bg-white border-t border-gray-300 pt-1 pl-2 bottom-0 left-0 w-25"
            >
                <div class="flex items-center">
                    <button
                        @click="showEmojiPicker = !showEmojiPicker"
                        class="mr-2"
                    >
                        😊
                    </button>
                    <input
                        type="file"
                        multiple
                        @change="onFileChange"
                        ref="fileInput"
                        class="hidden"
                        accept=".png, .jpg, .jpeg"
                    />
                    <button class="mr-2" @click="showImagePreviewModal">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="feather feather-link"
                        >
                            <path
                                d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"
                            ></path>
                            <path
                                d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"
                            ></path>
                        </svg>
                    </button>
                    <Audio
                        @startRecording="isShowMessageBox = false"
                        @uploadAudio="addAttachment"
                    />
                    <!-- Emoji Picker -->
                    <div v-if="showEmojiPicker" class="emoji-picker">
                        <emoji-picker @emoji-click="addEmoji"></emoji-picker>
                    </div>
                    <input
                        v-show="isShowMessageBox"
                        type="text"
                        v-model="message"
                        @keydown="sendTypingEvent"
                        @keyup.enter="sendMessage"
                        placeholder="Type a message..."
                        class="w-full p-2 rounded-md border border-gray-400 focus:outline-none focus:border-blue-500"
                    />
                    <button
                        v-show="isShowMessageBox"
                        @click="sendMessage"
                        class="bg-white text-white px-4 py-2 rounded-md ml-2"
                    >
                        <!-- Send -->
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="34"
                            height="34"
                            viewBox="0 0 24 24"
                            fill="#a0d790"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="feather feather-send"
                        >
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon
                                points="22 2 15 22 11 13 2 9 22 2"
                            ></polygon>
                        </svg>
                    </button>
                </div>
                <small v-if="isTyping" class="text-gray-700">
                    {{ chatUser.name }} is typing...
                </small>
            </footer>
        </div>
        <div v-else class="flex flex-1 flex-col mt-12 items-center">
            <h1 class="text-4xl font-bold mb-6">Start a new conversation</h1>
            <img
                src="/chat.png"
                alt=""
                class="h-auto max-w-lg rounded-lg object-contain"
            />
        </div>
    </div>

    <fwb-modal v-if="isShowModal" @close="closeModal">
        <template #header>
            <div class="flex items-center text-lg">Update Message</div>
        </template>
        <template #body>
            <fwb-textarea
                v-model="updatedMessage"
                :rows="4"
                label="Your message"
                placeholder="Write your message..."
            />
        </template>
        <template #footer>
            <div class="flex justify-between">
                <fwb-button @click="closeModal" color="alternative">
                    Cancel
                </fwb-button>
                <fwb-button @click="updateMessage" color="green">
                    Update
                </fwb-button>
            </div>
        </template>
    </fwb-modal>

    <FilePreview
        :showModal="isShowImagePreviewModal"
        :files="selectedFiles"
        @closeModal="closeImagePreviewModal"
        @addMoreImage="$refs.fileInput.click()"
        @send="addAttachment"
    />
</template>

<script setup>
import axios from "axios";
import { nextTick, onMounted, ref, watch } from "vue";
import SendSheduleMessage from "./SendSheduleMessage.vue";
import { messaging, getToken } from "../firebase";
import { initFlowbite } from "flowbite";
import { FwbButton, FwbModal, FwbTextarea } from "flowbite-vue";
import FilePreview from "./FilePreview.vue";
import Audio from "./Audio.vue";
import "emoji-picker-element";

const props = defineProps({
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
const activeDropdown = ref(null);
const isShowModal = ref(false);
const chatUser = ref(null);
const contacts = ref([]);
const fileInput = ref(null);
const showEmojiPicker = ref(false);
const selectedFiles = ref([]);
const isShowImagePreviewModal = ref(false);
const isShowMessageBox = ref(true);

const sendTypingEvent = () => {
    Echo.private(`chat.${chatUser.value.id}`).whisper("typing", {
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
    getUserList();

    if (
        Notification.permission !== "granted" ||
        props.currentUser?.device_token === null
    ) {
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
            isTyping.value = response.userID === chatUser.value.id;

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
                (user) => user.id === chatUser.value.id
            );
        })
        .joining((user) => {
            if (user.id === chatUser.value.id) isUserOnline.value = true;
        })
        .leaving((user) => {
            if (user.id === chatUser.value.id) isUserOnline.value = false;
        });
});

function getUserMessages(user) {
    chatUser.value = user;

    axios.get(`/messages/${user.id}`).then((response) => {
        messageList.value = response.data;
    });
}

function getUserList() {
    axios.post("/userList").then((response) => {
        contacts.value = response.data.users;
    });
}

function sendMessage() {
    let input = {
        user_id: chatUser.value.id,
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
    isShowModal.value = true;
    activeDropdown.value = null;
}

function cancelEdit() {
    editingMessageId.value = null;
    updatedMessage.value = "";
    closeModal();
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
    activeDropdown.value = null;
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
function toggleDropdown(index) {
    // Toggle dropdown for the clicked message
    if (this.activeDropdown === index) {
        activeDropdown.value = null; // Close if already open
    } else {
        activeDropdown.value = index; // Open the clicked dropdown
    }
}

function closeModal() {
    isShowModal.value = false;
    cancelEdit();
}
function showImagePreviewModal() {
    fileInput.value.click();
    isShowImagePreviewModal.value = true;
}

function addEmoji(emoji) {
    message.value += emoji.detail.unicode;
}

const onFileChange = (event) => {
    const files = Array.from(event.target.files); // Convert FileList to Array

    // Append the newly selected files to the existing ones
    selectedFiles.value.push(...files);
};

function closeImagePreviewModal() {
    selectedFiles.value = [];
    isShowImagePreviewModal.value = false;
}

function addAttachment($event) {
    isShowMessageBox.value = true;
    const formData = new FormData();
    formData.append("user_id", chatUser.value.id);
    formData.append("message", " ");
    // Append multiple files to FormData

    if (selectedFiles && selectedFiles.value.length) {
        selectedFiles.value.forEach((file, index) => {
            formData.append(`files[${index}]`, file);
        });
    }

    if ($event?.recording) {
        formData.append("audio", $event?.recording, "recording.wav");
    }

    axios
        .post("/sendMessage", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((result) => {
            closeImagePreviewModal();
            messageList.value.push(result.data.message);
        })
        .catch((err) => {
            console.log("Error: ", err);
        });
}

const downloadFile = (fileUrl, fileName) => {
    const link = document.createElement("a");
    link.href = fileUrl;
    link.setAttribute("download", fileName);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

async function copyMessage(text) {
    await navigator.clipboard.writeText(text);
    activeDropdown.value = null;
}

async function deleteFile(messageId,fileId) {
    axios
        .delete(`/deleteFile/${messageId}/${fileId}`)
        .then((response) => {
            console.log('response: ', response.data.message.attachments);
            const messageToUpdate = messageList.value.find(message => message.id === messageId);
            if (messageToUpdate) {
                messageToUpdate.attachments = response.data.message.attachments;
            }
        })
        .catch((error) => {
            console.error("Error deleteing File:", error);
        });
}
</script>

<style scoped>
.emoji-picker {
    position: absolute;
    bottom: 60px;
    right: 10px;
    z-index: 100;
    background: red;
}
</style>
