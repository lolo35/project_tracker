<template>
    <div class="h-screen w-screen bg-gray-50">
        <div class="flex md:flex-row flex-col items-center w-full h-full justify-center">
            <div class="bg-white shadow px-6 py-3 rounded w-full md:w-1/2 flex flex-col md:flex-row">
                <div class="flex flex-col md:flex-row w-full md:w-1/2">
                    <img :src="`${$store.state.url}images/undraw_Login_re_4vu2.svg`" alt="">
                </div>
                <div class="flex flex-col md:flex-row w-full md:w-1/2">
                    <form class="flex flex-col w-full space-y-2" @submit="validateLogin()">
                        <div class="flex flex-row w-full justify-center">
                            <h3 class="text-xl font-bold">
                                <i class="fas fa-dungeon text-gray-600"></i>
                                Login
                            </h3>
                        </div>
                        <div class="flex flex-col w-full">
                            <label for="user" class="block font-semibold text-gray-700">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input 
                                type="email" 
                                v-model="user" 
                                id="user" 
                                class="bg-white border border-gray-300 rounded outline-none px-4 py-2 focus:ring-1 focus:ring-blue-500" 
                                required
                            >
                        </div>
                        <div class="flex flex-col w-full relative">
                            <label for="pass" class="block font-semibold text-gray-700">
                                <i class="fas fa-unlock"></i>
                                Password
                            </label>
                            <input 
                                :type="showPass ? 'text' : 'password'" 
                                v-model="pass" 
                                id="pass" 
                                class="bg-white border border-gray-300 rounded outline-none px-4 py-2 focus:ring-1 focus:ring-blue-500"
                                required
                            >
                            <button type="button" class="absolute right-3 top-1/2" @click="showPass = !showPass">
                                <i class="far fa-eye" v-if="!showPass"></i>
                                <i class="far fa-eye-slash" v-if="showPass"></i>
                            </button>
                        </div>
                        <div class="flex flex-row w-full animate__animated animate__bounceIn" v-if="loginError">
                            <div class="bg-red-300 w-full rounded px-6 py-3">
                                <p class="text-red-500 font-bold text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Username or password is incorrect
                                </p>
                            </div>
                        </div>
                        <div class="w-full py-3">
                            <button class="bg-blue-500 text-white w-full rounded px-4 py-2">
                                <i class="fas fa-sign-in-alt"></i>
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import localforage from 'localforage';

export default {
    name: "Login",
    data(){
        return {
            loginError: false,
            showPass: false,
        }
    },
    methods: {
        async validateLogin(){
            event.preventDefault();
            let formData = new FormData();
            formData.append('user', this.user);
            formData.append('pass', this.pass);
            try {
                const response = await axios.post(`${this.$store.state.url}user/login`, formData, {
                    headers: {
                        'Content-type': 'application/x-www-form-urlencoded'
                    }
                });
                if(response.data.success){
                    console.log(response.data.user);
                    localforage.setItem('name', response.data.user.name).then( value => {
                        this.$store.dispatch('user/setName', value);
                    });
                    localforage.setItem('email', response.data.user.email).then(value => {
                        this.$store.dispatch('user/setEmail', value);
                    });
                    localforage.setItem('userId', response.data.user.id).then(value => {
                        this.$store.dispatch('user/setUserId', value);
                    });
                    localforage.setItem('autoliv_id', response.data.user.autoliv_id).then(value => {
                        this.$store.dispatch('user/setAutolivId', value);
                    });
                    this.$store.dispatch('setIsLogged');
                    this.$store.dispatch('user/setUserLoaded', true);
                    this.$router.push('/');
                }
            } catch(error){
                console.error(error);
            }
        }
    },
    computed: {
        user: {
            get(){
                return this.$store.state.login.user;
            },
            set(value){
                this.$store.dispatch('login/setUser', value);
            }
        },
        pass: {
            get(){
                return this.$store.state.login.pass;
            },
            set(value){
                this.$store.dispatch('login/setPass', value);
            }
        }
    }
}
</script>

<style>

</style>