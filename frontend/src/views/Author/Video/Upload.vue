<template>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          Video
        </div>
        <div class="card-body">
          <b-alert variant="danger" v-if="error" show>{{error}}</b-alert>

          <div class="mb-3">
            <b-form @submit="upload">
              <b-form-group label="File" label-for="uploadFile">
                <b-form-file
                    id="uploadFile"
                    v-model="form.file"
                    aria-describedby="uploadFileError"
                    :state="errors.file ? false : null"
                    required>
                </b-form-file>
                <b-form-invalid-feedback id="uploadFileError">{{ errors.name }}</b-form-invalid-feedback>
              </b-form-group>
              <b-button type="submit" variant="primary">Upload</b-button>
            </b-form>
          </div>

          <div v-if="progress">
            <b-progress :value="progress" :max="100" show-progress animated></b-progress>
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
        form: {
          file: null,
        },
        progress: 0,
        error: null,
        errors: []
      }
    },
    methods: {
      upload: function (event) {
        event.preventDefault();
        this.error = null;
        this.errors = [];
        this.progress = 0;
        const component = this;

        let data = new FormData();
        data.append('file', this.form.file);

        axios
          .post('/author/videos/create', data, {
            onUploadProgress: function (event) {
              component.progress = Math.round((event.loaded * 100) / event.total);
            }
          })
          .then(() => {
            this.$router.push({name: 'author'});
          })
          .catch(error => {
            if (error.response) {
              if (error.response.data.error) {
                this.error = error.response.data.error;
              } else if (error.response.data.errors) {
                this.errors = error.response.data.errors;
              }
            } else {
              console.log(error.message);
            }
          });
      }
    }
  }
</script>
