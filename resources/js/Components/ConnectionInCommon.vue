
<template>
    <div v-for="common in connection" :key="common.id" class="p-2 shadow rounded mt-2 text-white bg-dark">
      {{ common.name }} - {{ common.email }}
    </div>
</template>

<script>
export default {
  props: {
    connection: {
      type: Array,
      required: true
    }
  },
  mounted(){
  }
};
</script>
