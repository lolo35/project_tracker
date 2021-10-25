<template>
    <div class="flex flex-row bg-gray-700 p-2 rounded mb-2 items-center space-x-1">
        <router-link to="/discustionboard">
            <h3 class="text-white text-sm">
                <i class="fas fa-home"></i>
                <span class="underline ml-1">Discution board</span>
            </h3>
        </router-link>
        <p class="text-white text-sm">&lt;</p>
        <router-link :to="`/db_topic/${postData[0].topic_id}`" v-if="showPosts">
            <h3 class="text-white text-sm">
                <i class="fas fa-align-left"></i>
                <span class="underline ml-1">{{ postData[0].name }}</span>
            </h3>
        </router-link>
    </div>
    <div class="flex flex-col bg-gray-700 rounded-t p-2" v-if="showPosts">
        <h3 class="text-lg font-bold text-white">{{ postData[0].name }}</h3>
    </div>
    <div class="flex flex-col bg-gray-700 p-2 rounded-b mb-1" v-for="post in postDiscution" :key="post.id">
        <div class="flex flex-row space-x-2">
            <div class="flex flex-col w-1/12">
                <img :src="`${this.$store.state.url}images/placeholder_user.png`" alt="user_img">
                <h3 class="text-white text-sm font-semibold text-center">
                    {{ post.name }}
                </h3>
            </div>
            <div class="flex flex-col w-11/12 bg-gray-200 py-2 px-6" v-html="post.text"></div>
        </div>
    </div>
    <div class="flex flex-col bg-gray-700 p-2 rounded mb-1">
        <div class="flex flex-row p-1">
            <button type="button" class="bg-gray-300 hover:bg-gray-200 px-3 py-1 rounded font-semibold text-sm" @click="showReply = true">
                <i class="fas fa-reply text-red-300"></i>
                Post reply
            </button>
        </div>
        <add-reply v-if="showReply" :topic_id="parseInt(postData[0].topic_id)" :post_id="parseInt(topic_id)" v-on:hideReply="showReply = false" v-on:replyData="addReply"></add-reply>
    </div>
</template>

<script>
import axios from 'axios';
import AddReply from '../../components/DiscutionBoard/Topic/AddReply.vue';

export default {
    name: "Topic",
    data(){
        return {
            postData: Array,
            postDiscution: Array,
            showPosts: false,
            showReply: false,
        }
    },
    props: {
        topic_id: String,
    },
    components: {
        AddReply,
    },
    created(){
        this.topicDiscution();
        this.addViews();
    },
    methods: {
        addReply(payload){
            this.postDiscution.push(payload[0]);
        },
        async topicDiscution(){
            try {
                const response = await axios.get(`${this.$store.state.url}forum/topicDiscution?topic_id=${this.topic_id}`);
                console.log(response.data);
                if(response.data.success){
                    this.postData = response.data.postData;
                    this.postDiscution = response.data.postDiscution;
                    this.showPosts = true;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async addViews(){
            console.log('is this triggering??');
            
            try {
                let formData = new FormData();
                formData.append('topic_id', this.topic_id);
                const response = await axios.post(`${this.$store.state.url}forum/addView`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                console.log(response.data);
            } catch (error){
                console.error(error);
            }
        }
    }
}
</script>

<style>

</style>