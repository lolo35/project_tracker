<template>
    <header class="w-full bg-gray-800 p-4 flex justify-between items-center">
        <nav class="flex items-center">
            <img class="w-7 h-7" :src="`${url}images/pngwing.com.png`" alt="">
            <div class="text-white text-xs hidden sm:block ml-2">
                <router-link to="/dashboard" class="bg-gray-900 hover:bg-gray-700 p-2 rounded cursor-pointer">Dashboard</router-link>
                <router-link to="/projects" class="bg-gray-900 hover:bg-gray-700 p-2 rounded cursor-pointer ml-1">Projects</router-link>
                <router-link to="/issues" class="bg-gray-900 hover:bg-gray-700 p-2 rounded cursor-pointer ml-1">Issues</router-link>
                <router-link to="/discustionboard"  class="bg-gray-900 hover:bg-gray-700 p-2 rounded cursor-pointer ml-1">Boards</router-link>
            </div>
        </nav>
        <router-link  to="/userSettings" class="w-8 h-8 cursor-pointer">
            <img class="rounded-full" :src="`${url}images/no-photo.jpg`" alt="user" :title="$store.state.user.name">
        </router-link>
    </header>
</template>

<script>
import { mapState } from 'vuex';
export default {
    name: "Navbar",
    computed: {
        ...mapState(['url']),
    }
}
</script>

<style>

</style>