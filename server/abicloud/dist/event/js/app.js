var API_BASE_URL = "http://letsktv.chinacloudapp.cn/";
var APP_ID = "wx90f8e48d4b4f5d8d";
var ktvdata;
var openid;
var coupon;
var page;

var shareData = {
    title: "夜点送您广州KTV派对啤酒兑酒券",
    desc: "为您的KTV派对加点料！夜点兑酒券发放中，无论新老用户，点击即可参与抽奖！马上参与！",
    link: location.origin + location.pathname,
    imgUrl: location.origin + location.pathname + "/img/weixin_share_pic.jpg",
    success: function(res) {
        _hmt.push(["_trackEvent", page + res.errMsg, page + res.errMsg]);
    }
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

function deg2rad(deg) {
    return deg * (Math.PI / 180);
}

function getDistance(lat1, lon1, lat2, lon2) {
    var R = 6371;

    var deltaLat = deg2rad(lat2 - lat1);
    var deltaLon = deg2rad(lon2 - lon1);
    var a =
        Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
        Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d.toFixed(1);
}

function genList(list) {
    var html = "<ul>";

    $.each(list, function(idx, ktv) {
        html += "<li><a href='http://letsktv.chinacloudapp.cn/dist/#!/ktv/" + ktv.id + "' data-idx='" + idx + "' data-id='" + ktv.id + "'><div class='inner'>"
                + "<div class='pic' style='background-image:url(" + ktv.pic.replace(" ", "%20").replace("(", "\\(").replace(")", "\\)") + ")'></div>"
                + "<h3 class='name'>" + ktv.name + "</h3><span class='area'>" + ktv.area + "</span>"
            + "</div></a></li>";

        if (idx % 2 === 1) html += "</ul><ul>";
    });

    if (html.substring(html.length - 4) === "<ul>") html = html.substring(0, html.length - 4);

    $(".recommend-list").html(html).find("a").click(function() {
        var el = this;
        _hmt.push(["_trackEvent", "ktv_" + el.dataset.idx, "ktv_" + el.dataset.idx]);
        _hmt.push(["_trackEvent", "ktv_" + el.dataset.id, "ktv_" + el.dataset.id]);

        setTimeout(function() {
            location.href = el.href;
        }, 500);
        return false;
    });
}

function geoError() {
    $.getJSON(API_BASE_URL + "wechat_ktv/Home/Event/ktv_recommend", function(data) {
        var list = data.data.map(function(item) {
            return {
                name: item.name,
                area: item.district,
                pic: item.room_pic_small,
                id: item.ktvid
            };
        });
        genList(list);
    });
}

function goPage(pid) {
    var $tmpl = $("#" + pid);
    page = pid;
    $("body").attr("class", $tmpl.attr("class") || "");
    $("#main").html($tmpl.html());

    $("#logo, #rules").show();

    $("[data-track]").click(function() {
        var el = this;
        var data = $(el).data("track");

        _hmt.push(["_trackEvent", data, data]);

        setTimeout(function() {
            location.href = el.href;
        }, 500);

        return false;
    });

    if (coupon) {
        $(".coupon").addClass("type-" + coupon.beer_type + " count-" + coupon.count);
    }

    switch (pid) {
    case "ktv":
        wx.ready(function() {
            wx.getLocation({
                type: "gcj02",
                success: function(res) {
                    $.getJSON(API_BASE_URL + "booking/xktvcoords", function(data) {
                        var list = data.list.filter(function(item) {
                            return item.sjq;
                        });
                        list.forEach(function(item) {
                            item.distance = getDistance(item.lat, item.lng, res.latitude, res.longitude);
                        });
                        list.sort(function(a, b) {
                            return a.distance - b.distance;
                        });
                        list = list.slice(0, 6);

                        $.post(API_BASE_URL + "booking/xktvlist", JSON.stringify({
                            list: list.map(function(item) {
                                return item.xktvid;
                            })
                        }), function(data) {
                            genList(data.list.map(function(ktv) {
                                return {
                                    name: ktv.xktvname,
                                    area: ktv.district,
                                    pic: ktv.piclist[0].smallpicurl,
                                    id: ktv.xktvid
                                };
                            }));
                        });
                    });
                },
                fail: geoError,
                cancel: geoError
            });
        });
        break;
    case "reg":
        $.get(API_BASE_URL + "wechat_ktv/Home/Event/addMobile", {
            mobile: "",
            openid: openid
        });
        break;
    case "offline31":
    case "offline32":
        if (ktvdata) $(".btn-ktv").html("预订" + ktvdata.name.replace(/(\(|（)/, "<br>$1")).attr("href", "http://letsktv.chinacloudapp.cn/dist/#!/ktv/" + ktvdata.ktvid);
        break;
    default:
        break;
    }

    _hmt.push(["_trackPageview", location.pathname + "#/" + pid]);
}

function login(userdata) {
    $.post(API_BASE_URL + "user/oauthlogin", JSON.stringify(userdata), function(data) {
        $.ajaxSetup({
            headers: {
                "X-KTV-User-Token": data.token
            }
        });
        if (ktvdata) {
            $.getJSON(API_BASE_URL + "coupon/getcouponbyevents", function(data) {
                coupon = data.coupon;

                goPage(data.result === 0 ? "offline31" : "offline32");
            });
        } else {
            $.getJSON(API_BASE_URL + "coupon/getcouponstatusbyevents", function(data) {
                var got = data.result === 1;
                coupon = data.coupon;

                $.getJSON(API_BASE_URL + "wechat_ktv/Home/Event/is_subcribe", {
                    openid: openid
                }, function(data) {
                    var subscribe = data.result === 0;

                    if (got) {
                        goPage(subscribe ? "yy" : "yw");
                    } else {
                        goPage(subscribe ? "ktv" : "reg");
                    }
                });
            });
        }
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

function oauth(reload) {
    var code = getURLParameter("code");
    var redirectUrl = encodeURIComponent(location.href.split("#")[0]);
    // redirectUrl = encodeURIComponent("http://app.jingsocial.com/openid/dynamicOauth?wechat=" + redirectUrl);

    if (code && !reload) {
        sessionStorage.setItem("wx_code", code);

        $.getJSON(API_BASE_URL + "wechat_ktv/Home/WeChat/getopenid?code=" + code, function(data) {
            if (data.result == 0) {
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
                oauth(true);
            }
        });
    } else {
        location.replace("https://open.weixin.qq.com/connect/oauth2/authorize?appid=" + APP_ID + "&redirect_uri=" + redirectUrl + "&response_type=code&scope=snsapi_base&state=state#wechat_redirect");
    }
}

// http://letsktv.chinacloudapp.cn/wechat_ktv/Home/Event/enter/ktvid/XKTV00001
$.getJSON(API_BASE_URL + "wechat_ktv/Home/Event/getktvinfo", function(data) {
    if (data.result === 0) ktvdata = data.info;

    oauth();
});
