$(document).ready(function(e) {
    //高度自适应
    autoHeight();
    $(window).resize(function() {
        autoHeight();
    });

    function autoHeight() {
        var windowHeight = $(window).height();
        var videoHeight = $(".video-doc-box").width() * 9 / 16;
        $(".watch-fl,.watch-fr").css("height", windowHeight);
        if ($(".watch-embed-fr.haschat").hasClass("hasdoc")) {
            $(".watch-fl,.watch-fr").css("height", windowHeight - 240);
        }
        $(".watch-box,.category-box").css("height", windowHeight - 8);
        $(".video-img,.video-doc").css("height", Math.min(videoHeight, (windowHeight - 160)));
        $(".watch-embed-box,.watch-embed-box .video-img,.watch-embed-box .video-doc").css("height", windowHeight);
        $(".watch-fr .memberlist-box").css("height", windowHeight - 56);
        $(".watch-embed-fr.haschat .memberlist-box").css("height", windowHeight - 296);
        if ($(".watch-embed-fr.haschat").hasClass("hasdoc")) {
            $(".watch-fr .memberlist-box").css("height", windowHeight - 56);
        }
        $(".watch-fr .chatlist-box,.watch-fr .qalist-box,.watch-embed-fr.haschat.hasdoc .chatlist-box").css("height", windowHeight - 170);
        $(".watch-embed-fr .chatlist-box").css("height", windowHeight - 120);
        $(".watch-embed-fr.haschat .chatlist-box").css("height", windowHeight - 120);
        if ($(".watch-embed-fr.haschat").hasClass("hasdoc")) {
            $(".watch-embed-fr .chatlist-box").css("height", windowHeight - 360);
        }
        $(".watch-fr .chat-area").css("height", windowHeight - 170);
        $(".watch-embed-fr.haschat .chat-area").css("height", windowHeight - 170);
        if ($(".watch-embed-fr.haschat").hasClass("hasdoc")) {
            $(".watch-embed-fr .chat-area").css("height", windowHeight - 410);
        }

        if ($(".watch-embed-fr").hasClass("chatonly")) {
            $(".watch-embed-fr").css("height", windowHeight);
            $(".watch-fr .chat-area").css("height", windowHeight - 82);
            $(".watch-fr .memberlist-box").css("height", windowHeight - 56);
            $(".watch-embed-fr .chatlist-box").css("height", windowHeight - 120);
        }

        if ($('#memberlist').length > 0) {
            $('#memberlist .panel-body').height(windowHeight - 30 - 45*4);
        }

        if ($('#live-events').length > 0 || $('#all-events-list').length > 0) {
            var dom = ($('#live-events').length > 0 ? $('#live-events') : $('#all-events-list'));
            var width = dom.width();
            if ($('.category-box').width() - dom.width() != 22) {
                width = dom.width() - 30;
            }
            var count = Math.ceil(width / 226);
            dom.find('li,img').width(((width - 1) / count - 16).toFixed(2));
            var img = dom.find('img').eq(0);
            var imgHeight = parseInt(img.attr('_height')) / parseInt(img.attr('_width')) * img.width();
            dom.find('img').height(imgHeight);
            //$('.mCustomScrollbar').mCustomScrollbar('update');
        }

    }

    //选项卡
    vhallApp.tabInit("tab-btns", "a", "tab-content");
    /*$(".aboutit .share,.common-events-list .share").each(function(){
         vhallApp.floatCard($(this),$("#shareout-div"));
    });*/
    vhallApp.floatCard($(".common-events-list"), ".share", $("#shareout-div"));
    $(".arrow-left").click(function() {
        var target = $(".watch-fl");
        if (target.css("display") == "block") {
            target.hide();
            $(".watch-box").css("margin-left", 30);
            $(this).css("left", 0).addClass("right").html("&gt");
        } else {
            target.show();
            $(".watch-box").css("margin-left", 250);
            $(this).css("left", 220).removeClass("right").html("&lt");
        }
    });
    $(".arrow-right").click(function() {
        var target = $(".watch-fr");
        if (target.css("display") == "block") {
            target.hide();
            $(".watch-box").css("margin-right", 30);
            $(this).css("right", 0).addClass("left").html("&lt");
        } else {
            target.show();
            $(".watch-box").css("margin-right", 360);
            $(this).css("right", 350).removeClass("left").html("&gt");
        }
    });
 
   
    $("#to-login").click(function() {
        var completed = true;
        $("#loginBox input.form-control").each(function() {
            var val = $(this).val();
            if (!vhallApp.waringNull($(this)) || (val == $(this).attr("placeholder"))) {
                completed = false;
                return false;
            }
        });
        if (completed) {
            var pars = {
                'username': $("#username").val(),
                'password': $("#pwd").val() /*,'captcha':$(".v-code").val()*/
            };
            if ($("#auto-login").is(":checked")) {
                pars.remember = "remember";
            }
            var loginUrl = "";
            if (typeof toLoginUrl != "undefined") {
                loginUrl = toLoginUrl;
            } else {
                loginUrl = '/include/logo.func.php';
            }
            $.ajax({
                url: loginUrl,
                type: 'post',
                data: pars,
                success: function(res) {
                    //var status = res.code;
					if(res){
						top.location.reload();
					}
                    /*switch (status) {
                        case "200":
                            window.location.reload();
                            $(".reserve-event").click();
                            break;
                        case "500":
                            var target = $("#pwd");
                            var placeHolder = target.attr("placeholder");
                            target.addClass("warning").attr("placeholder", "密码错误").val("");
                            target.focus(function() {
                                $(this).attr("placeholder", placeHolder);
                            });
                    }*/
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    if ($.parseJSON(XMLHttpRequest.responseText).captcha === "验证码错误") {
                        if ($(".v-code").val() == "验证码错误") {
                            return false;
                        } else {
                            tmpText = $(".v-code").val();
                            $(".v-code").addClass("warning").val("验证码错误");
                        }
                    }
                }
            });
        }
    });
    $("#tosearch").keydown(function(e) {
        var curKey = e.which;
        if (curKey == 13) {
            if ($.trim($(this).val()) === '') {
                $(this).focus();
                return false;
            } else {
                if ($("search-box").length > 0) {
                    getEvents($("#all-events-list"), getSearchUrl, 1, 'all');
                }
                window.location.href = searchPageUrl + '/?wd=' + encodeURI($(this).val());
            }
            return false;
        }
    });
    if ($(".watch-embed-box").length === 0) {
        $("body").css({
            "min-width": 1000,
            "min-height": 420
        });
    }
    if ($(".watch-fr").hasClass("chatonly")) {
        $("body").css({
            "min-width": "100%",
            "min-height": "100%"
        });
    }
    if ($(".ad-list li").length === 0) {
        $(".ad-list").parent().hide();
    }
});
