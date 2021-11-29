<template>
    <v-main class="list">
        <h3 class="text-h3 font-weight-medium mb-5">Daftar Menu</h3>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="dialogforAdd = true">
					ADD
				</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="makanan" :search="search">
                <template v-slot:[`item.settings`]="{ item }">
                    <v-btn small color="black" class="mr-2 icongreen" @click="forEdit(item.id_menu)">
                        <v-icon>mdi-lead-pencil</v-icon>
                    </v-btn>
                    <v-btn small color="black" class="iconred" @click="hapuslah(item.id_menu)">
                        <v-icon>mdi-trash-can</v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Edit Harga Menu</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid">
                        <v-text-field label="Nama Menu" v-model="nama" :rules="nameRules" required></v-text-field>
                        <v-select v-model="tipe" :items="['Makanan Utama','Minuman', 'Side Dish']"></v-select>
                        <v-text-field label="Price (Rp)" v-model="harga" :rules="priceRules" required></v-text-field>
                        <v-select v-model="ingredients_name" :items="namabahan" item-text="nama_bahan" item-value="nama_bahan" label="Bahan"></v-select>
                        <v-text-field label="Size Porsi" v-model="size" :rules="sizeRules" required></v-text-field>
                        <v-select v-model="satuanmenu" :items="['plate', 'bowl', 'minibowl', 'glass', 'bottle']"
                            label="Satuan Menu" required></v-select>
                        <v-text-field label="Mini Description" v-model="desc" :rules="descRules" required></v-text-field>
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
                    <span class="headline">New Menu</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form v-model="valid">
                        <v-text-field label="Nama Menu" v-model="nama" :rules="nameRules" required></v-text-field>
                        <v-select v-model="tipe" :items="['Makanan Utama','Minuman', 'Side Dish']"></v-select>
                        <v-text-field label="Price (Rp)" v-model="harga" :rules="priceRules" required></v-text-field>
                        <v-select v-model="ingredients_name" :items="namabahan" item-text="nama_bahan" item-value="nama_bahan" label="Bahan"></v-select>
                        <v-text-field label="Size Porsi" v-model="size" :rules="sizeRules" required></v-text-field>
                        <v-select v-model="satuanmenu" :items="['plate', 'bowl', 'minibowl', 'glass', 'bottle']"
                            label="Satuan Menu" required></v-select>
                        <v-text-field label="Mini Description" v-model="desc" :rules="descRules" required></v-text-field>
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
                tipe: "",
                namabahan: [],
                satuantemp: "",
                satuanbahan: "",
                satuanmenu: "",
                ingredients_name: "",
                ingredients_id: "",
                size: "",
                harga: "",
                nama: "",
                desc: "",
                karyawandate: new Date().toISOString().substr(0, 10),
                valid: false,
                snackbar: false,
                menudate: false,
                search: null,
                temp: "",
                color: "",
                dialog: false,
                dialogforAdd: false,
                headers: [{
                        text: 'Menu',
                        align: 'start',
                        sortable: false,
                        value: 'nama_menu',
                    },
                    {
                        text: 'Tipe Menu',
                        value: 'tipe_jenis_menu'
                    },
                    {
                        text: 'Harga (rp)',
                        value: 'harga_menu'
                    },
                    {
                        text: 'Size Serving',
                        value: 'size_serving_menu'
                    },
                    {
                        text: 'Satuan',
                        value: 'satuan'
                    },
                    {
                        text: 'Description',
                        value: 'Deskripsi'
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
                makanan: [],
                error_message: "",
                editingId: null,
            }
        },
        methods: {
            makananload() {
                let url = this.$api + "/menu";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.makanan = response.data.data;
                    })
            },
            ingredientName() {
                let url = this.$api + "/ingredients";
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.namabahan = response.data.data;
                    })
            },
            gettingIdBahan(){
                var temp = null;
                var bahanlength = this.namabahan.length - 1;
                for (var i = 0; i <= bahanlength; i++){
                    if (this.ingredients_name == this.namabahan[i].nama_bahan) {
                        temp = this.namabahan[i].id_bahan;
                        return temp;
                    }
                }
                return temp;
            },
            gettingSatuanBahan(){
                var temp = null;
                var bahanlength = this.namabahan.length - 1;
                for (var i = 0; i <= bahanlength; i++){
                    if (this.ingredients_name == this.namabahan[i].nama_bahan) {
                        temp = this.namabahan[i].satuan;
                        return temp;
                    }
                }
                return temp;
            },
            cancel() {
                this.clear(),
                this.dialogforAdd = false,
                this.dialog = false;
            },
            forEdit(id) {
                var tempbahan
                this.editingId = id;
                let url = this.$api + "/menu/" + this.editingId;
                this.$http
                    .get(url, {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("token"),
                        },
                    })
                    .then((response) => {
                        this.harga = response.data.data.harga_menu;
                        this.nama = response.data.data.nama_menu;
                        this.tipe = response.data.data.tipe_jenis_menu;
                        tempbahan = response.data.data.id_bahan;
                        console.log(tempbahan);
                        this.size = response.data.data.size_serving_menu;
                        this.desc = response.data.data.Deskripsi;
                        this.ingredients_name = this.gettingbahan(tempbahan);
                    })
                    .catch((error) => {
                        this.error_message = error.response.message;
                        this.color = "red";
                        this.snackbar = true;
                    });
                this.dialog = true;
            },
            gettingbahan(idbahan){
                var temp;
                var bahanlength = this.namabahan.length - 1;
                for (var i = 0; i <= bahanlength; i++){
                    if (idbahan == this.namabahan[i].iid_bahan) {
                        temp = this.namabahan[i].id_bahan;
                        return temp;
                    }
                }
                console.log(temp);
                return temp;  
            },
            hapuslah(item){
                this.editingId = item;
                var url = this.$api + "/menu/" + this.editingId;
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
					this.makananload();
				})
				.catch((error) => {
					this.error_message = error.response.data.message;
					this.color = "red";
					this.snackbar = true;
				});
            },
            forAdding() {
                this.ingredients_id = this.gettingIdBahan();
                this.satuantemp = this.satuanmenu + "/" + this.gettingSatuanBahan();
                console.log(this.ingredients_id);
                var url = this.$api + "/registmenu";
                this.load = true;
                this.$http
				.post(url, {
                        tipe_jenis_menu: this.tipe,
                        nama_menu: this.nama,
                        harga_menu: this.harga,
                        size_serving_menu: this.size,
                        satuan: this.satuantemp,
                        id_bahan: this.ingredients_id,
                        Deskripsi: this.desc,
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
					this.makananload();
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
                this.ingredients_id = this.gettingIdBahan();
                this.satuantemp = this.satuanmenu + "/" + this.gettingSatuanBahan();
                console.log(this.satuantemp);
                console.log(this.ingredients_id);
                var url = this.$api + "/menu/" + this.editingId;
                this.load = true;
                this.$http
                    .put(url, {
                        tipe_jenis_menu: this.tipe,
                        nama_menu: this.nama,
                        harga_menu: this.harga,
                        size_serving_menu: this.size,
                        satuan: this.satuantemp,
                        id_bahan: this.ingredients_id,
                        Deskripsi: this.desc,
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
                        this.makananload(); //mengambil data
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
                this.tipe = "";
                this.nama = "";
                this.harga = "";
                this.size = "";
                this.satuantemp = "";
                this.ingredients_id = "";
                this.desc = "";
            },
        },
        beforeMount() {
            this.makananload();
            this.ingredientName();
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