/***FMS服务器接收js****/
/**
去掉HTML标签
*/
function removeHTMLTag(str) {
    str = escape2Html(str);
    str = str.replace(/<\/?[^>]*>/g, ''); //去除HTML tag
    str = str.replace(/[ | ]*\n/g, '\n'); //去除行尾空白
    str = str.replace(/ /ig, ''); //去掉 
    return str;
}

function escape2Html(str) {
    var arrEntities = {
        'lt': '<',
        'gt': '>',
        'nbsp': ' ',
        'amp': '&',
        'quot': '"'
    };
    return str.replace(/&(lt|gt|nbsp|amp|quot);/ig, function(all, t) {
        return arrEntities[t];
    });
}


function html_encode(str) {   
    var s = "";   
    if (str.length === 0) return "";   
    s = str.replace(/&/g, "&amp;");   
    s = s.replace(/</g, "&lt;");   
    s = s.replace(/>/g, "&gt;");   
    //s = s.replace(/ /g, "&nbsp;");   
    s = s.replace(/\'/g, "&#39;");   
    s = s.replace(/\"/g, "&quot;");   
    s = s.replace(/\n/g, "<br>");  
    return s;   
}
/**
向Flash发信息
*/
function getFlexObj(Id) {
    var myobj = null;
    if (navigator.appName.indexOf("Microsoft") > -1) {
        myobj = window[Id];
    } else {
        myobj = document[Id];
    }
    return myobj;
}

function sendMsgToFlash(type, obj) {
    var tmp_obj = $('.Intranet_Runner');
    $.each(tmp_obj, function(index, tmp) {
        try {
            tmp.sendMsgToAs(type, obj);
        } catch (e) {
            ldebug('flsah 错误信息：' + e.message);
        }
    });
}

function sendEveToFlash(type, obj) {
    var tmp_obj = $('.Intranet_Runner');
    $.each(tmp_obj, function(index, tmp) {
        try {
            tmp.sendEventToAs(type, obj);
        } catch (e) {
            ldebug('flsah 错误信息：' + e.message);
        }
    });
}

/**
接收flash发来的信息
*/
function sendCmdMsg(obj) {
    console.log('接收flash发来信息：' + obj);
    try {
        var object = Base64.decode(obj);
        obj = $.parseJSON(object);
        analyzeFlashMsg(obj);
        return true;
    } catch (e) {
        console.log('parse JSON error:' + e.message);
        return false;
    }
}

function CurentTime() {
    var myDate = new Date();

    var hh = myDate.getHours(); //时
    var mm = myDate.getMinutes(); //分
    var ss = myDate.getSeconds(); //秒

    var clock = " ";

    if (hh < 10) clock += "0";
    clock += hh + ":";
    if (mm < 10) clock += '0';
    clock += mm + ":";
    if (ss < 10) clock += '0';
    clock += ss;
    return (clock);
}

function isExistOption(id, value) {
    var isExist = false;
    var count = $('#' + id).find('option').length;
    for (var i = 0; i < count; i++) {
        if ($('#' + id).get(0).options[i].value == value) {
            isExist = true;
            break;
        }
    }
    return isExist;
}

//控制Flash音量 v 0-1
function setVolume(v) {
    ldebug('vol:' + v);
    if (v > 1 || v < 0) {
        return false;
    }
    var tmp_obj = {};
    tmp_obj.param = v;
    sendMsgToFlash('*volume', $.toJSON(tmp_obj));
}

//控制Flash清晰度 v low  middle high
function setBitrate(v) {
    ldebug('bit:' + v);
    var tmp_obj = {};
    tmp_obj.param = v;
    sendMsgToFlash('*bitrate', $.toJSON(tmp_obj));
}

//控制接口
$(function() {
    try {
        var arr = [];
        var commd = 0;
        var v = 0;
        $(window).hashchange(function() {
            var hash = location.hash;
            var str = hash.replace(/^#/, '') || 'blank';
            ldebug(str);
            arr = str.split('=');
            commd = arr[0];
            v = arr[1];
            ldebug(commd);
            ldebug(v);

            switch (commd) {
                case 'setVolume':
                    setVolume(v);
                    break;
                case 'setBitrate':
                    setBitrate(v);
                    break;
                default:
                    break;
            }

        });

        $(window).hashchange();
    } catch (e) {}
});


/**
调试
*/
function ldebug(arg) {
    var str = location.hash.split('#');
    str = str[str.length - 1];
    if (str === 'debug=true' && window.console) {
        //ldebug(str);
        try {
            throw new Error();
        } catch (e) {
            console.log(arg,e.stack.replace(/Error\n/,'').split(/\n/)[1]);
            /*console.log("Stack:" + e.stack);
            var loc= e.stack.replace(/Error\n/).split(/\n/)[1].replace(/^\s+|\s+$/, "");
            console.log(e.stack.replace(/Error\n/,'').split(/\n/)[0]);
            console.log("Location: "+loc+"");*/
        }
    }
}

var console = console || {
    log: function() {
        return false;
    }
};


function sendLog(obj) {
    //return false;
    $.ajax({
        url: pageinfo.log_service,
        type: 'post',
        data: JSON.stringify(obj)
    });
}
