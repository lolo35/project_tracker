<template>
    <tr>
        <td class="px-4 py-2 whitespace-nowrap border-r" :rowspan="members.length">
            <div class="flex flex-row items-center space-x-2">
                <button class="text-red-500 hover:text-red-700" @click="deleteTeam()">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <p class="text-xl">{{ team }}</p>
            </div>
            
        </td>
        <td class="px-4 py-2 whitespace-nowrap border-r" :rowspan="members.length">{{ leader }}</td>
        <td class="px-4 py-2 whitespace-nowrap" v-if="members[0] !== leader">{{ members[0] }}</td>
    </tr>
    <tr v-for="(member, index) in memberRows" :key="member">
        <td class="px-4 py-2 whitespace-nowrap" v-if="member !== leader">{{ member }}</td>
        <td class="px-4 py-2 whitespace-nowrap border-r border-l font-semibold text-xl" :rowspan="memberRows.length" v-if="index == 0">Project</td>
        <td class="px-4 py-2 whitespace-nowrap border-r border-l font-semibold text-xl" :rowspan="memberRows.length" v-if="index == 0">
            <i class="fas fa-tachometer-alt"></i>
            56%
        </td>
    </tr>
</template>

<script>
import Swal from 'sweetalert2';
import axios from 'axios';

export default {
    name: "TableRow",
    props: {
        team: String,
        leader: String,
        members: Array,
        teamId: Number,
    },
    methods: {
        deleteTeam(){
            Swal.fire({
                icon: 'question',
                html: `<p class="font-semibold text-gray-700">
                    Are you sure you want to delete team <span class="font-bold uppercase italic">${this.team}</span>
                </p>`,
                showCancelButton: true,
                cancelButtonText: "No",
                cancelButtonColor: "red",
                confirmButtonText: "Yes"
            }).then((isConfirmed) => {
                if(isConfirmed.value){
                    //console.log('true');
                    let formData = new FormData();
                    formData.append('teamId', this.teamId);
                    axios.post(`${this.$store.state.url}teams/delete`, formData, {
                        headers: {
                            'Content-type': 'application/x-www-form-urlencoded',
                        }
                    }).then(response => {
                        if(response.data.success){
                            this.$store.dispatch('teams/removeTeam', this.team);
                        }
                    }).catch(e => {
                        throw Error(e);
                    });
                }
            });
        }
    },
    computed: {
        memberRows(){
            let array = [];
            for(let i = 0; i < this.members.length; i++){
                if(i > 0){
                    array.push(this.members[i]);
                }
            }
            return array;
        },
    }
}
</script>

<style>

</style>