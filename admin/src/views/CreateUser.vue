<template>
    <div class="flex flex-col h-screen items-center justify-center">
        <div class="flex flex-row bg-white rounded px-3 py-6 shadow">
            <form class="flex flex-col" @submit="createUser()">
                <div class="flex flex-row items-center space-x-3">
                    <img :src="`${$store.state.url}images/timely_logo.svg`" class="w-20 h-20" alt="">
                    <h3 class="text-lg font-semibold">Create your Timely user</h3>
                </div>
                <div class="flex flex-col space-y-2">
                    <label for="name" class="text-xs font-semibold">
                        <i class="fas fa-user text-blue-500"></i>
                        Name
                    </label>
                    <input type="text" id="name" v-model="name" class="border rounded px-3 py-1 appearance-none" required>
                    <span class="text-xs text-gray-400">Your full name ex: Josh Smith</span>
                    <label for="email" class="text-xs font-semibold">
                        <i class="fas fa-envelope-open text-blue-500"></i>
                        Email
                    </label>
                    <input type="email" id="email" v-model="email" class="border rounded px-3 py-1 appearance-none" required>
                    <span class="text-xs font-semibold text-gray-400">Your work email ex: josh.smith@autoliv.com</span>
                    <label for="autolivId" class="text-xs font-semibold">
                        <i class="fas fa-id-card text-blue-500"></i>
                        Autoliv ID
                    </label>
                    <input type="text" class="border rounded px-3 py-1 appearance-none" id="autolivId" v-model="autolivId" required>
                    <span class="text-xs font-semibold text-gray-400">Your autoliv badge ID ex: TRO01234</span>
                    <label for="password" class="text-xs font-semibold">
                        <i class="fas fa-lock text-blue-500"></i>
                        Password
                    </label>
                    <input type="password" id="password" class="border rounded px-3 py-1" v-model="password" @input="checkPassStrength()" required>
                    <div :class="progressBarClass"></div>
                    <span class="text-xs font-semibold text-gray-400">
                        Please enter your password
                    </span>
                    <label for="confirmPassword" class="text-xs font-semibold">
                        <i class="fas fa-lock text-blue-500"></i>
                        Confirm password
                    </label>
                    <input type="password" class="border rounded px-3 py-1" id="confirmPassword" required v-model="confirmPass">
                    <span class="text-xs font-semibold text-gray-400">Please confirm your password</span>
                    <div class="bg-red-300 rounded text-red-500 px-3 py-1 text-center" v-if="showPassMismatch">
                        <p class="font-bold">Passwords do not match</p>
                    </div>
                    <div class="bg-red-300 rounded text-red-500 px-3 py-1 text-center" v-if="showPassComplexError">
                        <p class="font-bold">Password complexity is too low</p>
                        <!-- <p class="font-semibold">Password requires at least 6 characters in length</p> -->
                    </div>
                    <div class="bg-green-300 rounded text-green-600 font-bold text-center px-3 py-1" v-if="userCreationSuccess">
                        <p>User created succesfully</p>
                    </div>
                    <div class="bg-red-300 rounded text-red-500 font-bold text-center" v-if="showError">
                        {{ errorText }}
                    </div>
                    <button class="bg-blue-500 text-white rounded px-3 py-1" type="submit">Create</button>
                    <div class="relative rounded h-1 bg-gray-100">
                        <div class="absolute font-semibold -top-3 left-1/2 text-gray-500">or</div>
                        
                    </div>
                    <div class="text-center">
                        <router-link to="/login" class="text-blue-500 hover:text-blue-600 flex flex-row items-center justify-center space-x-3">
                            <i class="fas fa-arrow-left"></i>
                            <p>Login</p>
                        </router-link>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "CreateUser",
    data(){
        return {
            name: "",
            email: "",
            autolivId: "",
            password: "",
            confirmPass: "",
            progressBarClass: "",
            showPassMismatch: false,
            showPassComplexError: false,
            userCreationSuccess: false,
            showError: false,
            errorText: "",
            passed: 0,
        }
    },
    methods: {
        checkPassStrength(){
            let regex = new Array();
            regex.push("[A-Z]");
            regex.push("[a-z]");
            regex.push("[0-9]");
            regex.push("[$@$!%*#?&]");
            if(this.password.length > 6){
                this.passed = 1;
                for(let i = 0; i < regex.length; i++){
                    if(new RegExp(regex[i]).test(this.password)){
                        this.passed++;
                    }
                }
            }
            switch(this.passed){
                case 0: this.progressBarClass = "rounded h-1 bg-red-500 w-1/4"; break;
                case 1: this.progressBarClass = "rounded h-1 bg-red-300 w-1/4"; break;
                case 2: this.progressBarClass = "rounded h-1 bg-yellow-300 w-2/4"; break;
                case 3: this.progressBarClass = "rounded h-1 bg-yellow-500 w-3/4"; break;
                case 4: this.progressBarClass = "rounded h-1 bg-green-500 w-full"; break;
            }
        },
        async createUser(){
            event.preventDefault();
            if(this.passed >= 3){
                this.showPassComplexError = false;
                if(this.password === this.confirmPass){
                    this.showPassMismatch = false;
                    let formData = new FormData();
                    formData.append('name', this.name);
                    formData.append('email', this.email);
                    formData.append('autolivId', this.autolivId.toUpperCase());
                    formData.append('password', this.password);
                    const response = await axios.post(`${this.$store.state.url}user/createUser`, formData, { headers: { 'Content-type': 'application/x-www-form-urlencoded'}});
                    console.log(response.data);
                    if(response.data.success){
                        this.errorText = "";
                        this.showError = false;
                        this.name = "";
                        this.email = "";
                        this.password = "";
                        this.confirmPass = "";
                        this.autolivId = "";
                        this.progressBarClass = "";
                        this.userCreationSuccess = true;
                        setTimeout(() => {
                            this.userCreationSuccess = false;
                        }, 15000);
                    }else if(!response.data.success){
                        if(response.data.error === "userExists"){
                            this.errorText = "User already exists";
                            this.showError = true;
                        }else if(response.data.error === "userCreation"){
                            this.errorText = "Something bad happend...";
                            this.showError = true;
                        }
                    }
                }else{
                    this.showPassMismatch = true;
                }
            }else{
                this.showPassComplexError = true;
            }
        }
    }

}
</script>

<style>

</style>