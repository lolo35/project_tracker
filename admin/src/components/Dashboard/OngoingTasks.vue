<template>
    <div class="flex flex-row space-y-1 w-1/3" v-if="showTasks">
        <div class="flex flex-col bg-white px-3 py-1 shadow w-full space-y-1">
            <div class="flex flex-row">
                <h2 class="text-lg font-bold">Ongoing tasks</h2>
            </div>
            <div class="flex flex-row border px-3 py-1 items-center justify-between" v-for="data in limitTasks" :key="data.id">
                <div class="flex flex-col">
                    <h2 class="font-semibold">{{ data.task }}</h2>
                    <p class="text-sm text-gray-400 italic">{{ data.name }}</p>
                </div>
                <div class="flex flex-col">
                    <h2 class="font-semibold">Time on task</h2>
                    <p class="text-sm text-gray-400 italic">{{ data.minutesSpent }} min</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "OngoingTasks",
    data(){
        return {
            ongoingTasks: [],
            showTasks: false,
        }
    },
    created(){
        this.fetchTasks();
        setInterval(() => {
            this.fetchTasks();
        }, 60000);
    },
    methods: {
        async fetchTasks(){
            try {
                const response = await axios.get(`${this.$store.state.url}teams/workingTasks`);
                console.log(response.data);
                if(response.data.success){
                    this.ongoingTasks = response.data.data;
                    this.showTasks = true;
                }
            } catch (error){
                console.error(error);
            }
        }
    },
    computed: {
        limitTasks(){
            return this.ongoingTasks.slice(0, 5);
        }
    }
}
</script>

<style>

</style>