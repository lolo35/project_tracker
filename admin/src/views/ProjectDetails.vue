<template>
    <div class="flex px-3 py-1 items-center">
        <router-link to="/projects" class="bg-gray-50 px-3 py-1 rounded border border-gray-50 hover:bg-gray-300">
            <i class="fas fa-arrow-left"></i>
            Back
        </router-link>
        
    </div>
    <div class="flex flex-row w-full justify-between px-6 py-3" v-if="showDetails">
        <div class="flex flex-row w-full bg-gray-700 px-6 py-3 rounded justify-between">
            <div class="flex flex-col">
                <p class="font-semibold text-white">{{ projects.details.details[0].name }}</p>
                <div class="flex flex-row space-x-2 items-center">
                    <p class="text-sm text-gray-200">Priority: </p>
                    <button v-html="userFriendlyPriority" @click="showChangePriority = !showChangePriority"></button>
                    <button class="text-gray-500 text-xs bg-gray-200 rounded-full w-4 h-4 hover:bg-gray-300" title="Priority history..." @click="showPriorityHistory = !showPriorityHistory">
                        <i class="fas fa-question"></i>
                    </button>
                </div>
            </div>
            <div class="flex flex-row">
                <input v-model="searchTerm" type="text" class="px-3 py-1 rounded-l outline-none" placeholder="Search...">
                <button class="bg-gray-300 px-3 py-1 rounded-r hover:bg-gray-500">
                    <i class="fas fa-search text-white"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="flex flex-row w-full px-6 py-3" v-if="showChangePriority">
        <form @submit="changePriority()" class="flex flex-row w-full bg-gray-700 px-6 py-3 rounded space-x-2 items-end">
            <div class="flex flex-col">
                <label for="priority" class="text-sm font-semibold text-gray-200">Priority</label>
                <select id="priority" v-model="newPriority" class="w-full p-1 bg-white border border-gray-200 rounded shadow-sm appearance-none">
                    <option value="0">Highest</option>
                    <option value="1">Medium</option>
                    <option value="2">Low</option>
                    <option value="3">Lowest</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label for="reason" class="text-sm font-semibold text-gray-200">Reason</label>
                <input type="text" class="w-full p-1 bg-white border border-gray-200 rounded shadow-sm appearance-none" id="reason" v-model="reason" required>
            </div>
            <button type="submit" class="text-white bg-blue-500 hover:bg-blue-600 rounded px-3 py-1">Change</button>
        </form>
    </div>
    <priority-history v-if="showPriorityHistory" :project_id="parseInt(id)"></priority-history>
    <Details v-if="showDetails"></Details>
</template>

<script>
import Details from '../components/Projects/Details.vue';
import { mapState } from 'vuex';
import axios from 'axios';
import PriorityHistory from '../components/Projects/PriorityHistory.vue';

export default {
    name: "ProjectDetails",
    data(){
        return {
            showDetails: false,
            priority: Number,
            showChangePriority: false,
            reason: "",
            newPriority: "",
            showPriorityHistory: false,
        }
    },
    components: {
        Details,
        PriorityHistory,
    },
    created(){
        this.fetchDetails();
    },
    props: {
        id: String,
    },
    methods: {
        async changePriority(){
            event.preventDefault();
            let formData = new FormData();
            formData.append('reason', this.reason);
            formData.append('newPriority', this.newPriority);
            formData.append('project_id', this.id);

            try {
                const response = await axios.post(`${this.$store.state.url}projects/changePriority`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                console.log(response.data);
                if(response.data.success){
                    this.priority = parseInt(this.newPriority);
                    this.reason = "";
                    this.newPriority = "";
                    this.showChangePriority = false;
                }
            } catch (error){
                console.error(error);
            }
        },
        async fetchDetails(){
            try {
                const response = await axios.get(`${this.$store.state.url}projects/projectDetails?projectId=${this.id}`);
                console.log(response.data);
                if(response.data.success){
                    this.showDetails = true;
                    this.priority = parseInt(response.data.data[0].priority);
                    this.$store.dispatch('projects/details/setDetails', response.data.data);
                }
            } catch(error){
                console.error(error);
            }
        }
    },
    computed: {
        ...mapState(['projects']),
        searchTerm: {
            get(){
                return this.projects.details.searchTerm;
            },
            set(value){
                this.$store.dispatch('projects/details/setSearchTerm', value);
            }
        },
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