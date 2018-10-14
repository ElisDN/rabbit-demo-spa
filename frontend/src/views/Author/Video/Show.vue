<template>
  <div class="video">
    <div v-if="video">
      <h1>{{ video.name }}</h1>

      <video controls class="mb-3" :height="height" v-if="files">
        <source :src="file.url" :type="'video/' + file.format" v-for="file in files">
      </video>

      <b-form-group label="Size:">
        <b-button-group>
          <b-button
              v-for="current in heights"
              :variant="'outline-dark' + (current === height ? ' active' : '')"
              @click="height = current"
          >{{ current }}</b-button>
        </b-button-group>
      </b-form-group>
    </div>
  </div>
</template>

<script>
  import axios from "axios";

  export default {
    data() {
      return {
        video: null,
        height: null
      }
    },
    computed: {
      files() {
          return this.video.files.filter(file => file.size.height === this.height);
      },
      heights() {
          let heights = this.video.files.map(file => file.size.height);
          return [...new Set(heights)];
      }
    },
    mounted() {
      axios
        .get('/author/videos/' + this.$route.params['id'])
        .then(response => {
            this.height = response.data.files[0].size.height;
            this.video = response.data;
        })
    }
  }
</script>
