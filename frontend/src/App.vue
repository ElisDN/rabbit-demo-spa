<template>
  <div id="app">
    <header class="app-header">
      <Nav/>
    </header>

    <div class="app-content">
      <main class="container">
        <Notifications/>
        <router-view/>
      </main>
    </div>

    <footer class="app-footer">
      <div class="container">
        <hr />
        <p>&copy; App. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script>
  import Nav from '@/components/Nav.vue'
  import Notifications from '@/components/Notifications.vue'

  export default {
    components: {
      Nav,
      Notifications
    }
  }
</script>

<style lang="scss">
  @import "assets/scss/variables";
  @import "~bootstrap/scss/bootstrap";
  @import "~bootstrap-vue/dist/bootstrap-vue.css";
  @import "~@fortawesome/fontawesome-free/css/all.css";

  body {
    min-height: 100vh;
  }

  #app {
    min-height: 100vh;
    display: flex;
    flex-direction: column
  }

  .app-content {
    flex: 1;
    padding: 1rem 0;
  }
</style>
