<template>
  <div class="profile">
    <div class="card mb-3">
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

    <div v-if="author">
      <router-link class="btn btn-success" :to="{name: 'author.upload'}">Upload Video</router-link>

      <div v-if="videos.length">
        <div class="my-3">
          <div class="row">
            <div class="col-md-4" v-for="video in videos">
              <div class="card mb-3">
                <router-link :to="{name: 'author.video', params: {id: video.id}}">
                  <img class="card-img-top" :src="video.thumbnail.url" alt="">
                </router-link>
                <div class="card-body">
                  <p class="card-text">
                    <router-link :to="{name: 'author.video', params: {id: video.id}}">{{ video.name }}</router-link>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from "axios";

  export default {
    data() {
      return {
        author: null,
        count: null,
        videos: []
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
            axios
              .get('/author/videos')
              .then(response => {
                  this.count = response.data.count;
                  this.videos = response.data.data;
              })
          }
        })
    }
  }
</script>
