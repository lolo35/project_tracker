<template>
    <div class="flex flex-row space-y-1 w-1/3" v-if="showIssues">
        <div class="bg-white border px-3 py-1 shadow flex w-full flex-col space-y-1">
            <div class="flex flex-row justify-between items-center">
                <h3 class="text-lg font-bold">Open issues: {{ openIssues }}</h3>
                <h3 class="text-lg font-bold">Closed issues: {{ closedIssues }}</h3>
            </div>
            <div class="flex flex-row">
                <h3 class="text-lg font-bold">Latest issues</h3>
            </div>
            <div class="flex flex-row px-3 py-1 border items-center justify-between" v-for="data in latestIssues" :key="data.id">
                <div class="flex flex-col">
                    <h2 class="font-semibold">Project: <span class="italic">{{ data.name }}</span></h2>
                    <p class="font-semibold">Issue: <span class="italic text-gray-400 text-sm">{{ data.issue }}</span></p>
                </div>
                <div class="flex flex-col">
                    <h2 class="font-semibold">Opened by </h2>
                    <span class="italic">{{ data.openedBy }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "LatestIssues",
    data() {
        return {
            latestIssues: [],
            showIssues: false,
            openIssues: 0,
            closedIssues: 0,
            interval: "",
        }
    },
    created() {
        this.fetchIssuesData();
        this.issueCount();
        this.Interval = setInterval(() => {
            this.fetchIssuesData();
            this.issueCount();
        }, 60000);
    },
    unmounted(){
        clearInterval(this.interval);
    },
    methods: {
        async fetchIssuesData(){
            try {
                const response = await axios.get(`${this.$store.state.url}charts/latestIssues`);
                //console.log(response.data);
                if(response.data.success){
                    this.latestIssues = response.data.data;
                    this.showIssues = true;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async issueCount(){
            try{
                const response = await axios.get(`${this.$store.state.url}charts/issueCount`);
                //console.log(response.data);
                if(response.data.success){
                    this.openIssues = response.data.openIssues[0].total;
                    this.closedIssues = response.data.closedIssues[0].total;
                }
            } catch (error) {
                console.error(error);
            }
        }
    }
}
</script>

<style>

</style>