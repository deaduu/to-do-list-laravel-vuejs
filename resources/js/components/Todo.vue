<template>
    <div class="container my-4 p-4 bg-light rounded shadow">
        <h1 class="h2 fw-bold text-primary mb-4">{{ title }}</h1>

        <div
            v-if="alertVisible"
            :class="['alert', alertType, 'alert-dismissible', 'fade', 'show']"
            role="alert"
        >
            {{ alertMessage }}
            <button
                type="button"
                class="btn-close"
                aria-label="Close"
                @click="alertVisible = false"
            ></button>
        </div>

        <div class="mb-4">
            <form @submit.prevent="addTask">
                <div class="row">
                    <div class="col-6">
                        <input
                            type="text"
                            v-model="newTask"
                            placeholder="Add a new task"
                            class="form-control mb-2"
                        />
                    </div>
                    <div class="col-6">
                        <button class="btn btn-primary w-100">Add Task</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="mb-4">
            <button
                :class="[
                    'btn',
                    'btn-outline-primary',
                    'me-2',
                    { active: status == 'all' },
                ]"
                @click="filterTasks('all')"
            >
                All
            </button>
            <button
                :class="[
                    'btn',
                    'btn-outline-success',
                    'me-2',
                    { active: status == 'completed' },
                ]"
                @click="filterTasks('completed')"
            >
                Completed
            </button>
            <button
                :class="[
                    'btn',
                    'btn-outline-warning',
                    { active: status == 'pending' },
                ]"
                @click="filterTasks('pending')"
            >
                Pending
            </button>
        </div>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in tasks" :key="task.id">
                    <td>{{ task.id }}</td>
                    <td>{{ task.task }}</td>
                    <td>{{ task.completed ? "Completed" : "Pending" }}</td>
                    <td>
                        <button
                            class="btn btn-success"
                            v-if="!task.completed"
                            @click="markAsCompleted(task.id)"
                        >
                            <i class="fa fa-check-square-o"></i>
                        </button>
                        |
                        <button
                            class="btn btn-danger"
                            @click="deleteTask(task.id)"
                        >
                            <i class="fa fa-close"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            title: "PHP - Simple To Do List App",
            newTask: "",
            alertVisible: false,
            alertMessage: "",
            alertType: "alert-success",
            tasks: [],
            status: "pending",
        };
    },

    mounted() {
        this.fetch();
    },

    watch: {
        status(newStatus) {
            this.fetch();
        },
    },

    methods: {
        async addTask() {
            if (this.newTask.trim() === "") {
                alert("Please enter a task");
                return;
            }

            try {
                const response = await axios.post("/api/todos", {
                    task: this.newTask,
                });

                if (response.status === 201) {
                    this.alertMessage = "Task Added Successfully!";
                    this.alertVisible = true;
                    this.alertType = "alert-success";
                    this.newTask = "";
                    this.fetch();
                }
            } catch (error) {
                this.alertMessage =
                    error.response?.data?.message || "Error adding task!";
                this.alertType = "alert-warning";
                this.alertVisible = true;
                console.log(error);
            }
        },

        async fetch() {
            try {
                const response = await axios.get(
                    `/api/todos/fetch/${this.status}`
                );

                if (response.status === 200) {
                    this.tasks = response.data;
                }
            } catch (error) {
                console.log(error);
                this.alertMessage =
                    error.response?.data?.message || "Error fetching tasks!";
                this.alertType = "alert-danger";
                this.alertVisible = true;
            }
        },

        async markAsCompleted(id) {
            try {
                const response = await axios.put(
                    `/api/todos/statusUpdate/${id}`
                );

                if (response.status === 200) {
                    this.alertMessage = "Task marked as completed!";
                    this.alertType = "alert-success";
                    this.alertVisible = true;
                    this.fetch();
                }
            } catch (error) {
                console.log(error);
                this.alertMessage =
                    error.response?.data?.message ||
                    "Error marking task as completed!";
                this.alertType = "alert-danger";
                this.alertVisible = true;
            }
        },

        async deleteTask(id) {
            if (!confirm("Are you sure you want to delete this task?")) {
                return;
            }
            try {
                const response = await axios.delete(`/api/todos/delete/${id}`);

                if (response.status === 200) {
                    this.alertMessage = "Task deleted successfully!";
                    this.alertType = "alert-success";
                    this.alertVisible = true;
                    this.fetch();
                }
            } catch (error) {
                console.log(error);
                this.alertMessage =
                    error.response?.data?.message || "Error deleting task!";
                this.alertType = "alert-danger";
                this.alertVisible = true;
            }
        },

        filterTasks(status) {
            this.status = status;
        },
    },
};
</script>
