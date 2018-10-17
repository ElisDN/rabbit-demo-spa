<template>
  <div>
    <b-alert
        show dismissible
        variant="primary"
        v-for="(notification, index) in notifications"
        @dismissed="close(index)"
    >
      {{ notification }}
    </b-alert>
  </div>
</template>

<script>
  import axios from "axios";

  export default {
    name: 'Notifications',
    computed: {
      notifications() {
        return this.$store.state.notifications;
      }
    },
    methods: {
      close(index) {
        this.$store.commit('removeNotification', index);
      }
    }
  }
</script>