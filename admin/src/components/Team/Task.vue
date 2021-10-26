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
    emits: ['updateTask', 'editTask'],
    created(){
        this.checkStatus();
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
                }
            } catch(error){
                console.error(error);
            }
            //console.log(response.data);
        },
        async toggleActive(){
            let formData = new FormData();
            formData.append('taskId', this.id);
            if(!this.activeStatus){
                localforage.setItem(this.id.toString(), true);
                this.activeStatus = true;
                formData.append('status_id', -4);

            }else{
                localforage.setItem(this.id.toString(), false);
                this.activeStatus = false;
                formData.append('status_id', -5);
            }

            const response = await axios.post(`${this.$store.state.url}user/toggleTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
            console.log(response.data);
        },
        checkStatus(){
            localforage.getItem(this.id.toString()).then(value => {
                if(value){
                    this.activeStatus = value;
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