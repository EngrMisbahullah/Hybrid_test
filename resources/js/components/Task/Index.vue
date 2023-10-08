<template>
    <div>
        <div class="container mt-2">
            <div class="row bg-dark">
                <div class="col-md-9">
                    <nav class="navbar navbar-expand-sm p-3">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-list-4"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbar-list-4">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="https://s3.eu-central-1.amazonaws.com/bootstrapbaymisc/blog/24_days_bootstrap/fox.jpg"
                                            width="40" height="40" class="rounded-circle" />
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item" @click="logout()">Log Out</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <h4 class="text-white">Task Managment</h4>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 float-right">
                    <router-link  to="/add/task"><button class="btn btn-action btn-2xs btn-outline btn-primary m-1 float-right">
                        <i class="iconoir-edit"></i>
                        <span class="ml-1">Add Task</span>
                    </button></router-link>
                </div>
            </div>
            <div class="relative min-h-screen">
                <div class="sticky top-0 z-10 pb-3 bg-gray-200 dark:bg-gray-800">
                    <div class="flex flex-row px-2 lg:px-0 items-center justify-between w-full mt-2 mb-1 lg:mt-3 lg:mb-2">
                        <div class="flex text-2xl leading-tight dark:text-white"></div>
                        <div class="flex justify-end"></div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <div class="relative min-h-screen">
                        <div class="overflow-x-auto">
                            <div v-if="taskList != '' ">
                                <div v-for="(task, index) in taskList" :key="index" class="card mt-3">
                                        <div class="card-body">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <div class="flex flex-col space-y-3">
                                                    <h4 class="text-lg font-bold">
                                                        {{ task.title }}
                                                    </h4>
                                                    <p class="">
                                                        <b>{{ task.description }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 md:text-right">
                                            <div class="actions">
                                                <router-link :to="{ name: 'EditTask', params: { id: task.id } }">
                                                    <button class="btn btn-action btn-2xs btn-outline btn-primary m-1">
                                                        <i class="iconoir-edit"></i>
                                                        <span class="ml-1">Update</span>
                                                    </button>
                                                </router-link>
                                                <button class="btn btn-action btn-2xs btn-outline btn-primary m-1" @click="deleteTask(task.id)">
                                                    <i class="iconoir-edit"></i>
                                                    <span class="ml-1">Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div v-else class="card bg-gray-100 dark:bg-gray-800  mb-2 ">
                                <div class="card-body text-center">
                                    <h4 class="text-lg text-gray-500">Data not found</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { required } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
import { useAuthStore } from '../../store/auth.js';
import { useTaskStore } from '../../store/Task.js';
import AddTask from './AddTask.vue';

const router = useRouter();
const authStore = useAuthStore();
const taskStore = useTaskStore();
const taskList = ref({});
const taskId = ref();
const showForm = ref(false);
const TaskForm = ref(null);

const form = ref({
  title:  '',
  contents: '',
  category: ''
});

const rules = {
  title: {
      required: true
  },
  contents: {
      required: true
  },
  category: {
    required: true
  }
}

function openNew(id = null){
    showForm.value = true;
    if(id){
        TaskForm.value.loadTaskData(id)
    }
}
const v$ = useVuelidate(rules, form);


taskLists();
async function taskLists() {

    let response = await taskStore.taskListPaginated();
    if (response) {
        if(response.status == 401){
            console.log("Check Test")
            authStore.flushUser()
            router.push({ name: 'admin.login' });
            return false;
        }
        if (!response.success) {
            if (response.errors) {
                for (let key in response.errors) {
                    let error = response.errors[key];
                    v$.value[key].$serverError = error
                }
            }
            else if (response.message) {
                toast.add({severity:'error', summary: t('Success'), detail: t('Something went wrong!'), life: 3000});
            }
        }else{
            taskList.value = response.data.task
        }

    }

};
async function logout() {
  try {
    authStore.flushUser();
    router.push('/login');
  } catch (error) {
    console.error(error);
  }
}
async function deleteTask(id) {
  try {
    const response = await taskStore.taskDelete(id); // Access email as form.email.value

if (response && response.success) {
  // Login successful, you can redirect or perform any action here
  taskLists();
} else {
  // Login failed, handle the error accordingly
  console.error(response.message);
}
  } catch (error) {
    console.error(error);
  }
}
async function save() {
  try {
    const response = await taskStore.addtask(form.value); // Access email as form.email.value

    if (response && response.success) {
      // Login successful, you can redirect or perform any action here
      console.log('Success');
      router.push('/add/task');
    } else {
      // Login failed, handle the error accordingly
      console.error(response.message);
    }
  } catch (error) {
    console.error(error);
  }
}
</script>

<style scoped>
</style>
