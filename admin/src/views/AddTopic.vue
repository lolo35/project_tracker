<template>
    <form class="flex flex-col bg-gray-700 rounded p-2" @submit="addTopic()">
        <div class="flex flex-row mb-5">
            <h3 class="text-white font-semibold text-xl">
                <i class="fas fa-pencil-alt"></i>
                Add new topic
            </h3>
        </div>
        <div class="flex flex-col mb-2 w-1/4">
            <label for="subject" class="text-white font-semibold">
                <i class="fas fa-question"></i>
                Subject
            </label>
            <input type="text" v-model="name" class="w-full p-2 bg-white border border-gray-200 rounded shadow-sm appearance-none" required>
            <p class="text-sm text-gray-300">What is your topic about?</p>
        </div>

        <div class="flex flex-col w-1/4">
            <label for="description" class="text-white font-semibold">
                <i class="fas fa-align-left"></i>
                Description
            </label>
            <input type="text" v-model="description" class="w-full p-2 bg-white border border-gray-200 rounded shadow-sm appearance-none" required>
            <p class="text-sm text-gray-300">How would you describe your topic?</p>
        </div>
        <div class="flex flex-col mb-2">
            <label for="description" class="text-white font-semibold">
                <i class="far fa-comment-dots"></i>                
            </label>
            <div class="flex flex-row bg-gray-300 rounded-t p-2 space-x-2">
                <button type="button" class="" @click="format('bold')">
                    <i class="fas fa-bold"></i>
                </button>
                <button type="button" @click="format('italic')">
                    <i class="fas fa-italic"></i>
                </button>
                <button type="button" @click="format('insertunorderedlist')">
                    <i class="fas fa-list"></i>
                </button>
            </div>
            <div class="w-full px-6 bg-white border border-gray-200 rounded-b shadow-sm appearance-none h-60" ref="text" contenteditable="true"></div>
        </div>
        <div class="flex flex-row-reverse space-x-2">
            <button type="submit" class="text-white bg-blue-500 p-2 rounded hover:bg-blue-600 ml-2">Add topic</button>
            <router-link :to="`/db_topic/${topic_id}`" class="text-white p-2 rounded bg-gray-400 hover:bg-gray-500">Cancel</router-link>
        </div>
    </form>
</template>

<script>
import axios from 'axios';

export default {
    name: "AddTopic",
    data(){
        return {
            name: "",
            description: "",
            categoryId: Number,
            bold: false,
            italic: false,
            list: false,
        }
    },
    props: {
        topic_id: String,
    },
    created(){
        this.fetchCategory();
    },
    methods: {
        format(command, value){
            switch(command){
                case 'bold': this.bold = !this.bold; break;
                case 'italic': this.italic = !this.italic; break;
                case 'insertunorderedlist': this.list = !this.list; break;
            }
            document.execCommand(command, false, value);
        },
        testFunc(){
            console.log('change');
        },
        async fetchCategory(){
            try {
                const response = await axios.get(`${this.$store.state.url}forum/topic?topic_id=${this.topic_id}`);
                console.log(response.data);
                if(response.data.success){
                    this.categoryId = parseInt(response.data.data[0]['category_id']);
                }
            } catch (error) {
                console.log(error);
            }
        },
        async addTopic(){
            event.preventDefault();
            let text = this.$refs.text.innerHTML;
            let formData = new FormData();
            formData.append('category_id', this.categoryId);
            formData.append('topic_id', this.topic_id);
            formData.append('name', this.name);
            formData.append('description', this.description);
            formData.append('text', text);
            formData.append('username', this.$store.state.user.name);
            formData.append('user_id', this.$store.state.user.userId);

            try {
                const response = await axios.post(`${this.$store.state.url}forum/addTopic`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                console.log(response.data);
                if(response.data.success){
                    this.$router.push(`/db_topic/${this.topic_id}`);
                }
            } catch (error) {
                console.error(error);
            }
        }
    }
}
</script>

<style>
    li {
        list-style-type: disc;
    }
</style>