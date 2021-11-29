<template>
  <div class="container">
    <div class="card">
      <v-card width="100px" height="100px">
        <vue-qrcode :value="qrCode" />
        <PDFViewer :pdfjs-worker-instance="pdfjsWorker" />
      </v-card>
      <p class="card__name">Printed by</p>{{ karyawanname }}
      <div class="grid-container">
      </div>
      <img src="https://i.ibb.co/dprLS5Q/akb-removebg-preview.png" class="logoresize">
      <br><br>
      <br>
      Printed On
      <button class="btn draw-border">{{ month }}</button>
      <button class="btn draw-border">{{ hours }}</button>
    </div>
  </div>
</template>


<script>
  import VueQrcode from 'vue-qrcode'
  export default {
    components: {
      VueQrcode,
    },
    data() {
      return {
        month: "",
        hours: "",
        valid: false,
        qrCode: "",
        karyawanname: "",
        satuan: "gram   ",
        snackbar: false,
        menudate: false,
        search: null,
        color: "",
        dialog: false,
        dialogforAdd: false,
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
    },
    beforeMount() {
      this.qrCode = sessionStorage.getItem("qrgenerate");
      this.karyawanname = localStorage.getItem("name");
      this.month = new Date().getFullYear() + "-" + new Date().getMonth() + "-" + new Date().getDate();
      this.hours = new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds();
      this.dataload();
      window.print();
    },
    mounted() {
      setTimeout(function () {
        window.print();
      }, 0);
    },
  }
</script>

<style>
  @import url("https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500&display=swap");

  .container {
    display: flex;
    grid-template-columns: 300px 300px 300px;
    grid-gap: 50px;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f5f5f5;
    font-family: 'Baloo Paaji 2', cursive;
  }

  .logoresize {
    width: 50px;
    height: 50px;
  }

  .card {
    background-color: #222831;
    height: 37rem;
    border-radius: 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: rgba(0, 0, 0, 0.7);
    color: white;
  }

  .card__name {
    margin-top: 15px;
    font-size: 1.5em;
  }




  .draw-border {
    box-shadow: inset 0 0 0 4px #58cdd1;
    color: #58afd1;
    -webkit-transition: color 0.25s 0.0833333333s;
    transition: color 0.25s 0.0833333333s;
    position: relative;
  }

  .draw-border::before,
  .draw-border::after {
    border: 0 solid transparent;
    box-sizing: border-box;
    content: '';
    pointer-events: none;
    position: absolute;
    width: 0rem;
    height: 0;
    bottom: 0;
    right: 0;
  }

  .draw-border::before {
    border-bottom-width: 4px;
    border-left-width: 4px;
  }

  .draw-border::after {
    border-top-width: 4px;
    border-right-width: 4px;
  }

  .draw-border:hover {
    color: #ffe593;
  }

  .draw-border:hover::before,
  .draw-border:hover::after {
    border-color: #eb196e;
    -webkit-transition: border-color 0s, width 0.25s, height 0.25s;
    transition: border-color 0s, width 0.25s, height 0.25s;
    width: 100%;
    height: 100%;
  }

  .draw-border:hover::before {
    -webkit-transition-delay: 0s, 0s, 0.25s;
    transition-delay: 0s, 0s, 0.25s;
  }

  .draw-border:hover::after {
    -webkit-transition-delay: 0s, 0.25s, 0s;
    transition-delay: 0s, 0.25s, 0s;
  }

  .btn {
    background: none;
    border: none;
    cursor: pointer;
    line-height: 1.5;
    font: 700 1.2rem 'Roboto Slab', sans-serif;
    padding: 0.75em 2em;
    letter-spacing: 0.05rem;
    margin: 1em;
    width: 13rem;
  }

  .btn:focus {
    outline: 2px dotted #55d7dc;
  }


  .grid-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 20px;
    font-size: 1.2em;
  }
</style>