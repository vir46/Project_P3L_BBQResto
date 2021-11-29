<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">Meja</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="dialogforAdd = true">
					ADD
				</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="meja" :search="search">
                <template v-slot:[`item.settings`]="{ item }">
                    <v-btn small color="black" class="mr-2 icongreen" @click="forEdit(item.id_meja)">
                        <v-icon>mdi-lead-pencil</v-icon>
                    </v-btn>
                    <v-btn small color="black" class="iconred" @click="hapuslah(item.id_meja)">
                        <v-icon>mdi-trash-can</v-icon>
                    </v-btn>
                </template>
                <template v-slot:[`item.status_meja`]="{ item }">
                    <v-chip :color="getColor(item)" dark>
                        {{ item.status_meja }}
                    </v-chip>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Ubah Kapasitas</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid">
                    <v-container>
                        <v-text-field v-model="capacitymeja" label="Kapasitas" :rules="capacityRules" required></v-text-field>
                    </v-container>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel">
                        Cancel
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="forUpdate" :disabled="!valid">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogforAdd" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Tambah Meja</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form v-model="valid">
                        <v-text-field v-model="capacitymeja" label="Kapasitas" :rules="capacityRules" required></v-text-field>
                        </v-form>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel">
                        Cancel
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="forAdding" :disabled="!valid">
                        Add
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>
			{{ error_message }}
		</v-snackbar>
    </v-main>
</template>

<script>
    export default {
        data() {
            return {
                karyawandate: new Date().toISOString().substr(0, 10),
                valid: false,
                snackbar: false,
                menudate: false,
                search: null,
                color: "",
                dialog: false,
                dialogforAdd: false,
                headers: [{
                        text: 'Nomor Meja',
                        align: 'start',
                        sortable: false,
                        value: 'id_meja',
                    },
                    {
                        text: 'Status',
                        value: 'status_meja'
                    },
                    {
                        text: 'Kapasitas (orang)',
                        value: 'capacity'
                    },
                    {
                        text: 'Pengaturan',
                        value: 'settings'
                    },
                ],
                max: 10,
                capacityRules: [
                     (v) => !!v || 'Kapasitas tidak boleh kosong',
                     (v) => (!!v && v) <= 10 && (!!v && v) >= 1 || 'Kapasitas harus 1 - 10'
                ],
                capacitymeja: "",
                meja: [],
                error_message: "",
                editingId: null,
            }
        },
        methods: {
            mejaload() {
                let url = this.$api + "/meja";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.meja = response.data.data;
                    })
            },
            getColor(meja) {
                if (meja.status_meja == "Available") return 'green'
                else return 'red'
            },
            cancel() {
                this.clear(),
                this.dialogforAdd = false,
                this.dialog = false;
            },
            resetform() {
                this.form = {
                    karyawanuser: "",
                }
            },
            forEdit(id) {
                this.editingId = id;
                let url = this.$api + "/meja/" + this.editingId;
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.capacitymeja = response.data.data.capacity;
                    })
                    .catch((error) => {
                        this.error_message = error.response.message;
                        this.color = "red";
                        this.snackbar = true;
                    });
                this.dialog = true;
            },
            hapuslah(item){
                this.editingId = item;
                var url = this.$api + "/meja/" + this.editingId;
                this.$http
				.delete(url, {
					headers: {
						Authorization: "Bearer " + localStorage.getItem("token"),
					},
				})
				.then((response) => {
					this.error_message = response.data.message;
                    this.color = "green";
					this.snackbar = true;
					this.mejaload();
				})
				.catch((error) => {
					this.error_message = error.response.data.message;
					this.color = "red";
					this.snackbar = true;
				});
            },
            forAdding() {
                var url = this.$api + "/tambahmeja";
                this.load = true;
                this.$http
				.post(url, {
                        capacity: this.capacitymeja,
                    }, {
					headers: {
						Authorization: "Bearer " + localStorage.getItem("token"),
					},
				})
				.then((response) => {
					this.error_message = response.data.message;
					this.color = "green";
					this.snackbar = true;
					this.load = false;
					this.mejaload();
				})
				.catch((error) => {
					this.error_message = error.response.data.message;
					this.color = "red";
					this.snackbar = true;
					this.load = false;
				});
                this.clear();
                this.dialogforAdd = false;
            },
            forUpdate() {
                var url = this.$api + "/meja/" + this.editingId;
                this.load = true;
                this.$http
                    .put(url, {
                        capacity: this.capacitymeja,
                    }, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        console.log(response);
                        this.error_message = response.data.message;
                        if(response.data.value==0){
                            this.color = "red";
                        }else{
                            this.color = "green";
                        }
                        this.load = false;
                        this.mejaload(); //mengambil data
                        this.snackbar = true;
                    })
                    .catch((error) => {
                        // this.error_message = error.response.message;
                        this.error_message = error.response.message;
                        this.color = "red";
                        this.snackbar = true;
                        this.load = false;
                    });
                this.clear();
                this.dialog = false;
            },
            clear() {
                this.capacitymeja = "";
            },
        },
        beforeMount() {
            this.mejaload();
        },
    }
</script>

<style scoped>
    .icongreen{
        color: limegreen;
    }

    .iconred{
        color: red;
    }
</style>