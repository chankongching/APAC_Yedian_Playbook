var API_BASE_URL = "http://letsktv.chinacloudapp.cn/";
var APP_ID = "wx90f8e48d4b4f5d8d";
var openid;

var $countdowns = $("#countdown, #popup_countdown .countdown");

var eventStatus = 0;
var targetDate = new Date(2016, 6, 28, 21).getTime();
var serverDate;
var startTime;
var countdownTimer;
var waitAnimateTimer;
var fakeQueueTimer;

var SECOND = 1;
var MINUTE = SECOND * 60;
var HOUR = MINUTE * 60;

var shareData = {
    title: "什么！不花钱就能办派对？",
    desc: "夜点0元秒杀派对疯抢中！任意时间，三小时欢唱，12罐百威，更有果盘及小食若干！再晚来就抢光啦！",
    link: location.origin + location.pathname,
    imgUrl: location.origin + location.pathname + "/img/weixin_share_pic.jpg",
    success: function(res) {
        _hmt.push(["_trackEvent", res.errMsg, res.errMsg]);
    }
};

$.ajaxSetup({
    timeout: 20e3,
    headers: {
        "X-KTV-Application-Name": "eec607d1f47c18c9160634fd0954da1a",
        "X-KTV-Vendor-Name": "1d55af1659424cf94d869e2580a11bf8",
        "X-KTV-Application-Platform": "1"
    }
});

function padZero(n) {
    return n < 10 ? "0" + n : n;
}

function getURLParameter(name) {
    var results = (new RegExp("[?&]" + name + "=([^&]*)")).exec(location.search);
    return results ? decodeURIComponent(results[1]) : null;
}

function initWxJsSdk() {
    $.getJSON(API_BASE_URL + "wechat_ktv/Home/WeChat/getsign?url=" + encodeURIComponent(location.href.split("#")[0]), function(data) {
        wx.config({
            debug: false,
            appId: data.sign.appId,
            timestamp: data.sign.timestamp,
            nonceStr: data.sign.nonceStr,
            signature: data.sign.signature,
            jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ"]
        });
        wx.ready(function() {
            wx.onMenuShareTimeline(shareData);
            wx.onMenuShareAppMessage(shareData);
            wx.onMenuShareQQ(shareData);
        });
    });
}

function init() {
    $.post(API_BASE_URL + "wechat_ktv/Home/OneYuan/getTime", JSON.stringify({
            uid: openid
        })).done(function(data) {
            if (data.result === 0) {
                if (data.zhongjiang_status.status) {
                    $("#winPage").show();
                    if (data.zhongjiang_status.duijiangma) {
                        $("#popup_code").show().find(".code").val(data.zhongjiang_status.duijiangma);
                    }
                } else {
                    serverDate = new Date(data.active_status.now * 1e3 + (480 + (new Date).getTimezoneOffset()) * 1000 * 60).getTime();
                    startTime = Date.now();

                    switch (data.active_status.is_over) {
                        case 0:
                            if (Math.round((targetDate - serverDate) / 1e3) > 0) {
                                eventStatus = 0;
                                $("#mainPage").addClass("countdown");
                                countdown();
                            } else {
                                eventStatus = 1;
                                $("#mainPage").addClass("grabing");
                            }
                            break;
                        case 1:
                            eventStatus = 2;
                            $("#mainPage").addClass("over");
                            break;
                    }

                    $("#mainPage").show();
                }
            } else {
                init();
            }
        })
        .fail(function() {
            init();
        });
}

function login(userdata) {
    $.post(API_BASE_URL + "user/oauthlogin", JSON.stringify(userdata), function(data) {
        $.ajaxSetup({
            headers: {
                "X-KTV-User-Token": data.token
            }
        });
        init();
    });
}

