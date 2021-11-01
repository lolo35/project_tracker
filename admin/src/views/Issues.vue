<template>
    <div class="flex flex-col">
        <div class="flex flex-row bg-gray-700 px-6 py-3 rounded-t">
            <h3 class="text-lg text-white font-bold">Issues</h3>
        </div>
        <div v-if="showProjects">
            <general-issue
                v-for="project in projects"
                :key="project.id"
                :id="parseInt(project.id)"
                :project="project.name"
                :description="project.description"
            ></general-issue>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import GeneralIssue from '../components/Issues/GeneralIssue.vue';

export default {
    name: "Issues",
    data() {
        return {
            projects: Array,
            showProjects: false,
        }
    },
    components: {
        GeneralIssue,
    },
    created(){
        this.fetchProjects();
    },
    methods: {
        async fetchProjects(){
            try {
                const response = await axios.get(`${this.$store.state.url}projects/`);
                if(response.data.success){
                    this.projects = response.data.data;
                    this.showProjects = true;
                }else{
                    if(response.data.error.errorInfo){
                        this.fetchProjects();
                    }
                }
            } catch (error){
                console.error(error);
            }
        }
    }
}
</script>

<style>

</style>