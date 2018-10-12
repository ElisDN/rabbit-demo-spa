<template>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          Author
        </div>
        <div class="card-body">
          <b-alert variant="danger" v-if="error" show>{{error}}</b-alert>

          <b-form @submit="create">

            <b-form-group label="Name" label-for="createName">
              <b-form-input
                  id="createName"
                  type="text"
                  v-model="form.name"
                  aria-describedby="createNameError"
                  :state="errors.name ? false : null"
                  required>
              </b-form-input>
              <b-form-invalid-feedback id="createNameError">{{ errors.name }}</b-form-invalid-feedback>
            </b-form-group>

            <b-button type="submit" variant="primary">Save</b-button>

          </b-form>
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
          name: null,
        },
        error: null,
        errors: []
      }
    },
    methods: {
      create(event) {
        event.preventDefault();
        this.error = null;
        this.errors = [];
        axios
          .post('/author/create', this.form)
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
