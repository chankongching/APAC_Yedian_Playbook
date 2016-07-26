var API_BASE_URL = "http://wechat.fusionmaster.net/";
var APP_ID = "wx668efc6f66f4f21f";

var shareData = {
    title: "夜点送你广州KTV兑酒券！",
    desc: "好友K歌局，有酒才痛快！夜点免费兑酒券，等你来抢！",
    link: location.origin + location.pathname + "?coupon=" + getURLParameter("coupon"),
    imgUrl: location.origin + location.pathname + "/img/weixin_share_pic.jpg"
};

var modulesMap = {
    nnnn: "dq",
    nnny: "db",
    nnyn: "dtq",
    nynn: "eq",
    ynnn: "lq",
    yynn: "lq",
    ynyn: "dtq",
    ynny: "bl",
    nyyn: "dtq",
    nyny: "be",
    nnyy: "dbt",
    yyyn: "dtq",
    yyny: "bl",
    ynyy: "dbt",
    nyyy: "dbt",
    yyyy: "dbt",
    zero: "zq"
};

$.ajaxSetup({
    headers: {
        "X-KTV-Application-Name": "eec607d1f47c18c9160634fd0954da1a",
        "X-KTV-Vendor-Name": "1d55af1659424cf94d869e2580a11bf8",
        "X-KTV-Application-Platform": "1"
    }
});

function getURLParameter(name) {
    var results = (new RegExp("[?&]" + name + "=([^&]*)")).exec(location.search);
    return results ? decodeURIComponent(results[1]) : null;
}

function displayModules(lg, gq, lguo, gz, couponStatus, coupon) {
    var key = (lg ? "y" : "n") + (gq ? "y" : "n") + (lguo ? "y" : "n") + (gz ? "y" : "n");
    var modules = modulesMap[key].split("");

    modules.forEach(function(module) {
        switch (module) {
            case "d":
                $("#count").text("恭喜您，获得" + coupon.name);
                $("#remain").text("共" + couponStatus.total + "张，还剩" + couponStatus.count + "张");
                $("#coupon").addClass("coupon-" + coupon.beer_type + "-" + coupon.count);
                $("#couponBox").show();
                break;
            case "b":
                $("#btns, #btnsHolder").show();
                break;
            case "l":
                $("#heading").addClass("late").show();
                break;
            case "e":
                $("#heading").addClass("expired").show();
                break;
            case "t":
                $("#tip").show();
                setTimeout(function() {
                    $("#tip").hide();
                }, 3e3);
                break;
            case "q":
                $("#qrcodeBox").show();
                break;
            case "z":
                $("#heading").addClass("lose").show();
                break;
            default:
                break;
        }
    });

    $("#main").show();
}

function login(userdata, callback) {
    $.post(API_BASE_URL + "user/oauthlogin", JSON.stringify(userdata), function(oauthdata) {
        $.ajaxSetup({
            headers: {
                "X-KTV-User-Token": oauthdata.token
            }
        });
        callback(userdata.openid);
    });
}

function initWxJsSdk() {
    $.getJSON(API_BASE_URL + "wechat_ktv/Home/WeChat/getsign?url=" + encodeURIComponent(location.href.split("#")[0]), function(data) {
        wx.config({
            debug: false,
            appId: data.sign.appId,
            timestamp: data.sign.timestamp,
            nonceStr: data.sign.nonceStr,
            signature: data.sign.signature,
            jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ", "getLocation"]
        });
        wx.ready(function() {
            wx.onMenuShareTimeline(shareData);
            wx.onMenuShareAppMessage(shareData);
            wx.onMenuShareQQ(shareData);
        });
    });
}

function oAuth(callback, reload) {
    var code = getURLParameter("code");
    var redirectUrl = encodeURIComponent(location.href.split("#")[0]);

    if (code && !reload) {
        sessionStorage.setItem("wx_code", code);

        $.getJSON(API_BASE_URL + "wechat_ktv/Home/WeChat/getopenid?code=" + code, function(data) {
            if (data.result === 0) {
                login({
                    type: "wechat",
                    openid: data.openid,
                    display_name: data.display_name,
                    avatar_url: data.avatar_url
                }, callback);
                initWxJsSdk();
            } else {
                sessionStorage.removeItem("wx_code");
                oAuth(null, true);
            }
        });
    } else {
        location.replace("https://open.weixin.qq.com/connect/oauth2/authorize?appid=" + APP_ID + "&redirect_uri=" + redirectUrl + "&response_type=code&scope=snsapi_base&state=state#wechat_redirect");
    }
}

oAuth(function(openid) {
    $.post(API_BASE_URL + "coupon/getcouponbyshare", JSON.stringify({
        openid: openid,
        code: getURLParameter("coupon")
    }), function(data) {
        if (data.result === 0) {
            displayModules(data.is_lingguang, data.is_guoqi, data.is_lingguo, data.is_subscribe, data.coupon_status, data.coupon);
        } else {
            displayModules(false, true, false, false);
        }

        setTimeout(function() {
            /* globals IScroll */
            var myScroll = new IScroll("#wrapper", {
                preventDefaultException: {
                    className: /pde/
                }
            });
        }, 1e3);
    });
});
