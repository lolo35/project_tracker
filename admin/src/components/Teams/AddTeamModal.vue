<template>
    <div class="absolute top-0 left-0 right-0 bottom-0 bg-black opacity-50 z-10"></div>
    <form
        @submit="addTeam()" 
        class="absolute flex flex-col w-11/12 sm:w-5/6 lg:w-1/2 max-w-2xl mx-auto rounded-lg border border-gray-300 shadow-xl z-20 left-1/2 transform -translate-x-1/2">
        <div class="flex flex-row justify-between p-6 bg-white border-b border-gray-200 rounded-tl-lg rounded-tr-lg">
            <p class="font-semibold text-gray-800">Add a team</p>
            <button @click="$store.dispatch('teams/setShowModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="flex flex-col px-6 py-5 bg-gray-50">
            <p class="mb-2 font-semibold text-gray-700">Team name</p>
            <input type="text" v-model="teamName" class="px-5 py-3 mb-5 bg-white border border-gray-200 rounded shadow-sm" required>
            <div class="flex flex-col sm:flex-row items-center mb-5 sm:space-x-5">
                <div class="w-full sm:w-1/2">
                    <div class="w-full flex justify-between">
                        <p class="mb-2 font-semibold text-gray-700">Team member</p>
                        <button type="button" @click="$store.dispatch('teams/setTeamLeader', 0)" :class="{'text-blue-500': $store.state.teams.teamMembers[0] === $store.state.teams.teamLeader}">
                            <i class="fas fa-crown"></i>
                        </button>
                    </div>
                    <select id="" v-model="teamMember[0]" class="w-full p-5 bg-white border border-gray-200 rounded shadow-sm appearance-none">
                        <option value="" selected></option>
                        <option :value="user.name" v-for="user in teams.users" :key="user.id">{{ user.name }}</option>
                    </select>
                </div>
                <div class="w-full sm:w-1/2 mt-2 sm:mt-0">
                    <div class="w-full flex justify-between">
                        <p class="mb-2 font-semibold text-gray-700">Team member</p>
                        <button type="button" @click="$store.dispatch('teams/setTeamLeader', 1)" :class="{'text-blue-500': $store.state.teams.teamMembers[1] === $store.state.teams.teamLeader}">
                            <i class="fas fa-crown"></i>
                        </button>
                    </div>
                    <select id="" v-model="teamMember[1]" class="w-full p-5 bg-white border border-gray-200 rounded shadow-sm appearance-none">
                        <option value="" selected></option>
                        <option :value="user.name" v-for="user in teams.users" :key="user.id">{{ user.name }}</option>
                    </select>
                </div>
            </div>
            <hr />
            <!-- <div class="flex items-center mt-5 mb-3 space-x-4">
                <input type="text">
            </div> -->
            <div class="flex flex-row items-center justify-between p-5 bg-white border border-gray-200 rounded shadow-sm mt-5" v-if="$store.state.teams.teamLeader.length > 0">
                <div class="flex flex-row items-center">
                    <img :src="`${$store.state.url}images/no-photo.jpg`" class="w-10 h-10 mr-3 rounded-full" alt="">
                    <div class="flex flex-col">
                        <p class="font-semibold text-gray-800">{{ $store.state.teams.teamLeader }}</p>
                        <p class="text-gray-400">Team leader</p>
                    </div>
                </div>
                <button class="font-semibold text-red-400" @click="$store.dispatch('teams/removeTeamLeader')">Remove</button>
            </div>
            <div v-else class="flex flex-row items-center justify-between p-5 bg-white border border-gray-200 rounded shadow-sm mt-5">
                <div class="flex flex-row items-center">
                    <img :src="`${$store.state.url}images/no-photo.jpg`" class="w-10 h-10 mr-3 rounded-full" alt="">
                    <div class="flex flex-col">
                        <p class="font-semibold text-red-500">Please choose a team leader</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-row items-center justify-between p-5 bg-white border-t border-gray-200 rounded-bl-lg rounded-br-lg">
            <button class="font-semibold text-gray-600" @click="$store.dispatch('teams/setShowModal')">Cancel</button>
            <button type="submit" class="px-4 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-700 rounded">Add</button>
        </div>
    </form>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';
export default {
    name: "AddTeamModal",
    created(){
        this.getUsers();
    },
    methods: {
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
        async addTeam(){
            event.preventDefault();
            let formData = new FormData();
            formData.append('team', this.$store.state.teams.teamName);
            formData.append('leader', this.$store.state.teams.teamLeader);
            formData.append('members', this.$store.state.teams.teamMembers);
            try {
                await axios.post(`${this.$store.state.url}teams/add`, formData, {
                    headers: {
                        'Content-type': 'application/x-www-form-urlencoded'
                    }
                }).then(response => {
                    console.log(response.data);
                });
            }catch(error){
                console.error(error);
            }
        }
    },
    computed: {
        teamName: {
            get(){
                return this.$store.state.teams.teamName;
            },
            set(value){
                this.$store.dispatch('teams/setTeamName', value);
            }
        },
        teamMember: {
            get(){
                return this.$store.state.teams.teamMembers;
            },
            set(value){
                this.$store.dispatch('teams/setTeamMembers', value);
            }
        },
        ...mapState(['teams']),
    }
}
</script>

<style>

</style>