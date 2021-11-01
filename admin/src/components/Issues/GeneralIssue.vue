<template>
    <div class="flex flex-row bg-gray-50 border px-6 py-3 justify-between">
        <div class="flex flex-col">
            <div class="flex flex-row">
                <router-link :to="`/issues/${id}`" class="text-lg font-bold text-gray-800 hover:text-blue-500 cursor-pointer">{{ project }}</router-link>
            </div>
            <p class="text-sm text-gray-400 font-semibold">{{ description }}</p>
        </div>
        <div class="flex flex-col">
            <div class="flex flex-row space-x-5" v-if="showCount">
                <p class="text-gray-700 font-semibold">Opened: {{ this.opened }}</p>
                <p class="text-gray-700 font-semibold">Closed: {{ this.closed }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "GeneralIssue",
    data(){
        return {
            closed: Number,
            opened: Number,
            showCount: false,
        }
    },
    props: {
        id: Number,
        project: String,
        description: String,
    },
    created(){
        this.fetchCount();
    },
    methods: {
        async fetchCount(){
            try {
                const response = await axios.get(`${this.$store.state.url}issues/countIssues?project_id=${this.id}`);
                console.log(response.data);
                if(response.data.success){
                    this.closed = parseInt(response.data.closed[0].closed);
                    this.opened = parseInt(response.data.opened[0].opened);
                    this.showCount = true;
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