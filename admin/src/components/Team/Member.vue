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
    <div class="flex flex-row-reverse items-center">
        <label :for="`toggle-${userId}`" class="flex items-center cursor-pointer text-white" >
             <div class="relative" title="Reccuring...">
                <input type="checkbox" :id="`toggle-${userId}`" class="sr-only" @click="recurring = !recurring">
                <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                <div class="dot absolute w-6 h-6 bg-white rounded-full shadow-left -top-1 transition"></div>
            </div>
        </label>
    </div>
    <div class="flex flex-row" v-if="!recurring">
        <p 
            v-if="tasksLoaded"
            class="font-semibold"
            :class="percentDone == 0 ? 'text-red-500' : percentDone > 30 && percentDone < 60 ? 'text-yellow-500': percentDone >= 60 ? 'text-green-500' : ''"
        >
            {{ completedTasks }} / {{ tasks.length }}
        </p>
    </div>
    <div class="flex flex-row text-white" v-if="!recurring">
        <div class="flex h-1 bg-green-500 rounded justify-center text-sm" :style="`width: ${percentDone}%`"></div>
    </div>
    <div class="flex flex-col" v-if="!recurring">
        <label for="project" class="text-white font-semibold">Project</label>
        <select 
            id="project" 
            v-model="selectedProject" 
            class="px-3 py-1 rounded border appearance-none" 
            @change="setSelectdProject()"
            :class="{'border-red-500': highlightNoProject, 'border-gray-200': !highlightNoProject}"
        >
            <option value=""></option>
            <option :value="{id: project.id, name: project.name, user: userId}" :key="project.id" v-for="project in $store.state.projects.projects">{{ project.name }}</option>
        </select>
        <p class="text-sm font-semibold text-red-500" v-if="highlightNoProject">Please select a project</p>
    </div>
    <div class="flex flex-col" v-if="!recurring">
        <div class="flex items-center" v-if="!highlightNoProject">
            <label for="addtask" class="text-white font-semibold">Add task</label>
        </div>
        <form class="flex items-center px-4 py-2" @submit="addTask()" v-if="!highlightNoProject">
            <input type="text" id="addtask" v-model="task" class="w-full rounded px-2 py-1 outline-none" required :disabled="disabled">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-r-md" :disabled="disabled">
                <i class="fas fa-check"></i>
            </button>
        </form>
    </div>
    <recurring-tasks v-if="recurring" :userId="userId" v-on:updateTask="updateTasks"></recurring-tasks>
    <transition name="dropdown" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut animate__faster">
        <div v-if="showTasks" class="max-h-102 overflow-y-scroll">
            <div v-if="dailyTasks.length > 0">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-200">Daily Tasks</h3>
                    <button class="text-blue-500 mr-3" @click="showDailyTasks = !showDailyTasks">
                        <i class="fas" :class="{'fa-angle-double-down': !showDailyTasks, 'fa-angle-double-up': showDailyTasks}"></i>
                    </button>
                </div>
                <div v-if="showDailyTasks">
                    <daily-tasks
                        v-for="(task, index) in dailyTasks"
                        :index="index"
                        :key="task.id"
                        :task="task"
                        :userId="userId"
                        :type="'daily'"
                        v-on:taskChangedTF="changeTaskTimeframe"
                        v-on:taskDeleted="removeTask"
                    ></daily-tasks>
                </div>
            </div>
            <div v-if="weeklyTasks.length > 0">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-200">Weekly Tasks</h3>
                    <button class="text-blue-500 mr-3" @click="showWeeklyTasks = !showWeeklyTasks">
                        <i class="fas" :class="{'fa-angle-double-down': !showWeeklyTasks, 'fa-angle-double-up': showWeeklyTasks}"></i>
                    </button>
                </div>
                <div v-if="showWeeklyTasks">
                    <daily-tasks
                        v-for="(task, index) in weeklyTasks"
                        :index="index"
                        :key="task.id"
                        :task="task"
                        :userId="userId"
                        :type="'weekly'"
                        v-on:taskChangedTF="changeTaskTimeframe"
                        v-on:taskDeleted="removeTask"
                    ></daily-tasks>
                </div>
            </div>
             <div v-if="monthlyTasks.length > 0">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-200">Monthly Tasks</h3>
                    <button class="text-blue-500 mr-3" @click="showMonthlyTasks = !showMonthlyTasks">
                        <i class="fas" :class="{'fa-angle-double-down': !showMonthlyTasks, 'fa-angle-double-up': showMonthlyTasks}"></i>
                    </button>
                </div>
                <div v-if="showMonthlyTasks">
                    <daily-tasks
                        v-for="(task, index) in monthlyTasks"
                        :index="index"
                        :key="task.id"
                        :task="task"
                        :userId="userId"
                        :type="'monthly'"
                        v-on:taskChangedTF="changeTaskTimeframe"
                        v-on:taskDeleted="removeTask"
                    ></daily-tasks>
                </div>
            </div>
            <div v-if="quarterlyTasks.length > 0">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-200">Quarterly Tasks</h3>
                    <button class="text-blue-500 mr-3" @click="showQuarterlyTasks = !showQuarterlyTasks">
                        <i class="fas" :class="{'fa-angle-double-down': !showQuarterlyTasks, 'fa-angle-double-up': showQuarterlyTasks}"></i>
                    </button>
                </div>
                <div v-if="showQuarterlyTasks">
                    <daily-tasks
                        v-for="(task, index) in quarterlyTasks"
                        :index="index"
                        :key="task.id"
                        :task="task"
                        :userId="userId"
                        :type="'quarterly'"
                        v-on:taskChangedTF="changeTaskTimeframe"
                        v-on:taskDeleted="removeTask"
                    ></daily-tasks>
                </div>
            </div>
            <div v-if="yearlyTasks.length > 0">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-200">Yearly Tasks</h3>
                    <button class="text-blue-500 mr-3" @click="showYearlyTasks = !showYearlyTasks">
                        <i class="fas" :class="{'fa-angle-double-down': !showYearlyTasks, 'fa-angle-double-up': showYearlyTasks}"></i>
                    </button>
                </div>
                <div v-if="showYearlyTasks">
                    <daily-tasks
                        v-for="(task, index) in yearlyTasks"
                        :index="index"
                        :key="task.id"
                        :task="task"
                        :userId="userId"
                        :type="'yearly'"
                        v-on:taskChangedTF="changeTaskTimeframe"
                        v-on:taskDeleted="removeTask"
                    ></daily-tasks>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-200">Tasks</h3>
                <!-- <button class="rounded-full bg-gray-900 text-white w-8 h-8 hover:text-blue-300 hover:bg-gray-700">
                    <i class="fas fa-plus-circle"></i>
                </button> -->
            </div>
            <div class="flex items-center justify-between space-x-2 px-4 py-2 bg-gray-600 origin-top" :ref="`task-${task.id}`" v-for="(task, index) in tasks" :key="task.id">
                <task 
                    :id="task.id" 
                    :index="parseInt(index)" 
                    :name="task.task" 
                    :status="parseInt(task.status)" 
                    v-on:updateTask="updateTaskStatus" 
                    v-on:editTask="editThisTask"
                    v-on:taskAlreadyActive="warnOfActiveTask()"
                    :userId="userId"
                ></task>
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
import localforage from 'localforage';
import RecurringTasks from './RecurringTasks.vue';
import DailyTasks from './DailyTasks.vue';

