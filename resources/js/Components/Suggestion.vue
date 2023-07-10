<template>
    <div class="my-2 shadow text-white bg-dark p-1" id="suggestions">
      <div v-for="suggestion in suggestions" :key="suggestion.id" class="flex justify-between">
        <table class="ms-1">
          <td class="items-center">{{ suggestion.name }}</td>
          <td class="items-center"> - </td>
          <td class="items-center">{{ suggestion.email }}</td>
          <td class="items-center"></td>
        </table>
        <div>
          <button @click="handleAction(suggestion.id)" id="create_request_btn_" class="bg-blue-500 text-white hover:bg-blue-600 focus:bg-blue-600 px-4 py-2 rounded-md mr-1">Connect</button>
        </div>
      </div>
    </div>
  </template>

  <script>
  import { router } from '@inertiajs/vue3'
  export default {
    props: {
      suggestions: {
        type: Array,
        default: () => []
      }
    },
    methods: {
      handleAction(id) {
        var bodyFormData = new FormData();
        bodyFormData.append('id', id);
        var url = `/requests`
        axios({
            method: "POST",
            url: url,
            data: bodyFormData,
            headers: { "Content-Type": "multipart/form-data" },
            })
            .then(function (response) {
                router.visit('/dashboard/suggestions');
                //handle success
                console.log(response);
            })
            .catch(function (response) {
                //handle error
                console.log(response);
            });
        // Handle the connect action
        // Call the appropriate method or emit an event
        this.$emit('connect', id);
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
