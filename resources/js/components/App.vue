<template>
    <v-app>
        <div>
            <v-app-bar
                color="deep-purple accent-4"
                dark
            >
                <v-toolbar-title>Courses</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-app-bar>
        </div>
        <v-container class="mt-8">
            <add-lesson-form></add-lesson-form>
            <div class="mt-8">
                <lessons-table></lessons-table>
            </div>
        </v-container>
        <v-snackbar
            v-model="notification.visible"
            :timeout="2000"
        >
            {{ notification.text }}

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="blue"
                    text
                    v-bind="attrs"
                    @click="notification.visible = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>

<script>
export default {
    data: () => ({
        notification: {
            visible: false,
            text: '',
        }
    }),

    mounted() {
        this.$root.$on('show-notification', this.onGetNotification.bind(this));
    },

    methods: {
        onGetNotification: function (text) {
            this.notification.visible = true;
            this.notification.text = text;
        }
    }
}
</script>
