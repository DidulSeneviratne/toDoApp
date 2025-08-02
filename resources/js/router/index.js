import {createRouter, createWebHistory} from 'vue-router';
import TaskList from '../components/TaskList.vue';

export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: TaskList}
    ],
});