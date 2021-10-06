<template>
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center space-x-2">
            <img class="rounded-full w-8 h-8" :src="`${$store.state.url}images/no-photo.jpg`" alt="user" :title="$store.state.user.name">
            <h3 class="text-white text-lg">{{ name }}</h3>
        </div>
        <div class="flex">
            <button class="text-red-500 mr-2" @click="confirmRemoveTeamMember()">
                <i class="fas fa-user-times"></i>
            </button>
            <button class="text-blue-500" @click="showTasks = !showTasks">
                <i class="fas fa-angle-double-down"></i>
            </button>
        </div>
    </div>
    <div class="flex flex-row text-white">
        <div class="flex h-1 bg-green-500 rounded justify-center text-sm" :style="`width: ${percentDone}%`"></div>
    </div>
    <div class="flex items-center">
        <label for="addtask" class="text-white font-semibold">Add task</label>
    </div>
    <form class="flex items-center px-4 py-2" @submit="addTask()">
        <input type="text" id="addtask" v-model="task" class="w-full rounded px-2 py-1 outline-none" required>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-r-md">
            <i class="fas fa-check"></i>
        </button>
    </form>
    <transition name="dropdown" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut animate__faster">
        <div v-if="showTasks">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-200">Tasks</h3>
                <!-- <button class="rounded-full bg-gray-900 text-white w-8 h-8 hover:text-blue-300 hover:bg-gray-700">
                    <i class="fas fa-plus-circle"></i>
                </button> -->
            </div>
            <div class="flex items-center justify-between space-x-2 px-4 py-2 bg-gray-600 origin-top" v-for="(task, index) in tasks" :key="task.id">
                <task :id="task.id" :name="task.task" :status="parseInt(task.status)" v-on:updateTask="updateTaskStatus"></task>
                <button v-if="task.status != 1" class="text-red-500 w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-full" @click="confirmDeleteTask(task.id, index)">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="flex flex-row bg-yellow-50 text-yellow-500 px-6 py-3 border-l-8 border-yellow-600" v-if="tasks.length == 0">
                Looks like you don't have any tasks, add some using the input above
            </div>

        </div>
    </transition>    
</template>

<script>
import axios from 'axios';
import Task from './Task.vue';
import Swal from 'sweetalert2';

export default {
    name: "Member",
    data(){
        return {
            task: "",
            tasks: Array,
            showTasks: false,
            tasksLoaded: false,
        }
    },
    props: {
        name: String,
        userId: Number,
    },
    components: {
        Task,
    },
    created(){
        this.fetchTasks();
    },
    methods: {
        confirmRemoveTeamMember(){
            Swal.fire({
                icon: 'question',
                text: `Are you sure you want to remove ${this.name} from the team?`,
                showCancelButton: true,
                cancelButtonColor: "red",
                cancelButtonText: "No",
                confirmButtonText: "Yes",
            }).then((isConfirmed) => {
                if(isConfirmed.value){
                    this.removeTeamMember();
                }
            })
        },
        async removeTeamMember(){
            try {
                let formData = new FormData();
                formData.append('memberId', this.userId);
                const response = await axios.post(`${this.$store.state.url}teams/removeTeamMember`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded'}});
                if(response.data.success){
                    this.$store.dispatch('team/removeTeamMember', this.userId);
                }
            } catch(error) {
                console.error(error);
            }
        },
        async fetchTasks(){
            try {
                const response = await axios.get(`${this.$store.state.url}user/tasks?userId=${this.userId}`);
                console.log(response.data);
                if(response.data.success){
                    this.tasks = response.data.data.reverse();
                    this.tasksLoaded = true;
                }
            } catch (error){
                console.error(error);
            }
        },
        confirmDeleteTask(id, index){
            Swal.fire({
                icon: 'question',
                text: `Are you sure you want to delete the task "${this.tasks[index].task}" ?`,
                showCancelButton: true,
                cancelButtonText: "No",
                cancelButtonColor: "red",
                confirmButtonText: "Yes"
            }).then((isConfirmed) => {
                if(isConfirmed.value){
                    this.deleteTask(id, index);
                }
            });
        },
        async deleteTask(id, index){            
            let formData = new FormData();
            formData.append('id', id);
            const response = await axios.post(`${this.$store.state.url}user/deleteTask`, formData, { headers: {'Content-type': 'application/x-www-form-urlencoded'}});
            if(response.data.success){
                this.tasks.splice(index, 1);
            }
        },
        taskStatus(status){
            if(status === '1'){
                return true;
            }
            return false;
        },
        addTask(){
            event.preventDefault();
            let formData = new FormData();
            formData.append('userId', this.userId);
            formData.append('task', this.task);
            formData.append('name', this.name);

            axios.post(`${this.$store.state.url}user/addTask`, formData, {
                headers: {
                    'Content-type': 'application/x-www-form-urlencoded',
                }
            }).then(response => {
                console.log(response.data);
                if(response.data.success){
                    this.task = "";
                    this.tasks.unshift(response.data.data);
                }
            }).catch(error => {
                console.error(error);
            });
        },
        updateTaskStatus(taskId){
            //console.log(taskId);
            for(let i = 0; i < this.tasks.length; i++){
                if(taskId === this.tasks[i].id){
                    if(this.tasks[i].status == 0){
                        this.tasks[i].status = 1;
                    }else{
                        this.tasks[i].status = 0;
                    }
                    break;
                }
            }
        }
    },
    computed: {
        completedTasks(){
            let done = 0;
            if(this.tasksLoaded){
                for(let i = 0; i < this.tasks.length; i++){
                    if(this.tasks[i].status == "1"){
                        done++;
                    }
                }
            }            
            return done;
        },
        unfinishedTasks(){
            let inWork = 0;
            for(let i = 0; i < this.tasks.length; i++){
                if(this.tasks[i].status == 0){
                    inWork++;
                }
            }
            return inWork;
        },
        percentDone(){
            return (this.completedTasks / this.tasks.length) * 100;
        }
    }
}
</script>

<style>

</style>