<template>
	<router-view/>
</template>

<script>
import localforage from 'localforage';

export default {
	name: "App",
	created(){
		this.checkIfLogged();
		this.setUrl();
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
		},
		setUrl(){
			if(process.env.NODE_ENV === "development"){
				this.$store.dispatch('setUrl', "http://localhost/lumen/project_mgmt/public/");
			}else {
				this.$store.dispatch('setUrl', "http://artl-app04/timely/api/public/");
			}
		}
	}
}
</script>
<style>

</style>
