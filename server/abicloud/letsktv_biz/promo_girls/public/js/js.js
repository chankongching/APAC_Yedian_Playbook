var countdown_timeout = 0;

$(document).ready(function(){
});

/*
(function(){
    window.URL = location.href;
    window.isBack = false;
    function getRequest() {
        var url = window.location.search; // 获取url中"?"符后的字串
        var theRequest = {};
        if (url.indexOf("?") !== -1) {
            var str = url.substr(1);
            var strs = str.split("&");
            for ( var i = 0; i < strs.length; i++) {
                theRequest[strs[i].split("=")[0]] = decodeURIComponent(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    }
    
    var removeParameter = function(href, name) {
        var tmp = href.split("?");
        var newHref = tmp[0];
        var query = new Array();
        if (tmp[1]) {
            var paramObj = tmp[1].split("&");
            for (var i = 0; i < paramObj.length; i++) {
                if (paramObj[i].indexOf(name + "=") != 0) {
                    query.push(paramObj[i]);
                }
            }
        }
        if (query.length > 0) {
            newHref += "?";
            for (var i = 0; i < query.length; i++) {
                newHref += query[i];
                if (i + 1 < query.length) {
                    newHref += "&";
                }
            }
        }
        return newHref;
    };
    
    function go_WX(){
        var appid = "wxbf643add612855f6";
        var redirect_uri = "";
        if(window.URL.indexOf("?") === -1){
            redirect_uri = window.URL + "?wx_init_page=1";
        }else{
            redirect_uri = window.URL + "&wx_init_page=1";
        }
        history.replaceState(null,"",redirect_uri);
        var wx_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" + appid + "&redirect_uri=" + encodeURIComponent(redirect_uri) + "&response_type=code&scope=snsapi_userinfo&state=state#wechat_redirect";
        location.href = wx_url;
    }
    var _request = getRequest();
    var wx_code = window.sessionStorage.getItem("wx_code");
    var wx_openid = window.sessionStorage.getItem("wx_openid");
    var wx_code_request = _request.code;
    if(!wx_code && !wx_openid){
        if(!wx_code_request){
            go_WX();
        } else {
            window.sessionStorage.setItem("wx_code", wx_code_request);
            window.URL = removeParameter(window.URL, "state");
            window.URL = removeParameter(window.URL, "wx_init_page");
            window.URL = removeParameter(window.URL, "code");
            history.replaceState(null, "", window.URL);
        }
    } else {
        window.URL = removeParameter(window.URL, "state");
        if(_request.wx_init_page){
            window.isBack = true;
            window.URL = removeParameter(window.URL, "wx_init_page");
        }
        if(_request.code){
            window.isBack = false;
            window.URL = removeParameter(window.URL, "code");
        }
        if(window.isBack){
            document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
                WeixinJSBridge.call('closeWindow');
            });
            return false;
        }
    }
})();

$(document).ready(function(){
    var code = window.sessionStorage.getItem("wx_code");
    if(code) {
        $.ajax({
            url : "http://letsktv.chinacloudapp.cn/fusionway/_wechat/index.php?m=userinfo&code=" + code,
            dataType : "json",
            async : false,
            success : function(response){
                if(typeof response === "string"){
                    response = JSON.parse(response);
                }
                if(response.status == 1) {
                    window.sessionStorage.setItem("wx_openid", response.data.openid);
                    window.URL = removeParameter(window.URL, "state");
                    window.URL = removeParameter(window.URL, "wx_init_page");
                    window.URL = removeParameter(window.URL, "code");
                    history.replaceState(null, "", window.URL);
                } else {
                    alert(response.error);
                }
            },
            error : function(response){
                alert("err: 获取openid报错");
            }
        });
        window.sessionStorage.removeItem('wx_code');
    }

    var wx_openid = window.sessionStorage.getItem("wx_openid");
    if(wx_openid) {
        $.ajax({
            url : "http://letsktv.chinacloudapp.cn/fusionway/_wechat/index.php?m=checkuserbyopenid&openid=" + wx_openid,
            dataType : "json",
            async : false,
            success : function(response){
                if(typeof response === "string"){
                    response = JSON.parse(response);
                }
                if(response.status == 1) {
                    $('#wx_nickname').val(response.data.nickname);
                    window.sessionStorage.setItem("wx_status", response.data.status);
                } else {
                    window.sessionStorage.removeItem('wx_openid');
                    window.sessionStorage.removeItem('wx_status');
                    alert(response.error);
                }
            },
            error : function(response){
                alert("err: 获取身份信息失败");
                window.sessionStorage.removeItem('wx_openid');
                window.sessionStorage.removeItem('wx_status');
            }
        });
    }
    
    var wx_status = window.sessionStorage.getItem("wx_status");
    if(wx_status == 0) {
        $('#checkuserstatus').show();
    } else {
        
    }
    
    $('#checkuserstatus form').submit(function(){
        
        return false;
    });
    
    $("#send_yzm").click(function(){
        if($(this).hasClass("disabled")){
            return;
        }
        verifycode = "";
        sendSMC();
        return false;
    });
    
});
function sendSMC(){
    var wx_openid = window.sessionStorage.getItem("wx_openid");
    var mobile = $.trim($("#mobile").val());
    if(!checkTel(mobile)){
        alert("请填写正确的手机号");
        return false;
    }
    $.ajax({
        url :"http://letsktv.chinacloudapp.cn/fusionway/_wechat/index.php?m=sendcaptcha&phone=" + mobile + "&openid=" + wx_openid,
        type : "post",
        data : {
            "openid" : wx_openid,
            "phone" : mobile
        },
        dataType : "json",
        success : function(response){
            if(response.status == 1){
                $("#mobile").prop("readonly", true);
                $("#send_yzm").prop("disabled", true);
                countdown(120);
            } else {
                alert(response.error);
            }
        }
    });
}
var countdown_timeout = 0;
function countdown(sec) {
    $("#send_yzm").val("重新发送(" + sec + ")");
    countdown_timeout = setTimeout(function(){
        if(sec === 0){
            clearTimeout(countdown_timeout);
            $("#mobile").prop("readonly", false);
            $("#send_yzm").prop("disabled", false).val("发送验证码");
            return;
        }
        sec = sec - 1;
        countdown(sec);
    }, 1000);
}
function checkTel(value) {
    var isMob=/^((\+?86)|(\(\+86\)))?(13[0-9][0-9]{8}|15[0-9][0-9]{8}|18[0-9][0-9]{8}|147[0-9]{8}|1349[0-9]{7})$/;
    if(isMob.test(value)) {
        return true;
    } else {
        return false;
    }
}
*/