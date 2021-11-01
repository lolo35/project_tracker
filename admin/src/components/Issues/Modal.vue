<template>
    <div class="absolute top-0 left-0 right-0 bottom-0 bg-black opacity-40 z-10"></div>
    <div class="absolute top-0 left-0 bottom-0 right-0 z-20">
        <form 
            @submit.prevent="addIssue()"
            class="flex flex-col w-11/12 sm:w-5/6 lg:w-1/2 max-w-2xl mx-auto rounded-lg border border-gray-300 shadow-xl left-1/2 transform translate-y-1/2">
            <div class="flex flex-row justify-between p-6 bg-white border-b border-gray-200 rounded-tl-lg rounded-tr-lg">
                <p class="font-semibold text-gray-800">Add an issue</p>
                <button @click="$emit('closeModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex flex-col px-6 py-5 bg-gray-50 max-h-102 overflow-y-scroll">
                <div class="flex flex-col sm:flex-row items-center mb-5 flex-wrap justify-between">
                    <div class="w-full">
                        <div class="w-full flex justify-between">
                            <p class="mb-2 font-semibold text-gray-700">Issue</p>                            
                        </div>
                        <p class="text-xs text-gray-300">Please summarize the issue</p>
                        <input type="text" v-model="issue" class="w-full p-5 bg-white border border-gray-200 rounded shadow-sm appearance-none mb-5" required>
                        
                    </div>
                    <div class="w-full">
                        <div class="w-full flex justify-between">
                            <p class="mb-2 font-semibold text-gray-700">Description</p>
                        </div>
                        <p class="text-xs text-gray-300">Please describe the issue</p>
                        <textarea v-model="description" class="w-full p-5 bg-white border border-gray-200 rounded shadow-sm appearance-none" required></textarea>
                    </div>
                    
                </div>
            </div>
            <div class="flex flex-row items-center justify-between p-5 bg-white border-t border-gray-200 rounded-bl-lg rounded-br-lg">
                <button type="button" class="font-semibold text-gray-600" @click="$emit('closeModal')">Cancel</button>
                <button type="submit" class="px-4 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-700 rounded">Add issue</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "Modal",
    data(){
        return {
            description: "",
            issue: "",
        }
    },
    props: {
        project_id: Number,
    },
    emits: ['closeModal', 'issueAdded'],
    methods: {
        async addIssue(){
            //event.preventDefault();
            try {
                let formData = new FormData();
                formData.append('project_id', this.project_id);
                formData.append('description', this.description);
                formData.append('issue', this.issue);
                formData.append('user_id', this.$store.state.user.userId);
                const response = await axios.post(`${this.$store.state.url}issues/addIssue`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                console.log(response.data);
                if(response.data.success){
                    this.$emit('issueAdded', response.data.data);
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