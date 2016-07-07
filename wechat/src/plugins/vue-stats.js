function install(Vue, router) {
    let points = [];
    let $tabBar;
    let $masthead;

    function sendPoints() {
        let data = points.splice(0, points.length);

        if (data.length > 0) {
            Vue.api.post("tongji/click", {
                click: data
            });
        }
        setTimeout(sendPoints, 10e3);
    }

    function trackClick(event) {
        let page = location.hash.substring(2).split("?")[0];
        let pageY = event.pageY;

        if ($tabBar && $tabBar.contains(event.target)) pageY = -(window.innerHeight - (event.pageY - document.body.scrollTop));
        if ($masthead && $masthead.contains(event.target)) pageY = event.pageY - document.body.scrollTop;

        points.push({
            url: page,
            x: event.pageX,
            y: pageY
        });
    }

    Vue.prototype.$trackPageview = function(pageURL) {
        Vue.api.post("tongji/browse", {
            browse: {
                url: pageURL.replace("?fromTabBar=1", "")
            }
        });
        if (typeof _hmt != "undefined") _hmt.push(["_trackPageview", pageURL]);
        if (typeof trak != "undefined") trak.event({category: "_trackPageview", action: "PageView", data: {url: pageURL}});
    };
    Vue.prototype.$trackEvent = function(category, action, opt_label, opt_value) {
        if (typeof _hmt != "undefined") _hmt.push(["_trackEvent", category, action, opt_label, opt_value]);
        if (typeof trak != "undefined") {
            let eventData = {
                category: category,
                action: action,
                data: {}
            };
            if (opt_label) eventData.data.label = opt_label;
            if (opt_value) eventData.data.value = opt_value;
            trak.event(eventData);
        }
        console.info("trackEvent", category, action, opt_label, opt_value);
    };

    Vue.directive("track-link", {
        bind: function () {
            let args = this.expression.split(",");

            $(this.el).on("click.track", () => {
                this.vm.$trackEvent.apply(this.vm, args);

                setTimeout(() => {
                    location.href = this.el.href;
                }, 300);

                return false;
            });
        },
        unbind: function () {
            $(this.el).off("click.track");
        }
    });

    if (process.env.NODE_ENV !== "production") {
        router.afterEach(function(transition) {
            console.info("跳转到：", transition.to.path, transition.to);
        });
    } else {
        router.afterEach(function(transition) {
            Vue.prototype.$trackPageview(transition.to.path);

            setTimeout(function () {
                $tabBar = document.getElementById("tab-bar");
                $masthead = document.querySelector(".masthead");
            }, 1e3);
        });

        window.addEventListener("unload", sendPoints, false);
        document.addEventListener("click", trackClick, false);
        setTimeout(sendPoints, 10e3);
    }
}

module.exports = install;