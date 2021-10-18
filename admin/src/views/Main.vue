<template>
    <div class="flex">Dashboard</div>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto">
            <div class="py-2 inline-block align-middle min-w-full sm:px-6 lg:px-8">
                <div class="shadow border-b overflow-hidden border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left uppercase text-xs font-medium tracking-wider text-white">User</th>
                                <th class="px-6 py-3 text-left uppercase text-xs font-medium tracking-wider text-white">ID</th>
                                <th class="px-6 py-3 text-left uppercase text-xs font-medium tracking-wider text-white">Currently working on</th>
                                <th class="px-6 py-3 text-left uppercase text-xs font-medium tracking-wider text-white">Started</th>
                                <th class="px-6 py-3 text-left uppercase text-xs font-medium tracking-wider text-white">Time spent on job</th>
                                <th class="px-6 py-3 text-left uppercase text-xs font-medium tracking-wider text-white">Dispatch</th>
                                <th class="px-6 py-3 text-left uppercase text-xs font-medium tracking-wider text-white">Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="row in data" :key="row.id">
                                <td class="px-4 py-2 whitespace-nowrap border-r">{{ row.name }}</td>
                                <td class="px-4 py-2 whitespace-nowrap border-r">{{ row.autoliv_id }}</td>
                                <td class="px-4 py-2 whitespace-nowrap border-r">{{ row.task }}</td>
                                <td class="px-4 py-2 whitespace-nowrap border-r">{{ row.updated_at }}</td>
                                <td class="px-4 py-2 whitespace-nowrap border-r">{{ row.minutesSpent }}</td>
                                <td class="px-4 py-2 whitespace-nowrap border-r">
                                    <button class="text-blue-500 hover:underline" @click="fetchDispatch(row.dispatch_id)">
                                        {{ row.dispatch_id }}
                                    </button>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap border-r capitalize">{{ row.recurring }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { ref } from 'vue';
import { useStore } from 'vuex';

export default {
    name: "Main",
    setup(){
        const store = useStore();
        const data = ref([]);
        const fetchTasks = () => {
            return axios.get(`${store.state.url}teams/workingTasks`)
                .then(response => {
                    if(response.data.success){
                        data.value = response.data.data;
                    }
                }).catch(error => {
                    console.error(error);
                });
        }

        const fetchDispatch = (id) => {
            return axios.get(`https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/?auth=ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh&site=15&order_by=-created&id=${id}`)
                .then(response => {
                    console.log(response.data);
                    if(response.data.success){
                        let searchString = response.data.data['dispatchnumber'];
                        let url = `https://autoliv-eu2.leading2lean.com/dispatch/dispatch?searchString=${searchString}&selected_fields=resources&selected_fields=status&selected_fields=line&selected_fields=tooling&selected_fields=reported&selected_fields=time_up&selected_fields=total_time&selected_fields=response_time&selected_fields=due_date&selected_fields=valuestream&selected_fields=trade&selected_fields=description&selected_fields=submitted_by&selected_fields=due&selected_fields=cycle_due&selected_fields=questions&selected_fields=reason&selected_fields=action_components&selected_fields=notes&selected_fields=spares&selected_fields=external_costs&selected_fields=costs&selected_fields=scheduler_docs&selected_fields=docs&selected_fields=notifications_sent&automatic_refresh=True&num_per_page=100&show_area_ribbon=true&text_size=10px&show_user_avatars=true&show_resource_usernames=true&reported_start=&totalCount=%2F&area=ALL&orderby=d.dp00dp_num&direction=DESC&lastorderby=d.dp00dp_num&branch=15&pageNum=1`;
                        window.open(url, '_blank').focus();
                    }
                }).catch(error => {
                    console.error(error);
                });
        }

        return { data, fetchTasks, fetchDispatch };
    },
    created(){
        this.fetchTasks();
    }
}
</script>

<style>

</style>