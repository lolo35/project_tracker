<template>
    <div class="flex flex-row bg-yellow-300 text-yellow-700 px-6 py-4 items-center space-x-1 border-l-8 border-yellow-700">
        <div class="flex flex-col">
            <div class="flex flex-row items-center space-x-2">
                <p class="font-semibold">Looks like there aren't any projects to show </p>
                <i class="fas fa-heart-broken text-xl"></i>
            </div>
            <div class="flex flex-row">
                <button class="font-semibold hover:underline" @click="$store.dispatch('projects/toggleShowProjectModal')">Would you like to create one?</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "NoProjectWarning",
}
</script>

<style>

</style>