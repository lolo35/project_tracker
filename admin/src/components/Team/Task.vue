<template>
    <input type="checkbox" :id="id" :checked="statusComputed" @click="updateTask()">
    <label class="text-white cursor-pointer" :class="{'line-through': statusComputed}" :for="id">{{ name }}</label>
</template>

<script>
import axios from 'axios';
export default {
    name: "Task",
    props: {
        id: Number,
        status: Number,
        name: String,
    },
    emits: ['updateTask'],
    methods: {
        async updateTask(){
            let status = this.status == 0 ? 1 : 0;
            try {
                const response = await axios.post(`${this.$store.state.url}user/updateTask?id=${this.id}&status=${status}`);
                if(response.data.success){
                    this.$emit('updateTask', this.id);
                }
            } catch(error){
                console.error(error);
            }
            //console.log(response.data);
        }
    },
    computed: {
        statusComputed(){
            return this.status === 0 ? false : true;
        }
    }
}
</script>

<style>

</style>