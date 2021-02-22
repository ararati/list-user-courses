<template>
    <div v-on:add-event="addLesson">
        <v-data-table
            :headers="headers"
            :items="lessons"
            class="elevation-1"
            :loading="loading"
            @update:options="tableOptionsUpdated"
            :server-items-length="totalLessons"
        >
            <template v-slot:item.actions="{ item }">
                <v-icon
                    class="mr-2"
                    @click="downloadLesson(item)"
                >
                    mdi-download
                </v-icon>
                <v-icon
                    @click="showDeleteDialog(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
        </v-data-table>
        <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
                <v-card-title>Are you sure you want to delete this item?</v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" :disabled="deleteLoading" text @click="closeDeleteDialog">Cancel
                    </v-btn>
                    <v-btn color="blue darken-1" :loading="deleteLoading" text @click="deleteDialogConfirm">OK</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {deleteLesson, getLessons, createLesson} from "../api/lesson";

export default {
    data: () => ({
        headers: [
            {text: 'ID', value: 'id', sortable: false},
            {text: 'Name', value: 'name', sortable: false},
            {text: 'Author', value: 'user.name', sortable: false},
            {text: 'Action', value: 'actions', sortable: false, align: 'end', width: 100},
        ],
        lessons: [],
        totalLessons: 0,
        dialogDelete: false,
        loading: true,
        deleteLoading: false,
        selectedIndex: -1,
        selectedItem: null,
    }),

    emits: ['lesson-added'],

    mounted() {
        this.$root.$on('add-lesson', this.addLesson.bind(this));
    },

    methods: {
        loadLessons: async function (page, itemsPerPage) {
            try {
                this.loading = true;
                const {data, meta} = await getLessons(page, itemsPerPage)
                this.lessons = data;
                if (itemsPerPage > 0) {
                    this.totalLessons = meta.total;
                }
            } catch (e) {
                this.$root.$emit('error-on-lesson-added', e);
            } finally {
                this.loading = false;
            }
        },
        addLesson: async function (lesson) {
            try {
                const createdLesson = await createLesson({...lesson, user_id: lesson.author});
                await this.onLessonAdded(createdLesson);
            } catch (e) {
                this.showNotification(e);
            }
        },
        onLessonAdded: function (lesson) {
            this.lessons.push(lesson);
            this.$root.$emit('lesson-added');
        },
        downloadLesson: function () {
            this.showNotification('Download started..');
        },
        tableOptionsUpdated({page, itemsPerPage}) {
            this.loadLessons(page, itemsPerPage);
        },
        showDeleteDialog(item) {
            this.selectedIndex = this.lessons.indexOf(item);
            this.selectedItem = item;
            this.dialogDelete = true;
        },
        deleteDialogConfirm: async function () {
            try {
                this.deleteLoading = true;
                await deleteLesson(this.selectedItem.id);
                this.onItemDeleted();
            } catch (e) {
                this.showNotification(e);
            } finally {
                this.deleteLoading = false;
            }
        },
        onItemDeleted() {
            this.closeDeleteDialog();
            this.lessons.splice(this.selectedIndex, 1);
            this.deleteLoading = false;
            this.showNotification('Lesson successfully deleted');
        },
        closeDeleteDialog: function () {
            this.dialogDelete = false;
        },
        showNotification: function (text) {
            this.$root.$emit('show-notification', text);
        }
    }
}
</script>
