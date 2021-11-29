<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">Orderan Masuk</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
            </v-card-title>
            <v-data-table :headers="headers" :items="orderan" :search="search">
                <template v-slot:[`item.option`]="{ item }">
                    <v-btn small color="black" :disabled="item.status_order=='Paid'|| item.status_order=='served'" class="mr-2 icongreen" @click="prepareEdit(item.id_order)">
                        Serve Ready<v-icon>mdi-lead-pencil</v-icon>
                    </v-btn>
                </template>
                <template v-slot:[`item.pay`]="{ item }">
                    <v-btn small color="black" :disabled="item.status_order=='Paid' || item.status_order=='ordered' " class="mr-2 icongreen" @click="preparePay(item.id_order)">
                        PAY THIS ORDER <v-icon>mdi-wallet</v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialogPayment" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Pembayaran</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid">
                    <v-container>
                        <v-text-field label="Date Now" v-model="payDate" v-show="alwaysfalse" disabled required ></v-text-field>
                        <v-text-field label="Price U Must Pay" v-model="paytemp" disabled></v-text-field>
                        <v-select v-model="tipe" :items="['cash','kredit','debit']" label="Tipe Pembayaran"></v-select>
                        <v-text-field label="Payment Nominal" v-model="nominal" :rules="payRules" required ></v-text-field>
                    </v-container>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel">
                        Cancel
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="forPaid" :disabled="!valid">
                        PAY
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
                alwaysfalse: false,
                payDate: new Date().toISOString().substr(0, 10),
                savePayId: "",
                saveTableID: "",
                tipe: "",
                nominal: 0,
                paytemp: 0,
                dialogPayment: false,
                payselect: false,
                valid: false,
                foodsjl: 0,
                foods: [],
                foodie: "",
                bahanname: "",
                satuan: "gram   ",
                snackbar: false,
                menudate: false,
                search: null,
                color: "",
                dialog: false,
                dialogforAdd: false,
                headers: [{
                        text: 'Order Number',
                        align: 'start',
                        sortable: false,
                        value: 'id_order',
                    },
                    {
                        text: 'Quantity',
                        value: 'jumlah_order'
                    },
                    {
                        text: 'Total Harga',
                        value: 'harga_order'
                    },
                    {
                        text: 'Status',
                        value: 'status_order'
                    },
                    {
                        text: 'Reservasi Number',
                        value: 'id_reservasi'
                    },
                    {
                        text: 'Menu Yang Dipesan',
                        value: 'nama_menu'
                    },
                    {
                        text: 'Opsi',
                        value: 'option'
                    },
                    {
                        text: 'Paycheck',
                        value: 'pay'
                    },
                ],
                max: 18,
                jlRules: [
                    (v) => !!v || 'Jumlah tidak boleh kosong',
                    (v) => v >= 0 || `Harus lebih dari 0`
                ],
                payRules: [
                     (v) => !!v || 'Nominal tidak boleh kosong',
                     (v) => v >= this.paytemp || `Uangmu tidak cukup untuk membayar`
                ],
                menu: [],
                orderan: [],
                datatable: [],
                error_message: "",
                verifCode: "",
                editingId: null,
                change: 0,
                reservasi: [],
            }
        },
        methods: {
            dataload() {
                let url = this.$api + "/orderan";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.orderan = response.data.data;
                        this.turnIdtoStringg();
                    })
            },
            gettringRes(){
                let url = this.$api + "/reservasi";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.reservasi = response.data.data;
                        console.log(this.reservasi.length);
                        console.log(this.orderan);
                    })
            },
            gettingpricetopay(price){
                var pricel = this.orderan.length - 1;
                console.log(pricel);
                var tempreserv;
                for(var i = 0; i <= pricel ; i++){
                    if(this.orderan[i].id_order == price){
                        console.log("we are here");
                         this.paytemp = this.orderan[i].harga_order;
                         tempreserv = this.orderan[i].id_reservasi;
                    }
                }
                    var newl = this.reservasi.length - 1;
                    console.log(newl);
                    for(i = 0; i <= newl ; i++){
                    if(tempreserv == this.reservasi[i].id_reservasi){
                        console.log("are we? make it");
                         this.saveTableID = this.reservasi[i].id_meja;
                         console.log(this.saveTableID);
                    }
                }
            },
            forPaid(){
                // if(this.tipe != "cash"){
                //     this.verifCode = Math.random()*100 + "code";
                //     this.saveTableID = Math.random()*100;
                // }else{
                    this.verifCode = 0;
                    this.saveTableID = 2;
                // }
                if(this.nominal > this.paytemp){
                    this.change = this.nominal-this.paytemp;
                }else{
                    this.change = 0;
                }
                console.log(this.nominal);
                console.log(this.change);
                console.log(this.tipe);
                console.log(this.payDate);
                console.log(this.savePayId);
                console.log(this.saveTableID);
                console.log(this.verifCode);
                var url = this.$api + "/addpayment";
                this.load = true;
                this.$http
				.post(url, {
                        total_pembayaran: this.nominal,
                        total_kembalian: this.change,
                        jenis_pembayaran: this.tipe,
                        tanggal_bayar: this.payDate,
                        id_order: this.savePayId,
                        id_info: this.saveTableID,
                        Verif_Code: this.verifCode,
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
                var testtemp = "Paid"
                url = this.$api + "/orderan/" + this.savePayId;
                this.load = true;
                this.$http
                    .put(url, {
                        status_order: testtemp,
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
                this.dialogPayment = false;
            },
            preparePay(order){
                this.gettingpricetopay(order);
                this.savePayId = order;
                this.dialogPayment = true;
                return order;
            },
            foodload() {
                let url = this.$api + "/menu";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.menu = response.data.data;
                        // console.log(this.menu);
                    })
            },
            cancel() {
                this.clear(),
                this.dialogforAdd = false,
                this.dialog = false;
                this.dialogPayment = false;
            },
            clear() {
                this.foodie = null;
                this.foodsjl = null ;
            },
            foodsearch(id){
                var foodl = null;
                foodl = this.menu.length - 1;
                for(var i = 0; i <= foodl; i++){
                    if(id == this.menu[i].id_menu){
                        this.foodie = this.menu[i].nama_menu;
                        i = foodl;
                    }
                }
            },
            prepareEdit(id){
                this.editingId = id;
                this.forUpdate(this.editingId);
            },
            menutoId(){
                var foodl = null;
                foodl = this.menu.length - 1;
                for(var i = 0; i <= foodl; i++){
                    if(this.foodie == this.menu[i].nama_menu){
                        this.foodid = this.menu[i].id_menu;
                        i = foodl;
                    }
                }
            },
            forUpdate(id){
                this.menutoId();
                var url = this.$api + "/order/" + id;
                this.load = true;
                this.$http
                    .put(url, {
                        status_order: "served",
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
                        this.dataload();
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
            turnIdtoStringg(){
                this.orderan = this.orderan.map(v => ({...v, nama_menu: " "}));
                var customlength = this.menu.length - 1;
                var customlength2 = this.orderan.length - 1;
                for (var i = 0; i <= customlength; i++){
                    for(var j = 0; j<= customlength2; j++){
                        if (this.orderan[j].id_menu == this.menu[i].id_menu) {
                        this.orderan[j].nama_menu = this.menu[i].nama_menu;
                        }
                    }
                }
                for(i = 0; i <= customlength2; i++){
                    if(this.orderan[i].status_order != "ordered"){
                        this.orderan[i].checktest = false;
                    }else{
                        this.orderan[i].checktest = true;
                    }
                }
            },
        },
        beforeMount() {
            this.foodload();
            this.dataload();
            this.gettringRes();
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