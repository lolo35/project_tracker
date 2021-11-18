<template>
    <div class="flex space-x-1 items-center" v-if="userCheck">
        <button class="text-yellow-500" v-if="!statusComputed" title="Edit..." @click="edit = !edit">
            <i class="fas fa-edit"></i>
        </button>
        <input type="checkbox" :id="id" :checked="statusComputed" @click="updateTask()">
        <button class="" title="Start Task" v-if="!statusComputed" @click="toggleActive()">
            <i class="fas fa-play text-green-500" v-if="!activeStatus"></i>
            <i class="fas fa-pause text-yellow-500" v-if="activeStatus"></i>
        </button>
    </div>
    <label v-if="!edit" class="text-white cursor-pointer" :class="{'line-through': statusComputed}" :for="id">{{ name }}</label>
    <form class="flex flex-row" v-if="edit" @submit="editTaskDescription()">
        <input type="text" class="rounded-l appearance-none" :value="name" ref="editTask">
        <button class="rounded-r bg-blue-500 px-1 text-white" type="submit">
            <i class="fas fa-check"></i>
        </button>
    </form>
</template>

<script>
import localforage from 'localforage';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: "Task",
    data(){
        return {
            activeStatus: false,
            edit: false,
        }
    },
    props: {
        id: Number,
        status: Number,
        name: String,
        userId: Number,
        index: Number,
    },
    emits: ['updateTask', 'editTask', 'taskAlreadyActive'],
    created(){
        this.checkStatus();
        setInterval(() => {
            this.checkStatus();
        }, 2000);
    },
    methods: {
        async editTaskDescription(){
            event.preventDefault();
            let formData = new FormData();
            formData.append('task_id', this.id);
            formData.append('description', this.$refs.editTask.value);
            try {
                const response = await axios.post(`${this.$store.state.url}user/editTaskDescription`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                console.log(response.data);
                if(response.data.success){
                    this.$emit('editTask', {index: this.index, 'description': this.$refs.editTask.value});
                    this.edit = false;
                }
            } catch (error){
                console.error(error);
            }
        },
        async updateTask(){
            let status = this.activeStatus ? 1 : 0;
            console.log(status);
            try {
                const response = await axios.post(`${this.$store.state.url}user/updateTask?id=${this.id}&status=${status}`);
                console.log(response.data);
                if(response.data.success){
                    this.$emit('updateTask', this.id);
                    localforage.removeItem(this.id.toString());
                    localforage.removeItem('activeTask');
                }
            } catch(error){
                console.error(error);
            }
            //console.log(response.data);
        },
        async toggleActive(){
            let formData = new FormData();
            formData.append('taskId', this.id);
            let checkForActive = await localforage.getItem('activeTask');
            if(checkForActive === null || checkForActive === this.id){
                if(!this.activeStatus){
                localforage.setItem(this.id.toString(), true);
                this.activeStatus = true;
                formData.append('status_id', -4);
                localforage.setItem('activeTask', this.id);
                localforage.setItem('activeTaskType', 'project');

                }else{
                    localforage.setItem(this.id.toString(), false);
                    this.activeStatus = false;
                    formData.append('status_id', -5);
                    localforage.removeItem('activeTask');
                    localforage.removeItem('activeTaskType');
                }
                const response = await axios.post(`${this.$store.state.url}user/toggleTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                console.log(response.data);
            }else{
                this.$emit('taskAlreadyActive');
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
                                formData.append('taskId', this.id);
                                formData.append('status_id', -4);
                                const response = await axios.post(`${this.$store.state.url}user/toggleTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                                if(response.data.success){
                                    localforage.setItem('activeTask', this.id);
                                    localforage.setItem('activeTaskType', 'project');
                                    this.activeStatus = !this.activeStatus;
                                    localforage.setItem(`recurringTask-${task}`, false);
                                    localforage.setItem(this.id.toString(), true);
                                }
                            }
                        }else if(taskType === "project"){
                            let formData = new FormData();
                            formData.append('taskId', task);
                            formData.append('status_id', -5);
                            localforage.removeItem('activeTask');
                            const response = await axios.post(`${this.$store.state.url}user/toggleTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                            if(response.data.success){
                                localforage.setItem(`recurringTask-${task}`, false);
                                let formData = new FormData();
                                formData.append('taskId', this.id);
                                formData.append('status_id', -4);
                                const response = await axios.post(`${this.$store.state.url}user/toggleTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                                if(response.data.success){
                                    localforage.setItem('activeTask', this.id);
                                    localforage.setItem('activeTaskType', 'project');
                                    this.activeStatus = !this.activeStatus;
                                    localforage.setItem(this.id.toString(), true);
                                }
                            }
                        }
                    }
                });
            }
        },
        checkStatus(){
            localforage.getItem(this.id.toString()).then(value => {
                if(value){
                    this.activeStatus = value;
                }else{
                    this.activeStatus = false;
                }
            });
        }
    },
    computed: {
        statusComputed(){
            return this.status === 1 ? true : false;
        },
        userCheck(){
            if(this.$store.state.user.userId === this.userId){
                return true;
            }else{
                return false;
            }
        }
    }
}
</script>

<style>

</style>