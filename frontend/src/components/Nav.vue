<template>
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="#navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <router-link class="navbar-brand" :to="{name: 'home'}">
          <img src="../assets/logo.png" alt="App" height="28" />
        </router-link>
      </div>
      <!-- Links -->
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><router-link class="nav-link" :to="{name: 'home'}">Home</router-link></li>
          <template v-if="$store.getters.isLoggedIn">
            <li class="nav-item"><router-link class="nav-link" :to="{name: 'profile'}">Profile</router-link></li>
            <li class="nav-item"><router-link class="nav-link" :to="{name: 'author'}">Author</router-link></li>
            <li class="nav-item"><a class="nav-link" @click="logout" href="">Log Out</a></li>
          </template>
          <template v-else>
            <li class="nav-item"><router-link class="nav-link" :to="{name: 'login'}">Log In</router-link></li>
            <li class="nav-item"><router-link class="nav-link" :to="{name: 'signup'}">Sign Up</router-link></li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
  export default {
    name: 'Nav',

    methods: {
      logout(event) {
        event.preventDefault();
        this.$store.dispatch('logout')
          .then(() => {
            this.$router.push({name: 'login'});
          })
      }
    }
  }
</script>