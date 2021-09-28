<template>
    <div class="flex flex-row px-4 py-2">
        <div class="flex flex-col bg-gray-800 shadow rounded p-2 border-gray-600 border">
            <h3 class="text-lg text-white font-semibold">Team: {{ team.teamComp[0].team }}</h3>
            <p class="text-gray-400 italic text-sm">Leader: {{ team.teamComp[0].leader }}</p>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
export default {
    name: "TeamName",
    computed: {
        ...mapState(['team']),
    }
}
</script>

<style>

</style>