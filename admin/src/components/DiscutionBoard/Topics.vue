<template>
    <div v-if="showTopics">
        <router-link :to="`/db_topic/${data.id}`" class="flex flex-row items-center justify-between bg-gray-600 p-2 cursor-pointer hover:bg-gray-500 border-b" v-for="data in topics" :key="data.id">
            <div class="flex flex-col w-3/4">
                <div class="flex flex-row items-center space-x-2 w-full">
                    <div class="flex flex-row">
                        <div class="rounded-full p-2 bg-gray-50 h-10 w-10 flex items-center justify-center">
                            <i class="fas fa-align-left text-xl"></i>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex flex-row items-center">
                            <h3 class="text-white">{{ data.topic }}</h3>
                        </div>
                        <div class="flex-flex-row items-center">
                            <p class="text-sm text-gray-200">{{ data.description }}</p>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="flex flex-row w-1/4 items-center justify-between px-4">
                <h3 class="text-white">443</h3>
                <h3 class="text-white">660</h3>
                <h3 class="text-white mr-5">Test post</h3>
            </div>
        </router-link>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: "Topics",
    data(){
        return {
            showTopics: false,
            topics: Array,
        }
    },
    props: {
        category_id: Number,
    },
    created(){
        this.fetchTopics();
    },
    methods: {
        async fetchTopics(){
            try {
                const response = await axios.get(`${this.$store.state.url}forum/topics?category_id=${this.category_id}`);
                console.log(response.data);
                if(response.data.success){
                    //this.$store.dispatch('forumTopics/setTopics', response.data.data);
                    this.topics = response.data.data;
                    this.showTopics = true;
                }
            } catch (error){
                console.error(error);
            }
        }
    }
}
</script>

<style>

</style>