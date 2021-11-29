import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

function importComponent(path) {
    return () => import(`./components/${path}.vue`)
}

Vue.use(VueRouter);
const router = new VueRouter({
    mode: "history",
    routes: [{
        path: "/",
        name: "Login",
        meta: {
            title: "Korean Atma BBQ - Panel",
        },
        component: importComponent('landing'),
    },
    {
        path: "/qrcode",
        name: "qrcode",
        meta: {
            title: "QR Code",
        },
        component: importComponent('qrcode'),
    },
    {
        path: "/receipt",
        name: "receipt",
        meta: {
            title: "Thank You",
        },
        component: importComponent('orderprint'),
    },
    {
        path: "/outcome",
        name: "Outcome",
        meta: {
            title: "Outcome",
        },
        component: importComponent('outcome'),
    },
    {
        path: "/stocksisa",
        name: "stocksisa",
        meta: {
            title: "Sisa stock",
        },
        component: importComponent('stocksisa'),
    },
    {
        path: "/comingstock",
        name: "comingstock",
        meta: {
            title: "Stock Masuk",
        },
        component: importComponent('comingstock'),
    },
    {
        path: "/income",
        name: "income",
        meta: {
            title: "Income",
        },
        component: importComponent('income'),
    },
    {
        path: "/ownerpanel",
        name: "Owner",
        meta: {
            title: "Owner - Panel",
            requiresAuth: true,
        },
        component: importComponent('ownerpanel'),
        children: [
            {
                path: "/ownerpanel",
                name: "Owner Panel",
                meta: {
                    title: "Owner Panel",
                },
                component: importComponent('Dashboard'),
            },
            {
            path: "/karyawanlist",
            name: "listkaryawan",
            meta: {
                title: "Active List",
            },
            component: importComponent('owner/karyawanaktif'),
        },
    ]
    },
    {
        path: "/waitercashierpanel",
        name: "waiter cashier",
        meta: {
            title: "waiter - cashier",
            requiresAuth: true,
        },
        component: importComponent('waiterpanel'),
        children: [
            {
                path: "/waitercashierpanel",
                name: "Panel",
                meta: {
                    title: "Panel",
                },
                component: importComponent('Dashboard'),
            },
            {
                path: "/reservation",
                name: "reservasi",
                meta: {
                    title: "Reservation List",
                },
                component: importComponent('reservation'),
            },
            {
                path: "/orderan",
                name: "Orderan",
                meta: {
                    title: "Order",
                },
                component: importComponent('neworde_r'),
            },
            {
                path: "/payment",
                name: "Pembayaran",
                meta: {
                    title: "Payment List",
                },
                component: importComponent('payment'),
            },
    ]
    },
    {
        path: "/opsmgrpanel",
        name: "mgrpanel",
        meta: {
            title: "Operasional Manager",
            requiresAuth: true,
        },
        component: importComponent('managerpanel'),
        children: [{
            path: "/customerlist",
            name: "customer",
            meta: {
                title: "Customer Data",
            },
            component: importComponent('customerlist'),
        },
        {
            path: "/opsmgrpanel",
            name: "Operasional Manager",
            meta: {
                title: "Operasional Manager Panel",
            },
            component: importComponent('Dashboard'),
        },
        {
            path: "/mejalist",
            name: "meja",
            meta: {
                title: "Data Meja",
            },
            component: importComponent('mejalist'),
        },
        {
            path: "/ingredients",
            name: "bahan",
            meta: {
                title: "Bahan Makanan",
            },
            component: importComponent('ingredients'),
        },
        {
            path: "/menulist",
            name: "menu",
            meta: {
                title: "Menu Makanan",
            },
            component: importComponent('menulist'),
        },
        {
            path: "/stockish",
            name: "Stock",
            meta: {
                title: "Stock Watcher",
            },
            component: importComponent('stockstock'),
        },
        {
            path: "/stockistory",
            name: "Stock History",
            meta: {
                title: "Stock Watcher",
            },
            component: importComponent('history'),
        },
        {
            path: "/comestock",
            name: "Coming stock",
            meta: {
                title: "Stock Masuk",
            },
            component: importComponent('comingstock'),
        },
    ]
    },
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!localStorage.getItem('token') && !localStorage.getItem('id')) {
            next({
                name: 'Login'
            })
        } else {
            next() // go to wherever I'm going
        }
    } else {
        next() // does not require auth, make sure to always call next()!
    }
    document.title = to.meta.title
    next()
});

export default router