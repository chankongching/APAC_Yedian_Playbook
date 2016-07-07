import utils from "../libs/utils";

function install(Vue) {
    var context;

    function request(method, url, data) {
        return new Vue.Promise(function(resolve, reject) {
            Vue.http[method](url, data).then(function({data}) {
                if (data.result === 0) {
                    resolve(data);
                } else if (data.result === 450) {
                    data.list = [];
                    data.total = 0;
                    resolve(data);
                } else {
                    reject(data);
                }
            }, function(response) {
                reject(response);
            });
        }, context);
    }

    var api = {
        get(url, data) {
            return request("get", url, data);
        },
        post(url, data) {
            return request("post", url, data);
        },
        login(data) {
            return request("post", "user/oauthlogin", data).then(function(data) {
                Vue.http.headers.common["X-KTV-User-Token"] = data.token;
                return data;
            }, function(data) {
                throw new Error(data.msg || "用户登录失败");
            });
        },
        getUserInfo(preferLocal) {
            if (preferLocal && Object.keys(Vue.prototype.$userdata).length > 0) {
                return new Vue.Promise.resolve(Vue.prototype.$userdata, context);
            }
            return request("get", "user/info").then(function(data) {
                Vue.prototype.$userdata = data;
                if (data.lat != -1 && data.lng != -1) {
                    sessionStorage.setItem("location", JSON.stringify({
                        lat: data.lat,
                        lng: data.lng
                    }));
                }
                return data;
            }, function(data) {
                return data;
            });
        },
        updateUserInfo(data) {
            return request("post", "user/info", data);
        },
        sendCode(mobile) {
            if (!utils.isMobile(mobile)) {
                alert("请输入手机号");
                return new Vue.Promise.reject("请输入手机号", context);
            }
            return request("post", "user/sendcode", {
                mobile: mobile
            }).then(function(data) {
                alert(data.msg);
            }, function(data) {
                alert(data.msg || "发送失败");
            });
        },
        verifyCode(mobile, code) {
            return request("post", "user/phoneverify", {
                mobile: mobile,
                verifycode: code
            });
        }
    };

    Object.defineProperties(Vue, {
        api: {
            get: function() {
                context = this;
                return api;
            }
        }
    });

    Object.defineProperties(Vue.prototype, {
        $api: {
            get: function() {
                context = this;
                return api;
            }
        }
    });
}

module.exports = install;