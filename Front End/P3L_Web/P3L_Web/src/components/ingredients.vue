<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">Bahan Utama</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="dialogforAdd = true">
					ADD
				</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="bahan" :search="search">
                <template v-slot:[`item.settings`]="{ item }">
                    <v-btn small color="black" class="mr-2 icongreen" @click="forEdit(item.id_bahan)">
                        <v-icon>mdi-lead-pencil</v-icon>
                    </v-btn>
                    <v-btn small color="black" class="iconred" @click="hapuslah(item.id_bahan)">
                        <v-icon>mdi-trash-can</v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Edit</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid">
                    <v-container>
                        <v-text-field label="nama" v-model="bahanname" :rules="bahanRules" required></v-text-field>
                        <v-select v-model="satuan" :items="['ml', 'gram']"
                            label="Satuan" required></v-select>
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
                    <span class="headline">Adding New Ingredients</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form v-model="valid">
                        <v-text-field v-model="bahanname" label="Nama Bahan" :rules="bahanRules" required></v-text-field>
                        <v-select v-model="satuan" :items="['ml', 'gram']"
                            label="Satuan" required></v-select>
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
                valid: false,
                bahanname: "",
                satuan: "gram   ",
                snackbar: false,
                menudate: false,
                search: null,
                color: "",
                dialog: false,
                dialogforAdd: false,
                headers: [{
                        text: 'Nama Bahan',
                        align: 'start',
                        sortable: false,
                        value: 'nama_bahan',
                    },
                    {
                        text: 'Satuan',
                        value: 'satuan'
                    },
                    {
                        text: 'Pengaturan',
                        value: 'settings'
                    },
                ],
                max: 18,
                bahanRules: [
                     (v) => !!v || 'Bahan tidak boleh kosong',
                     (v) => (v || '').length <= this.max || `Maksimum ${this.max} karakter`
                ],
                bahan: [],
                error_message: "",
                editingId: null,
            }
        },
        methods: {
            bahanload() {
                let url = this.$api + "/ingredients";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.bahan = response.data.data;
                    })
            },
            cancel() {
                this.clear(),
                this.dialogforAdd = false,
                this.dialog = false;
            },
            forEdit(id) {
                this.editingId = id;
                let url = this.$api + "/ingredients/" + this.editingId;
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.bahanname = response.data.data.nama_bahan;
                        this.satuan = response.data.data.satuan;
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
                var url = this.$api + "/ingredients/" + this.editingId;
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
					this.bahanload();
				})
				.catch((error) => {
					this.error_message = error.response.data.message;
					this.color = "red";
					this.snackbar = true;
				});
            },
            forAdding() {
                var url = this.$api + "/registingredients";
                this.load = true;
                this.$http
				.post(url, {
                        nama_bahan: this.bahanname,
                        satuan: this.satuan,
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
					this.bahanload();
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
                var url = this.$api + "/ingredients/" + this.editingId;
                this.load = true;
                this.$http
                    .put(url, {
                        nama_bahan: this.bahanname,
                        satuan: this.satuan,
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
                        this.bahanload(); //mengambil data
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
                this.bahanname = null;
                this.jumlah = null ;
            },
        },
        beforeMount() {
            this.bahanload();
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