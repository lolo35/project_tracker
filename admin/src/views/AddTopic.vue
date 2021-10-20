<template>
    <div class="flex flex-col bg-gray-700 rounded p-2">
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
            <input type="text" v-model="subject" class="w-full p-2 bg-white border border-gray-200 rounded shadow-sm appearance-none">
            <p class="text-sm text-gray-300">What is your topic about?</p>
        </div>

        <div class="flex flex-col w-1/4">
            <label for="description" class="text-white font-semibold">
                <i class="fas fa-heading"></i>
                Description
            </label>
            <input type="text" v-model="description" class="w-full p-2 bg-white border border-gray-200 rounded shadow-sm appearance-none">
            <p class="text-sm text-gray-300">How would you describe your topic?</p>
        </div>
        <div class="flex flex-col">
            <label for="description" class="text-white font-semibold">
                <i class="far fa-comment-dots"></i>                
            </label>
            <textarea v-model="description" rows=15 class="w-full p-2 bg-white border border-gray-200 rounded shadow-sm appearance-none"></textarea>
        </div>
    </div>
</template>

<script>
export default {
    name: "AddTopic",
    data(){
        return {
            subject: "",
            description: "",
        }
    }
}
</script>

<style>

</style>