<template>
    <toolbar></toolbar>
    <no-project-warning v-if="$store.state.projects.projects.length == 0"></no-project-warning>
    <add-project-modal v-if="$store.state.projects.showAddProjectModal"></add-project-modal>
    <div class="flex flex-row flex-wrap space-x-1">
        <project-card 
            v-for="project in filteredProjects" 
            :project="project.name" 
            :description="project.description" 
            :key="project.id"
            :owner="project.owner"
        ></project-card>
    </div>
</template>

<script>
import axios from 'axios';
import { useStore } from 'vuex';
import NoProjectWarning from '../components/Projects/NoProjectWarning.vue';
import AddProjectModal from '../components/Projects/AddProjectModal.vue';
import ProjectCard from '../components/Projects/ProjectCard.vue';
import Toolbar from '../components/Projects/Toolbar.vue';
import { computed } from 'vue';

export default {
    name: "Projects",
    components: {
        NoProjectWarning,
        AddProjectModal,
        ProjectCard,
        Toolbar,
    },
    setup() {
        const store = useStore();
        const projects = async () => {
            try {
                const response = await axios.get(`${store.state.url}projects/`);
                store.dispatch('projects/setProjects', response.data.data);
                return response.data;
            } catch (error){
                console.error(error);
            }
        }

        projects();
        const filteredProjects = computed(() => {
            return store.getters['projects/filteredProjects'];
        })
        return { filteredProjects }
    }
}
</script>

<style>

</style>