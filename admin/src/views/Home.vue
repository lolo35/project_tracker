<template>
	<navbar></navbar>
	<main class="flex w-full h-screen">
		<sidebar></sidebar>
		<section class="w-full p-4">
			<router-view name="home"></router-view>
		</section>
	</main>
</template>

<script>
// @ is an alias to /src
import Navbar from '@/components/Navigation/Navbar.vue';
import Sidebar from '@/components/Navigation/Sidebar.vue';

export default {
	name: 'Home',
	components: {
		Navbar,
		Sidebar,
	}
}
</script>
