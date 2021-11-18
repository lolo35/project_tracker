<template>
    <div class="flex flex-col relative">
        <div class="flex items-center justify-between space-x-2 px-4 py-2 bg-gray-600 origin-top">
            <div class="flex flex-row items-center space-x-1">
                <input type="checkbox" title="Complete Task" :checked="statusComputed" @click="completeTask()">
                <button class="" title="Start Task" @click="toggleActive()">
                    <i class="fas fa-play text-green-500" v-if="!activeStatus"></i>
                    <i class="fas fa-pause text-yellow-500" v-if="activeStatus"></i>
                </button>
            </div>            
            <p class="text-white">{{ task.task }}</p>
            <button class="text-red-500 w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-full" @click="deleteTask()">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
        <button class="absolute text-xs text-gray-300 left-1" title="Change timeframe..." @click="showEditTimeframe = !showEditTimeframe">
            <i class="fas fa-history"></i>
        </button>
        <div class="absolute bg-gray-300 p-2 left-1 rounded top-4" v-if="showEditTimeframe">
            <form class="flex flex-col space-y-1" @submit="changeTimeframe()">
                <label for="changeTimeframe" class="text-xs font-semibold">Pick new timeframe</label>
                <div class="flex flex-row">
                    <select v-model="timeframe" id="changeTimeframe" class="rounded-l">
                        <option value="daily" :selected="task.recurring == 'daily' ? true : false">Daily</option>
                        <option value="weekly" :selected="task.recurring == 'weekly' ? true : false">Weekly</option>
                        <option value="monthly" :selected="task.recurring == 'monthly' ? true : false">Monthly</option>
                        <option value="quarterly" :selected="task.recurring == 'quarterly' ? true : false">Quarterly</option>
                        <option value="yearly" :selected="task.recurring == 'yearly' ? true : false">Yearly</option>
                    </select>
                    <button type="submit" class="text-white bg-blue-500 rounded-r px-1">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import localforage from 'localforage';
import Swal from 'sweetalert2';

export default {
    name: "DailyTasks",
    data(){
        return {
            activeStatus: false,
            showEditTimeframe: false,
            timeframe: "",
        }
    },
    props: {
        task: Object,
        index: Number,
        type: String,
    },
    emits: ['taskDeleted', 'taskChangedTF', 'taskAlreadyActive'],
    created(){
        this.checkStatus();
        setInterval(() => {
            this.checkStatus();
        }, 2000);
    },
    methods: {
        async checkStatus(){
            const status = await localforage.getItem(`recurringTask-${this.task.id}`);
            if(status){
                this.activeStatus = true;
            }else{
                this.activeStatus = false;
            }
        },
        async deleteTask(){
            let formData = new FormData();
            formData.append('id', this.task.id);
            try {
                const response = await axios.post(`${this.$store.state.url}recurring/deleteTask`, formData, { headers: { 'Content-Type': 'application/x-www-form-urlencoded'}});
                console.log(response.data);
                if(response.data.success){
                    this.$emit('taskDeleted', {type: this.type, index: this.index});
                }
            } catch (error){
                console.error(error);
            }
        },
        async toggleActive(){
            let formData = new FormData();
            formData.append('taskId', this.task.id);
            let checkForActive = await localforage.getItem('activeTask');
            if(checkForActive === null || checkForActive === this.task.id){
                if(!this.activeStatus){
                    formData.append('status_id', -4);
                    localforage.setItem('activeTask', this.task.id);
                    localforage.setItem('activeTaskType', 'reccurring');
                }
                if(this.activeStatus){
                    formData.append('status_id', -5);
                    localforage.removeItem('activeTask');
                    localforage.removeItem('activeTaskType');
                }
                const response = await axios.post(`${this.$store.state.url}recurring/activateTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded'}});
                if(response.data.success){
                    this.activeStatus = !this.activeStatus;
                    localforage.setItem(`recurringTask-${this.task.id}`, this.activeStatus);
                }else{
                    console.log(response.data);
                }
            }else{
                Swal.fire({
                    icon: 'warning',
                    text: 'You already have a running task, would you like to pause it and start your next task?',
                    confirmButtonText: "Yes",
                    showCancelButton: true,
                    cancelButtonColor: "red"
                }).then(async (isConfirmed) => {
                    if(isConfirmed.value){
                        let task = await localforage.getItem('activeTask');
                        let taskType = await localforage.getItem('activeTaskType');
                        if(taskType === "reccurring"){
                            let formData = new FormData();
                            formData.append('taskId', task);
                            formData.append('status_id', -5);
                            localforage.removeItem('activeTask');
                            const response = await axios.post(`${this.$store.state.url}recurring/activateTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded'}});
                            if(response.data.success){
                                localforage.setItem(`recurringTask-${task}`, false);
                                let formData = new FormData();
                                formData.append('taskId', this.task.id);
                                formData.append('status_id', -4);
                                const response = await axios.post(`${this.$store.state.url}recurring/activateTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded'}});
                                if(response.data.success){
                                    localforage.setItem('activeTask', this.task.id);
                                    localforage.setItem('activeTaskType', 'reccurring');
                                    this.activeStatus = !this.activeStatus;
                                    localforage.setItem(`recurringTask-${this.task.id}`, this.activeStatus);
                                }
                            }
                        }else if(taskType === "project"){
                            let formData = new FormData();
                            formData.append('taskId', task);
                            formData.append('status_id', -5);
                            localforage.removeItem('activeTask');
                            const response = await axios.post(`${this.$store.state.url}user/toggleTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                            if(response.data.success){
                                localforage.setItem(task.toString(), false);
                                let formData = new FormData();
                                formData.append('taskId', this.task.id);
                                formData.append('status_id', -4);
                                const response = await axios.post(`${this.$store.state.url}recurring/activateTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded'}});
                                if(response.data.success){
                                    localforage.setItem('activeTask', this.task.id);
                                    localforage.setItem('activeTaskType', 'reccurring');
                                    this.activeStatus = !this.activeStatus;
                                    localforage.setItem(`recurringTask-${this.task.id}`, this.activeStatus);
                                }
                            }
                        }
                    }
                });
            }
            
        },
        async completeTask(){
            let formData = new FormData();
            formData.append('task_id', this.task.id);
            try{
                const response = await axios.post(`${this.$store.state.url}recurring/completeTask`, formData, { headers: {'Content-type': 'application/x-www-form-urlencoded'}});
                console.log(response.data);
                if(response.data.success){
                    this.$emit('taskDeleted', {type: this.type, index: this.index});
                    localforage.removeItem(`recurringTask-${this.task.id}`);
                }else{
                    console.log(response.data);
                }
            }catch(error) {
                console.error(error);
            }
            
        },
        async changeTimeframe(){
            event.preventDefault();
            let formData = new FormData();
            formData.append('task_id', this.task.id);
            formData.append('timeframe', this.timeframe);

            try {
                const response = await axios.post(`${this.$store.state.url}recurring/changeTimeframe`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded'}});
                console.log(response.data);
                if(response.data.success){
                    this.$emit('taskChangedTF', {type: this.type, timeframe: this.timeframe, index: this.index,  task: this.task});
                    this.showEditTimeframe = false;
                }
            } catch (error){
                console.error(error);
            }
        }
    },
    computed: {
        statusComputed(){
            return this.task.status === "1" ? true : false;
        }
    }
}
</script>

<style>

</style>