<template>
    <div class="flex flex-col">
        <div class="text-lg font-bold px-4 py-2">
            Teams
        </div>
        <toolbar></toolbar>
    </div>
    <Table></Table>
    <add-team-modal v-if="teams.showModal"></add-team-modal>
</template>

<script>
import Table from '@/components/Teams/Table.vue';
import AddTeamModal from '@/components/Teams/AddTeamModal.vue';
import Toolbar from '@/components/Teams/Toolbar.vue';
import { mapState } from 'vuex';

export default {
    name: "Teams",
    components: {
        Table,
        AddTeamModal,
        Toolbar,
    },
    computed: {
        ...mapState(['teams']),
    }
}
</script>

<style>

</style>