<template>
    <div class="flex flex-col bg-gray-800 rounded p-2 mb-2" v-if="showTopic">
        <div class="flex flex-row">
            <router-link to="/discustionboard">
                <h3 class="text-white text-sm">
                    <i class="fas fa-home"></i>
                    <span class="underline ml-1">Discution board</span>
                </h3>
            </router-link>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-row w-3/4">
                <h3 class="text-white font-semibold">{{ topic[0].topic }}</h3>
            </div>
            <div class="flex flex-row w-1/4 items-center px-4 justify-between">
                <h3 class="text-white">Replies</h3>
                <h3 class="text-white">Views</h3>
                <h3 class="text-white mr-5">Last reply</h3>
            </div>
        </div>
        <div v-if="showPosts">
            <posts
                v-for="(post, index) in posts"
                :key="post.id"
                :postData="post"
                :index="index"
            ></posts>
        </div>
        <!-- <add-topic></add-topic> -->
        <div class="flex flex-row mt-5">
            <router-link :to="`addTopic/${topic_id}`" class="bg-gray-300 rounded px-3 py-1 hover:bg-gray-400 text-sm font-semibold">
                <i class="fas fa-pencil-alt"></i>
                Post new topic
            </router-link>
        </div>
    </div>
</template>

<script>
import Posts from '../components/DiscutionBoard/Posts.vue';
// import AddTopic from '../components/DiscutionBoard/AddTopic.vue';

import axios from 'axios';

export default {
    name: "Topics",
    data(){
        return {
            showTopic: false,
            showPosts: false,
            topic: Array,
            posts: Array,
        }
    },
    props: {
        topic_id: String,
    },
    components: {
        Posts,
        // AddTopic,
    },
    created(){
        this.fetchTopic();
        this.fetchPosts();
    },
    methods: {
        async fetchTopic(){
            try {
                const response = await axios.get(`${this.$store.state.url}forum/topic?topic_id=${this.topic_id}`);
                console.log(response.data);
                if(response.data.success){
                    this.topic = response.data.data;
                    this.showTopic = true;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async fetchPosts(){
            try {
                const response = await axios.get(`${this.$store.state.url}forum/topicPosts?topic_id=${this.topic_id}`);
                console.log(response.data);
                if(response.data.success){
                    this.posts = response.data.data;
                    this.showPosts = true;
                }
            } catch(error) {
                console.error(error);
            }
        }
    }
}
</script>

<style>

</style>