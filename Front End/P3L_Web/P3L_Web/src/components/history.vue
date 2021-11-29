<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">History Of Stock Transaction</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
            </v-card-title>
            <v-data-table :headers="headers" :items="datatable" :search="search">
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
                        text: 'Jenis Perubahan',
                        align: 'start',
                        sortable: false,
                        value: 'jenis_perubahan_stock',
                    },
                    {
                        text: 'Tanggal Perubahan',
                        value: 'tanggal_stock_berubah'
                    },
                    {
                        text: 'Jumlah Perubahan',
                        value: 'jumlah_stock_perubahan'
                    },
                    {
                        text: 'Bahan Terpengaruh',
                        value: 'nama_bahan'
                    },
                    {
                        text: 'Pelaku Transaksi',
                        value: 'nama_karyawan'
                    },
                ],
                max: 18,
                bahanRules: [
                     (v) => !!v || 'Bahan tidak boleh kosong',
                     (v) => (v || '').length <= this.max || `Maksimum ${this.max} karakter`
                ],
                karyawan: [],
                bahan: [],
                datatable: [],
                error_message: "",
                editingId: null,
            }
        },
        methods: {
            outcomePrepare(){
                window.open('/outcome','_blank');
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