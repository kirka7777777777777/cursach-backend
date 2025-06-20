<template>
    <div>
    <h1>Данные из API</h1>
    <pre>{{ apiData }}</pre>
</div>
    <div class="project-list">
        <h1>Список проектов</h1>
        <button @click="showCreateForm = true">Создать новый проект</button>

        <div v-if="showCreateForm">
            <h2>Создать проект</h2>
            <input v-model="newProject.name" placeholder="Название проекта" />
            <input v-model="newProject.description" placeholder="Описание проекта" />
            <button @click="createProject">Сохранить</button>
            <button @click="showCreateForm = false">Отмена</button>
        </div>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="project in projects" :key="project.id">
                <td>{{ project.id }}</td>
                <td>{{ project.name }}</td>
                <td>{{ project.description }}</td>
                <td>
                    <button @click="editProject(project)">Редактировать</button>
                    <button @click="deleteProject(project.id)">Удалить</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            projects: [],
            apiData: null,
            newProject: {
                name: '',
                description: ''
            },
            showCreateForm: false
        };
    },
    methods: {
        fetchProjects() {
            axios.get('http://localhost:8000/api/projects')
                .then(response => {
                    this.projects = response.data;
                });
        },
        createProject() {
            axios.post('http://localhost:8000/api/projects', this.newProject)
                .then(() => {
                    this.fetchProjects();
                    this.newProject.name = '';
                    this.newProject.description = '';
                    this.showCreateForm = false;
                });
        },
        editProject(project) {
            // Здесь вы можете реализовать логику редактирования проекта
            // Например, открытие формы редактирования с предзаполненными данными
        },
        fetchData() {
            axios.get('http://localhost:8000/api/data')
                .then(response => {
                    this.apiData = response.data;
                })
                .catch(error => {
                    console.error('Ошибка при получении данных:', error);
                });
        },
        deleteProject(id) {
            axios.delete(`http://localhost:8000/api/projects/${id}`)
                .then(() => {
                    this.fetchProjects();
                });
        }
    },
    mounted() {
        this.fetchProjects();
        this.fetchData();
    }
};
</script>

<style scoped>
.project-list {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: red;
}

h1 {
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}

button {
    margin: 5px;
}
</style>


