<template>
    <div class="flex flex-col mb-2">
        <div class="-my-2 overflow-x-auto">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-700">
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Comment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                        </thead>
                        <tbody  class="divide-y divide-gray-200 bg-white" v-if="showHistory">
                            <tr v-for="data in history" :key="data.id">
                                <td class="px-4 py-2 whitespace-nowrap border-r" v-html="computePriority(data.priority)"></td>
                                <td class="px-4 py-2 whitespace-nowrap border-r">{{ data.comment }}</td>
                                <td class="px-4 py-2 whitespace-nowrap border-r">{{ parseDate(data.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { parseDate } from '../../HelperFunctions/index';

export default {
    name: "PriorityHistory",
    data(){
        return {
            history: Array,
            showHistory: false,
        }
    },
    props: {
        project_id: Number,
    },
    created(){
        this.fetchPriorityHistory();
    },
    methods: {
        parseDate,
        async fetchPriorityHistory(){
            try {
                const response = await axios.get(`${this.$store.state.url}projects/priorityHistory?project_id=${this.project_id}`);
                console.log(response.data);
                if(response.data.success){
                    this.history = response.data.data;
                    this.showHistory = true;
                }
            } catch (error){
                console.error(error);
            }
        },
        computePriority(priority){
            let userFriendlyPriority;
            switch(priority){
                case "0" : userFriendlyPriority = `<p class="text-sm text-red-500">Highest</p>`; break;
                case "1" : userFriendlyPriority = `<p class="text-sm text-yellow-400">Medium</p>`; break;
                case "2" : userFriendlyPriority = `<p class="text-sm text-green-400">Low</p>`; break;
                case "3" : userFriendlyPriority = `<p class="text-sm text-gray-600">Lowest</p>`; break;
            }

            return userFriendlyPriority;
        }
    }
}
</script>

<style>

</style>