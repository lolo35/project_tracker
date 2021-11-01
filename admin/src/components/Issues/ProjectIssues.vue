<template>
    <div class="flex flex-col">
        <div class="flex flex-row mb-1">
            <router-link to="/issues" class="bg-gray-50 px-3 py-1 rounded border border-gray-50 hover:bg-gray-300">
                <i class="fas fa-arrow-left"></i>
                Back
            </router-link>
        </div>
        <div class="flex flex-col bg-gray-700 px-6 py-3 rounded-t" v-if="showData">
            <div class="flex flex-row justify-between items-center">
                <h3 class="text-lg text-white font-bold">{{ projectData[0].name }} Issues</h3>
                <button class="w-8 h-8 rounded-full bg-gray-900 hover:bg-gray-600" @click="showModal = !showModal">
                    <i class="fas fa-plus-circle text-white"></i>
                </button>
            </div>
            <p class="text-xs text-gray-400 font-semibold">{{ projectData[0].description }}</p>
        </div>
        <div v-if="showIssues">
            <individual-issues
                v-for="(issue, index) in issues"
                v-on:issueClosed="closeIssue"
                :key="issue.id"
                :issue="issue.issue"
                :description="issue.description"
                :status="parseInt(issue.status)"
                :opened_by="issue.open_name"
                :closed_by="issue.close_name"
                :id="parseInt(issue.id)"
                :index="index"
            ></individual-issues>
        </div>
    </div>
    <modal 
        v-on:closeModal="showModal = false" 
        v-if="showModal"
        :project_id="parseInt(project_id)"
        v-on:issueAdded="addIssue"
    ></modal>
</template>

<script>
import axios from 'axios';
import Modal from './Modal.vue';
import IndividualIssues from './IndividualIssues.vue';

export default {
    name: "ProjectIssues",
    data(){
        return {
            showData: false,
            projectData: Array,
            showModal: false,
            issues: [],
            showIssues: false,
        }
    },
    props: {
        project_id: String,
    },
    components: {
        Modal,
        IndividualIssues,
    },
    created(){
        this.fetchProjectDetails();
        this.fetchIssues();
    },
    methods: {
        async fetchProjectDetails(){
            try {
                const response = await axios.get(`${this.$store.state.url}projects/projectById?project_id=${this.project_id}`);
                console.log(response.data);
                if(response.data.success){
                    this.projectData = response.data.data;
                    this.showData = true;
                }
            } catch (error){
                console.error(error);
            }
        },
        addIssue(payload){
            this.issues.push(payload[0]);
            this.showIssues = true;
        },
        async fetchIssues(){
            try {
                const response = await axios.get(`${this.$store.state.url}issues/issuesForProject?project_id=${this.project_id}`);
                console.log(response.data);
                if(response.data.success){
                    this.issues = response.data.data;
                    this.showIssues = true;
                }
            } catch (error){
                console.error(error);
            }
        },
        closeIssue(payload){
            this.issues[payload.index].status = 0;
        }
    }
}
</script>

<style>

</style>