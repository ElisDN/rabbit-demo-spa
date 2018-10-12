<template>
  <div class="profile">
    <div class="card">
      <div class="card-header">
        Profile
      </div>
      <table class="table table-stripped mb-0" v-if="profile">
        <tbody>
        <tr>
          <th>ID</th>
          <td>{{ profile.id }}</td>
        </tr>
        <tr>
          <th>Email</th>
          <td>{{ profile.email }}</td>
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
        profile: null
      }
    },
    mounted() {
      axios
        .get('/profile')
        .then(response => {
          this.profile = response.data;
        })
    }
  }
</script>
