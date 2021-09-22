<template>
    <div class="flex flex-col">
        <div class="text-lg font-bold px-4 py-2">
            Teams
        </div>
        <toolbar></toolbar>
    </div>
    <Table></Table>
</template>

<script>
import Table from '@/components/Teams/Table.vue';
import Toolbar from '@/components/Teams/Toolbar.vue';


export default {
    name: "Teams",
    components: {
        Table,
        Toolbar,
    },
}
</script>

<style>

</style>