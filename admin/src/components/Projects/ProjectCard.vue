<template>
    <div class="bg-gray-700 text-white px-6 py-3 rounded border flex border-gray-700 relative">
        <div class="flex flex-col">
            <div class="flex flex-row items-center justify-between">
                <p class="text-lg font-semibold">{{ project }}</p>
            </div>
            <p class="text-sm font-semibold text-gray-400">{{ description }}</p>
            <p class="text-sm text-gray-400">Owner: {{ owner }}</p>
        </div>
        <button class="absolute top-1 right-2">
            <i class="fas fa-info-circle"></i>
        </button>
    </div>
</template>

<script>
export default {
    name: "ProjectCard",
    props: {
        project: String,
        description: String,
        owner: String,
    }
}
</script>

<style>

</style>