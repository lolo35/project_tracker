<template>
	<navbar></navbar>
	<main class="flex w-full h-screen">
		<sidebar></sidebar>
		<section class="w-full p-4">
			<router-view name="home"></router-view>
		</section>
	</main>
	<add-team-modal v-if="teams.showModal"></add-team-modal>
</template>

<script>
// @ is an alias to /src
import Navbar from '@/components/Navigation/Navbar.vue';
import Sidebar from '@/components/Navigation/Sidebar.vue';
import AddTeamModal from '@/components/Teams/AddTeamModal.vue';
import { mapState } from 'vuex';

export default {
	name: 'Home',
	components: {
		Navbar,
		Sidebar,
		AddTeamModal,
	},
	computed: {
        ...mapState(['teams']),
    }
}
</script>
