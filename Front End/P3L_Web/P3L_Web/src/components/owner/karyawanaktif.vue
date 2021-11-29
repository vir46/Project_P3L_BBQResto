<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">Karyawan</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="dialogforAdd = true">
					ADD
				</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="karyawan" :search="search">
                <template v-slot:[`item.settings`]="{ item }">
                    <v-btn :disabled="item.status_karyawan!='Aktif'||item.role_karyawan=='owner'" small class="mr-2" @click="prepareEditKaryawan(item.id_karyawan)">
                        edit
                    </v-btn>
                    <v-btn :disabled="item.status_karyawan!='Aktif'||item.role_karyawan=='owner'" small @click="deathActive(item)">
                        Deactivate
                    </v-btn>
                </template>
                <template v-slot:[`item.status_karyawan`]="{ item }">
                    <v-chip :color="getColor(item)" dark>
                        {{ item.status_karyawan }}
                    </v-chip>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Edit Form</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form v-model="valid">
                        <v-text-field v-model="karyawanuser" :rules="accountRules" :counter="12" required></v-text-field>
                        <v-select v-model="karyawangender" :items="['Pria', 'Wanita']"
                            label="Kelamin" required></v-select>
                        <v-text-field v-model="karyawanphone" label="Telepon" required :rules="phoneRules" ></v-text-field>
                        <v-text-field v-model="karyawanmail" label="Email" :rules="emailRules" required></v-text-field>
                        <v-select v-model="stats" :items="['Aktif','Resign']" disabled></v-select>
                        </v-form>
                    </v-container>
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
                    <span class="headline">Karyawan Baru</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid">
                    <v-container>
                        <v-text-field v-model="karyawanname" label="Nama" required :rules="nameRules"  :counter="8"></v-text-field>
                        <v-select v-model="karyawangender" :items="['Pria', 'Wanita']"
                            label="Kelamin" required></v-select>
                        <v-text-field v-model="karyawanphone" label="Telepon" required :rules="phoneRules" ></v-text-field>
                        <v-text-field v-model="karyawanuser" label="User Account" required :rules="accountRules"  :counter="12"></v-text-field>
                        <v-select v-model="karyawanrole" :items="['ops_manager', 'Chef', 'Waiter', 'Kasir', 'Waiter & Cashier']"
                            label="Role" required></v-select>
                        <v-text-field v-model="karyawanmail" label="Email" :rules="emailRules" required></v-text-field>
                        <v-text-field label="Password" required type="password" v-model="karyawanpassword" :rules="passRules">
                        </v-text-field>
                        <v-text-field label="Status" :value="Aktif" required v-model="stats" disabled></v-text-field>
                        <!--  -->
                        <v-col cols="12" sm="6" md="4">
                            <v-menu v-model="menudate" :close-on-content-click="false" :nudge-right="40"
                                transition="scale-transition" offset-y min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-model="karyawandate" label="Tanggal Masuk"
                                        prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                                </template>
                                <v-date-picker v-model="karyawandate" @input="menudate = false"></v-date-picker>
                            </v-menu>
                        </v-col>
                        <!--  -->
                    </v-container>
                    </v-form>
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
                menudate: false,
                search: null,
                dialog: false,
                valid: false,
                snackbar: false,
                dialogforAdd: false,
                headers: [{
                        text: 'Nama Asli',
                        align: 'start',
                        sortable: false,
                        value: 'nama_karyawan',
                    },
                    {
                        text: 'Kelamin',
                        value: 'kelamin'
                    },
                    {
                        text: 'Nomor Telp',
                        value: 'telepon'
                    },
                    {
                        text: 'Email',
                        value: 'email'
                    },
                    {
                        text: 'Role Pekerjaan',
                        value: 'role_karyawan'
                    },
                    {
                        text: 'Status',
                        value: 'status_karyawan'
                    },
                    {
                        text: 'User_Account',
                        value: 'user_akun'
                    },
                    {
                        text: 'Tanggal_Masuk',
                        value: 'tanggal_masuk'
                    },
                    {
                        text: 'Pengaturan',
                        value: 'settings'
                    },
                ],
                max: 8,
                numbermax: 11,
                karyawanname: null,
                nameRules: [
                     (v) => !!v || 'Nama tidak boleh kosong :(',
                     (v) => (v || '').length <= this.max || `Maksimum ${this.max} karakter`
                ],
                karyawanrole: null,
                karyawangender: null,
                genderRules: [
                    (v) => !!v || 'Wajib Diisi',
                ],
                karyawanphone: null,
                phoneRules: [
                    (v) => !!v || 'Wajib Diisi',
                    (v) => (v || '').length >= this.numbermax || `Min ${this.max} Digit`
                ],
                karyawanmail: null,
                emailRules: [
                     (v) => !!v || 'Email tidak boleh kosong :(',
                     (v) => /.+@.+\..+/.test(v) || 'Masukkan Email dengan Benar!',
                ],
                karyawanpassword: null,
                passRules: [
                     (v) => !!v || 'Password tidak boleh kosong :(',
                     (v) => (v || '').length >= this.max || `Minimum ${this.max} karakter`,
                     (v) => (v || '').indexOf(' ') < 0 || 'No spaces are allowed'
                ],
                karyawanuser: null,
                accountRules: [
                     (v) => !!v || 'Account name tidak boleh kosong :(',
                     (v) => (v || '').length <= this.max+4 || `Maksimum ${this.max+4} karakter`
                ],
                stats: "Aktif",
                karyawan: [],
                editingId: null,
            }
        },
        methods: {
            karyawanload() {
                let url = this.$api + "/karyawan";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.karyawan = response.data.data;
                    })
            },
            getColor(karyawan) {
                if (karyawan.status_karyawan == "Aktif") return 'green'
                else return 'red'
            },
            cancel() {
                this.clear();
                this.dialogforAdd = false,
                this.dialog = false;
            },
            resetform() {
                this.form = {
                    karyawanuser: "",
                }
            },
            prepareEditKaryawan(id) {
                this.editingId = id;
                let url = this.$api + "/karyawan/" + this.editingId;
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        console.log(response);
                        this.karyawanuser = response.data.data.user_akun;
                        this.karyawangender = response.data.data.kelamin;
                        this.karyawanphone = response.data.data.telepon;
                        this.karyawanmail = response.data.data.email;
                        this.stats = response.data.data.status_karyawan;
                        // this.form.karyawanuser= response.data.data.user_akun;
                    })
                    .catch((error) => {
                        this.error_message = error.response.data.message;
                        this.color = "red";
                        this.snackbar = true;
                    });
                this.dialog = true;
            },
            deathActive(item){
                this.editingId = item.id_karyawan;
                var url = this.$api + "/karyawan/" + this.editingId;
                this.load = true;
                this.$http
                    .put(url, {
                        user_akun: item.user_akun,
                        status_karyawan: "resign",
                    }, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        console.log(response);
                        this.error_message = response.data.message;
                        this.color = "green";
                        this.snackbar = true;
                        this.load = false;
                        this.karyawanload(); //mengambil data
                    })
                    .catch((error) => {
                        this.error_message = error.response.message;
                        this.color = "red";
                        this.snackbar = true;
                        this.load = false;
                    });
            },
            forAdding() {
                var url = this.$api + "/registkaryawan";
                this.load = true;
                this.$http
				.post(url, {
                        nama_karyawan: this.karyawanname,
                        kelamin: this.karyawangender,
                        telepon: this.karyawanphone,
                        email: this.karyawanmail,
                        role_karyawan: this.karyawanrole,
                        user_akun: this.karyawanuser,
                        password: this.karyawanpassword,
                        tanggal_masuk: this.karyawandate,
                    }, {
					headers: {
						Authorization: "Bearer " + localStorage.getItem("token"),
					},
				})
				.then((response) => {
                    console.log(response);
					this.error_message = response.data.message;
					this.color = "green";
					this.snackbar = true;
					this.load = false;
					this.karyawanload();
				})
				.catch((error) => {
                    console.log(error);
					this.error_message = error.response.data.message;
					this.color = "red";
					this.snackbar = true;
					this.load = false;
				});
                this.clear();
                this.dialogforAdd = false;
            },
            forUpdate() {
                var url = this.$api + "/karyawan/" + this.editingId;
                this.load = true;
                this.$http
                    .put(url, {
                        user_akun: this.karyawanuser,
                        kelamin: this.karyawangender,
                        telepon: this.karyawanphone,
                        email: this.karyawanmail,
                        status_karyawan: this.stats,
                    }, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        console.log(response);
                        this.error_message = response.data.message;
                        this.color = "green";
                        this.snackbar = true;
                        this.load = false;
                        // this.close();
                        this.karyawanload(); //mengambil data
                        // this.resetForm();
                    })
                    .catch((error) => {
                        this.error_message = error.response.message;
                        this.color = "red";
                        this.snackbar = true;
                        this.load = false;
                    });
                this.clear();
                this.dialog = false;
            },
            clear() {
                this.karyawandate = "";
                this.karyawanrole = "";
                this.karyawanname = "";
                this.karyawangender = "";
                this.karyawanphone = "";
                this.karyawanmail = "";
                this.karyawanpassword = "";
                this.karyawanuser = "";
                this.stats = "";
            },
        },
        beforeMount() {
            this.karyawanload();
        },
    }
</script>