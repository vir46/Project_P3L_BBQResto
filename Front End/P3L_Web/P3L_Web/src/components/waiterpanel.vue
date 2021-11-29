<template>
	<div class="dashboard">
		<v-navigation-drawer class="fullheight" width="256" permanent app fixed>
			<v-list-item>
				<v-list-item-content>
					<v-list-item-title class="title">
						{{ username }}
					</v-list-item-title>
					<v-list-item-subtitle>
						{{ role }}
					</v-list-item-subtitle>
				</v-list-item-content>
			</v-list-item>

			<v-divider></v-divider>

			<v-list dense nav>
				<v-list-item
					v-for="item in items"
					:key="item.title"
					link
					tag="router-link" :to="item.to">
					<v-list-item-content>
						<v-list-item-title>{{ item.title }}</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
			</v-list>
			<v-btn color="blue darken-1" text @click="exit">
                <v-icon>mdi-exit-to-app</v-icon>Exit
            </v-btn>
		</v-navigation-drawer>
		<div class="grey lighten-4 fullheight pa-5">
			<router-view></router-view>
		</div>
	</div>
</template>
<script>
export default {
	name: "Dashboard",
	data() {
		return {
            username: localStorage.getItem("name"),
            role: localStorage.getItem("role"),
			items: [
				{ 
					title: "Homepage", 
					to: "/waitercashierpanel" 
				},
				{ 
					title: "Reservation", 
					to: "/reservation" 
				},
				{ 
					title: "Order", 
					to: "/orderan" 
				},
				{ 
					title: "Payment Checklist", 
					to: "/payment" 
				},
                ]
		};
	},
	methods:{
		exit(){
			localStorage.removeItem("token");
			localStorage.removeItem("id");
			localStorage.removeItem("role");
			localStorage.removeItem("name");
			location.reload();
		},
	},
};
</script>
<style scoped>
	.fullheight {
		min-height: 100vh !important;
	}
</style>