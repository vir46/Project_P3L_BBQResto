<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">History Pembayaran</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
                <v-btn small color="white" @click="preparePendapatan()">
                        Laporan Pendapatan <v-icon>mdi-microsoft-xbox</v-icon>
                    </v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="payment" :search="search">
                <template v-slot:[`item.note_print`]="{ item }">
                    <v-btn small color="black" :disabled="item.status_order=='Paid'" class="mr-2 icongreen" @click="preparePrint(item)">
                        Print Notes  <v-icon>mdi-newspaper-variant-outline</v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>

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
                        text: 'Status',
                        align: 'start',
                        sortable: false,
                        value: 'status_pembayaran',
                    },
                    {
                        text: 'Nomor Pembayaran',
                        value: 'id_pembayaran'
                    },
                    {
                        text: 'Total Pay',
                        value: 'total_pembayaran'
                    },
                    {
                        text: 'Kembalian',
                        value: 'total_kembalian'
                    },
                    {
                        text: 'Jenis Bayar',
                        value: 'jenis_pembayaran'
                    },
                    {
                        text: 'Nomor Order',
                        value: 'id_order'
                    },
                    {
                        text: 'Print',
                        value: 'note_print'
                    },
                ],
                max: 18,
                bahanRules: [
                     (v) => !!v || 'Bahan tidak boleh kosong',
                     (v) => (v || '').length <= this.max || `Maksimum ${this.max} karakter`
                ],
                payment: [],
                bahan: [],
                datatable: [],
                error_message: "",
                editingId: null,

            }
        },
        methods: {
            preparePendapatan(){
                window.open('/income','_blank');
            },
            preparePrint(item){
                console.log(item);
                var Totalpay = item.total_pembayaran;
                var Totalex = item.total_kembalian;
                var datelunas = item.tanggal_bayar;
                var testorderid = item.id_order;
                sessionStorage.setItem("payment",Totalpay);
                sessionStorage.setItem("change",Totalex);
                sessionStorage.setItem("datel",datelunas);
                sessionStorage.setItem("ordernu",testorderid);
                window.open('/receipt','_blank');
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
            karyawanload() {
                let url = this.$api + "/payment";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.payment = response.data.data;
                    })
            },
            dataload() {
                let url = this.$api + "/histori";
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