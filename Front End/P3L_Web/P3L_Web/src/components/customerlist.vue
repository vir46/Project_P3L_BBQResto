<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">Customer</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="dialogforAdd = true">
					ADD
				</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="customer" :search="search">
                <template v-slot:[`item.settings`]="{ item }">
                    <v-btn small color="black" class="mr-2 icongreen" @click="forEditCustomer(item.id_customer)">
                        <v-icon>mdi-lead-pencil</v-icon>
                    </v-btn>
                    <v-btn small color="black" class="iconred" @click="hapuslah(item.id_customer)">
                        <v-icon>mdi-trash-can</v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Cust change</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid">
                    <v-container>
                        <v-text-field label="nama" v-model="customername" :rules="nameRules" required></v-text-field>
                        <v-text-field label="Telepon" v-model="customertelpon" :rules="phoneRules" :counter="12" required></v-text-field>
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
                    <span class="headline">Adding Customer</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form v-model="valid">
                        <v-text-field v-model="customername" label="Nama" :rules="nameRules" required></v-text-field>
                        <v-text-field v-model="customeremail" label="Email" :rules="emailRules" required></v-text-field>
                        <v-text-field v-model="customertelpon" label="Phone Number" :rules="phoneRules" :counter="12" required></v-text-field>
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
                        text: 'Nama',
                        align: 'start',
                        sortable: false,
                        value: 'nama_customer',
                    },
                    {
                        text: 'Email',
                        value: 'email_customer'
                    },
                    {
                        text: 'telepon',
                        value: 'telepon_customer'
                    },
                    {
                        text: 'Pengaturan',
                        value: 'settings'
                    },
                ],
                max: 10,
                nameRules: [
                     (v) => !!v || 'Nama tidak boleh kosong :(',
                     (v) => (v || '').length <= this.max || `Maksimum ${this.max} karakter`
                ],
                emailRules: [
                     (v) => !!v || 'Email tidak boleh kosong :(',
                     (v) => /.+@.+\..+/.test(v) || 'Masukkan Email dengan Benar!',
                ],
                phoneRules:[
                    (v) => !!v || 'Harus ada nomor telpon',
                    (v) => (v && v.length >= 9) || 'Minimal 9 Digit',
                ],
                customername: "",
                customeremail: "",
                customertelpon: "",
                customer: [],
                error_message: "",
                editingId: null,
            }
        },
        methods: {
            customerload() {
                let url = this.$api + "/customer";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.customer = response.data.data;
                    })
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
            forEditCustomer(id) {
                this.editingId = id;
                let url = this.$api + "/customer/" + this.editingId;
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.customername = response.data.data.nama_customer;
                        this.customertelpon = response.data.data.telepon_customer;

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
                var url = this.$api + "/customer/" + this.editingId;
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
					this.customerload();
				})
				.catch((error) => {
					this.error_message = error.response.data.message;
					this.color = "red";
					this.snackbar = true;
				});
            },
            forAdding() {
                var url = this.$api + "/addcustomer";
                this.load = true;
                this.$http
				.post(url, {
                        nama_customer: this.customername,
                        email_customer: this.customeremail,
                        telepon_customer: this.customertelpon,
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
					this.customerload();
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
                var url = this.$api + "/customer/" + this.editingId;
                this.load = true;
                this.$http
                    .put(url, {
                        nama_customer: this.customername,
                        telepon_customer: this.customertelpon,
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
                        this.customerload(); //mengambil data
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
                this.customername = null;
                this.customeremail = null ;
                this.customertelpon = null ;
            },
        },
        beforeMount() {
            this.customerload();
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