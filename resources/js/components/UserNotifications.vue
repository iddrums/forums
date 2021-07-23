<template>
        <ul>
            <li class="nav-item dropdown" v-if="notifications.length">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <span class="glyphicon glyphicon-bell"></span>
                    </a>
            </li>
                <li v-for="notification in notifications" :key="notification">
                    <a :href="notification.data.link"
                        v-text="notification.data.message"
                        @click="markAsRead(notification)"></a>
                </li>
         </ul>
</template>

<script>
export default {
    // name: 'user-notifications',

    // components: {
    //   user-notifications
    // },
    data() {
        return { notifications: false }
    },

    created() {
       axios.get("/profiles/" + window.App.user.name + "/notifications")
        .then(response => this.notifications = response.data);
    },

    methods: {
        // /profiles/{$user->name}/notifications/{$notificationId}"
        markAsRead(notification) {
            axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id)
        }
    }
}
</script>
