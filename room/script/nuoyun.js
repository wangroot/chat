 var rotate;
$("#rotateMain").click(function () {
  rotate= layer.open({
        type: 2,
        title: false,
        area: ['900px', '565px'],
         bgcolor: '',
      shadeClose: true,
       closeBtn: false,
        content: ['/apps/rotate/index.php']
    });
                    layer.style(rotate, {
   'box-shadow':'none',
   'background-color': 'transparent'
    
}); 
});

$(".zhan").live("click", function () {
    var id = $(this).attr("hdid");
$.ajax({
                url: "/apps/hd_zan.php",
                data: {id:id},
                 type: "POST",
                dataType: "JSON",
                success: function (rep) {
                    if (rep==1){
                var zan=$('a[hdid="' + id +'"] span').html();
                zan=parseInt(zan)+1;
                   $('a[hdid="' + id +'"] span').html(zan);
                    }else{
                        var xuanze='a[hdid="' + id +'"] span';
                            layer.tips('您已经点过赞了！', xuanze); 
                    }
                       
                }
            });
});

function center(a, b) {
     b &&
    function(a) {
        var b = $(a),
        c = ($(window).width() - b.outerWidth()) / 2;
        b.css({
            left: c
        })
    } (a),
    function(a) {
        var e, f, b = $(a),
        c = b.outerHeight(),
        d = $(window).height();
        c > d ? (b.css("top", "0px"), e = 0) : (f = (d - c) / 2, b.css("top", f + "px"), e = f)
    } (a)
}

