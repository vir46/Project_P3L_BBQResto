<template>
<v-app>   
         <v-container fluid fill-height class="posisinya">
             <v-layout flex align-center justify-center>
                 <v-flex xs12 sm6 elevation-6>
                     <v-toolbar class="black darken-3">
                         <img src="https://i.ibb.co/dprLS5Q/akb-removebg-preview.png" class="iconbbq">
                         <v-toolbar-title class="white--text">
                             <h1>ATMA KOREAN BBQ</h1>
                         </v-toolbar-title>
                     </v-toolbar>
                     <v-card>
                         <v-card-text class="pt-4">
                             <div>
                                 <v-form v-model="valid" ref="form">
                                     <v-text-field label="Username" v-model="username" :rules="userRules" required>
                                     </v-text-field>
                                     <v-text-field label="Password" v-model="password" type="password" min="8"
                                         :rules="passwordRules" counter required></v-text-field>
                                     <v-layout justify-end>
                                         <v-btn class="mr-2" @click="Submit"
                                             :class=" { 'black darken-1 white--text' : valid, disabled: !valid }">Login
                                         </v-btn>
                                         <v-btn @click="clear" class="grey darken-3 white--text">Clear</v-btn>
                                     </v-layout>
                                 </v-form>
                             </div>
                         </v-card-text>
                     </v-card>
                     <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>
                         {{error_message}}
                     </v-snackbar>
                 </v-flex>
             </v-layout>
         </v-container>
     </v-app>     
</template>

 <style scoped>

     .grey--text {
         font-family: "Comic Sans";
     }

     .posisinya {
         background:url('https://image.freepik.com/free-photo/blurred-cafe-background-coffee-shop-blur-background-with-bokeh_1484-1948.jpg');
         background-position: center;
         background-repeat: no-repeat;
         background-size: cover;
         position: absolute;
         left: 0px;
         right: 0px;
     }

     .iconbbq{
         height: 30px;
     }
 </style>
 <script>
     export default {
         name: "Login",
         data: () => ({
                 load: false,
                 snackbar: false,
                 error_message: '',
                 color: '',
                 valid: false,
                 password: '',
                 passwordRules: [
                     (v) => !!v || 'Password tidak boleh kosong :(',

                 ],
                 username: '',
                 userRules: [
                     (v) => !!v || 'Username tidak boleh kosong :(',

                 ], 
         }),
         methods: {
             Submit(){
                if (this.$refs.form.validate()) {
                    //cek apakah data yang akan dikirim sudah valid
                        this.load = true;
                        this.$http
                        .post(this.$api + "/loginkaryawan", {
                            user_akun: this.username,
                            password: this.password,
                        })
                        .then((response) => {
                            console.log(response);
                            localStorage.setItem("id", response.data.karyawan.id_karyawan); //menyimpan id user yang sedang login
                            localStorage.setItem("name", response.data.karyawan.nama_karyawan); //menyimpan id user yang sedang login
                            localStorage.setItem("role", response.data.karyawan.role_karyawan); //menyimpan id user yang sedang login

                            localStorage.setItem("token",response.data.access_token); //menyimpan auth token
                            this.error_message = "Login Success, Welcome!";
                            this.color = "green";
                            this.snackbar = true;
                            this.load = false;
                            if(localStorage.getItem("role")=="owner"){
                                this.$router.push('/ownerpanel');
                            }else if(localStorage.getItem("role")=="ops_manager"){
                                this.$router.push('/opsmgrpanel');
                            }else if(localStorage.getItem("role")=="Waiter" || localStorage.getItem("role")=="Kasir"){
                                this.$router.push('/waitercashierpanel');
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                            this.color = "red";
                            this.snackbar = true;
                            localStorage.removeItem("token");
                            this.load = false;
                        });
                }
            },
             clear() {
                 this.$refs.form.reset() //Clear form login    
             }
         },
     };
 </script>