import Vue from "vue";
import VueRouter from "vue-router";
import VueResource from "vue-resource";
import VueApi from "./plugins/vue-api";
import VueUser from "./plugins/vue-user";
import VueStats from "./plugins/vue-stats";
import VueWeChat from "./plugins/vue-wechat";
import filters from "./filters";
import directives from "./directives";
import wechat from "./libs/wechat";

Vue.use(VueRouter);
Vue.use(VueResource);
Vue.use(VueApi);
Vue.use(VueUser);

Vue.http.options.root = "http://t1.intelfans.com";
Vue.http.headers.common["X-KTV-Application-Name"] = "eec607d1f47c18c9160634fd0954da1a";
Vue.http.headers.common["X-KTV-Vendor-Name"] = "1d55af1659424cf94d869e2580a11bf8";
Vue.http.headers.common["X-KTV-Application-Platform"] = "1";

let App = Vue.extend(require("./views/app.vue"));

let componentsReq = require.context("./components/", false, /\.vue$/);
componentsReq.keys().forEach(function(path) {
    Vue.component(path.match(/\.\/(.*?)\.vue/)[1], Vue.extend(componentsReq(path)));
});

Object.keys(filters).forEach(function(id) {
    Vue.filter(id, filters[id]);
});

Object.keys(directives).forEach(function(id) {
    Vue.directive(id, directives[id]);
});

let router = new VueRouter({
    hashbang: true
});

router.map({
    "/ktv": {
        name: "list",
        title: "夜点娱乐",
        component: require("./views/ktv-list.vue")
    },
    "/ktv/:id": {
        name: "detail",
        hideBar: true,
        component: require("./views/ktv-detail.vue")
    },
    "/ktv/:id/map": {
        name: "map",
        hideBar: true,
        component: require("./views/map.vue")
    },
    "/book": {
        name: "book",
        title: "包房预订",
        hideBar: true,
        component: require("./views/book.vue")
    },
    "/search/:keyword": {
        name: "search",
        hideBar: true,
        component: require("./views/search.vue")
    },
    "/order": {
        name: "order",
        title: "我的订单",
        component: require("./views/order.vue"),
        subRoutes: {
            "/ktv": {
                component: require("./views/order-ktv.vue")
            },
            "/gift": {
                component: require("./views/order-gift.vue")
            }
        }
    },
    "/order/ktv/:id": {
        name: "ktv-order",
        title: "我的订单",
        hideBar: true,
        component: require("./views/order-ktv-detail.vue")
    },
    "/order/gift/:id": {
        name: "gift-order",
        title: "兑换成功",
        hideBar: true,
        component: require("./views/order-gift-detail.vue")
    },
    "/store": {
        name: "store",
        title: "礼品兑换",
        component: require("./views/store.vue")
    },
    "/store/:id": {
        name: "gift",
        title: "礼品兑换",
        hideBar: true,
        component: require("./views/gift.vue")
    },
    "/user": {
        name: "user",
        title: "个人中心",
        component: require("./views/user.vue")
    },
    "/user/favorite": {
        name: "favorite",
        title: "我的收藏",
        component: require("./views/favorite.vue")
    },
    "/coupon": {
        name: "coupons",
        title: "我的兑酒券",
        component: require("./views/coupon-list.vue")
    },
    "/events": {
        name: "events",
        title: "夜点精彩",
        component: require("./views/events.vue")
    }
});

router.redirect({
    "/order": "/order/ktv",
    "*": "/ktv"
});

router.afterEach(function(transition) {
    if (transition.to.title) document.title = transition.to.title;
});

Vue.use(VueStats, router);
Vue.use(VueWeChat, router);

function startApp(data) {
    Vue.api.login({
        type: "wechat",
        openid: data.openid,
        display_name: data.display_name,
        avatar_url: data.avatar_url
    }).then(function() {
        let hash = location.hash;

        if (!(!hash || hash == "#!/ktv" || hash.indexOf("#!/ktv?") === 0)) {
            history.replaceState(null, null, "#!/ktv");
            history.pushState(null, null, hash);
        }

        Vue.api.getUserInfo().then(function() {
            router.start(App, "app");
        }, function(error) {
            alert(error.message);
        });
    }, function(error) {
        alert(error.message);
    });
}

// just for debugging
if (process.env.NODE_ENV !== "production") {
    Vue.config.debug = true;
    window.Vue = Vue;
    window.router = router;
    window.$ = jQuery;

    startApp({
        "openid": "oQPyxvxrLZeiwO8roMyD07GFwS2E",
        "display_name": "小影",
        "avatar_url": ""
    });
} else {
    wechat.init(function(data) {
        startApp(data);
    }, function(data) {
        alert(data.msg || JSON.stringify(data));
    });
}
