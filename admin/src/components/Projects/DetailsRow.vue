<template>
    <tr>
        <td class="px-4 py-2 whitespace-nowrap border-r">{{ dispatch }}</td>
        <td class="px-4 py-2 whitespace-nowrap border-r">{{ task }}</td>
        <td class="px-4 py-2 whitespace-nowrap border-r">{{ user }}</td>
        <td class="px-4 py-2 whitespace-nowrap border-r">{{ time }}</td>
        <td class="px-4 py-2 whitespace-nowrap border-r">{{ created }}</td>
        <td class="px-4 py-2 whitespace-nowrap border-r">{{ due }}</td>
        <td class="px-4 py-2 whitespace-nowrap border-r flex items-center">
            <p 
                class="font-semibold text-xs px-3 rounded"
                :class="cStatus === 'Completed' ? 'text-gray-700 bg-green-300' : cStatus === 'In Work' ? 'text-gray-700 bg-blue-300' : 'text-gray-700 bg-yellow-300'"
            >
                {{ cStatus }}
            </p>
        </td>
    </tr>
</template>

<script>
export default {
    name: "DetailsRow",
    props: {
        dispatch: Number,
        task: String,
        user: String,
        time: Number,
        created: String,
        due: String,
        status: Number,
    },
    computed: {
        cStatus(){
            return this.status === 1 ? "Completed" : this.status === 2 ? "In work" : "Idle";
        }
    }
}
</script>

<style>

</style>