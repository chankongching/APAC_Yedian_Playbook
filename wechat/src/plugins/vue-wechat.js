function install(Vue, router) {
    Vue.prototype.$wxShare = function(data) {
        window.shareData.title = data.title;
        window.shareData.desc = data.desc;
        window.shareDataTL.title = data.titleTL || data.title;
        if (data.imgUrl) window.shareData.imgUrl = window.shareDataTL.imgUrl = data.imgUrl;
        if (data.link) window.shareData.link = window.shareDataTL.link = data.link;
    };

    router.afterEach(function() {
        window.shareData.title = window.defaultShareData.title;
        window.shareData.desc = window.defaultShareData.desc;
        window.shareDataTL.title = window.defaultShareData.titleTL;
        window.shareData.imgUrl = window.shareDataTL.imgUrl = window.defaultShareData.imgUrl;
        window.shareData.link = window.shareDataTL.link = location.href.replace(location.search, "").replace("?fromTabBar=1", "");
    });
};

module.exports = install;