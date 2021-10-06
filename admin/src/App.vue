<template>
	<router-view/>
</template>

<script>
import localforage from 'localforage';

export default {
	name: "App",
	created(){
		this.checkIfLogged();
	},
	methods: {
		checkIfLogged(){
			if(!this.$store.state.isLogged){
				localforage.getItem('userId').then(value => {
					if(!value){
						this.$router.push('/login');
						this.$store.dispatch('user/setUserLoaded', false);
					}else{
						this.$store.dispatch('setIsLogged');
						this.$store.dispatch('user/setUserId', value);
						localforage.getItem('name').then(value => {
							this.$store.dispatch('user/setName', value);
						});
						localforage.getItem('email').then( value => {
							this.$store.dispatch('user/setEmail', value)
						});
						localforage.getItem('autoliv_id').then(value => {
							this.$store.dispatch('user/setAutolivId', value);
						})
						this.$store.dispatch('user/setUserLoaded', true);
					}
				});
			}
		}
	}
}
</script>
<style>

</style>
