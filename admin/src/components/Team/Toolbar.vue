<template>
    <div class="flex flex-row px-4">
        <div class="flex flex-row bg-gray-800 px-4 py-2 rounded">
            <button 
                class="rounded-full bg-gray-900 text-white w-8 h-8 hover:text-blue-300 hover:bg-gray-700" 
                title="Add team member" 
                @click="$store.dispatch('team/setShowModal', true)"
            >
                <i class="fas fa-user-plus"></i>
            </button>
        </div>
    </div>
    <add-team-member-modal v-if="$store.state.team.showModal"></add-team-member-modal>
</template>

<script>
import AddTeamMemberModal from './AddTeamMemberModal.vue';

export default {
    name: "Toolbar",
    components: {
        AddTeamMemberModal,        
    },
    methods: {
        
    }
}
</script>

<style>

</style>