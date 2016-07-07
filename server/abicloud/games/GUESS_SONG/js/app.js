(function(window, $) {
    var QUESTIONS = [{
            "answer": "因为爱所以爱",
            "keyboard": "因为爱所以情心跳的感觉一百次你爱点多每天里",
            "id": 0
        }, {
            "answer": "一千年以后",
            "keyboard": "一千年以后之恋未来爱情上你我的等回来满人等",
            "id": 1
        }, {
            "answer": "淘汰",
            "keyboard": "淘汰出局说谎我爱你放弃结输怕不安果散相信了",
            "id": 2
        }, {
            "answer": "时间煮雨",
            "keyboard": "时间煮雨去哪一直下乌云天等停在那分离悲伤起",
            "id": 3
        }, {
            "answer": "默",
            "keyboard": "默沉重温散逃过爱缘孤寂结失你我挣伤悲重离退",
            "id": 4
        }, {
            "answer": "离开地球表面",
            "keyboard": "离开地球表面火箭星漫游环绕飞私奔到月球公里",
            "id": 5
        }, {
            "answer": "会痛的石头",
            "keyboard": "会痛的石头尖叫疼呼吸冲撞猛烈泪水哭倔强坚强",
            "id": 6
        }, {
            "answer": "反方向的钟",
            "keyboard": "反方向的钟摆忘记时间移动空时间失控穿梭爱回",
            "id": 7
        }, {
            "answer": "爱在西元前",
            "keyboard": "爱在西元前黄昏后忘记传说情年的誓言预言永远",
            "id": 8
        }, {
            "answer": "日不落",
            "keyboard": "日不落帝国爱恋守护想念未免夜英思安骑士保卫",
            "id": 9
        }, {
            "answer": "你把我灌醉",
            "keyboard": "你把我灌醉酒喝独自心碎离退回收人女流泪罪的",
            "id": 10
        }, {
            "answer": "看我72变",
            "keyboard": "看我7236计变游戏野蛮再见爱情改攻略48",
            "id": 11
        },
        /*{
               "answer": "最美",
               "keyboard": "最美爱情心里面中你是我的丽童话传说微笑比赛",
               "id": 12
           },*/
        {
            "answer": "月亮代表我的心",
            "keyboard": "月亮代表我的心天空你爱情亲不是深吻思念味道",
            "id": 13
        }, {
            "answer": "夜空中最亮的星",
            "keyboard": "夜空中最亮的星辰大海安静的里意义孤独晚思念",
            "id": 14
        }, {
            "answer": "小苹果",
            "keyboard": "小苹果你是我的亲爱情儿心红宝贝星上人最美感",
            "id": 15
        },
        /*{
               "answer": "下一站天后",
               "keyboard": "下一站天后地铁女王台遇见上最后变情恋新娘的",
               "id": 16
           },*/
        {
            "answer": "五环之歌",
            "keyboard": "五环之歌奥运六七你四多少几个红北京中国梦心",
            "id": 17
        },
        /*{
               "answer": "王妃",
               "keyboard": "王菲妃子笑贵醉喝酒杯美玫瑰霸占太忘书情歌爱",
               "id": 18
           },*/
        {
            "answer": "思念是一种病",
            "keyboard": "思念是一种病太美你突然想我她不得爱好没有多",
            "id": 19
        }, {
            "answer": "青春修炼手册",
            "keyboard": "青春修炼手册纪念记忆约定的夏天宁静那年你回",
            "id": 20
        }, {
            "answer": "倩女幽魂",
            "keyboard": "倩女幽魂人生路漫似梦幻茫风雨悠间长小影恰的",
            "id": 21
        },
        /*{
               "answer": "南山南",
               "keyboard": "南山南北人生梦四季变时间光阴岁月那边的一如",
               "id": 22
           }, {
               "answer": "龙卷风",
               "keyboard": "龙卷风快走爱情暴雷危险快世界之的力量带全险",
               "id": 23
           },*/
        {
            "answer": "挥着翅膀的女孩",
            "keyboard": "挥着翅膀的女孩的微笑天使舞动手唱歌生是美丽",
            "id": 24
        },
        /*{
               "answer": "红日",
               "keyboard": "红日太阳金色我们的不落光辉岁月如时光生命忘",
               "id": 25
           }, {
               "answer": "红尘客栈",
               "keyboard": "红尘客栈殇土国沙风古世外雅缘颜叹年逍遥龙飞",
               "id": 26
           },*/
        {
            "answer": "法海你不懂爱",
            "keyboard": "法海你不懂爱情是什么那对男女和尚知道谁困惑",
            "id": 27
        }, {
            "answer": "发如雪",
            "keyboard": "发如雪花飘落白发魔女浪漫冬日恋歌默等粉红记",
            "id": 28
        }, {
            "answer": "豆浆油条",
            "keyboard": "豆浆油条每天都要吃早餐碗你和我爱情的味道奶",
            "id": 29
        },
        /*{
               "answer": "波斯猫",
               "keyboard": "波斯猫双眼不同彩色瞳孔改变异小咪一只可爱的",
               "id": 30
           }, {
               "answer": "私奔到月球",
               "keyboard": "私奔到月球离开表面男女孩拉手牵一起走亮之上",
               "id": 31
           }, {
               "answer": "红玫瑰",
               "keyboard": "红玫瑰鲜花颜色朵美丽白艳的一女人送给你动人",
               "id": 32
           },*/
        {
            "answer": "黑色幽默",
            "keyboard": "黑色幽默大写板上的字笑话冷嘲笑白不懂什么看",
            "id": 33
        }, {
            "answer": "过火",
            "keyboard": "过火走焰煎熬烫热的爱情路那条折磨伤痛别怪你",
            "id": 34
        }, {
            "answer": "斑马斑马",
            "keyboard": "斑马斑马两只动物的爱情夫妻一对老虎般配相像",
            "id": 35
        }, {
            "answer": "曹操",
            "keyboard": "曹操三国水煮东汉末年魏国英雄江山末年战乱豪",
            "id": 36
        }, {
            "answer": "开不了口",
            "keyboard": "开不了口拉链人男说话藏秘密谎的深露表白沉默",
            "id": 37
        }, {
            "answer": "蒙娜丽莎的眼泪",
            "keyboard": "蒙娜丽莎的眼泪恋人女玛利亚水痕心碎安静独自",
            "id": 38
        }, {
            "answer": "心如刀割",
            "keyboard": "心如刀割绞滴血痛苦刺穿头插伤害狠男你不爱我",
            "id": 39
        }, {
            "answer": "一剪梅",
            "keyboard": "一剪寒梅冬腊月花独立风雪飘北国冷枝苍茫傲然",
            "id": 40
        }
    ];

    function api(action, params, callback, error) {
        $.ajax({
            type: "POST",
            url: "index.php?m=api&a=" + action + "&time=" + Date.now(),
            data: params,
            dataType: "json",
            success: function(data) {
                data.status == 1 ? callback(data) : error();
            },
            error: error
        });
    };

    function getUrlParameter(name) {
        var matches = new RegExp("[?|&](" + name + ")(=(.*?))?(?:&|$)").exec(location.search);
        return matches ? (matches[3] ? decodeURIComponent(matches[3]) : undefined) : null;
    };

    var GuessGame = {
        duration: 0,
        help: 0,
        questionSize: 5,
        easyQuestionSize: 2,
        easyQuestionIDs: [2, 9, 15, 17, 28, 29, 33, 34, 36, 37],

        shuffleArray: function(o) {
            for (var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
            return o;
        },

        splice: function(list, id) {
            var question;
            $.each(list, function(idx) {
                if (this.id == id) {
                    question = list.splice(idx, 1)[0];
                    return false;
                };
            });
            return question;
        },

        genQuestions: function(qId) {
            var self = this,
                questions = this.shuffleArray(this.questionbank.slice()),
                firstQuestion;

            $.each(this.shuffleArray(this.easyQuestionIDs).slice(0, this.easyQuestionSize), function() {
                questions.unshift(self.splice(questions, this));
            });

            qId = parseInt(qId);

            if (!isNaN(qId)) {
                $.each(questions, function(idx) {
                    if (this.id == qId) {
                        questions.unshift(self.splice(questions, qId));
                        return false;
                    };
                });
            };

            return questions.slice(0, this.questionSize);
        },

        setQuestion: function(question) {
            this.question = question;

            $("#progress").text(this.questions.length == 0 ? "最后一题" : "还剩" + ["二","三","四","五"][this.questions.length-1] + "题");

            var chars = this.shuffleArray(question.keyboard.slice(0, 21).split(""));

            var img = new Image();
            img.src = "img/questions/" + question.id + ".png";
            img.onload = function() {
                this.className = "loaded";
            };
            $("#gamePage .picbox").find("img").removeClass("loaded");
            $("#gamePage .picbox").append(img);

            var html = "";
            for (var i = 0, len = question.answer.length; i < len; i++) {
                html += "<span></span>";
            }
            html = "<div class='answerbox'>" + html + "</div>";

            var kHtml = "";
            $.each(chars, function() {
                kHtml += "<span data-char='" + this + "'>" + this + "</span>";
            });
            html += "<div class='keyboard'>" + kHtml + "</div>";

            $("<div class='qbox' />").html(html).appendTo($("#gamePage .bottom"));

            shareData.link = shareUrl + "?qid=" + question.id;
            shareData.title = "江湖救急！快来帮我看看这到底是哪首歌！大奖见者有份！";
            shareData.desc = "离我的Apple Watch只差一步了，夜点派大奖，见者有份！";
            shareData.imgUrl = shareUrl + "/img/questions/" + question.id + ".png";

            _hmt.push(["_trackPageview", "/question/" + question.id]);
        },

        checkAnswer: function() {
            var answer = $(".answerbox").text();
            if (answer.length != this.question.answer.length) return false;
            if (this.question.answer == answer) {
                var question = this.questions.shift();
                $(".answerbox").addClass("right");
                setTimeout((function() {
                    if (question) {
                        $("#gamePage .qbox").addClass("out");
                        this.setQuestion(question);
                    } else {
                        this.gameOver();
                    };
                }).bind(this), 1e3);
            } else {
                $(".answerbox").addClass("wrong");
            };
        },

        bindEvent: function() {
            var self = this;

            $("#gamePage").on("animationend webkitAnimationEnd", function() {
                var $target = $(event.target);
                $target.filter(".out").remove();
                $(".wrong, .right").removeClass("wrong right");
            }).on("click", ".answerbox span", function() {
                var $this = $(this);
                if ($this.text() != "") {
                    $this.text("").get(0).$keyEl.removeClass("hide");
                };
            }).on("click", ".keyboard span", function() {
                var $this = $(this);
                var $canAnswer = $(".answerbox span:empty");
                if ($canAnswer.length) {
                    $canAnswer.eq(0).text($this.data("char")).get(0).$keyEl = $this;
                    $this.addClass("hide");
                    self.checkAnswer();
                };
            });

            $(".btn_help").click(function() {
                $("#helpLayer").show().find(".useup").toggle(self.help >= 3);
            });

            $("#helpLayer").click(function() {
                $(this).hide();
            });
        },

        import: function(questions) {
            this.questionbank = questions;
        },

        onHelp: function(source) {
            if (!this.question || this.help >= 3) {
                _hmt.push(["_trackEvent", "other", source]);
                return false;
            } else {
                _hmt.push(["_trackEvent", this.question.answer, source]);
            };

            this.help++;
            $("#helpLayer").hide();
            if (this.help < 3) {
                $(".btn_help .n").text(3 - this.help);
            } else {
                $(".btn_help").hide();
                $(".btn_restart").css("display", "block");
            };

            var answer = this.question.answer;
            var answerChars = answer.split("");
            $(".answerbox span").each(function(idx) {
                $(this).text(answerChars[idx]);
            });
            $(".keyboard .hide").removeClass("hide");
            $(".keyboard span").each(function() {
                var idx = answer.indexOf($(this).data("char"));
                if (idx > -1) {
                    answer = answer.substring(0, idx) + answer.substring(idx + 1);
                    $(this).addClass("hide");
                };
            });
            this.checkAnswer();
        },

        countDown: function() {
            this.duration = +((this.duration + 0.1).toFixed(1));
            var time = this.duration * 10 % 10 == 0 ? this.duration + ".0" : this.duration;
            time = time.toString().split(".");
            $("#time").html("<span class='s'>" + time[0] + "</span><span class='ms'>." + time[1] + "</span>");
        },

        init: function() {
            this.bindEvent();
        },

        start: function(qId) {
            clearInterval(this.timer);
            this.help = 0;
            this.duration = 0;
            this.question = null;
            this.questions = this.genQuestions(qId);

            $("#gamePage .picbox, #gamePage .bottom").empty();
            $("#time").text(0);
            $(".btn_help").show().find(".n").text(3);
            $(".btn_restart").hide();

            this.timer = setInterval(this.countDown.bind(this), 100);
            this.setQuestion(this.questions.shift());
        },

        gameOver: function() {
            clearInterval(this.timer);
            $("#gamePage .answerbox").css("pointer-events", "none");

            var time = this.duration;
            this.question = null;

            api("submit", {
                score: time,
            }, function(data) {
                var type = 4;
                time = data.score;
                shareData.link = shareUrl;
                shareData.desc = "夜点正在火热派送5块Apple Watch，10副Beats耳机以及大量小米音箱和电影票！够快就来拿！";
                shareData.imgUrl = shareUrl + "/img/weixin_share_pic.jpg";
                if (data.pct < 20) {
                    type = 1;
                    shareData.title = "众爱卿平身，朕用时" + time + "秒完成挑战！独孤求败！";
                } else if (data.pct < 45) {
                    type = 2;
                    shareData.title = "闪开！我才是“中华小曲库”，用时" + time + "秒完成挑战，全国排名" + data.rank + "！";
                } else if (data.pct < 70) {
                    type = 3;
                    shareData.title = "未来的Super Star就是我！用时" + time + "秒完成挑战，全国排名" + data.rank + "！";
                } else {
                    shareData.title = "好吧，我准备放弃音乐生涯…";
                    shareData.desc = "为什么这些歌曲都这么难猜！我要赢夜点派的Apple Watch和Beats耳机！";
                };
                shareData.imgUrl = shareData.link + "/img/weixin_share_pic" + type + ".jpg";
                $("#gamePage").hide();
                $("#resultPage").show();
                $("#resultPage .time").text(time);
                $("#resultPage .rank").text(data.rank);
                $("#resultPage, #shareLayer").attr("class", "type" + type);
                if (data.rank <= 100) {
                    toplistLoaded = 0;
                    $toplist.empty();
                    $board.off("scroll").find(".loadingtip").show();
                    initToplist();
                };
            }, function() {
                alert("提交分数失败。");
                $("#homePage").show();
                $("#gamePage").hide();
                shareData.link = shareUrl;
                shareData.title = "“超级估歌王”快来拿走你的Apple Watch和Beats耳机吧！";
                shareData.desc = "夜点正在火热派送5块Apple Watch，10副Beats耳机以及大量小米音箱和电影票！够快就来拿！";
                shareData.imgUrl = shareUrl + "/img/weixin_share_pic.jpg";
            });
        }
    };

    GuessGame.init();
    GuessGame.import(QUESTIONS);

    $("#homePage .btn_start").click(function() {
        $("#homePage").hide();
        $("#gamePage").show();
        GuessGame.start();
    });
    $("#homePage .btn_list").click(function() {
        $("#homePage").hide();
        $("#listPage").show();
    });

    $("#gamePage .btn_restart").click(function() {
        GuessGame.start();
    });

    $("#resultPage .btn_again").click(function() {
        $("#resultPage").hide();
        $("#gamePage").show();
        GuessGame.start();
    });
    $("#resultPage .btn_list").click(function() {
        $("#resultPage").hide();
        $("#listPage").show();
    });
    $("#resultPage .btn_share").click(function() {
        document.body.scrollTop = 0;
        $("#shareLayer").show();
    });

    $("#listPage .btn_again").click(function() {
        $("#listPage").hide();
        $("#gamePage").show();
        GuessGame.start();
    });
    $("#listPage .btn_rule").click(function() {
        $("#ruleLayer").show();
    });
    $("#listPage .btn_share").click(function() {
        $("#shareLayer").attr("class", "type0").show();
    });

    $("#ruleLayer, #shareLayer").click(function() {
        $(this).hide();
    });

    var toplist = [];
    var toplistLoaded = 0;
    var $board = $("#listPage .board");
    var $toplist = $board.find("#toplist");

    function throttle(fn, delay) {
        var timer = null;
        return function() {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function() {
                fn.apply(context, args);
            }, delay);
        };
    };

    function loadToplist() {
        if (toplist.length <= 0) return false;
        if ($board.scrollTop() + $board.height() == this.scrollHeight) {
            $toplist.append(genToplistHTML(toplist.splice(0, 10)));
            if (toplist.length <= 0) $board.off("scroll").find(".loadingtip").remove();
        };
    };

    function genToplistHTML(list) {
        var html = "";
        $.each(list, function(idx, row) {
            html += "<li class='top" + (toplistLoaded + idx + 1) + "'><span class='no'>" + (toplistLoaded + idx + 1) + "</span><img src='" + row.headimgurl.replace(/\/\d{1,2}$/, "/96") + "' /><strong>" + row.nickname + "</strong></li>";
        });
        toplistLoaded += list.length;
        return html;
    };

    function initToplist() {
        api("toplist", {}, function(data) {
            toplist = data.list;
            $toplist.append(genToplistHTML(toplist.splice(0, 20)));
            if (toplist.length <= 0) {
                $board.find(".loadingtip").hide();
            } else {
                $board.scroll(throttle(loadToplist, 100));
            };
        }, function() {
            $board.find(".loadingtip").text("获取排行榜数据失败。");
        });
    };

    window.onload = function() {
        var qid = parseInt(getUrlParameter("qid"));
        var startGame = false;

        initToplist();

        if (!isNaN(qid)) {
            $.each(QUESTIONS, function() {
                if (this.id == qid) {
                    $("#gamePage").show();
                    GuessGame.start(qid);
                    startGame = true;
                    return false;
                };
            });
        };
        if (!startGame) $("#homePage").show();
    };


    var shareUrl = location.origin + location.pathname.replace(/index\.(html|php)$/, "");
    var shareData = {
        title: "“超级估歌王”快来拿走你的Apple Watch和Beats耳机吧！",
        desc: "夜点正在火热派送5块Apple Watch，10副Beats耳机以及大量小米音箱和电影票！够快就来拿！",
        link: shareUrl,
        imgUrl: shareUrl + "/img/weixin_share_pic.jpg",
        success: function(res) {
            GuessGame.onHelp(res.errMsg.split(":")[0]);
        }
    };
    $.getJSON("/wechat/index.php?m=wxconfig", {
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
        var $listPage = $("#listPage");
        if ($listPage.css("display") != "none") {
            $listPage.hide();
            setTimeout(function() {
                hotcss.mresize();
                $listPage.show();
            }, 500);
        };
    });
})(window, Zepto);