<template>
  <div class="flex flex-col">
        <toolbar v-if="$store.state.team.team"></toolbar>
        <team-name v-if="$store.state.team.team"></team-name>
        <members v-if="$store.state.team.team"></members>
        <div class="flex flex-row" v-if="!$store.state.team.team">
            <div class="bg-yellow-300 text-yellow-800 w-full rounded text-center px-6 py-3 border-yellow-400 border shadow">
                Looks like you have no team. 
                <button @click="$store.dispatch('teams/setShowModal')" class="italic hover:underline">Would you like to create one?</button>
            </div>
        </div>
  </div>
  <add-team-modal v-if="$store.state.teams.showModal"></add-team-modal>
</template>

<script>
import TeamName from '../components/Team/TeamName.vue';
import AddTeamModal from '../components/Teams/AddTeamModal.vue';
import Members from '../components/Team/Members.vue';
import Toolbar from '../components/Team/Toolbar.vue';
import axios from 'axios';

export default {
    name: "Team",
    data(){
        return {
            
        }
    },
    components: {
        TeamName,
        AddTeamModal,
        Members,
        Toolbar,
    },
    created(){
        this.getTeam();
    },
    methods: {
        async getTeam(){
            const response = await axios.get(`${this.$store.state.url}teams/team?userId=${this.$store.state.user.userId}`);
            console.log(response.data);
            if(!response.data.success){
                if(response.data.error === "noTeam"){
                    this.$store.dispatch('team/setTeam', false);
                }
            }else{
                this.$store.dispatch('team/setTeamComp', response.data.data);
                this.$store.dispatch('team/setTeam', true);
            }
        }
    }
}
</script>

<style>

</style>