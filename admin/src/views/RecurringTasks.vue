<template>
    <div class="flex flex-col bg-gray-700 p-2 rounded mb-2">
        <div class="flex flex-row justify-between">
            <div class="flex flex-row">
                <h3 class="text-white font-semibold">Recurring tasks</h3>
            </div>
            <div class="flex flex-row items-center">
                <input type="text" v-model="selectedUser" @change="setUserId()" class="w-full p-1 bg-white border border-gray-200 rounded-l shadow-sm appearance-none" list="users" placeholder="Search by user...">
                <button class="bg-blue-500 p-1 rounded-r text-white border border-blue-500" @click="fetchHistoryForUser()">
                    <i class="fas fa-search"></i>
                </button>
                <datalist id="users" v-if="showDatalist">
                    <option :value="item.id" v-for="item in list" :key="item.id">{{ item.name }}</option>
                </datalist>
            </div>
        </div>
    </div>
    <div class="flex flex-col p-2 rounded" v-if="showTable">
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-800">
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Dispatch</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    <div class="flex flex-row items-center justify-between">
                                        Task
                                        <input type="text" class="border rounded px-3 py-1 appearance-none text-gray-400" placeholder="Search..." v-model="searchTerm">
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    <div class="flex flex-row justify-between items-center">
                                        Timeframe
                                        <div class="flex flex-col">
                                            <button class="hover:text-gray-500" @click="sortByTimeframe('up')">
                                                <i class="fas fa-caret-up"></i>
                                            </button>
                                            <button class="hover:text-gray-500" @click="sortByTimeframe('down')">
                                                <i class="fas fa-caret-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    <div class="flex flex-row justify-between items-center">
                                        Time spent
                                        <div class="flex flex-col">
                                            <button class="hover:text-gray-500" @click="sortBytimeSpent('up')">
                                                <i class="fas fa-caret-up"></i>
                                            </button>
                                            <button class="hover:text-gray-500" @click="sortBytimeSpent('down')">
                                                <i class="fas fa-caret-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </th>
                                
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    <div class="flex flex-row justify-between items-center">
                                        Completed
                                        <div class="flex flex-col">
                                            <button class="hover:text-gray-500" @click="sortByCompleted('up')">
                                                <i class="fas fa-caret-up"></i>
                                            </button>
                                            <button class="hover:text-gray-500" @click="sortByCompleted('down')">
                                                <i class="fas fa-caret-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </th>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="task in filteredTask" :key="task.id">
                                    <td class="px-4 py-2 whitespace-nowrap border-r">{{ task.dispatch_id }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap border-r">{{ task.task }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap border-r capitalize">{{ task.timeframe }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap border-r">{{ task.minutesSpent }} min</td>
                                    <td class="px-4 py-2 whitespace-nowrap border-r">{{ parseDate(task.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { parseDate } from '../HelperFunctions/index';

export default {
    name: "RecurringTasks",
    data() {
        return {
            showDatalist: false,
            list: Array,
            selectedUser: "",
            selectedUserId: Number,
            userHistory: Array,
            showTable: false,
            searchTerm: "",
        }
    },
    created(){
        this.getUsers();
    },
    methods: {
        parseDate,
        setUserId(){
            const user = parseInt(this.selectedUser);
            this.selectedUserId = this.selectedUser;
            for(let i = 0; i < this.list.length; i++){
                if(user === this.list[i].id){
                    this.selectedUser = this.list[i].name;
                    break;
                }
            }
        },
        async getUsers(){
            try {
                const response = await axios.get(`${this.$store.state.url}user/users`);
                console.log(response.data);
                if(response.data.success){
                    this.list = response.data.data;
                    this.showDatalist = true;
                }
            } catch (error){
                console.error(error);
            }
        },
        async fetchHistoryForUser(){
            try {
                const response = await axios.get(`${this.$store.state.url}recurring/recurringHistory?user_id=${this.selectedUserId}`);
                console.log(response.data);
                if(response.data.success){
                    this.userHistory = response.data.data;
                    this.showTable = true;
                }
            } catch (error){
                console.error(error);
            }
        },
        sortByTimeframe(direction){
            if(direction === "up"){
                const newArr = this.userHistory.slice(0);
                this.userHistory = newArr.sort(function(a, b) {
                    console.log(a.timeframe);
                    return (a.timeframe > b.timeframe) - (a.timeframe < b.timeframe);
                });
            }
            if(direction === "down"){
                const newArr = this.userHistory.slice(0);
                this.userHistory = newArr.sort(function(a, b) {
                    return (b.timeframe > a.timeframe) - (b.timeframe < a.timeframe);
                });
            }
        },
        sortBytimeSpent(direction){
            if(direction === "up"){
                const newArr = this.userHistory.slice(0);
                this.userHistory = newArr.sort(function(a, b) {
                    return b.minutesSpent - a.minutesSpent;
                });
            }
            if(direction === "down"){
                const newArr = this.userHistory.slice(0);
                this.userHistory = newArr.sort(function(a, b) {
                    return a.minutesSpent - b.minutesSpent;
                });
            }
        },
        sortByCompleted(direction){
            if(direction === "up"){
                const newArr = this.userHistory.slice(0);
                this.userHistory = newArr.sort(function(a, b){
                    let date1 = new Date(a.created_at);
                    let date2 = new Date(b.created_at);
                    return date1 - date2;
                });
            }
            if(direction === "down"){
                const newArr = this.userHistory.slice(0);
                this.userHistory = newArr.sort(function(a, b){
                    let date1 = new Date(b.created_at);
                    let date2 = new Date(a.created_at);
                    return date1 - date2;
                });
            }
        }
    },
    computed: {
        filteredTask(){
            if(this.showTable){
                const searchTerm = this.searchTerm.toString().toLowerCase();
                return this.userHistory.filter(element => {
                    const task = element.task.toString().toLowerCase();
                    return task.includes(searchTerm);
                });
            }else{
                return false;
            }
        }
    }
}
</script>

<style>

</style>