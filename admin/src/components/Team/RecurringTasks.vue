<template>
    <div class="flex flex-col">
        <div class="flex flex-row space-x-1">
            <div class="flex flex-row space-x-1 items-center">
                <label for="daily" class="text-xs text-gray-300">Daily</label>
                <input type="radio" value="daily" id="daily" checked v-model="recurring"  @click="establishTimeFrame('daily')">
            </div>
            <div class="flex flex-row space-x-1 items-center">
                <label for="weekly" class="text-xs text-gray-300">Weekly</label>
                <input type="radio" value="weekly" id="weekly" v-model="recurring" @click="establishTimeFrame('weekly')">
            </div>
            <div class="flex flex-row space-x-1 items-center">
                <label for="monthly" class="text-xs text-gray-300">Monthly</label>
                <input type="radio" value="monthly" id="monthly" v-model="recurring" @click="establishTimeFrame('monthly')">
            </div>
            <div class="flex flex-row space-x-1 items-center">
                <label for="quarterly" class="text-xs text-gray-300">Quarterly</label>
                <input type="radio" value="quarterly" id="quarterly" v-model="recurring" @click="establishTimeFrame('quarterly')">
            </div>
            <div class="flex flex-row space-x-1 items-center">
                <label for="yearly" class="text-xs text-gray-300">Yearly</label>
                <input type="radio" value="yearly" id="yearly" v-model="recurring" @click="establishTimeFrame('yearly')">
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col w-full">
                <div class="flex items-center">
                    <label for="addtask" class="text-white font-semibold">Add task</label>
                </div>
                <form class="flex items-center px-4 py-2 relative" @submit="addTask()">
                    <input type="text" id="addtask" v-model="task" class="w-full rounded px-2 py-1 outline-none" required>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-r-md">
                        <i class="fas fa-check"></i>
                    </button>
                    <div class="w-6 h-6 left-5 rounded-full bg-green-500 flex items-center justify-center absolute" v-if="taskAdded">
                        <i class="fas fa-check text-white"></i>
                    </div>
                </form>
            </div>
        </div>
        <weekly-input v-if="weekly"></weekly-input>
    </div>
</template>

<script>
import WeeklyInput from './WeeklyInput.vue';
import axios from 'axios';

export default {
    name: "RecurringTasks",
    data(){
        return {
            recurring: "daily",
            daily: true,
            weekly: false,
            monthly: false,
            quarterly: false,
            yearly: false,
            task: "",
            taskAdded: false,
        }
    },
    components: {
        WeeklyInput,
    },
    props: {
        userId: Number,
    },
    emits: ['updateTask'],
    methods: {
        establishTimeFrame(timeFrame){
            switch(timeFrame){
                case 'daily': this.daily = true; this.weekly = false; this.monthly = false; this.quarterly = false; this.yearly = false; break;
                case 'weekly': this.daily = false; this.weekly = true; this.monthly = false; this.quarterly = false; this.yearly = false; break;
                case 'monthly': this.daily = false; this.weekly = false; this.monthly = true; this.quarterly = false; this.yearly = false; break;
                case 'quarterly': this.daily = false; this.weekly = false; this.monthly = false; this.quarterly = true; this.yearly = false; break;
                case 'yearly': this.daily = false; this.weekly = false; this.monthly = false; this.quarterly = false; this.yearly = true; break;
            }
        },
        async addTask(){
            event.preventDefault();
            //console.log(this.task);
            let formData = new FormData();
            formData.append('userId', this.userId);
            formData.append('occurence', this.recurring);
            formData.append('task', this.task);
            
            try {
                const response = await axios.post(`${this.$store.state.url}recurring/addTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                console.log(response.data);
                if(response.data.success){
                    this.handleSuccessfullTask();
                    this.$emit('updateTask', response.data.data);
                }
            } catch(error){
                console.error(error);
            }

        },

        handleSuccessfullTask(){
            this.task = "";
            this.taskAdded = true;
            setTimeout(() => {
                this.taskAdded = false;
            }, 5000);
        }
    }
}
</script>

<style>

</style>