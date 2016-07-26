function install(Vue) {
    Vue.prototype.$handleLink = function(link, source) {
        this.$trackEvent(source, "click", link);

        if (link.indexOf("#!") === 0) {
            this.$router.go(link);
        } else {
            setTimeout(function () {
                location.href = link;
            }, 300);
        }
    };
}

module.exports = install;
