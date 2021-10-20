<template>
    <aside class="w-80 h-screen bg-gray shadow-md w-fulll hidden sm:block">
        <div class="flex flex-col justify-between h-screen p-4 bg-gray-800">
            <div class="text-sm">
                <router-link to="/teams">
                    <div 
                        class="p-5 rounded cursor-pointer hover:bg-gray-700 hover:text-blue-300"
                        :class="{'bg-gray-700 text-blue-300': teams, 'bg-gray-900 text-white': !teams}"
                        >Teams in Space
                    </div>
                </router-link>
                <div class="bg-gray-900 text-white p-2 rounded mt-2 cursor-pointer hover:bg-gray-700 hover:text-blue-300">Backlog</div>
                <router-link to="/main">
                    <div 
                        :class="{'bg-gray-700 text-blue-300': main, 'bg-gray-900 text-white': !main}"
                        class="p-2 rounded mt-2 cursor-pointer hover:bg-gray-700 hover:text-blue-300">
                        Ongoing
                    </div>
                </router-link>
                <router-link to="/recurring">
                    <div 
                        :class="{'bg-gray-700 text-blue-300': recurring, 'bg-gray-900 text-white': !recurring}"
                        class="p-2 rounded mt-2 cursor-pointer hover:bg-gray-700 hover:text-blue-300"
                        >
                        Recurring Tasks    
                    </div>    
                </router-link>                
                <div class="bg-gray-900 flex justify-between items-center text-white p-2 rounded mt-2 cursor-pointer hover:bg-gray-700 hover:text-blue-300">
                    <span>Reports</span>
                    <span class="w-4 h-4 bg-blue-600 rounded-full text-white text-center font-normal text-xs">5</span>
                </div>
                <div class="bg-gray-900 text-white p-2 rounded mt-2 cursor-pointer hover:bg-gray-700 hover:text-blue-300">Releases</div>
                <div class="bg-gray-900 text-white p-2 rounded mt-2 cursor-pointer hover:bg-gray-700 hover:text-blue-300">Components</div>
                <router-link to="/team">
                    <div 
                        class="p-2 rounded mt-2 cursor-pointer hover:bg-gray-700 hover:text-blue-300"
                        :class="{'bg-gray-900 text-white': !team, 'bg-gray-700 text-blue-300': team}"
                        >
                        My team
                    </div>
                </router-link>
                
            </div>
            <button class="flex p-3 text-white bg-red-500 rounded cursor-pointer text-center text-sm" @click="logout()">
                <div class="rounded inline-flex items-center">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="font-semibold">Logout</span>
                </div>
            </button>
        </div>
    </aside>
</template>

<script>
import localforage from 'localforage';

export default {
    name: "Sidebar",
    methods: {
        logout(){
            localforage.removeItem('autoliv_id');
            localforage.removeItem('email');
            localforage.removeItem('name');
            localforage.removeItem('userId');
            this.$store.dispatch('setIsLogged');
            this.$store.dispatch('user/setUserLoaded', false);
            this.$router.push('/login');
        }
    },
    computed: {
        main(){
            return this.$route.name === "Main" ? true : false;
        },
        teams(){
            return this.$route.name === "Teams" ? true : false;
        },
        team(){
            return this.$route.name === "Team" ? true : false;
        },
        recurring(){
            return this.$route.name === "Recurring" ? true : false;
        }
    }
}
</script>

<style>

</style>