<template>
    <div class="flex flex-row px-6 py-3 bg-gray-700 rounded mb-2 items-center justify-between">
        <div class="flex flex-row items-center">
            <button class="w-8 h-8 rounded-full bg-gray-900 hover:bg-gray-600" @click="$store.dispatch('projects/toggleShowProjectModal')">
                <i class="fas fa-plus-circle text-white"></i>
            </button>
        </div>
        <div class="flex flex-row items-center">
            <input type="text" v-model="searchTerm" placeholder="Search..." class="rounded-l px-2 py-1">
            <button class="bg-gray-400 px-2 py-1 rounded-r">
                <i class="fas fa-search text-white"></i>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Toolbar",
    computed: {
        searchTerm: {
            get(){
                return this.$store.state.projects.searchTerm;
            },
            set(value){
                this.$store.dispatch('projects/setSearchTerm', value);
            }
        }
    }
}
</script>

<style>

</style>