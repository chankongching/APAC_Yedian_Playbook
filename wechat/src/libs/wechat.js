const API_BASE_URL = "http://t1.intelfans.com/";
const APP_ID = "wx668efc6f66f4f21f";

function wx_init(success, error) {
    let sessionCode = sessionStorage.getItem("wx_code");

    if (sessionCode) {
        wx_userinfo(sessionCode, success, error);
    } else {
        wx_authorize();
    }
}

function wx_authorize() {
    location.replace("./oauth.html?target_url=" + location.hash);
}

function wx_userinfo(code, success, error) {
    $.getJSON(API_BASE_URL + "wechat_ktv/Home/WeChat/getopenid?code=" + code, function(data) {
        if (data.result == 0) {
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
        if (data.status == 1) {
            wx.config({
                debug: false,
                appId: data.sign.appId,
                timestamp: data.sign.timestamp,
                nonceStr: data.sign.nonceStr,
                signature: data.sign.signature,
                jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ", "onMenuShareWeibo", "onMenuShareQZone", "getLocation", "openLocation", "scanQRCode", "closeWindow"]
            });

            wx.ready(function() {
                window.wxIsReady = true;
                wx.onMenuShareTimeline(shareDataTL);
                wx.onMenuShareAppMessage(shareData);
                wx.onMenuShareQQ(shareData);
                wx.onMenuShareQZone(shareData);
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
    link: location.href.replace(location.search, "").replace("?fromTabBar=1", ""),
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
    init: wx_init
};