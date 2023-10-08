<template>
    <div class="container">
	<header class="header">
		<h1 id="title" class="text-center">Submit Your Task</h1>
	</header>
	<div class="form-wrap">
		<form id="survey-form" @submit.prevent="save()">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label id="name-label" for="name">Title</label>
						<input type="text" v-model="v$.title.$model" id="name" placeholder="Enter your Title" class="form-control" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label id="email-label" for="email">Description</label>
						<input type="text" v-model="v$.contents.$model" id="email" placeholder="Enter your Description" class="form-control" required>
					</div>
				</div>
			</div>

			<div class="row">
                <div class="col-md-6">
					<div class="form-group">
						<label id="email-label" for="email">Due Date</label>
						<input type="date" v-model="v$.due.$model" id="email" placeholder="Enter your email" class="form-control" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Task Status</label>
						<select id="dropdown"  v-model="v$.status.$model" class="form-control" required>
							<option disabled selected value>Select</option>
							<option value="pending">Pending</option>
							<option value="in-progress">In Progress</option>
							<option value="completed">Completed</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<button type="submit" id="submit" class="btn btn-primary btn-block">Submit Survey</button>
				</div>
			</div>

		</form>
	</div>
</div>
</template>

<script setup>
import { ref , onMounted} from 'vue';
import { useRouter } from 'vue-router';
import { required } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
import { useAuthStore } from '../../store/auth.js';
import { useTaskStore } from '../../store/Task.js';

const router = useRouter();
const authStore = useAuthStore();
const taskStore = useTaskStore();
const taskId = ref();

const form = ref({
  title:  '',
  contents: '',
  due: '',
  status: ''
});

onMounted(() => {
 taskId.value = router.currentRoute.value.params.id;
  console.log('Company ID.....:', taskId.value);
  if(taskId.value)
  {
    getTaskDatabyId(taskId.value);
  }
});

async function getTaskDatabyId(id){
    let response = await taskStore.gettaskDatabyId(id);
    if (response && response.success) {
            form.value.title = response.data.task.title
            form.value.contents = response.data.task.description
            form.value.due = response.data.task.due_date
            form.value.status = response.data.task.status
            } else {
            // Login failed, handle the error accordingly
            console.error(response.message);
            }

}
const rules = {
  title: {
      required: true
  },
  contents: {
      required: true
  },
  due: {
      required: true
  },
  status: {
    required: true
  }
}

const v$ = useVuelidate(rules, form);

async function save() {
        try {
            const response = await taskStore.edittask(taskId.value,form.value); // Access email as form.email.value
            if (response && response.success) {
            router.push('/');
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
@import url('https://fonts.googleapis.com/css?family=Roboto:400,500,700,900&display=swap');

body{
	padding: 100px 0;
	background: #ecf0f4;
	width: 100%;
	height: 100%;
	font-size: 18px;
	line-height: 1.5;
	font-family: 'Roboto', sans-serif;
	color: #222;
}
.container{
	max-width: 1230px;
	width: 100%;
}

h1{
	font-weight: 700;
	font-size: 45px;
	font-family: 'Roboto', sans-serif;
}

.header{
	margin-bottom: 80px;
}
#description{
	font-size: 24px;
}

.form-wrap{
	background: rgba(255,255,255,1);
	width: 100%;
	max-width: 850px;
	padding: 50px;
	margin: 0 auto;
	position: relative;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	-webkit-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
	-moz-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
	box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
}
.form-wrap:before{
	content: "";
	width: 90%;
	height: calc(100% + 60px);
	left: 0;
	right: 0;
	margin: 0 auto;
	position: absolute;
	top: -30px;
	background: #00bcd9;
	z-index: -1;
	opacity: 0.8;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	-webkit-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
	-moz-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
	box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
}
.form-group{
	margin-bottom: 25px;
}
.form-group > label{
	display: block;
	font-size: 18px;
	color: #000;
}
.custom-control-label{
	color: #000;
	font-size: 16px;
}
.form-control{
	height: 50px;
	background: #ecf0f4;
	border-color: transparent;
	padding: 0 15px;
	font-size: 16px;
	-webkit-transition: all 0.3s ease-in-out;
	-moz-transition: all 0.3s ease-in-out;
	-o-transition: all 0.3s ease-in-out;
	transition: all 0.3s ease-in-out;
}
.form-control:focus{
	border-color: #00bcd9;
	-webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	-moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
}
textarea.form-control{
	height: 160px;
	padding-top: 15px;
	resize: none;
}

.btn{
	padding: .657rem .75rem;
	font-size: 18px;
	letter-spacing: 0.050em;
	-webkit-transition: all 0.3s ease-in-out;
	-moz-transition: all 0.3s ease-in-out;
	-o-transition: all 0.3s ease-in-out;
	transition: all 0.3s ease-in-out;
}

.btn-primary {
  color: #fff;
  background-color: #00bcd9;
  border-color: #00bcd9;
}

.btn-primary:hover {
  color: #00bcd9;
  background-color: #ffffff;
  border-color: #00bcd9;
	-webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	-moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
}

.btn-primary:focus, .btn-primary.focus {
  color: #00bcd9;
  background-color: #ffffff;
  border-color: #00bcd9;
  -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	-moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
}

.btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled).active,
.show > .btn-primary.dropdown-toggle {
  color: #00bcd9;
  background-color: #ffffff;
  border-color: #00bcd9;
}

.btn-primary:not(:disabled):not(.disabled):active:focus, .btn-primary:not(:disabled):not(.disabled).active:focus,
.show > .btn-primary.dropdown-toggle:focus {
  -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	-moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
	box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
}
</style>