function Hongbao(hbid){
   
        if(My.chatid.indexOf('x')>-1){
            layer.alert('请登录后再来抢红包把！', {
    title:'红包提示',
    
  icon: 6},function(index){

  
  layer.close(index);
  openWin(2,false,'room/minilogin.php',390,310);
          return;
})
         
        }
        var json,hbid,json, mobile, mymoney, money1, success, fail, had;
        //  hbid  = $(this).attr("moneygiftid")
           $.post("/apps/redbag/index.php?act=GetMoneygift", {
        customerId: My.chatid,
        moneyGiftId: hbid
    },
    function(data) {
        data && (json = eval("(" + data + ")"),
          $("#successredbag,#meetredbag,#hadredbag,#lookredbag,#lookthisbag,#failredbag").remove(), 1 == json.status ? (mymoney = $.trim($("#MyMoney").val()), mymoney = Math.floor(100 * mymoney), money1 = $.trim(json.amount), money1 = Math.floor(100 * money1), mymoney = (mymoney + money1) / 100, $("#MyMoney").val(mymoney), success = '<div id="successredbag">', success += '<div class="redbagclose"></div>', success += "" != json.senderAvatar ? '<div class="img"><img src="' + json.senderAvatar + '" width="65" height="65"></div>': '<div class="img"><img src="../images/avatar/52/01.png" width="65" height="65"></div>', success += '<p style="margin-top:5px;font-size: 14px;color:#000;">' + json.senderName + "的红包</p>", success += '<p class="p1" style="margin-top:5px; font-size: 12px;color: #CCC;">' + json.moneygiftTitle + "</p>", success += '<div class="money bagred">' + json.amount + '<span style="color:#333;font-size: 14px;">元</span></div>', success += '<p class="baginformation">已领取' + json.robcount + "/" + json.allcount + "个，共" + json.realityMoney + "元</p>", success += '<div class="HBlist">', $.each(json.rows,
        function(a, b) {
            success += '<div class="list-info">',
            success += null != b.senderAvatar ? '<div class="user-img"><img src="' + b.senderAvatar + '" width="35" height="35"></div>': '<div class="user-img"><img src="../images/avatar/52/01.png" width="35" height="35"></div>',
            success += '<div class="list-right">',
            success += '<div class="list-right-top">',
            success += '<div class="user-name fl f14">' + b.nickname + "</div>",
            success += '<div class="user-money fr f14">' + b.getmoney + "元</div></div>",
            success += '<div class="user-time">' + b.addtime + "</div>",
            success += "</div></div>"
        }), success += "</div>", success += '<div class="minebag1 cursor">查看我的红包>></div>', success += "</div>", $("body").append(success), center("#successredbag", !0), $(".minebag1").click(function() {
            minebag()
        }),SysSend.command('hongbaoinfo',json.senderName+'_+_'+json.amount)) : 0 == json.status ? (fail = '<div id="failredbag">', fail += '<div class="redbagclose"></div>', fail += "" != json.senderAvatar ? '<div class="img"><img src="' + json.senderAvatar + '" width="60" height="60"></div>': '<div class="img"><img src="../images/avatar/52/01.png" width="60" height="60"></div>', fail += '<p style="margin-top: 15px;">' + json.senderName + "</p>", fail += '<p class="memos">手慢了，红包派完了！</p>', fail += '<div class="lookthisbag">看看大家的手气>></div>', fail += "</div>", $("body").append(fail), center("#failredbag", !0), $(".lookthisbag").click(function() {
            thisbag(hbid)
        })) : (had = '<div id="hadredbag">', had += '<div class="redbagclose"></div>', had += "" != json.senderAvatar ? '<div class="img"><img src="' + json.senderAvatar + '" width="60" height="60"></div>': '<div class="img"><img src="../images/avatar/52/01.png" width="60" height="60"></div>', had += '<p style="margin-top: 15px;">' + json.senderName + "</p>", had += '<p class="memos mt10">您已经抢过这个红包了!</p>', had += '<div class="minebag cursor f14">看看大家的手气>></div>', had += "</div>", $("body").append(had), center("#hadredbag", !0), $(".minebag").click(function() {
            thisbag(hbid)
        }))
   ) })
    }
 $("body").on("click", ".redbagclose",
    function() {
        $(this).parent().remove()
    }),
  $(".hongBao").click(function() {
     var a = '<div id="hongBaoClick">';
        a += '<div class="bagbtn">',
        a += '<div class="sethongBao">发红包</div>',
        a += '<div class="lookmainbag">我的红包</div>',
        a += "</div>",
        a += '<div class="redbagclose"></div>',
        a += "</div>",
        $("body").append(a),
        center("#hongBaoClick", !0),
        $(".lookmainbag").click(function() {
            $("#hongBaoClick").remove(),
            minebag()
        }),
        $(".sethongBao").click(function() {
            var a, b, c;
            $("#hongBaoClick").remove(),
            a = '<div id="setEnvelope">',
            a += '<div class="redbagclose"></div>',
            a += '<div class="envelopeBody">',
            a += '<div class="mt10 pt10"><div class="registerNewconter" style="padding-right:60px;">金额：<input type="text" id="realmoney" name="realmoney" maxlength="4" value="请填写红包金额" title="请填写红包金额"> 元</div><p class="envelopetip" id="realmoneytip" style="margin-left:97px;"></p></div>',
            a += '<div class="mt10"><div class="registerNewconter" style="padding-right:60px;">个数：<input type="text" name="realnumber" id="realnumber" maxlength="4" value="请填写红包个数" title="请填写红包个数"> 个</div><p class="envelopetip" id="realnumbertip" style="margin-left:97px;"></p></div>',
            a += '<div class="mt10"><div class="registerNewconter" style="padding-right:79px;"><span>备注：</span><textarea name="envelopetext" id="envelopetext" maxlength="30">恭喜发财,大吉大利！</textarea></div></div>',
            a += '<div class="registerNewconter bagred" style="padding-right:0;"><p id="money" style="color: #fc4c4c;">￥0.00</p></div>',
            a += '<div class="registerNewconter" style="padding-right:0;"><p style="font-size:14px;color:#a0a0a0;">余额￥<span id="mymoney1">' + $("#MyMoney").val() + "</span>元</p></div>",
            a += '<div class="registerNewconter"><button class="btnEnvelope mt10" id="btnenvelope">发红包</button><button class="btnEnvelope mt10" id="btnenvelopeing">正在发..</button></div>',
            a += "</div>",
            a += "</div>",
            $("body").append(a),
            center("#setEnvelope", !0),
            $("#setEnvelope input").focus(function() {
                $(this).val() == $(this).attr("title") && ($(this).val(""))
            }),
            $("#setEnvelope input").blur(function() {
                "" == $(this).val() && $(this).val($(this).attr("title"))
            }),
            b = /^[0-9]*$/,
            c = /^[0-9]+(\.[0-9]+)?$/,
            $("#realmoney").blur(function() {
                var b, d, e, f, g, h, i, a = $.trim($("#realmoney").val());
                c.test(a) && "0" != a && "0.0" != a && "0.00" != a ? (a = Math.floor(100 * a),a<200?$("#realmoneytip").text("红包最低金额为2元"): (b = $.trim($("#MyMoney").val()), b = Math.floor(100 * b), b >= a ? (b -= a, a /= 100, b /= 100, a.toString().indexOf(".") < 0 && (a += ".00"), d = a.toString().split("."), e = parseInt(d[0], 10), f = d[1], 1 == f.length && (f += "0"), a = e + "." + f, b.toString().indexOf(".") < 0 && (b += ".00"), g = b.toString().split("."), h = parseInt(g[0], 10), i = g[1], 1 == i.length && (i += "0"), b = h + "." + i, $("#realmoney").val(a), $("#realmoneytip").text(""), $("#money").text("￥" + a), $("#mymoney1").text(b)) : (b /= 100, b.toString().indexOf(".") < 0 && (b += ".00"), d = b.toString().split("."), e = parseInt(d[0], 10), f = d[1], 1 == f.length && (f += "0"), b = e + "." + f, $("#realmoney").val(b), $("#realmoneytip").text("余额不足 最多" + b + "元"), $("#money").text("￥" + b), $("#mymoney1").text("0.00")))) : a != $("#realmoney").attr("title") && ("0" == a || "0.0" == a || "0.00" == a ? $("#realmoneytip").text("金额不能为0") : $("#realmoneytip").text("请填写正确的金额"))
            }),
            $("#realnumber").blur(function() {
                var a = $.trim($("#realnumber").val());
                b.test(a) && "0" != a ? (a = parseInt(a), a > 30 ? ($("#realnumbertip").text("红包数量最多30个"), $("#realnumber").val(30)) : ($("#realnumbertip").text(""), $("#realnumber").val(a))) : a != $("#realnumber").attr("title") && ("0" == a ? $("#realnumbertip").text("数量不能为0") : $("#realnumbertip").text("请填写正确的数量"))
            }),
            $("#btnenvelope").click(function() {
                var a, d, e, f,s;
                a = 0, d = $.trim($("#realmoney").val()), c.test(d) && "0" != d && "0.0" != d && "0.00" != d ?(s=$.trim($("#realmoney").val()),s = Math.floor(100 * s),s<200?($("#realmoneytip").text("红包最低金额为2元"),a++): $("#realmoneytip").text("")) : (d != $("#realmoney").attr("title") && ("0" == d || "0.0" == d || "0.00" == d ? $("#realmoneytip").text("金额不能为0") : $("#realmoneytip").text("请填写正确的金额")), a++), e = $.trim($("#realnumber").val()), b.test(e) && "0" != e ? $("#realnumbertip").text("") : (e != $("#realnumber").attr("title") && ("0" == e ? $("#realnumbertip").text("数量不能为0") : $("#realnumbertip").text("请填写正确的数量")), a++), f = $.trim($("#envelopetext").val()), 0 == a && (h = "checked" == $("#robotbag").attr("checked") ? !0 : !1, i = "-1" == $("#spoker>option:checked").val() ? "": $("#spoker>option:checked").text(), 
                $.post("/apps/redbag/index.php?act=SendMoneygift", {
                    amount: d,
                    num: e,
                    memos: f
                },
                function(a) {
                    var b, c,neir,str,msgid;
                     "ok" == a.status ? ($("#btnenvelope").hide(), $("#btnenvelopeing").show(), b = $("#mymoney1").text(), $("#MyMoney").val(b), $("#setEnvelope").remove(), c = '<div id="setEnvelopeOK"><p class="tipOK">红包发送成功!</p><div class="OK cursor">好的</div></div>', $("body").append(c), center("#setEnvelopeOK", !0), 
                    neir='<div class="content-detail" moneygiftid="'+a.id+'"><div class="redbag-top" title="'+a.beizhu+'" adminname="" isrobot="0" ><div class="fl"><img src="/images/hongbao.png" style="margin-top: 3px;"></div><div class="fl ml10" style="color:#fff;"><p style="font-weight:bold;margin-bottom:4px;color:#f30;font-size:14px;">'+a.beizhu+'</p>领取红包</div></div><div style="padding:3px 10px;">直播室红包</div></div>',
                   msgid=randStr()+randStr(),
                   //str='SendMsg=M=ALL|false|padding: 0px;|'+msgid+'_+_'+encodeURIComponent(neir),
                   str = '{"type":"SendMsg","ToChatId":"ALL","IsPersonal":"false","Style":"padding: 0px","Txt":"'+msgid+'_+_'+encodeURIComponent(neir)+'"}',
                   ws.send(str),
                PutMessage(My.rid,My.chatid,'ALL',My.nick,'ALL','false','padding:0px;',neir,msgid),
                    $("#setEnvelopeOK .OK").click(function() {
                        $("#setEnvelopeOK").remove(),
                        setTimeout("$('#meetredbag').show();", 800)
                    })) : alert('发送失败');
                } ,"json"))
            })
        })
    })
    

    var thisbag,minebag;
  thisbag= function(moneyGiftId) { 
   $.post("/apps/redbag/index.php?act=GetMoneygiftList",{ 
       moneyGiftId: moneyGiftId },
   function (data){ 
       var json, success; data && (json = eval(" (" + data + ")"), $("#successredbag,#meetredbag,#hadredbag,#lookredbag,#lookthisbag,#failredbag ").remove(), success = '<div id="lookthisbag">', success += '<div class="redbagclose"></div>', success += "" != json.senderAvatar ? '<div class="img"><img src="' + json.senderAvatar + '" width="65" height="65"></div>' : '<div class="img"><img src="../images/avatar/52/01.png" width="65" height="65"></div>', success += '<p style="margin-top:5px;font-size:14px;color:#000;">' + json.senderName + "的红包</p>", success += '<p class="p1" style="margin-top:5px;font-size:12px;color:#CCC;">' + json.moneygiftTitle + "</p>", success += '<div class="money bagred">' + json.realityMoney + '<span style="color:#333;font-size:14px;">元</span></div>', success += '<p class="baginformation">已领取' + json.robcount + "/" + json.allcount + "个，共" + json.realityMoney + "元 </p>",
        success += '<div class="HBlist">', $.each(json.rows, function (a, b) { success += '<div class="list-info">', 
        success += null != b.senderAvatar ? '<div class="user-img"><img src="' + b.senderAvatar + '" width="35" height="35"></div> ' : ' < div class = "user-img" > <img src = "../images/avatar/52/01.png" width = "35" height = "35"></div>', 
        success += '<div class="list-right">', success += '<div class="list-right-top">', 
        success += '<div class="user-name fl f14">' + b.nickname + "</div>", 
        success += '<div class="user-money fr f14">' + b.getmoney + "元 </div></div> ", 
        success += '<div class="user-time">' + b.addtime + " </div>", 
        success += "</div></div>" }), 
    success += "</div>", 
    success += '<div class="minebag1 cursor">查看我的红包>></div>', 
    success += " </div>", $("body").append(success), 
    center("#lookthisbag", !0), $(".minebag1").click(function () { minebag() })) }) }