export default {
    name: "Member",
    data(){
        return {
            task: "",
            tasks: Array,
            dailyTasks: Array,
            weeklyTasks: Array,
            monthlyTasks: Array,
            quarterlyTasks: Array,
            yearlyTasks: Array,
            showTasks: false,
            tasksLoaded: false,
            selectedProject: "",
            highlightNoProject: true,
            recurring: false,
            recurringTasks: [],
            timeframes: ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'],
            disabled: false,
            showDailyTasks: false,
            showWeeklyTasks: false,
            showMonthlyTasks: false,
            showQuarterlyTasks: false,
            showYearlyTasks: false,
        }
    },
    props: {
        name: String,
        userId: Number,
    },
    components: {
        Task,
        RecurringTasks,
        DailyTasks,
    },
    created(){
        this.fetchProjects();
        this.fetchRecurringDailyTasks();
    },
    methods: {
        async warnOfActiveTask(){
            let activeTask = await localforage.getItem('activeTask');
            //let activeTaskType = await localforage.getItem('activeTaskType');
            console.log(activeTask);
            this.$refs[`task-${activeTask}`].classList.add('border');
            this.$refs[`task-${activeTask}`].classList.add('border-red-500');
            this.$refs[`task-${activeTask}`].classList.remove('bg-gray-600');
            this.$refs[`task-${activeTask}`].classList.add('bg-red-500');
            setTimeout(() => {
                this.$refs[`task-${activeTask}`].classList.remove('border');
                this.$refs[`task-${activeTask}`].classList.remove('border-red-500');
                this.$refs[`task-${activeTask}`].classList.remove('bg-red-500');
                this.$refs[`task-${activeTask}`].classList.add('bg-gray-600');
            }, 5000);
        },
        changeTaskTimeframe(payload){
            console.log(payload);
            switch(payload.timeframe){
                case "daily": this.dailyTasks.push(payload.task); break;
                case "weekly": this.weeklyTasks.push(payload.task); break;
                case "monthly": this.monthlyTasks.push(payload.task); break;
                case "quarterly": this.quarterlyTasks.push(payload.task); break;
                case "yearly": this.yearlyTasks.push(payload.task); break;
            }

            switch(payload.type){
                case "daily": this.dailyTasks.splice(payload.index, 1); break;
                case "weekly": this.weeklyTasks.splice(payload.index, 1); break;
                case "monthly": this.monthlyTasks.splice(payload.index, 1); break;
                case "quarterly": this.quarterlyTasks.splice(payload.index, 1); break;
                case "yearly": this.yearlyTasks.splice(payload.index, 1); break;
            }

        },
        editThisTask(payload){
            this.tasks[payload.index].task = payload.description;
        },
        updateTasks(payload){
            if(payload.occurence === "daily"){
                this.dailyTasks.push(payload.data);
            }else if(payload.occurence === "weekly"){
                this.weeklyTasks.push(payload.data);
            }else if(payload.occurence === "monthly"){
                this.monthlyTasks.push(payload.data);
            }else if(payload.occurence === "quarterly"){
                this.quarterlyTasks.push(payload.data);
            }else if(payload.occurence === "yearly"){
                this.yearlyTasks.push(payload.data);
            }
        },
        removeTask(payload){
            //console.log(payload);
            if(payload.type === "daily"){
                this.dailyTasks.splice(payload.index, 1);
            }else if(payload.type === "weekly"){
                this.weeklyTasks.splice(payload.index, 1);
            }else if(payload.type === "monthly"){
                this.monthlyTasks.splice(payload.index, 1);
            }else if(payload.type === "quarterly"){
                this.quarterlyTasks.splice(payload.index, 1);
            }else if(payload.type === "yearly"){
                this.yearlyTasks.splice(payload.splice, 1);
            }
        },
        async setSelectdProject(){
            try {
                await localforage.setItem(`selectedProject-${this.userId}`, {id: this.selectedProject.id, name: this.selectedProject.name, user: this.selectedProject.user});
                this.highlightNoProject = false;
                this.fetchTasks();
            } catch( error ){
                console.error(error);
            }
        },
        async fetchRecurringDailyTasks(){
            try {
                const response = await axios.get(`${this.$store.state.url}recurring/dailyTasks?userId=${this.userId}`);
                console.log('Daily Tasks: ', response.data);
                if(response.data.success){
                    
                    this.dailyTasks = response.data.data.daily;
                    this.weeklyTasks = response.data.data.weekly;
                    this.monthlyTasks = response.data.data.monthly;
                    this.quarterlyTasks = response.data.data.quarterly;
                    this.yearlyTasks = response.data.data.yearly;
                    
                }else{
                    if(response.data.error.errorInfo[1] === 2002){
                        this.fetchRecurringDailyTasks();
                    }
                }
            } catch (error) {
                console.error(error);
            }
        },
        async fetchProjects(){
            try {
                const response = await axios.get(`${this.$store.state.url}projects/`);
                const project = await localforage.getItem(`selectedProject-${this.userId}`);
                if(project !== null){
                    if(this.userId === project.user){
                        this.selectedProject = project;
                        this.highlightNoProject = false;
                        this.fetchTasks();
                    }
                }else{
                    this.highlightNoProject = true;
                }
                //console.log(project);
                if(response.data.success){
                    this.$store.dispatch('projects/setProjects', response.data.data);
                }
            } catch (error){
                console.error(error);
                if(error.errorInfo[1] === 2002){
                    this.fetchProjects();
                }
            }
        },
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
            //console.log('fetching tasks');
            try {
                const response = await axios.get(`${this.$store.state.url}user/tasks?userId=${this.userId}&projectId=${this.selectedProject.id}`);
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
                localforage.removeItem(id.toString());
            }
        },
        taskStatus(status){
            if(status === '1'){
                return true;
            }
            return false;
        },
        addTask(){
            if(Object.prototype.hasOwnProperty.call(this.selectedProject, 'id')){
                this.highlightNoProject = false;
                this.disabled = true;
                event.preventDefault();
                let formData = new FormData();
                formData.append('userId', this.userId);
                formData.append('task', this.task);
                formData.append('name', this.name);
                formData.append('projectId', this.selectedProject.id);

                axios.post(`${this.$store.state.url}user/addTask`, formData, {
                    headers: {
                        'Content-type': 'application/x-www-form-urlencoded',
                    }
                }).then(response => {
                    console.log(response.data);
                    if(response.data.success){
                        this.task = "";
                        this.tasks.unshift(response.data.data);
                        this.disabled = false;
                    }
                }).catch(error => {
                    console.error(error);
                    this.disabled = false;
                });                
            }else{
                this.highlightNoProject = true;
            }
        },
        updateTaskStatus(taskId){
            taskId = parseInt(taskId);
            console.log(taskId);
            for(let i = 0; i < this.tasks.length; i++){
                if(taskId === this.tasks[i].id){
                    // if(this.tasks[i].status == 0){
                    this.tasks[i].status = 1;
                    // }else{
                    //     this.tasks[i].status = 0;
                    // }
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
        },
    }
}
</script>

<style>
input:checked ~ .dot {
    transform: translateX(100%);
    background-color: #48bb78;
}
</style>