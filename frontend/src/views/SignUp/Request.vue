<template>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          SignUp
        </div>
        <div class="card-body">
          <b-alert variant="danger" v-if="error" show>{{error}}</b-alert>

          <b-form @submit="signup">

            <b-form-group label="Email address" label-for="signupEmail">
              <b-form-input
                  id="signupEmail"
                  type="email"
                  v-model="form.email"
                  aria-describedby="signupEmailError"
                  :state="errors.email ? false : null"
                  required>
              </b-form-input>
              <b-form-invalid-feedback id="signupEmailError">{{ errors.email }}</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Password" label-for="signupPassword">
              <b-form-input
                  id="signupPassword"
                  type="password"
                  v-model="form.password"
                  aria-describedby="signupPasswordError"
                  :state="errors.password ? false : null"
                  required>
              </b-form-input>
              <b-form-invalid-feedback id="signupPasswordError">{{ errors.password }}</b-form-invalid-feedback>
            </b-form-group>

            <b-button type="submit" variant="primary">Sign Up</b-button>

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
          email: '',
          password: '',
        },
        error: null,
        errors: []
      }
    },
    methods: {
      signup(event) {
        event.preventDefault();
        this.error = null;
        this.errors = [];
        axios
          .post('/auth/signup', this.form)
          .then(() => {
            this.$store.commit('changeCurrentEmail', this.form.email);
            this.$router.push({name: 'signup.confirm'});
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
