<template>
    <div class="flex flex-row bg-gray-50 border px-6 py-3 justify-between">
        <div class="flex flex-col">
            <div class="flex flex-row">
                <h3 class="text-lg font-bold">{{ issue }}</h3>
            </div>
            <p class="text-gray-400 font-semibold">{{ description }}</p>
            <div class="flex flex-row items-center justify-between w-full">
                <p class="text-xs font-semibold text-gray-400">Opened by: {{ opened_by }}</p>                
            </div>
            <div class="flex flex-row items-center justify-between w-full" v-if="!open && closedByLoaded">
                <div class="flex flex-col">
                    <p class="text-xs font-semibold text-gray-400">
                        Closing reason: <span class="italic font-bold text-gray-500">{{ closed_by[0].reason }}</span>
                    </p>
                </div>
            </div>
            <div class="flex flex-row">
                <button class="bg-green-300 rounded text-green-600 px-1 hover:bg-green-400 hover:text-green-700" v-if="open" @click="closeIssue()">
                    <i class="fas fa-check"></i>
                    Close
                </button>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="flex flex-row items-start">
                <div class="bg-yellow-300 px-6 py-1 rounded text-yellow-600" v-if="open">
                    <i class="fas fa-cog"></i>
                    Open
                </div>
                <div class="bg-green-300 px-6 py-1 rounded text-green-600" v-if="!open">
                    <i class="fas fa-check-circle"></i>
                    Closed
                </div>
            </div>
            <div class="flex flex-row items-end h-full" v-if="!open && closedByLoaded">
                <p class="text-xs font-semibold text-gray-400">Closed by: {{ closed_by[0].name }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';
import axios from 'axios';

export default {
    name: "IndividualIssues",
    data(){
        return {
            closed_by: Array,
            closedByLoaded: false,
        }
    },
    props: {
        issue: String,
        description: String,
        status: Number,
        opened_by: String,
        id: Number,
        index: Number,
    },
    emits: ['issueClosed'],
    created(){
        this.fetchClosedBy();
    },
    methods: {
        closeIssue(){
            Swal.fire({
                icon: 'question',
                html: `<div class="flex flex-col">
                        <p>Are you sure you want to close this issue?</p>
                        <blockquote class="p-4 italic border-l-4 bg-gray-100">${ this.issue }</blockquote>
                    </div>`,
                input: 'text',
                inputPlaceholder: "Enter reason...",
                showCancelButton: true,
                cancelButtonColor: "red",
                confirmButtonText: "Yes",
            }).then((isConfirmed) => {
                if(isConfirmed.value){
                    //console.log();
                    this.postCloseIssue(isConfirmed.value);
                }
            });
        },
        async postCloseIssue(inpVal){
            let formData = new FormData();
            formData.append('issue_id', this.id);
            formData.append('user_id', this.$store.state.user.userId);
            formData.append('reason', inpVal);
            const response = await axios.post(`${this.$store.state.url}issues/closeIssue`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
            console.log(response.data);
            if(response.data.success){
                this.$emit('issueClosed', {index: this.index});
            }
        },
        async fetchClosedBy(){
            if(!this.open){
                try {
                    const response = await axios.get(`${this.$store.state.url}issues/closedBy?issue_id=${this.id}`);
                    console.log(response.data);
                    if(response.data.success){
                        this.closed_by = response.data.data;
                        this.closedByLoaded = true;
                    }
                } catch (error){
                    console.log(error);
                }
            }
        }
    },
    computed: {
        open(){
            return this.status === 1 ? true : false;
        }
    }
}
</script>

<style>

</style>