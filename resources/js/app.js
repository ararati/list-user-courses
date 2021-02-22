import Vue from 'vue';
import vuetify from './plugins/vuetify'

import App from './components/App';
import AddLessonForm from './components/AddLessonForm';
import LessonsTable from './components/LessonsTable';

Vue.config.performance = true;

Vue.component('app', App);
Vue.component('add-lesson-form', AddLessonForm);
Vue.component('lessons-table', LessonsTable);

new Vue({
    el: "#app",
    vuetify: vuetify
});
