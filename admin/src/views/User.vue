<template>
    <div class="flex flex-col">
        <div class="flex flex-row space-x-2">
            <div class="flex flex-col bg-white shadow border px-3 py-1 w-64 space-y-2">
                <img :src="`${$store.state.url}images/no-photo.jpg`" alt="user" :title="$store.state.user.name">
                <div class="flex flex-row items-center justify-between">
                    <h3 class="text-lg font-semibold">{{ $store.state.user.name }}</h3>
                    <button title="Change photo..." class="bg-blue-500 rounded-full text-white w-6 h-6 hover:bg-blue-700">
                        <i class="far fa-image"></i>
                    </button>
                </div>
            </div>
            <div class="flex flex-col bg-white shadow border px-3 py-1 w-1/5">
                <form class="flex flex-col space-y-1" @submit="changePass()">
                    <h3 class="text-lg font-semibold">Password change</h3>
                    <label for="old_password" class="text-gray-500 font-semibold">Current password</label>
                    <input id="old_password" type="password" class="px-3 py-1 border rounded" required v-model="currentPass">
                    <span class="text-xs text-gray-300 italic">Please enter your current password</span>

                    <label for="new_password" class="text-gray-500 font-semibold">New password</label>
                    <input type="password" id="new_password" class="px-3 py-1 border rounded" required @input="checkPassStrength()" v-on:keydown.delete="resetInput()" v-model="newpassword">
                    <div :class="progressBarClass"></div>
                    <span class="text-xs italic" :class="txtColor">{{ msg }}</span>

                    <label for="conf_newpass" class="text-gray-500 font-semibold">Confirm new password</label>
                    <input type="password" id="conf_newpass" class="px-3 py-1 border rounded" required v-model="confNewPass">
                    <span class="text-xs text-gray-300 italic">Please confirm your new password</span>

                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded-sm">Change password</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: "User",
    data(){
        return {
            progressBarClass: "",
            newpassword: "",
            currentPass: "",
            confNewPass: "",
            passed: 0,
            msg: "Please enter your new password",
            txtColor: "text-gray-300"
        }
    },
    methods: {
        async changePass(){
            event.preventDefault();
            if(this.passed >= 3){
                if(this.newpassword === this.confNewPass){
                    try {
                        let formData = new FormData();
                        formData.append('user_id', this.$store.state.user.userId);
                        formData.append('current_password', this.currentPass);
                        formData.append('new_password', this.newpassword);
                        const response = await axios.post(`${this.$store.state.url}user/changePass`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }});
                        console.log(response.data);
                        if(response.data.success){
                            this.progressBarClass = "";
                            this.newpassword = "";
                            this.currentPass = "";
                            this.confNewPass = "";
                            Swal.fire({
                                icon: 'success',
                                text: 'Password was changed successfully',
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000
                            });
                        }
                    } catch (error){
                        console.error(error);
                    }
                }else{
                    this.msg = "Passwords do not match!";
                    this.txtColor = "text-red-500";
                }
            }else{
                this.msg = "Password complexity does not meet minimum requirments";
                this.txtColor = "text-red-500";
            }
        },
        resetInput(){
            this.msg = "Please enter your new password";
            this.txtColor = "text-gray-300";
            this.checkPassStrength();
        },
        checkPassStrength(){
            let regex = new Array();
            regex.push("[A-Z]");
            regex.push("[a-z]");
            regex.push("[0-9]");
            regex.push("[$@$!%*#?&]");
            if(this.newpassword.length > 6){
                this.passed = 1;
                for(let i = 0; i < regex.length; i++){
                    if(new RegExp(regex[i]).test(this.newpassword)){
                        this.passed++;
                    }
                }
            }else{
                this.passed = 0;
            }
            switch(this.passed){
                case 0: this.progressBarClass = "rounded h-1 bg-red-500 w-1/4"; break;
                case 1: this.progressBarClass = "rounded h-1 bg-red-300 w-1/4"; break;
                case 2: this.progressBarClass = "rounded h-1 bg-yellow-300 w-2/4"; break;
                case 3: this.progressBarClass = "rounded h-1 bg-yellow-500 w-3/4"; break;
                case 4: this.progressBarClass = "rounded h-1 bg-green-500 w-full"; break;
            }
        },
    }
}
</script>

<style>

</style>