<template>
    <header class="w-full bg-gray-800 p-4 flex justify-between items-center">
        <nav class="flex items-center">
            <img class="w-7 h-7" :src="`${url}images/pngwing.com.png`" alt="">
            <div class="text-white text-xs hidden sm:block ml-2">
                <a href="" class="bg-gray-900 hover:bg-gray-700 p-2 rounded cursor-pointer">Dashboard</a>
                <router-link to="/projects" class="bg-gray-900 hover:bg-gray-700 p-2 rounded cursor-pointer ml-1">Projects</router-link>
                <a href="" class="bg-gray-900 hover:bg-gray-700 p-2 rounded cursor-pointer ml-1">Issues</a>
                <a href="" class="bg-gray-900 hover:bg-gray-700 p-2 rounded cursor-pointer ml-1">Boards</a>
            </div>
        </nav>
        <div class="w-8 h-8 cursor-pointer">
            <img class="rounded-full" :src="`${url}images/no-photo.jpg`" alt="user" :title="$store.state.user.name">
        </div>
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