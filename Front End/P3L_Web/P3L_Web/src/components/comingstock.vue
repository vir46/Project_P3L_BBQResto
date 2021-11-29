<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">History Of Stock Income</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="outcomePrepare()">
					Pengeluaran
				</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="datatable" :search="search">
                <template v-slot:[`item.settings`]="{ item }">
                    <v-btn small color="black" class="mr-2 icongreen" @click="forEdit(item)">
                        Modify
                    </v-btn>
                    <!-- <v-btn small color="black" class="iconred" @click="hapuslah(item.id_menu)">
                        <v-icon>mdi-trash-can</v-icon>
                    </v-btn> -->
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Modify Incoming</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid">
                    <v-container>
                        <v-text-field label="Jumlah Stock" v-model="stock" :rules="stockRules2" required></v-text-field>{{ satuan_temp }}
                        <v-text-field label="Harga Satuan" v-model="hargasatuan" :rules="stockRules" required></v-text-field>
                        <v-col cols="auto" sm="6" md="4">
                            <v-menu v-model="menudate" :close-on-content-click="false" :nudge-right="10"
                                transition="scale-transition" offset-y min-width="auto" d>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field disabled v-model="datenow" label="Reservation for"
                                        prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                                </template>
                                <v-date-picker v-model="reservedate" @input="menudate = false"></v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-container>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel">
                        Cancel
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="forAddStock" :disabled="!valid">
                        Update
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
                stock: 0,
                hargasatuan: 0,
                idtemp: "",
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
                        text: 'Tanggal Stock Masuk',
                        align: 'start'  ,
                        sortable: false,
                        value: 'tanggal_stock_in',
                    },
                    {
                        text: 'Total Harga',
                        value: 'total_harga_stock'
                    },
                    {
                        text: 'Harga satuan',
                        value: 'harga_satuan'
                    },
                    {
                        text: 'Bahan',
                        value: 'nama_bahan'
                    },
                    {
                        text: 'Pelaku Transaksi',
                        value: 'nama_karyawan'
                    },
                    {
                        text: 'Incoming Setting',
                        value: 'settings'
                    },
                ],
                max: 18,
                bahanRules: [
                     (v) => !!v || 'Bahan tidak boleh kosong',
                     (v) => (v || '').length <= this.max || `Maksimum ${this.max} karakter`
                ],
                karyawan: [],
                bahan: [],
                stockRules2: [
                     (v) => !!v || 'tidak boleh kosong',
                     (v) => v >= 1 || `Minimum ada 1 stock`
                ],
                stockRules: [
                     (v) => !!v || 'tidak boleh kosong',
                     (v) => v >= 50 || `Minimum harga 50`
                ],
                datatable: [],
                error_message: "",
                datenow:  new Date().getFullYear() + "-" + new Date().getMonth() + "-" + new Date().getDate(),
                editingId: null,
                id_aktif: localStorage.getItem("id"),
                stockbefore: 0,
                bahantemp: 0,
                stockslang: 0,
                
            }
        },
        methods: {
            outcomePrepare(){
                window.open('/outcome','_blank');
                localStorage.setItem("data",this.datatable);
            },
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
            forEdit(item){
                this.idtemp = item.id_stock_in;
                this.bahantemp = item.id_bahan;
                this.stock = item.jumlah_stock_transaksi;
                this.stockbefore = item.jumlah_stock_transaksi;
                this.hargasatuan = item.harga_satuan;
                this.dialog = true;
            },
            forAddStock(){
                this.stockslang = this.stock - this.stockbefore;
                var totalprice = this.stock * this.hargasatuan;
                var url = this.$api + "/incoming/" + this.idtemp;
                this.load = true;
                this.$http
                    .put(url, {
                        tanggal_stock_in: this.datenow,
                        total_harga_stock: totalprice,
                        jumlah_stock_transaksi: this.stock,
                        harga_satuan: this.hargasatuan,
                        // id_karyawan: this.id_aktif,
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
                        this.karyawanload();
                        this.snackbar = true;
                    })
                    .catch((error) => {
                        this.error_message = error.response.message;
                        this.color = "red";
                        this.snackbar = true;
                        this.load = false;
                    });
                    var bahancount = 0;
                    var lastcount = this.gettingcount();
                    bahancount = lastcount + this.stockslang;
                    console.log(bahancount);
                    var url2 = this.$api + "/stockchange/" + this.bahantemp;
                    this.load = true;
                    this.$http
                    .put(url2, {
                        jumlah_bahan: bahancount,
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
                    this.dialog = false;
            },
            gettingcount(){
                var lengthnya = this.bahan.length - 1;
                for(var i = 0; i <= lengthnya; i++){
                    if(this.bahantemp == this.bahan[i].id_bahan){
                        return this.bahan[i].jumlah_bahan;
                    }
                }
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
                        this.bahanload();
                        this.dataload();
                    })
            },
            dataload() {
                let url = this.$api + "/income";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.datatable = response.data.data;
                        this.turnIdtoStringg();
                    })
            },
            cancel() {
                this.clear(),
                this.dialogforAdd = false,
                this.dialog = false;
            },
            clear() {
                this.bahanname = null;
                this.jumlah = null ;
            },
            turnIdtoStringg(){
                this.datatable = this.datatable.map(v => ({...v, nama_karyawan: "", nama_bahan: ""}));
                var customlength = this.karyawan.length - 1;
                var customlength2 = this.datatable.length - 1;
                for (var i = 0; i <= customlength; i++){
                    for(var j = 0; j<= customlength2; j++){
                        if (this.datatable[j].id_karyawan == this.karyawan[i].id_karyawan) {
                        this.datatable[j].nama_karyawan = this.karyawan[i].nama_karyawan;
                        }
                    }
                }
                customlength = this.bahan.length - 1;
                for (i = 0; i <= customlength; i++){
                    for(j = 0; j<= customlength2; j++){
                        if (this.datatable[j].id_bahan == this.bahan[i].id_bahan) {
                        this.datatable[j].nama_bahan = this.bahan[i].nama_bahan;
                        }
                    }
                }
            }
        },
        beforeMount() {
            this.karyawanload();
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