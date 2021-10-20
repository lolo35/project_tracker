<template>
    <div v-if="showCategories">
        <div class="flex flex-col bg-gray-800 rounded p-2 mb-2" v-for="data in $store.state.forumCategories.categories" :key="data.id">
            <div class="flex flex-row items-center">
                <div class="flex flex-row w-3/4">
                    <h3 class="text-white">{{ data.category }}</h3>
                </div>
                <div class="flex flex-row justify-between items-center w-1/4 px-4">
                    <h3 class="text-white">Topics</h3>
                    <h3 class="text-white">Posts</h3>
                    <h3 class="text-white mr-5">Last post</h3>
                </div>
            </div>
            <topics :category_id="parseInt(data.id)"></topics>
        </div>
    </div>
</template>

<script>
import Topics from './Topics.vue';
import axios from 'axios';

export default {
    name: "Categories",
    data() {
        return {
            showCategories: false,
        }
    },
    components: {
        Topics,
    },
    created(){
        this.fetchCategories();
    },
    methods: {
        async fetchCategories(){
            try {
                const response = await axios.get(`${this.$store.state.url}forum/`);
                console.log(response.data);
                if(response.data.success){
                    this.$store.dispatch('forumCategories/setCategories', response.data.data);
                    this.showCategories = true;
                }
            } catch (error) {
                console.error(error);
            }
        }
    }
}
</script>

<style>

</style>