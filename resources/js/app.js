import { createApp } from 'vue'
import Todo from './components/Todo'
import 'bootstrap/dist/css/bootstrap.min.css';

const app = createApp({})

app.component('todo', Todo)

app.mount('#app')