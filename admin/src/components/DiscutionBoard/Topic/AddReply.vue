<template>
    <div class="flex flex-col mb-2">
        <label for="description" class="text-white font-semibold">
            <i class="far fa-comment-dots"></i>                
        </label>
        <div class="flex flex-row bg-gray-300 rounded-t p-2 space-x-2">
            <button type="button" :class="{'text-red-300' : bold}" @click="format('bold')">
                <i class="fas fa-bold"></i>
            </button>
            <button type="button" :class="{'text-red-300' : italic}" @click="format('italic')">
                <i class="fas fa-italic"></i>
            </button>
            <button type="button" :class="{'text-red-300' : list}" @click="format('insertunorderedlist')">
                <i class="fas fa-list"></i>
            </button>
        </div>
        <div class="w-full px-6 bg-white border border-gray-200 rounded-b shadow-sm appearance-none h-60 mb-1.5" ref="text" contenteditable="true"></div>
        <div class="flex flex-row-reverse space-x-2">
            <button type="submit" class="text-white bg-blue-500 p-2 rounded hover:bg-blue-600 ml-2" @click="postReply()">Add reply</button>
            <button class="text-white p-2 rounded bg-gray-400 hover:bg-gray-500" @click="$emit('hideReply')">Cancel</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "AddReply",
    data() {
        return {
            bold: false,
            italic: false,
            list: false,
        }
    },
    props: {
        post_id: Number,
        topic_id: Number,
    },
    emits: ['hideReply', 'replyData'],
    methods: {
        format(command, value){
            switch(command){
                case 'bold': this.bold = !this.bold; break;
                case 'italic': this.italic = !this.italic; break;
                case 'insertunorderedlist': this.list = !this.list; break;
            }
            document.execCommand(command, false, value);
        },
        async postReply(){
            let formData = new FormData();
            formData.append('topic_id', this.topic_id);
            formData.append('post_id', this.post_id);
            formData.append('text', this.$refs.text.innerHTML);
            formData.append('user_id', this.$store.state.user.userId);

            try {
                const response = await axios.post(`${this.$store.state.url}forum/postReply`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                console.log(response.data);
                if(response.data.success){
                    this.$emit('replyData', response.data.reply);
                    this.$emit('hideReply');
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