minebag = function () {
$.post("/apps/redbag/index.php?act=GetUsertMoneygift", function(data){var json,lookredbag;data&&(json=eval("(" + data + ")"), 
    $("#successredbag,#meetredbag,#hadredbag,#lookredbag,#lookthisbag,#failredbag ").remove(), 
    lookredbag = '<div id="lookredbag">', 
    lookredbag += '<div class="redbagclose"></div>', 
    lookredbag += "" != json.avatar ? '<div class="img"><img src="' + json.avatar + '"width="65" height="65"></div>' : '<div class="img"><img src="../images/avatar/52/01.png" width="65" height="65"></div>', 
    lookredbag += '<p style="margin-top:10px;font-size: 14px;color:#000">' + json.nickName + '共收到<span style="color:red;">' + json.allcount + "</span>个红包</p>", 
    lookredbag += '<div class="money bagred">' + json.allmoney + '<span style="color:#333;font-size:14px;">元</span></div>', 
    lookredbag += '<div class="HBlist">', null!=json.rows ? $.each(json.rows, function (a, b) { lookredbag += '<div class="list-info">', 
        lookredbag += null != json.avatar ? '<div class="user-img"><img src="' + json.avatar+ '" width="35" height="35"></div>' : '<div class="user-img"><img src="../images/avatar/52/01.png" width="35" height="35"></div>', 
        lookredbag += '<div class="list-right">', lookredbag += '<div class="list-right-top">', 
        lookredbag += '<div class="user-name fl f14">' + b.nickname + " </div>", 
        lookredbag += '<div class="user-money fr f14">' + b.getmoney + "元</div></div>", 
        lookredbag += '<div class="user-time">' +  b.addtime + "</div>", 
        lookredbag += " </div></div> " }):'', 
    lookredbag += " </div></div>", $("body").append(lookredbag), center("#lookredbag", !0)) }) }

$(function() {

$("#UI_Central").delegate(".content-detail", "click",
	function() {
		var b, c;
            
	b = $(this), c = b.attr("moneygiftid"),  $(this).unbind("click"),Hongbao(c)
	})

})