<template>
    <div class="audio-recorder flex items-center space-x-4">
        <button @click="startRecording" v-show="!isRecording">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic">
                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                <line x1="12" y1="19" x2="12" y2="23"></line>
                <line x1="8" y1="23" x2="16" y2="23"></line>
            </svg>
        </button>
        <button @click="stopRecording" v-show="isRecording">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                <!-- Apply medium red fill and a subtle drop shadow -->
                <circle cx="12" cy="12" r="10" fill="rgba(255, 0, 0, 0.6)" class="shadow-md transition-transform duration-300 hover:scale-110"></circle>
            </svg>

        </button>
        <audio v-if="audioURL" :src="audioURL" controls class="ml-2 max-w-xs"></audio>
        <button @click="uploadAudio" v-show="audioBlob" class="bg-white text-white px-4 py-2 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="#a0d790" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </button>
    </div>
</template>

  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';

  const emits = defineEmits(['startRecording','stopRecording','uploadAudio'])
  
  const isRecording = ref(false);
  const audioBlob = ref(null);
  const audioURL = ref(null);
  let mediaRecorder;
  let audioChunks = [];
  
  const startRecording = () => {
    navigator.mediaDevices.getUserMedia({ audio: true })
      .then((stream) => {
        mediaRecorder = new MediaRecorder(stream);
        mediaRecorder.start();
        isRecording.value = true;
        
        mediaRecorder.ondataavailable = (event) => {
          audioChunks.push(event.data);
        };
        emits('startRecording');
        mediaRecorder.onstop = () => {
          audioBlob.value = new Blob(audioChunks, { type: 'audio/wav' });
          audioURL.value = URL.createObjectURL(audioBlob.value);
          audioChunks = [];  // Reset for next recording
          isRecording.value = false;
        };
      })
      .catch((error) => {
        console.error('Error accessing microphone:', error);
      });
  };
  
  const stopRecording = () => {
    if (mediaRecorder) {
        emits('stopRecording');
      mediaRecorder.stop();
    }
  };
  
  const uploadAudio = () => {
    let audio = {
        recording:audioBlob.value
    };
    setTimeout(() => {
        audioURL.value = null;
        audioBlob.value = null;
    }, 100);
    emits('uploadAudio',audio);
    
  };
  </script>
  
  <style scoped>
  /* Add your custom styles here */
  </style>
  