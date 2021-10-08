<template>
    <div class="flex flex-row flex-wrap px-4 py-2">
        <div class="flex flex-col w-1/3 lg:w-1/4 bg-gray-800 shadow rounded px-4 py-2 h-full mb-2 mr-2" v-for="member in $store.state.team.teamComp" :key="member.id">
            <member :name="member.members" :userId="parseInt(member.memberId)"></member>
        </div>
    </div>
</template>

<script>
import Member from './Member.vue';
export default {
    name: "Members",
    components: {
        Member,
    }
}
</script>

<style>

</style>