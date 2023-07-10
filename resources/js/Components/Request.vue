<template>
    <div class="my-2 shadow text-white bg-dark p-1" :id="mode">
      <template v-if="mode === 'sent'">
        <div v-for="sendRequest in sendRequests" :key="sendRequest.id" class="flex justify-between">
          <table class="ms-1">
            <td class="items-center">{{ sendRequest.receiver[0].name }}</td>
            <td class="items-center"> - </td>
            <td class="items-center">{{ sendRequest.receiver[0].email }}</td>
            <td class="items-center"></td>
          </table>
          <div>
            <button @click="withdrawRequest(sendRequest.id)" id="cancel_request_btn_sent"
            class="bg-red-500 text-white hover:bg-red-600 focus:bg-red-600 px-4 py-2 rounded-md mr-1">Withdraw Request</button>
          </div>
        </div>
      </template>

      <template v-else>
        <div v-for="receivedRequest in receivedRequests" :key="receivedRequest.id" class="flex justify-between">
          <table class="ms-1">
            <td class="items-center">{{ receivedRequest.sender[0].name }}</td>
            <td class="items-center"> - </td>
            <td class="items-center">{{ receivedRequest.sender[0].email }}</td>
            <td class="items-center"></td>
          </table>
          <div>
            <button @click="acceptConnection(receivedRequest.id)" id="accept_request_btn_" class="bg-blue-500 text-white hover:bg-blue-600 focus:bg-blue-600 px-4 py-2 rounded-md mr-1">Accept</button>
          </div>
        </div>
      </template>
    </div>
  </template>

  <script>
  import { router } from '@inertiajs/vue3'
  export default {
    props: {
      mode: {
        type: String,
        required: true
      },
      sendRequests: {
        type: Array,
        default: () => []
      },
      receivedRequests: {
        type: Array,
        default: () => []
      }
    },
    methods: {
        withdrawRequest(id, type) {
            axios.delete(`/requests/${id}`)
            .then(res => {
                router.visit('/dashboard/sent');
            })
            .catch(error => {
                // Handle error
            });
            this.$emit('withdraw-request', id, type);
        },
      acceptConnection(id) {
        // Handle accept connection logic
        // Call the appropriate method or emit an event
        this.$emit('accept-connection', id);
      }
    }
  };
  </script>

  <style scoped>
 .justify-content-between {
    justify-content: space-between !important;
    margin: 7px !important;
}

  </style>
