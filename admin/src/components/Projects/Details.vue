<template>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-700">
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Dispatch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Task</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                <div class="flex flex-row items-center space-x-3">
                                    <p class="">Time spent</p>
                                    <div class="flex flex-col">
                                        <button class="text-gray-300 hover:text-gray-500" @click="$store.dispatch('projects/details/sortByTimeSpent', 'up')">
                                            <i class="fas fa-caret-up"></i>
                                        </button>
                                        <button class="text-gray-300 hover:text-gray-500" @click="$store.dispatch('projects/details/sortByTimeSpent', 'down')">
                                            <i class="fas fa-caret-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                <div class="flex flex-row items-center space-x-3">
                                    <p class="">Task created</p>
                                    <div class="flex flex-col">
                                        <button class="text-gray-300 hover:text-gray-500" @click="$store.dispatch('projects/details/sortByCreated', 'up')">
                                            <i class="fas fa-caret-up"></i>
                                        </button>
                                        <button class="text-gray-300 hover:text-gray-500" @click="$store.dispatch('projects/details/sortByCreated', 'down')">
                                            <i class="fas fa-caret-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Due date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                <div class="flex flex-row items-center space-x-3">
                                    <p class="">Status</p>
                                    <div class="flex flex-col">
                                        <button class="text-gray-300 hover:text-gray-500" @click="$store.dispatch('projects/details/setSortByStatus', 'up')">
                                            <i class="fas fa-caret-up"></i>
                                        </button>
                                        <button class="text-gray-300 hover:text-gray-500" @click="$store.dispatch('projects/details/setSortByStatus', 'down')">
                                            <i class="fas fa-caret-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </th>
                        </thead>
                        <tbody  class="divide-y divide-gray-200 bg-white">
                            <details-row
                                v-for="details in filteredDetails"
                                :key="details.id"
                                :dispatch="parseInt(details.dispatch_id)"
                                :task="details.task"
                                :user="details.username"
                                :time="parseInt(details.minutesSpent)"
                                :created="details.created_at"
                                :due="details.due"
                                :status="parseInt(details.status)"
                            ></details-row>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import DetailsRow from './DetailsRow.vue';
import { mapState, mapGetters } from 'vuex';

export default {
    name: "Details",
    components: {
        DetailsRow,
    },
    computed: {
        ...mapState(['projects']),
        ...mapGetters('projects/details/', ['filteredDetails']),
    }
}
</script>

<style>

</style>