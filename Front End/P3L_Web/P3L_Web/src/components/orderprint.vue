<template>
    <div class="container">
        <h1 class="page-title">Thanks For Order </h1>

        <article class="receipt">
            <section class="receipt__half upper">
                <p>Order Number : {{ orderid }}</p>
                <p>Total Price</p>
                <h1>{{ total }}</h1>
                <p class="sm">Printed On {{ month}} - {{ hours }}</p>

                <div class="receipt__content">
                    <table>
                        <tbody>
                            <tr>
                                <td>Printed By</td>
                                <td>{{ karyawanname }}</td>
                            </tr>
                            <tr>
                                <td>You pay for</td>
                                <td>{{ youpay }}</td>
                            </tr>
                            <tr>
                                <td>Paid at</td>
                                <td>{{ paiddate }}</td>
                            </tr>
                            <tr>
                                <td>Change</td>
                                <td>{{ youchange }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="receipt__half lower">
                <button>More info</button>
            </section>
        </article>


    </div>

</template>


<script>
    export default {
        data() {
            return {
                month: "",
                hours: "",
                valid: false,
                karyawanname: "",
                satuan: "gram   ",
                snackbar: false,
                menudate: false,
                search: null,
                color: "",
                paiddate: "",
                youpay: 0,
                youchange: 0,
                orderid: "",
                total: 0,
                dialog: false,
                dialogforAdd: false,
            }
        },
        methods: {
            // bahanload() {
            //     let url = this.$api + "/ingredients";
            //     this.$http
            //         .get(url, {
            //             headers: {
            //                 Authorization: "Bearer " + localStorage.getItem("token"),
            //             },
            //         })
            //         .then((response) => {
            //             this.bahan = response.data.data;
            //         })
            // },
        },
        beforeMount() {
            // this.qrCode = sessionStorage.getItem("qrgenerate");
            this.karyawanname = localStorage.getItem("name");
            this.month = new Date().getFullYear() + "-" + new Date().getMonth() + "-" + new Date().getDate();
            this.hours = new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds();
            this.paiddate = sessionStorage.getItem("datel");
            this.youpay = sessionStorage.getItem("payment");
            this.youchange = sessionStorage.getItem("change");
            this.orderid = sessionStorage.getItem("ordernu");
            this.total = this.youpay - this.youchange;
            // this.dataload();
        },
        mounted() {
            const receipt = document.querySelector('.receipt')
            const button = document.querySelector('button')

            const toggleReceipt = () => {
                receipt.classList.toggle('active')

                if (receipt.classList.contains('active')) {
                    button.innerHTML = 'Less info'
                } else {
                    button.innerHTML = 'More info'
                }
            }

            button.addEventListener('click', toggleReceipt)
        },
    }
</script>

<style>
    html {
        box-sizing: border-box;
    }

    *,
    *:before,
    *:after {
        box-sizing: inherit;
    }

    body {
        margin: 40px;
        background: #f4f4f4;
    }

    .receipt {
        width: 400px;
        max-width: 100%;
        margin: auto;
        position: relative;
    }

    .receipt__half {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        position: relative;
        background: white;
        perspective: 1000px;
    }

    .receipt__half::after {
        content: "";
        display: block;
        width: 100%;
        height: 100px;
        position: absolute;
        left: 0;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        transition: all 0.8s ease;
        transition-delay: 0.35s;
    }

    .active .receipt__half::after {
        transition-delay: 0s;
    }

    .upper {
        padding: 20px;
        z-index: 10;
    }

    .upper::after {
        top: 100%;
        background: #f7f7f7;
        border-top: 1px dashed #dedede;
        transform: rotate3d(1, 0, 0, -90deg);
        transform-origin: top center;
    }

    .active .upper::after {
        background: white;
        transform: rotate3d(1, 0, 0, 0deg);
    }

    .lower {
        transition: transform 0.8s ease;
        transition-delay: 0.35s;
    }

    .lower::after {
        bottom: 100%;
        background: #ebebeb;
        transform: rotate3d(1, 0, 0, 90deg);
        transform-origin: bottom center;
    }

    .active .lower {
        border-top: none;
        transform: translateY(200px);
        transition-delay: 0s;
    }

    .active .lower::after {
        background: white;
        transform: rotate3d(1, 0, 0, 0deg);
    }

    h1 {
        margin: 10px 0 20px;
    }

    h1.page-title {
        text-align: center;
        margin-bottom: 60px;
    }

    p {
        margin: 0;
    }

    p.sm {
        font-size: 80%;
    }

    button {
        width: 100%;
        padding: 15px 20px;
        background: none;
        outline: none;
        border: none;
        border-top: 1px dashed #dedede;
        color: #888;
        font-size: 90%;
        font-weight: 600;
        cursor: pointer;
        transition: all ease 0.35s;
    }

    button:hover {
        color: #484848;
    }

    .receipt__content {
        display: flex;
        align-items: center;
        width: 100%;
        height: 200px;
        padding: 20px;
        position: absolute;
        top: 140px;
        left: 0;
        z-index: 20;
        pointer-events: none;
        opacity: 0;
        transform: translateY(-8px);
        transition: opacity ease 0.35s, transform ease 0.35s;
        transition-delay: 0s;
    }

    .active .receipt__content {
        opacity: 1;
        transform: translateY(0);
        transition-delay: 0.8s;
    }

    table {
        width: 100%;
    }

    tr {
        display: flex;
        justify-content: space-between;
        margin: 10px 0;
    }
</style>