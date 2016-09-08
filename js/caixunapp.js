// JavaScript Document
/*vhallApp*/
var jiathis_config = {
    url: "",
    title: "",
    summary: "",
    pic: ""
};
var sendMsgId = "";
var vhallApp = {
    tabInit: function(btns, tag, con) {
        $('.' + btns).on('click', tag, function() {
            $('.' + btns + ' ' + tag).removeClass("active");
            $(this).addClass("active");
            var target = $('.' + btns).next().find('.' + con);
            if (target.hasClass('active')) {
                target.removeClass('active').hide();
            } else {
                target.hide();
            }
            if ($('.' + con).is("tbody")) {
                target.eq($('.' + btns + ' ' + tag).index($(this))).css("display", "table-row-group").addClass("active");
            } else {
                target.eq($('.' + btns + ' ' + tag).index($(this))).show();
            }
        });
    },
    showMsg: function(msg, warning) {
        if ($("#showMsg").length === 0) {
            msg = '<div id="showMsg"><span class="icon"></span><p class="msg">' + msg + '</p></div>';
            $("body").append($(msg));

        } else {
            $("#showMsg .msg").html(msg);
        }

        var target = $("#showMsg");
        var msgWidth = target.outerWidth();
        target.css("margin-left", -msgWidth / 2);
        if (warning) {
            target.addClass("warning");
        } else {
            target.removeClass("warning");
        }
        target.fadeIn();
        var timer = setTimeout(function() {
            target.fadeOut();
        }, 3000);
    },
    addCookie: function(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        var cookieStr = vhallApp.getCookie(cname);
        cookieStr += '|' + cvalue;
        document.cookie = cname + "=" + cookieStr + "; " + expires + ';path=/';
    },
    getCookie: function(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1);
            if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
        }
        return "";
    },
    showGuide: function(target, img, disX, disY, guide) {
        var guideBox;
        if ($(".guide-mask").length === 0) {
            var guideMask = '<div class="guide-mask"></div>';
            var btnClass = "close";
            if (guide == "guide-box-set1" || guide == "guide-box-edit1") {
                btnClass = "next";
            }
            guideBox = '<div class="' + guide + '"><img src=' + img + ' alt=""><a href="javascript:void(0);" class="' + btnClass + '"></a></div>';
            $("body").append($(guideMask));
            $("body").append($(guideBox));
        } else {
            if (guide == "guide-box-set2") {
                $(".guide-box-set1").hide();
                guideBox = '<div class="' + guide + '"><img src=' + img + ' alt=""><a href="javascript:void(0);" class="next"></a></div>';
                $("body").append($(guideBox));

            } else if (guide == "guide-box-set3" || guide == "guide-box-edit2") {
                $(".guide-box-set2").hide();
                $(".guide-box-edit1").hide();
                guideBox = '<div class="' + guide + '"><img src=' + img + ' alt=""><a href="javascript:void(0);" class="close"></a></div>';
                $("body").append($(guideBox));
            }
        }
        $("." + guide).css({
            "position": "absolute",
            "left": ($(target).offset().left + disX),
            "top": $(target).offset().top + disY
        });
        //$("body").addClass("mask-open");
        $(window).resize(function() {
            $("." + guide).css({
                "position": "absolute",
                "left": $(target).offset().left + disX,
                "top": $(target).offset().top + disY
            });
        });
        $(window).resize();
        $("." + guide).find(".close").click(function() {
            $(".guide-mask,." + guide).fadeOut();
            //$("body").removeClass("mask-open");	
        });
    },
    floatCard: function(parent, sclass, target) {
        var xDis, yDis;
        var timer = null;
        parent.on('mouseenter', sclass, function(e) {
            xDis = $(this).offset().left;
            yDis = $(this).offset().top;
            if (target.is("#shareout-div")) {
                var parents = $(this).parents(".li");
                var scan = parents.find(".scan");
                var href = scan.attr("href");
                var imgurl = scan.find("img").attr("src");
                var title = scan.attr("title");
                jiathis_config.url = href;
                jiathis_config.title = '中国最大的网络直播互动平台——';
                jiathis_config.summary = title;
                jiathis_config.pic = imgurl;
                target.css({
                    left: (xDis - 85),
                    top: (yDis + 20)
                }).show();
            } else {
                target.css({
                    left: (xDis + 20),
                    top: (yDis + 30)
                }).show();
                var nickName = $(this).attr("title");
                var headImg = $(this).find("img").attr("src");
                var oid = $(this).parent().attr("oid");
                target.find(".nickname").attr("title", "nickName").html(nickName);
                target.find(".head").attr("src", headImg);
                target.find(".follow-someone,.private-chat").attr("uid", oid);
                target.find(".private-chat").attr("receiver", nickName);
            }
        });
        parent.on('mouseleave', sclass, function(e) {
            timer = setTimeout(function() {
                target.hide();
            }, 200);
        });
        target.hover(function() {
            clearTimeout(timer);
        }, function() {
            timer = setTimeout(function() {
                target.hide();
            }, 200);
        });
    },
    selectAll: function(btn, target) {
        btn.change(function() {
            if ($(this).is(":checked")) {
                target.prop("checked", true);
            } else {
                target.prop("checked", false);
            }
        });
    },
    waringNull: function(target, msg) {
        if ($.trim(target.val()) === "" || $.trim(target.val()) == target.attr("placeholder")) {
            tmpText = "";
            if (!msg) {
                target.addClass("warning");
            } else {
                target.addClass("warning").val(msg);
            }
            return false;
        }
        return true;
    },
    testEmail: function(ele) {
        var val = ele.val();
        var regEmail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regEmail.test(val)) {
            tmpText = val;
            ele.addClass("warning").val("您输入的邮箱格式不正确");
            return false;
        }
        return true;
    },
    testPhone: function(ele) {
        var val = ele.val();
        var regPhone = /^1[3|5|7|8]\d{9}$/;
        if (!regPhone.test(val)) {
            tmpText = val;
            ele.addClass("warning").val("您输入的手机格式不正确");
            return false;
        }
        return true;
    },
    sendMsg: function(url) {
        $(".chatlist").on('click', '.private-chat', function() {
            $("#sendMsg #msg-receiver").html($(this).attr("receiver"));
            sendMsgId = $(this).attr("uid");
        });
        $("[data-target='#sendMsg']").on('click', function() {
            $("#sendMsg #msg-receiver").html($(this).attr("receiver"));
            sendMsgId = $(this).attr("uid");
        });
        $("#sendMsg .btn").click(function() {
            if(!$("#msg-con").val()){
                vhallApp.showMsg('内容不能为空', 'warning');
                return;
            }
            $.ajax({

                url: url ? url : pageinfo.domain + '/user/addmessage',
                type: 'post',
                dataType: 'json',
                data: {
                    'user_id': sendMsgId,
                    'content': $("#msg-con").val(),
                    'sendid': $('#sendid').val(),
                    'uname': $('#uname').val()
                },
                beforeSend: function() {

                },
                success: function(res) {
                    var status = res.code;
                    if (status == "200") {
                        $("#sendMsg").modal("hide");
                        $("#msg-con").val("");
                        vhallApp.showMsg("发送成功！");
                    } else {
                        $("#sendMsg").modal("hide");
                        $("#msg-con").val("");
                        vhallApp.showMsg(res.msg, 'warning');
                    }

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    if (XMLHttpRequest.responseText == "Unauthorized.") {
                        $("#loginBox").modal("show");
                    }
                }

            });
        });
    },
   
    isNumber: function(e) {
        var k = window.event ? e.keyCode : e.which;
        if (((k >= 48) && (k <= 57)) || ((k >= 96) && (k <= 105)) || k == 8 || k == 46) {} else {
            if (window.event) {
                window.event.returnValue = false;
            } else {
                e.preventDefault(); //for firefox 
            }
        }
    }
};
var tmpText = "";
$(document).ready(function() {
    $("input,textarea").focus(function() {
        if ($(this).hasClass("warning")) {
            $(this).removeClass("warning").val(tmpText);
            if ($(this).hasClass("has-msg")) {
                $(this).parent().next().hide();
            }
        }
    });
    $(".modal .cancel").click(function() {
        $(this).parents(".modal").modal("hide");
    });
    $(".modal").on('keydown', 'input[type="text"],input[type="password"]', function(e) {
        if (e.which == 13) {
            $(this).parents('.modal').find('.btn.btn-submit').each(function() {
                if ($(this).css('display') != 'none') {
                    $(this).click();
                }
            });
        }
    });
});

Date.prototype.Format = function(fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};

function timeFormat (time) {
    //格式化时间
    var hour = parseInt(time / 3600),
        minute = parseInt(time / 60),
        second = parseInt(time % 60),
        html = '';
    if (hour < 1) {
        html += minute >= 10 ? minute + ':' : '0' + minute + ':';
        html += second >= 10 ? second : '0' + second;
    } else {
        minute = parseInt(minute % 60);
        html += hour >= 10 ? hour + ':' : '0' + hour + ':';
        html += minute >= 10 ? minute + ':' : '0' + minute + ':';
        html += second >= 10 ? second : '0' + second;
    }
    if (isNaN(hour)) {
        html = "00:00:00";
    }
    return html;
}
