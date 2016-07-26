const API_BASE_URL = "http://wechat.fusionmaster.net/";
const APP_ID = "wx668efc6f66f4f21f";

function getURLParameter(name) {
    let results = (new RegExp("[?&]" + name + "=([^&]*)")).exec(location.search);
    return results ? decodeURIComponent(results[1]) : null;
}

function getCleanQueryString(extra) {
    let querystring = location.search.substring(1);

    if (!querystring) return "";

    let uselessParams = ["code", "state", "from", "isappinstalled"];
    let params = {};
    let paramsArray;

    if (extra) uselessParams = uselessParams.concat(extra);

    querystring.split("&").forEach(function(param) {
        let [name, value] = param.split("=");
        params[name] = value;
    });

    uselessParams.forEach(function (name) {
        delete params[name];
    })

    paramsArray = Object.keys(params).map(function(name) {
        return name + "=" + params[name];
    });

    return paramsArray.length ? "?" + paramsArray.join("&") : "";
};

function wx_init(success, error) {
    let urlCode = getURLParameter("code");
    let sessionCode = sessionStorage.getItem("wx_code");

    if (sessionCode) {
        wx_userinfo(sessionCode, success, error);
    } else if (urlCode) {
        sessionStorage.setItem("wx_code", urlCode);

        let targetUrl = getURLParameter("target_url");
        location.replace(location.origin + location.pathname + (getCleanQueryString("target_url") || "?") + (targetUrl ? "#!" + targetUrl : ""));
    } else {
        wx_authorize();
    }
}

function wx_authorize() {
    let qs = getCleanQueryString();
    let redirectUrl = location.origin + location.pathname + qs + location.hash.replace("#!", (qs ? "&" : "?") + "target_url=");
    // redirectUrl = encodeURIComponent("http://app.jingsocial.com/openid/dynamicOauth?wechat=" + redirectUrl);
    location.replace("https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx668efc6f66f4f21f&redirect_uri=" + redirectUrl + "&response_type=code&scope=snsapi_userinfo&state=state#wechat_redirect");
}

function wx_userinfo(code, success, error) {
    $.getJSON(API_BASE_URL + "wechat_ktv/Home/WeChat/getopenid?code=" + code, function(data) {
        if (data.result === 0) {
            success(data);
            wx_jssdk();
            trak.init(APP_ID);
            trak.options.openId = data.openid;
            if (process.env.NODE_ENV !== "production") trak.options.debug = true;
        } else {
            sessionStorage.removeItem("wx_code");
            wx_authorize();
        }
    });
}

function wx_jssdk() {
    $.getJSON(API_BASE_URL + "wechat_ktv/Home/WeChat/getsign?url=" + encodeURIComponent(location.href.split("#")[0]), function(data) {
        if (data.status === 1) {
            wx.config({
                debug: false,
                appId: data.sign.appId,
                timestamp: data.sign.timestamp,
                nonceStr: data.sign.nonceStr,
                signature: data.sign.signature,
                jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ", "onMenuShareWeibo", "onMenuShareQZone", "getLocation", "openLocation", "scanQRCode", "closeWindow", "chooseWXPay"]
            });

            wx.ready(function() {
                window.isWXReady = true;

                wx.onMenuShareTimeline(shareDataTL);
                wx.onMenuShareAppMessage(shareData);
                wx.onMenuShareQQ(shareData);
                wx.onMenuShareQZone(shareData);
            });

            wx.error(function(res) {
                window.isWXReady = false;
                window.wxErrorMsg = res.errMsg;
            });
        } else {
            alert(data.msg);
        }
    });
}

let imgLink = document.createElement("a");
imgLink.href = "./assets/img/logo.png";

window.defaultShareData = {
    title: "用夜点一键预订KTV！要派对，不排队！",
    desc: "广州百家KTV一键预订，想嗨就嗨，无需等待！",
    titleTL: "用夜点一键预订KTV！想嗨就嗨，无需等待！",
    link: location.href.replace("?fromTabBar=1", ""),
    imgUrl: imgLink.href
};

window.shareData = {
    title: defaultShareData.title,
    desc: defaultShareData.desc,
    link: defaultShareData.link,
    imgUrl: defaultShareData.imgUrl
};

window.shareDataTL = {
    title: defaultShareData.titleTL,
    link: defaultShareData.link,
    imgUrl: defaultShareData.imgUrl
};

module.exports = {
    authenticate: wx_init
};