function oAuth(reload) {
    var code = getURLParameter("code");
    var redirectUrl = encodeURIComponent(location.href.split("#")[0]);
    // redirectUrl = encodeURIComponent("http://app.jingsocial.com/openid/dynamicOauth?wechat=" + redirectUrl);

    if (code && !reload) {
        sessionStorage.setItem("wx_code", code);

        $.getJSON(API_BASE_URL + "wechat_ktv/Home/WeChat/getopenid?code=" + code, function(data) {
            if (data.result === 0) {
                openid = data.openid;
                login({
                    type: "wechat",
                    openid: data.openid,
                    display_name: data.display_name,
                    avatar_url: data.avatar_url
                });
                initWxJsSdk();
            } else {
                sessionStorage.removeItem("wx_code");
                oAuth(true);
            }
        });
    } else {
        location.replace("https://open.weixin.qq.com/connect/oAuth2/authorize?appid=" + APP_ID + "&redirect_uri=" + redirectUrl + "&response_type=code&scope=snsapi_base&state=state#wechat_redirect");
    }
}

function grab() {
    $.post(API_BASE_URL + "wechat_ktv/Home/OneYuan/getzige", {
        uid: openid
    }).done(function(data) {
        $("#mainPage, #popup_queue").hide();
        $(".queue_animate .can").remove();
        clearTimeout(waitAnimateTimer);

        if (data.result === 0 && data.zige_info.zhongjiang) {
            $("#winPage").show();
            _hmt.push(["_trackPageview", location.pathname + "#/win"]);
        } else {
            $("#losePage").show();
            _hmt.push(["_trackPageview", location.pathname + "#/lose"]);
        }
    }).fail(function() {
        $("#losePage").show();
        _hmt.push(["_trackPageview", location.pathname + "#/lose"]);
    });
}

function addCan() {
    $(".queue_animate").append("<div class='can' />");
    waitAnimateTimer = setTimeout(addCan, 1300);
}

function countdown() {
    var elapsedTime = Date.now() - startTime;
    var deltaInSecond = Math.round((targetDate - (serverDate + elapsedTime)) / 1e3);

    var hours = Math.floor(deltaInSecond / HOUR);
    var minutes = Math.floor((deltaInSecond % HOUR) / MINUTE);
    var seconds = Math.floor((deltaInSecond % MINUTE) / SECOND);

    if (deltaInSecond > 0) {
        $countdowns.text(padZero(hours) + " : " + padZero(minutes) + " : " + padZero(seconds));
    } else {
        $("#popup_countdown").hide();
        $("#mainPage").removeClass("countdown").addClass("grabing");
        clearInterval(countdownTimer);
        eventStatus = 1;
    }

    countdownTimer = setTimeout(countdown, 1e3);
}

$(".queue_animate").on("webkitAnimationEnd animationend", function(event) {
    $(event.target).remove();
});

$("#grab").click(function() {
    if (eventStatus === 0) {
        $("#popup_countdown").show();
    } else if (eventStatus === 1) {
        $("#popup_queue").show();
        addCan();
        fakeQueueTimer = setTimeout(grab, 6e3 + Math.random() * 4e3);
    }
});

$(".popup .btn_close").click(function() {
    $(this).closest(".popup").hide();
});

$("#popup_queue .btn_close").click(function() {
    $(".queue_animate .can").remove();
    clearTimeout(waitAnimateTimer);
    clearTimeout(fakeQueueTimer);
});

$("#losePage .btn_again").click(function() {
    $("#mainPage").show();
    $("#losePage").hide();
});

$("#winPage form").submit(function() {
    var phone = this.phone.value;

    if (!/1\d{10}/.test(phone)) {
        alert("请输入手机号");
        return false;
    }

    $.post(API_BASE_URL + "wechat_ktv/Home/OneYuan/AddMobile", {
        mobile: phone,
        uid: openid
    }, function(data) {
        if (data.result === 0) {
            $("#winPage form").off("submit");
            $("#popup_code").show().find(".code").val(data.duijiangma);
        } else {
            alert(data.msg);
        }
    });

    _hmt.push(["_trackEvent", "win提交", "win提交"]);
    return false;
});

$(".btn_friends").click(function() {
    $("#shareLayer").show();
});

$("#shareLayer").click(function() {
    $(this).hide();
});

$("[data-track]").click(function() {
    var el = this;
    var data = $(el).data("track");

    console.log(["_trackEvent", data, data]);
    _hmt.push(["_trackEvent", data, data]);

    if (el.href) {
        setTimeout(function() {
            location.href = el.href;
        }, 500);
        return false;
    }
});

oAuth();
