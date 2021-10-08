<template>
    <div class="flex px-3 py-1 items-center">
        <router-link to="/projects" class="bg-gray-50 px-3 py-1 rounded border border-gray-50 hover:bg-gray-300">
            <i class="fas fa-arrow-left"></i>
            Back
        </router-link>
        
    </div>
    <div class="flex flex-row w-full justify-between px-6 py-3" v-if="showDetails">
        <div class="flex flex-row w-full bg-gray-700 px-6 py-3 rounded justify-between">
            <p class="font-semibold text-white">{{ projects.details.details[0].name }}</p>
            <div class="flex flex-row">
                <input v-model="searchTerm" type="text" class="px-3 py-1 rounded-l outline-none" placeholder="Search...">
                <button class="bg-gray-300 px-3 py-1 rounded-r hover:bg-gray-500">
                    <i class="fas fa-search text-white"></i>
                </button>
            </div>
        </div>
    </div>
    <Details v-if="showDetails"></Details>
</template>

<script>
import Details from '../components/Projects/Details.vue';
import { mapState } from 'vuex';
import axios from 'axios';
export default {
    name: "ProjectDetails",
    data(){
        return {
            showDetails: false,   
        }
    },
    components: {
        Details,
    },
    created(){
        this.fetchDetails();
    },
    props: {
        id: String,
    },
    methods: {
        async fetchDetails(){
            try {
                const response = await axios.get(`${this.$store.state.url}projects/projectDetails?projectId=${this.id}`);
                console.log(response.data);
                if(response.data.success){
                    this.showDetails = true;
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
        }
    }
}
</script>

<style>

</style>