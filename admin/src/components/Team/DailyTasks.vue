<template>
    <div class="flex flex-col">
        <div class="flex items-center justify-between space-x-2 px-4 py-2 bg-gray-600 origin-top">
            <button class="" title="Start Task" v-if="!statusComputed" @click="toggleActive()">
                <i class="fas fa-play text-green-500" v-if="!activeStatus"></i>
                <i class="fas fa-check text-green-500" v-if="activeStatus"></i>
            </button>
            <p class="text-white">{{ task.task }}</p>
            <button class="text-red-500 w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-full" @click="deleteTask()">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
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
    methods: {
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
            this.activeStatus = !this.activeStatus;
        }
    },
    computed: {
        statusComputed(){
            return this.task.status === 1 ? true : false;
        }
    }
}
</script>

<style>

</style>