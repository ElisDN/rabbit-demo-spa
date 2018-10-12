<template>
  <div class="profile">
    <div class="card">
      <div class="card-header">
        Author
      </div>
      <table class="table table-stripped mb-0" v-if="author">
        <tbody>
        <tr>
          <th>Name</th>
          <td>{{ author.name }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  import axios from "axios";

  export default {
    data() {
      return {
        author: null,
      }
    },
    mounted() {
      axios
        .get('/author')
        .then(response => {
          if (response.status === 204) {
            this.$router.push({name: 'author.create'});
          } else {
            this.author = response.data;
          }
        })
    }
  }
</script>
