<template>
    <div class="flex flex-row space-y-1 w-1/3" v-if="showProjects">
        <div class="bg-white border shadow flex flex-col space-y-1 w-full px-3 py-1">
            <div class="flex flex-row">
                <h2 class="text-lg font-bold">Latest projects</h2>
            </div>
            <div class="flex flex-row border px-3 py-1 items-center justify-between" v-for="data in projectData" :key="data.id">
                <div class="flex flex-col">
                    <h2 class="text-lg font-semibold">{{ data.name }}</h2>
                    <p class="text-sm text-gray-500 italic">{{ data.description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "Projects",
    data(){
        return {
            projectData: [],
            showProjects: false,
        }
    },
    created(){
        this.fetchLatestProjects();
        setInterval(() => {
            this.fetchLatestProjects();
        }, 60000);
    },
    methods: {
        async fetchLatestProjects(){
            try {
                const response = await axios.get(`${this.$store.state.url}charts/latestProjects`);
                console.log(response.data);
                if(response.data.success){
                    this.projectData = response.data.data;
                    this.showProjects = true;
                }
            } catch (error){
                console.errro(error);
            }
        }
    }
}
</script>

<style>

</style>