<template>
    <div class="flex flex-col space-y-2">
        <div class="flex flex-row space-x-2">
            <div class="bg-white border px-3 py-1 shadow w-1/3">
                <pie-chart
                    v-if="showPieChart"
                    :chartData="pieChartData"
                    :chartOptions="pieChartOptions"
                    :key="pieChartKey"
                ></pie-chart>
            </div>
            <div class="bg-white border-px-3 py-1 shadow w-2/3 relative">
                <column-chart
                    v-if="showBarChart"
                    :chartData="this.tasksEvolutionData"
                    :chartOptions="barChartOptions"
                    :key="columnChartKey"
                >
                </column-chart>
                <div class="absolute top-0 bottom-0 left-0 right-0 bg-black opacity-25" v-if="!showBarChart"></div>
                <div class="absolute top-1/2 left-1/2" v-if="!showBarChart">
                    <i class="fas fa-spinner fa-2x font-bold animate-spin"></i>
                </div>
            </div>
        </div>
        <div class="flex flex-row space-x-2 w-full">
            <latest-issues></latest-issues>
            <projects></projects>
            <ongoing-tasks></ongoing-tasks>
        </div>
    </div>
</template>

<script>
// import { GoogleCharts } from 'google-charts';
import PieChart from '../components/Charts/PieChart.vue';
import ColumnChart from '../components/Charts/ColumnChart.vue';
import LatestIssues from '../components/Dashboard/LatestIssues.vue';
import Projects from '../components/Dashboard/Projects.vue';
import OngoingTasks from '../components/Dashboard/OngoingTasks.vue';

import axios from 'axios';

export default {
    name: "Dashboard",
    data(){
        return {
            pieChartData: [],
            tasksEvolutionData: [],
            pieChartOptions: {
                title: "Tasks"
            },
            barChartOptions: {
                title: "5 day task evolution"
            },
            showPieChart: false,
            showBarChart: false,
            latestIssues: [],
            showIssues: false,
            columnChartKey: 0,
            pieChartKey: 0,
            tasksInterval: "",
        }
    },
    components: {
        PieChart,
        ColumnChart,
        LatestIssues,
        Projects,
        OngoingTasks,
    },
    created() {
        this.fetchTasksData();
        this.fetchTasksEvolution();
        this.tasksInterval = setInterval(() => {
            this.fetchTasksData();
            this.fetchTasksEvolution();
        }, 60000);
    },
    unmounted(){
        clearInterval(this.tasksInterval);
    },
    methods: {
        async fetchTasksData(){
            this.pieChartData = [];
            try {
                const response = await axios.get(`${this.$store.state.url}charts/tasksSummary`);
                if(response.data.success){
                    
                    this.pieChartData = [
                        ['Tasks', 'Tasks ammount'],
                        ['Done tasks', parseInt(response.data.doneTasks[0].total)],
                        ['Idle tasks', parseInt(response.data.idleTasks[0].total)],
                        ['Ongoing tasks', parseInt(response.data.workingTasks[0].total)]
                    ];
                    this.showPieChart = true;
                    this.pieChartKey++;
                }
            } catch(error){
                console.error(error);
            }
        },
        async fetchTasksEvolution(){
            try {
                
                const response = await axios.get(`${this.$store.state.url}charts/taskCountEvolution`);
                //console.log(response.data);
                if(response.data.success){
                    this.showBarChart = false;
                    this.tasksEvolutionData = [];
                    this.tasksEvolutionData.push(['Day', 'Ammount']);
                    for(const property in response.data.doneTasks){
                        console.log(property, response.data.doneTasks[property]);
                        this.tasksEvolutionData.push([property, response.data.doneTasks[property]]);
                    }
                    this.showBarChart = true;
                    this.columnChartKey++;
                }
            } catch (error){
                console.error(error);
            }
        },
    }
}
</script>

<style>

</style>