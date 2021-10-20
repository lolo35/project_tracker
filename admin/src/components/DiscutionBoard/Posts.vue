<template>
    <div class="flex flex-row items-center justify-between bg-gray-600 p-2 cursor-pointer hover:bg-gray-500 border-b">
        <div class="flex flex-col w-3/4">
            <div class="flex flex-row items-center w-full space-x-2">
                <div class="flex flex-row">
                    <div class="rounded-full p-2 bg-gray-50 h-10 w-10 flex items-center justify-center">
                        <i class="fas fa-align-left text-xl"></i>
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="flex flex-row items-center">
                        <h3 class="text-white">{{ postData.name }}</h3>
                    </div>
                    <div class="flex-flex-row items-center">
                        <p class="text-sm text-gray-200">{{ postData.description }}</p>
                    </div>
                </div> 
            </div>
        </div>
        <div class="flex flex-row w-1/4 items-center justify-between px-4">
            <h3 class="text-white">{{ postData.replies }}</h3>
            <h3 class="text-white">{{ postData.views }}</h3>
            <div class="flex flex-col">
                <div class="flex flex-row">
                    <h3 class="text-white mr-5">by 
                        <span class="text-blue-300">
                            {{ postData.last_post }}
                        </span>
                    </h3>
                </div>
                <div class="flex flex-row">
                    <p class="text-sm text-white">
                        {{ postData.updated_at }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Posts",
    props: {
        postData: Object,
        index: Number,
    }
}
</script>

<style>

</style>