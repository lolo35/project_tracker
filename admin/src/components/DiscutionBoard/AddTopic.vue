<template>
    <div class="flex flex-row mt-5">
        <button class="bg-gray-300 rounded px-3 py-1 hover:bg-gray-400 text-sm font-semibold" @click="showModal = !showModal">
            <i class="fas fa-pencil-alt"></i>
            Post new topic
        </button>
    </div>
    <div class="absolute bg-black z-10 top-0 bottom-0 left-0 right-0 opacity-50" v-if="showModal"></div>
    <div class="absolute top-0 left-0 bottom-0 right-0 z-20" v-if="showModal">
        <form
            class="flex flex-col w-11/12 sm:w-5/6 lg:w-1/2 max-w-2xl mx-auto rounded-lg border border-gray-300 shadow-xl left-1/2 transform translate-y-1/2"
        >
            <div class="flex flex-row justify-between p-6 bg-white border-b border-gray-200 rounded-tl-lg rounded-tr-lg">
                <p class="font-semibold text-gray-800">Post new topic</p>
                <button @click="showModal = !showModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex flex-col px-6 py-5 bg-gray-50 max-h-102 overflow-y-scroll">
                <div class="flex flex-col sm:flex-row items-center mb-5 flex-wrap justify-between">
                    <div class="w-full">
                        <div class="w-full flex justify-between">
                            <p class="mb-2 font-semibold text-gray-700">Subject</p>                            
                        </div>
                        <p class="text-xs text-gray-300">What would you like to discuss</p>
                        <input type="text" v-model="topic" class="w-full p-5 bg-white border border-gray-200 rounded shadow-sm appearance-none mb-5" required>
                        
                    </div>
                    <div class="w-full">
                        <div class="w-full flex justify-between">
                            <p class="mb-2 font-semibold text-gray-700">Description</p>
                        </div>
                        <p class="text-xs text-gray-300">How would you describe your topic?</p>
                        <input type="text" v-model="description" class="w-full p-5 bg-white border border-gray-200 rounded shadow-sm appearance-none" required>
                    </div>
                    <div class="w-full">
                        <div class="w-full flex justify-between">
                            <p class="mb-2 font-semibold text-gray-700">Description</p>
                        </div>
                        <p class="text-xs text-gray-300">How would you describe your topic?</p>
                        <textarea v-model="text" class="w-full p-5 bg-white border border-gray-200 rounded shadow-sm appearance-none" required></textarea>
                    </div>
                </div>
            </div>
            <div class="flex flex-row items-center justify-between p-5 bg-white border-t border-gray-200 rounded-bl-lg rounded-br-lg">
                <button class="font-semibold text-gray-600" @click="showModal = !showModal">Cancel</button>
                <button type="submit" class="px-4 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-700 rounded">Post topic</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "AddTopic",
    data(){
        return {
            showModal: false,
            topic: "",
            description: "",
            text: "",
        }
    }
}
</script>

<style>

</style>