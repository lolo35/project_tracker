<template>
    <div class="flex flex-col">
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
    </div>
</template>

<script>
import axios from 'axios';
import localforage from 'localforage';

export default {
    name: "DailyTasks",
    data(){
        return {
            activeStatus: false,
        }
    },
    props: {
        task: Object,
        index: Number,
    },
    emits: ['taskDeleted'],
    created(){
        this.checkStatus();
    },
    methods: {
        async checkStatus(){
            const status = await localforage.getItem(`recurringTask-${this.task.id}`);
            if(status){
                this.activeStatus = true;
            }
        },
        async deleteTask(){
            let formData = new FormData();
            formData.append('id', this.task.id);
            try {
                const response = await axios.post(`${this.$store.state.url}recurring/deleteTask`, formData, { headers: { 'Content-Type': 'application/x-www-form-urlencoded'}});
                console.log(response.data);
                if(response.data.success){
                    this.$emit('taskDeleted', this.index);
                }
            } catch (error){
                console.error(error);
            }
        },
        async toggleActive(){
            let formData = new FormData();
            formData.append('taskId', this.task.id);
            if(!this.activeStatus){
                formData.append('status_id', -4);
            }
            if(this.activeStatus){
                formData.append('status_id', -5);
            }
            const response = await axios.post(`${this.$store.state.url}recurring/activateTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded'}});
            if(response.data.success){
                this.activeStatus = !this.activeStatus;
                localforage.setItem(`recurringTask-${this.task.id}`, true);
            }else{
                console.log(response.data);
            }
            
        },
        async completeTask(){
            let formData = new FormData();
            formData.append('task_id', this.task.id);
            try{
                const response = await axios.post(`${this.$store.state.url}recurring/completeTask`, formData, { headers: {'Content-type': 'application/x-www-form-urlencoded'}});
                if(response.data.success){
                    this.$emit('taskDeleted', this.index);
                    localforage.removeItem(`recurringTask-${this.task.id}`);
                }else{
                    console.log(response.data);
                }
            }catch(error) {
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