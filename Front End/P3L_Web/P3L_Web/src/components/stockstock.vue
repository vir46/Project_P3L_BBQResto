<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">Stock Control Panel</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="prepareEmpty()">
					Show All Empty Stock
				</v-btn>
                <!-- <v-spacer></v-spacer> -->
                <v-btn color="primary" @click="stockList()" style="margin-left:10px">
					Laporan Remaining Stock
				</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="bahan" :search="search">
                <template v-slot:[`item.settings`]="{ item }">
                    <v-btn small color="black" class="mr-1 icongreen" @click="addPrepare(item.id_bahan)">
                        <v-icon>mdi-cart-arrow-up</v-icon>
                    </v-btn>
                    <v-btn small color="black" class="mr-1 iconred" @click="removePrepare(item.id_bahan)" :disabled="checkifEmpty(item.id_bahan)">
                        <v-icon>mdi-cart-remove</v-icon>
                    </v-btn>
                    <v-btn small color="black" class="mr-1 iconorange" @click="editPrepare(item.id_bahan)" >
                        Edit Stock
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Add Stock?</span>
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
                                    <v-text-field disabled v-model="datechange" label="Reservation for"
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
                        Purchase
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogEdit" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Change Stock?</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form v-model="valid">
                        <v-text-field label="Jumlah Stock" v-model="stock" :rules="stockRules2" required></v-text-field>{{ satuan_temp }}
                        </v-form>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel">
                        Cancel
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="forEditStock" :disabled="!valid">
                        Change
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogEmpty" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Empty stock</span>
                </v-card-title>
                <v-card-text>
                    <v-data-table :headers="headtwo" :items="bahan_empty">
                    </v-data-table>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>   
                    <v-btn color="blue darken-1" text @click="cancel">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogRemove" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">deleting stock</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <span>Are you sure you want to delete all stock?</span>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel">
                        Cancel
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="forRemove">
                        Yes, Sure!
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
                totalharga: 0,
                hargasatuan: "",
                dialogEmpty: false,
                datechange: new Date().getFullYear() + "-" + new Date().getMonth() + "-" + new Date().getDate(),
                satuan_temp: "",
                temp_stock: 0,
                stock: null,
                valid: false,
                satuan: "gram   ",
                snackbar: false,
                menudate: false,
                search: null,
                color: "",
                dialog: false,
                dialogEdit: false,
                dialogRemove: false,
                dialogforAdd: false,
                headtwo: [{
                    text: 'Nama Bahan Habis',
                    align: 'start',
                    sortable: false,
                    value: 'bahan_habis',
                }],
                headers: [{
                        text: 'Nama Bahan',
                        align: 'start',
                        sortable: false,
                        value: 'nama_bahan',
                    },
                    {
                        text: 'Stock Bahan',
                        value: 'jumlah_bahan'
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
                stockRules2: [
                     (v) => !!v || 'tidak boleh kosong',
                     (v) => v >= 1 || `Minimum ada 1 stock`
                ],
                stockRules: [
                     (v) => !!v || 'tidak boleh kosong',
                     (v) => v >= 50 || `Minimum harga 50`
                ],
                bahan: [],
                bahan_empty: [],
                pegawai: localStorage.getItem("name"),
                id_aktif: localStorage.getItem("id"),
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
            stockList(){
                window.open('/stocksisa','_blank');
            },
            prepareEmpty(){
                var penghitung = 0;
                this.bahan_empty = this.bahan;
                this.bahan_empty = this.bahan_empty.map(v => ({...v, bahan_habis: " "}));
                // this.bahan_empty = this.bahan_empty.map({bahan_habis: ""});
                console.log(this.bahan_empty);
                var lengthnya = this.bahan.length - 1;
                for(var i = 0; i <= lengthnya; i++){
                    if(this.bahan[i].jumlah_bahan == 0){
                        this.bahan_empty[penghitung].bahan_habis = this.bahan[i].nama_bahan;
                        penghitung++;
                    }
                }
                console.log(this.bahan_empty);
                this.dialogEmpty = true;
            },
            cancel() {
                this.clear(),
                this.dialogEmpty = false;
                this.dialogforAdd = false;
                this.dialog = false;
                this.dialogEdit = false;
            },
            searchStock(id_baru){
                var lengthnya = this.bahan.length - 1;
                for(var i = 0; i <= lengthnya; i++){
                    if(id_baru == this.bahan[i].id_bahan){
                        this.temp_stock = this.bahan[i].jumlah_bahan;
                        return 0;
                    }
                }
                return 0;
            },
            satuanTemp(id){
                var lengthnya = this.bahan.length - 1;
                for(var i = 0; i <= lengthnya; i++){
                    if(id == this.bahan[i].id_bahan){
                        this.satuan_temp = this.bahan[i].satuan;
                        return 0;
                    }
                }
                return 0;
            },
            addPrepare(id){
                this.editingId = id;
                this.searchStock(this.editingId);
                this.satuanTemp(this.editingId);
                this.dialog = true;
            },
            editPrepare(id){
                this.editingId = id;
                this.searchStock(this.editingId);
                this.satuanTemp(this.editingId);
                this.stock = this.temp_stock;
                this.dialogEdit = true;
            },
            checkifEmpty(id){
                var lengthnya = this.bahan.length - 1;
                for(var i = 0; i <= lengthnya; i++){
                    if(id == this.bahan[i].id_bahan){
                        if(this.bahan[i].jumlah_bahan == 0){
                            return true;
                        }else{
                            return false;
                        }
                    }
                }
                return 0;
            },
            writeHistory(stock,bahanid,jenisberubah){
                var url = this.$api + "/newhistori";
                this.load = true;
                this.$http
				.post(url, {
                        jenis_perubahan_stock: jenisberubah,
                        tanggal_stock_berubah: this.datechange,
                        jumlah_stock_perubahan: stock,
                        id_bahan: bahanid,
                        id_karyawan: this.id_aktif,
                    }, {
					headers: {
						Authorization: "Bearer " + localStorage.getItem("token"),
					},
				})
				.then((response) => {
					this.error_message = response.data.message;
					
				})
				.catch((error) => {
					this.error_message = error.response.data.message;
				});
            },
            removePrepare(id){
                this.editingId = id;
                this.searchStock(this.editingId);
                this.dialogRemove = true;
            },
            forRemove(){
                var stockremoved = 0;
                this.searchStock(this.editingId);
                stockremoved = this.temp_stock;
                this.writeHistory(stockremoved,this.editingId,"terbuang");
                var url = this.$api + "/stockchange/" + this.editingId;
                this.load = true;
                this.$http
                    .put(url, {
                        jumlah_bahan: 0,
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
                this.dialogRemove = false;
            },
            forEditStock() {
                var stockremoved = 0;
                var stocknow = 0;
                var counted = 0;
                this.searchStock(this.editingId);
                stockremoved = this.temp_stock;
                var url = this.$api + "/stockchange/" + this.editingId;
                this.load = true;
                this.$http
                    .put(url, {
                        jumlah_bahan: this.stock,
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
                this.searchStock(this.editingId);
                stocknow = this.stock;
                if(stocknow > stockremoved){
                    counted = stocknow - stockremoved;
                }else{
                    counted = stockremoved - stocknow;
                }
                this.writeHistory(counted,this.editingId,"penyesuaian stock (diubah)");
                this.dialogEdit = false;
            },
            writeComing(stock,bahanid){
                this.totalharga = this.hargasatuan * stock;
                console.log(this.datechange);
                console.log(this.totalharga);
                console.log(stock);
                console.log(this.hargasatuan);
                console.log(bahanid);
                console.log(this.id_aktif);
                var url = this.$api + "/newincome";
                this.load = true;
                this.$http
                    .post(url, {
                        tanggal_stock_in: this.datechange,
                        total_harga_stock: this.totalharga,
                        jumlah_stock_transaksi: stock,
                        harga_satuan: this.hargasatuan,
                        id_bahan: bahanid,
                        id_karyawan:this.id_aktif
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
                        this.bahanload();
                        this.snackbar = true;
                    })
                    .catch((error) => {
                        // this.error_message = error.response.message;
                        this.error_message = error.response.message;
                        this.color = "red";
                        this.snackbar = true;
                        this.load = false;
                    });
            },
            forAddStock() {
                var temp = +this.stock + +this.temp_stock;
                this.writeHistory(this.stock,this.editingId,"refill (purchase)");
                this.writeComing(this.stock,this.editingId);
                var url = this.$api + "/stockchange/" + this.editingId;
                this.load = true;
                this.$http
                    .put(url, {
                        jumlah_bahan: temp,
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
        color: #fa646b;
    }

    .iconorange{
        color: orange;
    }
</style>