<template>
  <p v-if="name">
    Using <span class="api-name">{{ name }}</span> version {{ version }}
  </p>
  <p v-else>
    Loading...
  </p>
</template>

<script>
  import axios from "axios";

  export default {
    name: 'ApiStatus',
    data() {
      return {
        name: null,
        version: null
      }
    },
    mounted() {
      axios.get('/').then(response => {
        this.name = response.data.name;
        this.version = response.data.version;
      });
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  .api-name {
    font-weight: bold;
  }
</style>
