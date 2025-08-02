<template>
  <div>
    <h1>Todo List</h1>
    <input v-model="title" @keyup.enter="createTask" placeholder="New task" />
    <ul>
      <li v-for="task in tasks" :key="task.id">
        <input type="checkbox" v-model="task.completed" @change="updateTask(task)" />
        <input v-model="task.title" @blur="updateTask(task)" />
        <button @click="deleteTask(task.id)">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return { tasks: [], title: '' };
  },
  mounted() {
    this.fetchTasks();
  },
  methods: {
    async fetchTasks() {
      const res = await axios.get('/api/tasks');
      this.tasks = res.data;
    },
    async createTask() {
      if (!this.title.trim()) return;
      const res = await axios.post('/api/tasks', { title: this.title });
      this.tasks.push(res.data);
      this.title = '';
    },
    async updateTask(task) {
      await axios.put(`/api/tasks/${task.id}`, task);
    },
    async deleteTask(id) {
      await axios.delete(`/api/tasks/${id}`);
      this.tasks = this.tasks.filter(t => t.id !== id);
    }
  }
}
</script>
