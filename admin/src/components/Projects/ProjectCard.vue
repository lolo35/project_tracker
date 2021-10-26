<template>
    <div class="bg-gray-700 text-white px-6 py-3 rounded border flex border-gray-700 relative" style="min-width: 300px;">
        <div class="flex flex-col w-full">
            <div class="flex flex-row items-center justify-between">
                <p class="text-lg font-semibold">{{ project }}</p>
            </div>
            <p class="text-sm font-semibold text-gray-400">{{ description }}</p>
            <div class="flex flex-row justify-between items-center" v-if="tasks">
                <p class="font-semibold text-gray-400">Tasks:</p>
                <p class="font-semibold">{{ doneTasks }} / {{ totalTasks }}</p>
            </div>
            <div v-else class="flex flex-row">
                <p class="font-semibold text-red-300">Project has no tasks yet</p>
            </div>
            <!-- <p class="text-sm text-gray-400">Owner: {{ owner }}</p> -->
            <div v-if="tasks" class="flex flex-col">
                <div class="flex h-1 bg-green-500" :style="`width: ${percentDone}%;`"></div>
                <div class="flex flex-row items-center justify-between">
                    <p class="text-sm text-gray-400">Total time spent: </p>
                    <p class="text-sm text-gray-400">{{ timeSpent }} min</p>
                </div>
                <div class="flex flex-row items-center justify-between">
                    <p class="text-sm text-gray-400">Priority: </p>
                    <div v-html="userFriendlyPriority"></div>
                </div>
            </div>
        </div>
        <router-link :to="`/projectDetails/${projectId}`" class="absolute top-1 right-2">
            <i class="fas fa-info-circle"></i>
        </router-link>
        
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "ProjectCard",
    data(){
        return {
            tasks: false,
            doneTasks: 0,
            totalTasks: 0,
            percentDone: 0,
            timeSpent: 0,
        }
    },
    props: {
        project: String,
        description: String,
        owner: String,
        projectId: Number,
        priority: Number,
    },
    created(){
        this.fetchStats();
    },
    methods: {
        async fetchStats(){
            try {
                const response = await axios.get(`${this.$store.state.url}projects/projectStats?projectId=${this.projectId}`);
                console.log(response.data);
                if(response.data.success){
                    this.doneTasks = parseInt(response.data.doneTasks[0].total);
                    this.totalTasks = parseInt(response.data.totalTasks[0].total);
                    this.percentDone = parseFloat(response.data.percent);
                    this.timeSpent = parseInt(response.data.totalTime);
                    this.tasks = true;
                }else{
                    if(response.data.error.errorInfo){
                        this.fetchStats();
                    }
                }
            } catch(error){
                console.error(error);
            }
            
        }
    },
    computed: {
        userFriendlyPriority() {
            let status;
            switch(this.priority){
                case 0: status = `<p class="text-sm text-red-500">Highest</p>`; break;
                case 1: status = `<p class="text-sm text-yellow-400">Medium</p>`; break;
                case 2: status = `<p class="text-sm text-green-400">Low</p>`; break;
                case 3: status = `<p class="text-sm text-gray-600">Lowest</p>`; break;
            }
            return status;
        }
    }
}
</script>

<style>

</style>