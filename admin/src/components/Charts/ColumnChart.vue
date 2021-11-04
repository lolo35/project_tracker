<template>
    <div ref="columnChart" class="chart">

    </div>
</template>

<script>
import { GoogleCharts } from 'google-charts';

export default {
    name: "ColumnChart",
    props: {
        chartData: Array,
        chartOptions: Object,
    },
    created(){
        GoogleCharts.load(this.drawChart);
    },
    watch: {
        chartData: function(){
            console.log('redraw');
            GoogleCharts.load(this.drawChart);
        }
    },
    methods: {
        drawChart(){
            const data = GoogleCharts.api.visualization.arrayToDataTable(this.chartData);
            const bar_chart = new GoogleCharts.api.visualization.ColumnChart(this.$refs.columnChart);
            bar_chart.draw(data, this.chartOptions);
        }
    }
}
</script>

<style>

</style>