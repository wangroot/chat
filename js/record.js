/**
接收flash发来的翻页信息发送给其他flash
*/
function sendRecordMsg(type, params) {
    console.log(params);
    try {
        sendEveToFlash(type, params);
        return true;
    } catch (e) {
        ldebug('parse JSON error:' + e.message);
        return false;
    }
}
var timer=null;
var watchApp={
	sendReport:function(parent,name,con,url){
		var  checked=parent.find("[name='"+name+"']:checked");
		if((checked.length===0)&&(con.val()==="")){
			parent.find(".tips").css("visibility","visible");
			timer=setTimeout(function(){
				parent.find(".tips").css("visibility","hidden");
			},3000);
			return false;
		}else{
			clearTimeout(timer);
		}

        var type = '';
        checked.each(function(i){
            if(checked.length == 1){
                type = $(this).val();
            } else {
                if(i+1 == checked.length){
                    type += $(this).val();
                } else {
                    type += $(this).val()+',';
                }
            }
        });

		var feedbackType=type;
		var feedbackContent=con.val();
        var uid = $('#problem_uid').val();

        if(!feedbackContent){
            vhallApp.showMsg('内容不能为空!','warning');
            return ;
        }
		$.ajax({
			url:url,
			type:'post',
            dataType: 'json',
			data:{'type':feedbackType,'content':feedbackContent, 'uid':uid},
			beforeSend: function() {

			},
			success:function(res){
				var status=res.code;
				switch(status){
					case "200":
						parent.modal("hide");
						checked.prop("checked",false);
						con.val("");
						vhallApp.showMsg('反馈成功');
						break;

                    case "500":
                        parent.modal("hide");
                        checked.prop("checked",false);
                        con.val("");
                        vhallApp.showMsg(res.msg, 'warning');
                        break;
					default:
						break;
				}
			}
		}); 
	},
	animationTimer:null,
	noticeDistence:null,
	noticeSysCon:null,
	disLeft:null,
	showNoticeTips:function(msg){
		clearInterval(watchApp.animationTimer);
		var target=$(".sys-notice");
		this.noticeDistence=$(".video-doc-box").width();	
		if(target.length===0){
			$(".video-doc-box").prepend('<p class="sys-notice" style="display:none;"><span class="sys-con nowrap" style="left:'+this.noticeDistence+'px">'+msg+'</span><a href="javascript:void(0);" class="close-notice" title="关闭"></a></p>');			
		}else{
			target.find(".sys-con").html(msg);
		}
		$(".sys-notice").fadeIn(1200);
		this.noticeSysCon=$(".sys-notice").find(".sys-con");
		this.disLeft=this.noticeSysCon.width();
		function startAnimation(){	
			watchApp.animationTimer=setInterval(function(){
				watchApp.noticeDistence--;
				watchApp.noticeSysCon.css("left",watchApp.noticeDistence);
				if(watchApp.noticeDistence==(-watchApp.disLeft)){
					watchApp.noticeDistence=$(".video-doc-box").width();	
				}	
			},30);
		}
		startAnimation();
		//var timer=setTimeout(function(){$(".sys-notice").fadeOut(1500);},10000);
	}
};