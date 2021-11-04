<template>
    <div ref="chart" class="chart">

    </div>
</template>

<script>
import { GoogleCharts } from 'google-charts';

export default {
    name: "PieChart",
    props: {
        chartData: Array,
        chartOptions: Object,
    },
    created(){
        //setTimeout(() => {
            GoogleCharts.load(this.drawChart);
        //}, 1000);
    },
    methods: {
        drawChart(){
            const data = GoogleCharts.api.visualization.arrayToDataTable(this.chartData);
            const pie_chart = new GoogleCharts.api.visualization.PieChart(this.$refs.chart);
            pie_chart.draw(data, this.chartOptions);
        }
    }
}
</script>

<style>
    .chart {
        width: 100%;
        min-height: 400px;
    }
</style>