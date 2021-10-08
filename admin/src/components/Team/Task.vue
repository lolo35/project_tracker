<template>
    <div class="flex space-x-1 items-center">
        <input type="checkbox" :id="id" :checked="statusComputed" @click="updateTask()">
        <button class="" title="Start Task" v-if="!statusComputed" @click="toggleActive()">
            <i class="fas fa-play text-green-500" v-if="!activeStatus"></i>
            <i class="fas fa-pause text-yellow-500" v-if="activeStatus"></i>
        </button>
    </div>
    <label class="text-white cursor-pointer" :class="{'line-through': statusComputed}" :for="id">{{ name }}</label>
</template>

<script>
import localforage from 'localforage';
import axios from 'axios';
export default {
    name: "Task",
    data(){
        return {
            activeStatus: false,
        }
    },
    props: {
        id: Number,
        status: Number,
        name: String,
    },
    emits: ['updateTask'],
    created(){
        this.checkStatus();
    },
    methods: {
        async updateTask(){
            let status = this.status == 0 ? 1 : 0;
            
            try {
                const response = await axios.post(`${this.$store.state.url}user/updateTask?id=${this.id}&status=${status}`);
                console.log(response.data);
                if(response.data.success){
                    this.$emit('updateTask', this.id);
                    localforage.removeItem(this.id.toString());
                }
            } catch(error){
                console.error(error);
            }
            //console.log(response.data);
        },
        async toggleActive(){
            let formData = new FormData();
            formData.append('taskId', this.id);
            if(!this.activeStatus){
                localforage.setItem(this.id.toString(), true);
                this.activeStatus = true;
                formData.append('status_id', -4);

            }else{
                localforage.setItem(this.id.toString(), false);
                this.activeStatus = false;
                formData.append('status_id', -5);
            }

            const response = await axios.post(`${this.$store.state.url}user/toggleTask`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
            console.log(response.data);
        },
        checkStatus(){
            localforage.getItem(this.id.toString()).then(value => {
                if(value){
                    this.activeStatus = value;
                }
            });
        }
    },
    computed: {
        statusComputed(){
            return this.status === 1 ? true : false;
        }
    }
}
</script>

<style>

</style>