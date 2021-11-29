<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">Reservasi</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="dialogforAdd = true">
					ADD
				</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="reservasi" :search="search">
                <template v-slot:[`item.`]="{ item }">
                        {{ item.status_meja }}
                </template>
                <template v-slot:[`item.settings`]="{ item }">
                    <v-btn small color="black" class="mr-1 icongreen" @click="forEdit(item.id_reservasi)">
                        <v-icon>mdi-lead-pencil</v-icon>
                    </v-btn>
                    <v-btn small color="black" class="mr-1 iconred" @click="hapuslah(item.id_reservasi)">
                        <v-icon>mdi-trash-can</v-icon>
                    </v-btn>
                    <v-btn small color="black" class="iconsky" @click="generateQr(item)">
                        <v-icon>mdi-qrcode</v-icon> QR Code
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Reservation Edit</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid">
                    <v-container>
                        <v-select v-model="reservername" :items="customer" item-text="nama_customer" item-value="nama_customer" label="Customer"></v-select>
                        <v-col cols="auto" sm="6" md="4">
                            <v-menu v-model="menudate" :close-on-content-click="false" :nudge-right="10"
                                transition="scale-transition" offset-y min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-model="reservedate" label="Reservation for"
                                        prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                                </template>
                                <v-date-picker v-model="reservedate" @input="menudate = false"></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-select v-model="reservesession" :items="['lunch','dinner']" label="Session"></v-select>
                        <v-select v-model="reservetable" :items="meja" item-text="id_meja" item-value="id_meja" label="Nomor Meja"></v-select>
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
                    <span class="headline">New Reservasi</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form v-model="valid">
                        <v-text-field label="Tanggal Reservasi Masuk" v-model="todaydate" :rules="nameRules" disabled required ></v-text-field>
                        <v-text-field label="Jam Reservasi Masuk" v-model="todaytime" :rules="nameRules" disabled required ></v-text-field>
                        <v-select v-model="reservername" :items="customer" item-text="nama_customer" item-value="nama_customer" label="Customer"></v-select>
                        <v-col cols="auto" sm="6" md="4">
                            <v-menu v-model="menudate" :close-on-content-click="false" :nudge-right="10"
                                transition="scale-transition" offset-y min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-model="reservedate" label="Reservation for"
                                        prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                                </template>
                                <v-date-picker v-model="reservedate" @input="menudate = false"></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-select v-model="reservesession" :items="['lunch','dinner']" label="Session"></v-select>
                        <v-select v-model="reservetable" :items="meja" item-text="id_meja" item-value="id_meja" label="Nomor Meja"></v-select>
                        <v-text-field v-model="pencatat" label="Pencatat Reservasi" disabled></v-text-field>
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
                reservername: "",
                reserveridc : "",
                reserveridkaryawan : "",
                reservercustomer: "",
                reservetable: null,
                reservedate: new Date().getFullYear() + "-" + new Date().getMonth() + "-" + new Date().getDate(),
                reservesession: "",
                pencatat: localStorage.getItem("name"),
                todaydate: new Date().toISOString().slice(0, 10),
                todaytime: new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds(),
                valid: false,
                snackbar: false,
                menudate: false,
                search: null,
                temp: "",
                color: "",
                dialog: false,
                dialogforAdd: false,
                headers: [{
                        text: 'Nomor Reservasi',
                        align: 'start',
                        sortable: false,
                        value: 'id_reservasi',
                    },
                    {
                        text: 'Tanggal Reservasi Masuk',
                        value: 'tanggal_pencatatan_reservasi'
                    },
                    {
                        text: 'Jam Reservasi Masuk',
                        value: 'jam_pencatatan_reservasi'
                    },
                    {
                        text: 'Reservation for date',
                        value: 'tanggal_reservasi'
                    },
                    {
                        text: 'Sesi',
                        value: 'sesi_reservasi'
                    },
                    {
                        text: 'Nomor Meja',
                        value: 'id_meja'
                    },
                    {
                        text: 'Customer',
                        value: 'nama_customer'
                    },
                    {
                        text: 'Pencatat',
                        value: 'nama_karyawan'
                    },
                    {
                        text: 'Status',
                        value: 'status_reservasi'
                    },
                    {
                        text: 'Pengaturan',
                        value: 'settings'
                    },
                ],
                max: 10,
                priceRules: [
                     (v) => !!v || 'Harga harus diisi',
                     (v) => (v) >= 0 && (v) <= 30000 || `Harga Antara Free [0] - 30k`
                ],
                sizeRules: [
                     (v) => !!v || 'Size harus diisi',
                     (v) => (v) > 10 && (v) < 1000 || `Size Antara 10 - 1000`
                ],
                nameRules: [
                     (v) => !!v || 'Nama menu tidak boleh kosong :(',
                ],
                descRules:[
                    (v) => !!v || 'Harus ada description',
                    (v) => (v && v.length >= 30) || 'Minimal 30 Huruf',
                ],
                reservasi: [],
                customer: [],
                karyawan: [],
                meja: [],
                tanggal_catat: "",
                error_message: "",
                editingId: null,
            }
        },
        methods: {
            dataload() {
                let url = this.$api + "/reservasi";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.reservasi = response.data.data;
                        this.reservasi = this.reservasi.map(v => ({...v, nama_customer: "", nama_karyawan: ""}));
                        this.revealCustomer();
                        this.revealPegawai();
                    })
            },
            generateQr(object){
                var temp_object = object.id_reservasi;
                sessionStorage.setItem("qrgenerate",temp_object);
                window.open('/qrcode','_blank');
            },
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
            tableload(){
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
            getAllId(){
                var customlength = this.customer.length - 1;
                for (var i = 0; i <= customlength; i++){
                    if (this.reservername == this.customer[i].nama_customer) {
                        this.reserveridc = this.customer[i].id_customer;
                        i = customlength;
                    }
                }
                customlength = this.karyawan.length - 1;
                for (i = 0; i <= customlength; i++){
                    if (this.pencatat == this.karyawan[i].nama_karyawan) {
                        this.reserveridkaryawan = this.karyawan[i].id_karyawan;
                        i = customlength;
                    }
                }
                console.log(this.reserveridc);
                console.log(this.reserveridkaryawan);
                return 0;
            },
            revealCustomer(){
                // var temp_name = null;
                var lengthofreservation = this.reservasi.length - 1;
                var lengthofcustomer = this.customer.length - 1;
                for( var i = 0; i <=lengthofreservation; i++){
                    for(var j = 0; j <= lengthofcustomer; j++){
                        if (this.reservasi[i].id_customer == this.customer[j].id_customer) {
                            this.reservasi[i].nama_customer = this.customer[j].nama_customer
                        }
                    }
                }
                return 0;
            },
            nameBack(idcust){
                var temp = "";
                var lengthofreservation = this.customer.length - 1;
                for( var i = 0; i <= lengthofreservation; i++){
                    if (idcust == this.customer[i].id_customer) {
                            temp = this.customer[i].nama_customer
                            i = lengthofreservation;
                            return temp;
                        }
                }
                return temp;
            },
            revealPegawai(){
                var lengthofreservation = this.reservasi.length - 1;
                var lengthofkaryawan = this.karyawan.length - 1;
                for( var i = 0; i <=lengthofreservation; i++){
                    for(var j = 0; j <= lengthofkaryawan; j++){
                        if (this.reservasi[i].id_karyawan == this.karyawan[j].id_karyawan) {
                            this.reservasi[i].nama_karyawan = this.karyawan[j].nama_karyawan
                        }
                    }
                }
                console.log(this.reservasi);
                return 0;
            },
            cancel() {
                this.clear(),
                this.dialogforAdd = false,
                this.dialog = false;
            },
            forEdit(id) {
                this.editingId = id;
                let url = this.$api + "/reservasi/" + this.editingId;
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.reservedate = response.data.data.tanggal_reservasi;
                        this.reservesession = response.data.data.sesi_reservasi;
                        this.reservername = this.nameBack(response.data.data.id_customer);
                        this.reservetable = response.data.data.id_meja;
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
                var url = this.$api + "/reservasi/" + this.editingId;
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
					this.dataload();
				})
				.catch((error) => {
					this.error_message = error.response.data.message;
					this.color = "red";
					this.snackbar = true;
				});
            },
            forAdding() {
                this.getAllId();
                var url = this.$api + "/addreservasi";
                this.load = true;
                this.$http
				.post(url, {
                        tanggal_pencatatan_reservasi: this.todaydate,
                        jam_pencatatan_reservasi: this.todaytime,
                        tanggal_reservasi: this.reservedate,
                        sesi_reservasi: this.reservesession,
                        id_customer: this.reserveridc,
                        id_meja: this.reservetable,
                        id_karyawan: this.reserveridkaryawan,
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
					this.dataload();
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
                this.getAllId();
                var url = this.$api + "/reservasi/" + this.editingId;
                this.load = true;
                this.$http
                    .put(url, {
                        tanggal_reservasi: this.reservedate,
                        sesi_reservasi: this.reservesession,
                        id_customer: this.reserveridc,
                        id_meja: this.reservetable,
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
                        this.dataload(); //mengambil data
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
                
            },
        },
        beforeMount() {
            this.tableload();
            this.customerload();
            this.karyawanload();
            this.dataload();
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

    .iconsky{
        color: skyblue;
    }
</style>