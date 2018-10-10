<template>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          SignUp
        </div>
        <div class="card-body">
          <b-alert variant="danger" v-if="error" show>{{error}}</b-alert>

          <b-form @submit="confirm">

            <b-form-group label="Email address" label-for="confirmEmail">
              <b-form-input
                  id="confirmEmail"
                  type="email"
                  v-model="form.email"
                  aria-describedby="confirmEmailError"
                  :state="errors.email ? false : null"
                  required>
              </b-form-input>
              <b-form-invalid-feedback id="confirmEmailError">{{ errors.email }}</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Token" label-for="confirmToken">
              <b-form-input
                  id="confirmToken"
                  type="number"
                  v-model="form.token"
                  aria-describedby="confirmTokenError"
                  :state="errors.token ? false : null"
                  required>
              </b-form-input>
              <b-form-invalid-feedback id="confirmTokenError">{{ errors.token }}</b-form-invalid-feedback>
            </b-form-group>

            <b-button type="submit" variant="primary">Confirm</b-button>

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
          email: this.$store.state.currentEmail,
          token: '',
        },
        error: null,
        errors: []
      }
    },
    methods: {
      confirm(event) {
        event.preventDefault();
        this.error = null;
        this.errors = [];
        axios
          .post('/auth/signup/confirm', this.form)
          .then(() => {
            this.$store.commit('changeCurrentEmail', this.form.email);
            this.$router.push({name: 'login'});
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
