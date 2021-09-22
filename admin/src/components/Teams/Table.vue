<template>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-700">
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Team</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Team Leader</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Members</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Currently woking on</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                        </thead>
                        <tbody v-if="showTableRows" class="divide-y divide-gray-200 bg-white">
                            <table-row 
                                v-for="(row, name ) in $store.state.teams.teams" :key="row.id"
                                :team="name"
                                :leader="row.leader"
                                :members="row.members"
                                :teamId="row.teamId"
                            ></table-row>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
import TableRow from './TableRow.vue';
export default {
    name: "Table",
    data(){
        return {
            showTableRows: false,
        }
    },
    components: {
        TableRow,
    },
    created(){
        this.getTeams();
    },
    methods: {
        async getTeams(){
            try {
                //console.log(`${this.$store.state.url}teams/`);
                const response = await axios.get(`${this.$store.state.url}teams/`);
                if(response.data.success){
                    this.$store.dispatch('teams/setTeams', response.data.data);
                    this.showTableRows = true;
                }
            } catch (error){
                console.error(error);
            }
        }
    }
}
</script>

<style>

</style>