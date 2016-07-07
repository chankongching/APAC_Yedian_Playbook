$("[led]").each(function() {
	this.style.backgroundImage = "url(" + $(this).find("img").attr("src").replace("_l", "") + ")";
});

var rotate = 0;
var isAnimating = false;

//var win = true;

function lottery(win, chance, code) {
	rotate -= rotate % 360; // 归正
	rotate += 360 * 10; // 转10圈
	rotate += win ? 180 : 0; // 转到结果位

	$("#lottery .dial").css({
		"-webkit-transform": "rotate(" + rotate + "deg)",
		transform: "rotate(" + rotate + "deg)"
	}).one("webkitTransitionEnd transitionend", function() {
		setTimeout(function() {
			if (win) {
				$("#code").text(code);
				$("#winLayer").show();
			} else {
				if (chance > 0) {
					$("#chance").text(chance);
					$("#loseLayer").show();
				} else {
					$("#lose2Layer").show();
				};
			};
			isAnimating = false;
		}, 1e3);
	});
};

$("#lottery .indicator").click(function() {
	if (isAnimating) return false;
	isAnimating = true;

	$.getJSON("http://letsktv.chinacloudapp.cn/games/20160128__Lucky_Draw/act?m=api&a=lottery&time=" + Date.now(), function(data) {
		if (data.status == 1) {
			lottery(data.win, data.chance, data.code);
		} else {
			isAnimating = false;
            alert(data.message)
		};
	});
});

$(".btn_again").click(function() {
	$("#loseLayer, #lose2Layer").hide();
});
$(".btn_rule").click(function() {
	$("#ruleLayer").show();
});
$("#ruleLayer .close").click(function() {
	$("#ruleLayer").hide();
});

var shareData = {
    title: "什么！？预定K房还能免费得一打啤酒！",
    desc: "夜点幸运大转盘，999打百威啤酒免费送，超高中奖率，和夜点一起去唱K！",
    link: "http://letsktv.chinacloudapp.cn/games/20160128__Lucky_Draw/index",
    imgUrl: "http://letsktv.chinacloudapp.cn/games/20160128__Lucky_Draw/static/img/weixin_share_pic.jpg"
};
$.getJSON("http://letsktv.chinacloudapp.cn/wechat/index.php?m=wxconfig", {
    m: "wxconfig",
    url: location.href.split("#")[0]
}, function(wxconfig) {
    wxconfig.jsApiList = ["onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ"];
    wx.config(wxconfig);
    wx.ready(function() {
        wx.onMenuShareTimeline(shareData);
        wx.onMenuShareAppMessage(shareData);
        wx.onMenuShareQQ(shareData);
    });
});

window.addEventListener("orientationchange", function() {
    var $landingPage = $("#landingPage");
    if ($landingPage.css("display") != "none") {
        $landingPage.hide();
        setTimeout(function() {
            hotcss.mresize();
            $landingPage.show();
        }, 1000);
    };
});

window.onload = function() {
	$("#landingPage").show();
};
