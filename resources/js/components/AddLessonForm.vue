<template>
    <validation-observer
        ref="observer"
        v-slot="{ invalid }"
        lazy-validation
    >
        <v-form @submit.prevent="submit">
            <v-row>
                <v-col
                    cols="12"
                    md="4"
                >
                    <validation-provider
                        v-slot="{ errors }"
                        name="Name"
                        rules="required|max:255"
                    >
                        <v-text-field
                            v-model="lesson.name"
                            :counter="255"
                            :error-messages="errors"
                            label="Lesson name"
                            required
                            clearable
                            prepend-icon="mdi-alphabet-latin"
                        ></v-text-field>
                    </validation-provider>
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <validation-provider
                        v-slot="{ errors }"
                        name="Url"
                        rules="required|url|max:1024"
                    >
                        <v-text-field
                            v-model="lesson.url"
                            :counter="1024"
                            :error-messages="errors"
                            label="Lesson url"
                            required
                            clearable
                            prepend-icon="mdi-link"
                        ></v-text-field>
                    </validation-provider>
                </v-col>

                <v-col
                    cols="12"
                    md="3"
                >
                    <validation-provider
                        v-slot="{ errors }"
                        name="Author"
                        rules="required"
                    >
                        <v-select
                            v-model="lesson.author"
                            :items="users"
                            label="Choose author"
                            required
                            clearable
                            :error-messages="errors"
                            prepend-icon="mdi-account"
                            :loading="usersLoading"
                        ></v-select>
                    </validation-provider>
                </v-col>
                <v-col
                    cols="12"
                    md="1"
                    class="d-flex align-center"
                >
                    <v-btn
                        :loading="addLessonLoading"
                        block
                        color="primary"
                        elevation="2"
                        @click="addLesson"
                        :disabled="invalid"
                    >Add
                    </v-btn>
                </v-col>
            </v-row>
        </v-form>
    </validation-observer>
</template>

<script>
import {required, max} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider} from 'vee-validate'
import {getUsers} from "../api/user";

extend('required', {
    ...required,
    message: '{_field_} can not be empty',
})

extend('max', {
    ...max,
    message: '{_field_} may not be greater than {length} characters',
})

extend('url', (v) => {
    try {
        new URL(v);
        return true;
    } catch (_) {
        return false;
    }
});

export default {
    components: {
        ValidationProvider,
        ValidationObserver,
    },
    data: () => ({
        lesson: {
            author: null,
            name: '',
            url: '',
        },
        users: [],
        usersLoading: true,
        addLessonLoading: false,
    }),

    mounted() {
        this.loadUsers();

        this.$root.$on('lesson-added', this.lessonAdded.bind(this))
        this.$root.$on('error-on-lesson-added', this.errorOnLessonAdded.bind(this))
    },

    emits: ['add-lesson'],

    methods: {
        loadUsers: async function () {
            try {
                const users = await getUsers();
                this.users = users.map(user => ({
                    text: user.name,
                    value: user.id
                }));
                this.usersLoading = false;
            } catch (e) {
                this.usersLoading = false;
                this.showNotification(e);
            }
        },
        addLesson: async function () {
            if (await this.$refs.observer.validate()) {
                this.$root.$emit('add-lesson', this.lesson);
                this.addLessonLoading = true;
            }
        },
        lessonAdded: function () {
            this.addLessonLoading = false;
            this.resetFormFields();
            this.showNotification('Lesson successfully added');
        },
        resetFormFields: function () {
            this.$refs.observer.reset();
            this.lesson.name = '';
            this.lesson.url = '';
            this.lesson.author = null;
        },
        errorOnLessonAdded: function (e) {
            this.addLessonLoading = false;
            this.showNotification(e);
        },
        showNotification: function (text) {
            this.$root.$emit('show-notification', text);
        }
    }
}
</script>
