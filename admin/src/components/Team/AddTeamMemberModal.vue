<template>
    <div class="absolute top-0 left-0 right-0 bottom-0 bg-black opacity-50 z-10"></div>
    <div class="absolute top-0 left-0 bottom-0 right-0 z-20">
        <form
            @submit="addMember()" 
            class="flex flex-col w-11/12 sm:w-5/6 lg:w-1/2 max-w-2xl mx-auto rounded-lg border border-gray-300 shadow-xl left-1/2 transform translate-y-1/2">
            <div class="flex flex-row justify-between p-6 bg-white border-b border-gray-200 rounded-tl-lg rounded-tr-lg">
                <p class="font-semibold text-gray-800">Add a team member</p>
                <button @click="$store.dispatch('team/setShowModal', false)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex flex-col px-6 py-5 bg-gray-50 max-h-102 overflow-y-scroll">
                <div class="flex flex-col sm:flex-row items-center mb-5 flex-wrap justify-between">
                    <div class="w-full">
                        <div class="w-full flex justify-between">
                            <p class="mb-2 font-semibold text-gray-700">Team member</p>
                        </div>
                        <select id="" v-model="teamMember" class="w-full p-5 bg-white border border-gray-200 rounded shadow-sm appearance-none">
                            <option value="" selected></option>
                            <option :value="user.id" v-for="user in teams.users" :key="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex flex-row items-center justify-between p-5 bg-white border-t border-gray-200 rounded-bl-lg rounded-br-lg">
                <button class="font-semibold text-gray-600" @click="$store.dispatch('team/setShowModal', false)">Cancel</button>
                <button type="submit" class="px-4 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-700 rounded">Add</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';

export default {
    name: "AddTeamMemberModal",
    data(){
        return {
            teamMember: "",
        }
    },
    created(){
        this.getUsers();
    },
    methods: {
        async addMember(){
            event.preventDefault();

            let formData = new FormData();
            formData.append('userId', this.teamMember);
            formData.append('name', this.username);
            formData.append('teamId', this.$store.state.team.teamComp[0].teamId);
            formData.append('team', this.$store.state.team.teamComp[0].team);
            formData.append('leader', this.$store.state.team.teamComp[0].leader);
            formData.append('leaderId', this.$store.state.team.teamComp[0].leaderId);

            const response = await axios.post(`${this.$store.state.url}teams/addTeamMember`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});

            if(response.data.success){
                this.$store.dispatch('team/addTeamMember', response.data.data);
            }
        },
        async getUsers(){
            try{
                await axios.get(`${this.$store.state.url}user/users`).then(response => {
                    if(response.data.success){
                        this.$store.dispatch('teams/setUsers', response.data.data);
                    }
                });
            }catch(error){
                console.error(error);
            }
        },
    },
    computed: {
        ...mapState(['teams']),
        username(){
            let username = "";
            let id = parseInt(this.teamMember);
            for(let i = 0; i < this.$store.state.teams.users.length; i++){
                if(id === this.$store.state.teams.users[i].id ){
                    username = this.$store.state.teams.users[i].name;
                    break;
                }
            }

            return username;
        }
    }
}
</script>

<style>

</style>