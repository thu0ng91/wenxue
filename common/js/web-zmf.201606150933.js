/*!
 * NEWSOUL's production http://newsoul.cn
 * Copyright 2016
 * Author zmf http://blog.newsoul.cn
 * This is NOT a freeware, use is subject to license terms
 */
function rebind(){$("img.lazy").lazyload({threshold:600}),$("a[action=getContents]").unbind("click").click(function(){var a=$(this),b=a.attr("data-id"),c=a.attr("data-type"),d=a.attr("data-page"),e=a.attr("data-target");if(!e)return!1;if("1"===a.attr("data-loaded"))return $("#"+e+"-box").hide(),a.attr("data-loaded",0),!1;d||(d=1);var f='<div class="loading-holder"><a class="btn btn-default btn-sm disabled" href="javascript:;">拼命加载中...</a></div>';$("#"+e).children(".loading-holder").each(function(){$(this).remove()}),$("#"+e).append(f),$("#"+e+"-box").show(),$.post(zmf.ajaxUrl,{action:"getContents",type:c,page:d,data:b,YII_CSRF_TOKEN:zmf.csrfToken},function(f){if(ajaxReturn=!0,a.attr("data-loaded",1),f=$.parseJSON(f),1===f.status){var g=f.msg,h="",i="";if(""!==g.html&&(i+=g.html),1===g.loadMore){var j=parseInt(d)+1;h+='<div class="loading-holder"><a class="btn btn-default btn-sm"  href="javascript:;" action="getContents" data-type="'+c+'" data-id="'+b+'" data-page="'+j+'" data-target="'+e+'">加载更多</a></div>'}else h+=""===g.html&&1===d?"":'<div class="loading-holder"><a class="btn btn-default btn-sm disabled" href="javascript:;">已全部加载</a></div>';$("#"+e+" .loading-holder").each(function(){$(this).remove()}),d>1?$("#"+e).append(i):(""===g.html&&(i='<div class="help-block text-center">暂无内容</div>'),$("#"+e).html(i)),$("#"+e).append(h),1===d&&$("#"+e+"-form").html(g.formHtml),rebind()}else dialog({msg:f.msg})})}),$("a[action=delContent]").unbind("click").click(function(){var a=$(this),b=a.attr("data-id"),c=a.attr("data-type"),d=a.attr("data-confirm"),e=a.attr("data-redirect"),f=a.attr("data-target");if(!b||!c)return!1;var g=!0;return 1===parseInt(d)&&(g=confirm("确定删除此内容？")?!0:!1),g&&checkAjax()?void $.post(zmf.ajaxUrl,{action:"delContent",type:c,data:b,YII_CSRF_TOKEN:zmf.csrfToken},function(a){return ajaxReturn=!0,a=$.parseJSON(a),1===a.status?e?window.location.href=e:f?$("#"+f).fadeOut(500).remove():alert(a.msg):dialog({msg:a.msg}),!1}):!1}),$("a[action=favorite]").unbind("click").click(function(){var a=$(this);favorite(a)}),$(".tag-item").unbind("click").click(function(){var a=$(this);a.hasClass("active")?(a.removeClass("active"),a.children("input").removeAttr("checked")):(a.addClass("active"),a.children("input").attr("checked","checked"))}),$(".comment-textarea").unbind("click").click(function(){$(".toggle-area").each(function(){$(this).fadeIn(500)})}),$("a[action=add-comment]").unbind("click").click(function(){var a=$(this),b=a.attr("action-data"),c=a.attr("action-type");addComment(a,c,b)}),$("a[action=add-tips]").unbind("click").click(function(){var dom=$(this),k=dom.attr("action-data"),t=dom.attr("action-type"),c=$("#content-"+t+"-"+k).val(),score=parseInt($('#add-tips-score input[name="score"]:checked ').val());return k&&t&&c?(score||(score=0),(!score||1>score||score>5)&&0===to?(dialog({msg:"请打一个分吧"}),!1):checkAjax()?void $.post(zmf.ajaxUrl,{action:"addTip",k:k,t:t,c:c,score:score,YII_CSRF_TOKEN:zmf.csrfToken},function(result){ajaxReturn=!0,result=eval("("+result+")"),"1"==result.status?($("#content-"+t+"-"+k).val(""),$("#comments-"+t+"-"+k).append(result.msg),cancelReplyOne(k),rebind()):dialog({msg:result.msg})}):!1):(dialog({msg:"请填写内容"}),!1)}),$("a[action=scroll]").unbind("click").click(function(){var a=$(this),b=a.attr("action-target");return b?void $("body,html").animate({scrollTop:$("#"+b).offset().top},200):!1}),$("a[action=share]").unbind("click").click(function(){var a=$(this);share(a)}),$("a[action=setStatus]").unbind("click").click(function(){var dom=$(this),id=parseInt(dom.attr("data-id"));if(!id)return alert("缺少参数"),!1;var type=dom.attr("data-type");if(!type)return alert("缺少参数"),!1;var action=dom.attr("data-action");return action?void(confirm("确定该操作？")&&$.post(zmf.ajaxUrl,{action:"setStatus",type:type,id:id,actype:action,YII_CSRF_TOKEN:zmf.csrfToken},function(result){return result=eval("("+result+")"),"1"!=result.status?(dialog({msg:result.msg}),!1):void window.location.reload()})):(alert("缺少参数"),!1)}),$("a[action=publishBook]").unbind("click").click(function(){var dom=$(this),id=parseInt(dom.attr("data-id"));return id?void(confirm("确定立即发表作品么？")&&$.post(zmf.ajaxUrl,{action:"publishBook",id:id,YII_CSRF_TOKEN:zmf.csrfToken},function(result){return result=eval("("+result+")"),"1"!=result.status?(dialog({msg:result.msg}),!1):void window.location.reload()})):(alert("缺少参数"),!1)}),$("a[action=publishChapter]").unbind("click").click(function(){var dom=$(this),id=parseInt(dom.attr("data-id"));return id?void(confirm("确定立即发表本章节么？")&&$.post(zmf.ajaxUrl,{action:"publishChapter",id:id,YII_CSRF_TOKEN:zmf.csrfToken},function(result){return result=eval("("+result+")"),"1"!=result.status?(dialog({msg:result.msg}),!1):void window.location.reload()})):(alert("缺少参数"),!1)}),$("#add-post-btn").unbind("click").click(function(){$(window).unbind("beforeunload")}),$('[data-toggle="tooltip"]').tooltip({container:"body"}),$("a[action=showChapters]").unbind("click").click(function(){var a=$(this);a.hasClass("active")?(a.removeClass("active"),$("#fixed-chapters").hide()):(a.addClass("active"),$("#fixed-chapters").show())}),$("a[action=nextChapter]").unbind("click").click(function(){var a=$("#nextChapter"),b=a.attr("title");b=b?b:a.attr("data-original-title"),b=b?b:"正在加载";var c=a.attr("href");"javascript:;"!==c&&""!==c?window.location.href=c:simpleDialog({content:b})}),$("a[action=preChapter]").unbind("click").click(function(){var a=$("#preChapter"),b=a.attr("title");b=b?b:a.attr("data-original-title"),b=b?b:"正在加载";var c=a.attr("href");"javascript:;"!==c&&""!==c?window.location.href=c:simpleDialog({content:b})}),$(".tooltip").mouseover(function(){$(this).remove()}),$("a[action=feedback]").unbind("click").click(function(){var a='<div class="form-group"><label for="feedback-contact">联系方式</label><input type="text" id="feedback-contact" class="form-control" placeholder="常用联系方式(邮箱、QQ、微信等)，便于告知反馈处理进度(可选)"/></div><div class="form-group"><label for="feedback-content">反馈内容</label><textarea id="feedback-content" class="form-control" max-lenght="255" placeholder="您的意见或建议"></textarea></div>';dialog({msg:a,title:"意见反馈",action:"feedback"}),$("button[action=feedback]").unbind("click").click(function(){feedback()})}),$("a[action=report]").unbind("click").click(function(){var dom=$(this),type=dom.attr("action-type"),id=dom.attr("action-id"),title=dom.attr("action-title");if(!type)return alert("缺少参数"),!1;var html='<div class="form-group"><label>举报对象</label><p class="help-block ui-nowarp">'+title+'</p><input type="hidden" name="report-id" id="report-id" value="'+id+'"/></div>';if("book"===type||"chapter"===type)html+='<div class="form-group"><label for="feedback-reason">举报原因</label><select name="report-reason" id="report-reason" class="form-control"><option value="色情低俗">色情低俗</option><option value="暴力血腥">暴力血腥</option><option value="涉政违规">涉政违规</option><option value="欺诈广告">欺诈广告</option><option value="抄袭侵权">抄袭侵权</option><option value="">其他原因</option></select></div>';else{if("tip"!==type&&"book"!==type&&"comment"!==type&&"post"!==type&&"user"!==type&&"author"!==type)return alert("暂不支持该分类"),!1;html+='<div class="form-group"><label for="feedback-reason">举报原因</label><select name="report-reason" id="report-reason" class="form-control"><option value="恶意攻击">恶意攻击</option><option value="色情低俗">色情低俗</option><option value="暴力血腥">暴力血腥</option><option value="涉政违规">涉政违规</option><option value="欺诈广告">欺诈广告</option><option value="抄袭侵权">抄袭侵权</option><option value="">其他原因</option></select></div>'}html+='<div class="form-group displayNone" id="report-content-holder"><label for="report-content">其他原因</label><textarea id="report-content" class="form-control" max-lenght="255" placeholder="请描述你的举报原因"></textarea></div>',checkLogin()||(html+='<div class="form-group"><label for="report-contact">联系方式</label><input type="text" id="report-contact" class="form-control" placeholder="常用联系方式(邮箱、QQ、微信等)，便于告知处理进度(可选)"/></div>'),dialog({msg:html,title:"举报",action:"doReport"}),$("#report-reason").on("change",function(){var a=$(this).val();a?$("#report-content-holder").hide():$("#report-content-holder").show()}),$("button[action=doReport]").unbind("click").click(function(){var logid=$("#report-id").val(),reason=$("#report-reason").val(),content=$("#report-content").val(),contact=$("#report-contact").val();return logid?reason||content?(reason||(reason=content),contact||(contact=""),void $.post(zmf.ajaxUrl,{action:"report",logid:logid,type:type,reason:reason,contact:contact,url:window.location.href,YII_CSRF_TOKEN:zmf.csrfToken},function(result){return result=eval("("+result+")"),"1"==result.status?(simpleDialog({content:result.msg}),closeDialog(),!1):(simpleDialog({content:result.msg}),!1)})):(simpleDialog({content:"请填写举报原因"}),!1):(alert("缺少参数"),!1)})}),$(".openGallery").unbind("click").click(function(){var dom=$(this),holder=dom.attr("data-holder"),field=dom.attr("data-field");dialog({msg:'<div id="gallery-select-modal" class="gallery-body"></div>',title:"选择图片"}),$.post(zmf.userGalleryUrl,{from:"selectImg",YII_CSRF_TOKEN:zmf.csrfToken},function(result){result=eval("("+result+")"),"1"==result.status?($("#gallery-select-modal").html(result.msg.html),$(".select-gallery-img").unbind("click").click(function(){var a=$(this);selectThisImg(a,holder,field)}),rebind()):alert(result.msg)})}),$(".sendSms-btn").unbind("click").click(function(){var dom=$(this),_target=dom.attr("data-target");if(!_target)return dialog({msg:"请输入手机号"}),!1;var phone=$("#"+_target).val();if(!phone)return dialog({msg:"请输入手机号"}),!1;var type=dom.attr("data-type");return type?void $.post(zmf.ajaxUrl,{action:"sendSms",type:type,phone:phone,YII_CSRF_TOKEN:zmf.csrfToken},function(result){if(result=eval("("+result+")"),1===result.status){var totalTime=60,times=0;dom.text("重新发送 "+totalTime+"s").attr("disabled","disabled");var interval=setInterval(function(){times+=1;var a=totalTime-times;dom.text("重新发送 "+a+"s"),0>=a&&(clearInterval(interval),dom.removeAttr("disabled").text("重新发送"))},1e3)}else dialog({msg:result.msg})}):(dialog({msg:"缺少类型参数"}),!1)}),$(".nextStep-btn").unbind("click").click(function(){var dom=$(this),hasError=!1;$("#send-sms-form .bitian").each(function(){var a=$(this);return a.val()?void 0:(hasError=!0,dialog({msg:a.attr("placeholder")+"不能为空"}),!1)});var type=dom.attr("data-type");if(!type)return dialog({msg:"缺少类型参数"}),!1;var _target=dom.attr("data-target");if(!_target)return dialog({msg:"请输入手机号"}),!1;var phone=$("#"+_target).val();if(!phone)return dialog({msg:"请输入手机号"}),!1;var vcode=$("#verifycode").val();if(hasError)return!1;var passData={action:"checkSms",type:type,phone:phone,code:vcode,YII_CSRF_TOKEN:zmf.csrfToken},passwd="";if("forget"===type||"authorPass"===type){if(11!==phone.length)return dialog({msg:"请输入有效的11位手机号码"}),!1;if(passwd=$("#password").val(),!passwd||passwd.length<6)return dialog({msg:"请输入长度不小于6位的有效密码"}),!1;passData.password=passwd}$.post(zmf.ajaxUrl,passData,function(result){result=eval("("+result+")"),1===result.status?"exphone"===type?(dialog({msg:"手机号修改成功"}),window.location.href=result.msg):"checkPhone"===type?(dialog({msg:"手机号已验证"}),window.location.href=result.msg):"forget"===type?(dialog({msg:result.msg}),setTimeout(function(){location.href=zmf.loginUrl},1e3)):"authorPass"===type?(dialog({msg:"密码修改成功，正在跳转至登录页面"}),setTimeout(function(){location.href=result.msg},1e3)):($("#hashCode").val(result.msg),$("#send-sms-form").submit()):dialog({msg:result.msg})})});var clipboard=new Clipboard(".btn-copy");clipboard.on("success",function(a){simpleDialog({content:"复制成功"})}),clipboard.on("error",function(a){dialog({msg:"复制失败，请手动复制浏览器链接"})})}function searchType(a,b){return a&&b?($("#searchTypeBtn").html(b+' <span class="caret"></span>'),void $("#search-type").val(a)):!1}function selectThisImg(a,b,c){var d=a.attr("data-original");return d?(b&&$("#"+b).attr("src",d),void(c&&$("#"+c).val(d))):(alert("获取图片地址失败"),!1)}function favorite(a){var b=a.attr("action-data"),c=a.attr("action-type"),d=a.html(),e=parseInt(a.text());if(!b||!c)return!1;if(!checkLogin())return dialog({msg:"请先登录哦~",modalSize:"modal-sm"}),!1;if(!checkAjax())return!1;var f=a.children("i");"tip"===c?f.hasClass("fa-thumbs-up")?a.html('<i class="fa fa-thumbs-o-up"></i> '+--e):a.html('<i class="fa fa-thumbs-up"></i> '+ ++e):"author"===c?a.hasClass("btn-default")?a.removeClass("btn-default").addClass("btn-danger").html('<i class="fa fa-plus"></i> 关注'):a.removeClass("btn-danger").addClass("btn-default").html('<i class="fa fa-check"></i> 已关注'):"book"===c?a.hasClass("btn-default")?a.removeClass("btn-default").addClass("btn-danger").html('<i class="fa fa-heart-o"></i> 收藏'):a.removeClass("btn-danger").addClass("btn-default").html('<i class="fa fa-heart"></i> 已收藏'):"post"===c?a.hasClass("btn-default")?a.removeClass("btn-default").addClass("btn-danger").html('<i class="fa fa-thumbs-o-up"></i> 赞'):a.removeClass("btn-danger").addClass("btn-default").html('<i class="fa fa-thumbs-up"></i> 已赞'):"user"===c&&(a.hasClass("btn-default")?a.removeClass("btn-default").addClass("btn-danger").html('<i class="fa fa-star-o"></i> 赞'):a.removeClass("btn-danger").addClass("btn-default").html('<i class="fa fa-star"></i> 已赞')),$.post(zmf.favoriteUrl,{type:c,data:b,YII_CSRF_TOKEN:zmf.csrfToken},function(b){return ajaxReturn=!0,b=$.parseJSON(b),1===b.status||(2===b.status?(a.html(d),dialog({msg:b.msg})):3===b.status||(4===b.status?(a.html(d),dialog({msg:b.msg})):(a.html(d),dialog({msg:b.msg})))),!1})}function addComment(dom){var k=dom.attr("action-data"),t=dom.attr("action-type"),to=parseInt($("#replyoneHolder-"+k).attr("tocommentid")),c=$("#content-"+t+"-"+k).val();if(!k||!t||!c)return dialog({msg:"请填写内容"}),!1;if(to||(to=0),!checkAjax())return!1;var targetBox="comments-"+t+"-"+k;$.post(zmf.addCommentUrl,{k:k,t:t,c:c,to:to,YII_CSRF_TOKEN:zmf.csrfToken},function(result){if(ajaxReturn=!0,result=eval("("+result+")"),"1"==result.status){$("#content-"+t+"-"+k).val("");var loadingDom=$("#"+targetBox+" .loading-holder"),loadingHtml=loadingDom.html();loadingHtml=loadingHtml?'<p class="loading-holder">'+loadingHtml+"</p>":"",loadingDom.remove(),$("#comments-"+t+"-"+k).append(result.msg+loadingHtml),cancelReplyOne(k),rebind()}else dialog({msg:result.msg})})}function replyOne(a,b,c){var d="<span class='label label-success'><span class='fa fa-share'></span>回复“"+c+"”<a href='javascript:' onclick='cancelReplyOne("+b+")' title='取消设置'> <span class='fa fa-remove'></span></a></span>",e=$("#replyoneHolder-"+b).offset().top;$("html,body").animate({scrollTop:e},1e3),$("#replyoneHolder-"+b).attr("tocommentid",a).html(d)}function cancelReplyOne(a){$("#replyoneHolder-"+a).attr("tocommentid","").html("")}function setStatus(a,b,c){return checkLogin()?checkAjax()?void $.post(zmf.setStatusUrl,{a:a,b:b,c:c,YII_CSRF_TOKEN:zmf.csrfToken},function(result){ajaxReturn=!0,result=eval("("+result+")"),1==result.status?dialog({msg:result.msg,time:3}):2==result.status?window.location.href=zmf.userLoginUrl+"&redirect="+window.location.href:dialog({msg:result.msg})}):!1:(dialog({msg:"请先登录"}),!1)}function getNotice(){doGetNotice(),window.setInterval("doGetNotice()",1e4)}function doGetNotice(){return checkLogin()?void $.post(zmf.ajaxUrl,{action:"getNotice",YII_CSRF_TOKEN:zmf.csrfToken},function(a){if(a=$.parseJSON(a),1===a.status){var b=parseInt(a.msg);b>0?($("#top-nav-count").html(b).css("display","inline-block"),null===flashTitle.timer&&flashTitle.show()):($("#top-nav-count").hide(),flashTitle.clear())}}):!1}function feedback(){var a=$("#feedback-content").val();if(!a)return alert("内容不能为空哦~"),!1;if(!checkAjax())return!1;var b=window.location.href,c=$("#feedback-contact").val();$.post(zmf.feedbackUrl,{content:a,email:c,url:b,YII_CSRF_TOKEN:zmf.csrfToken},function(a){return ajaxReturn=!0,a=$.parseJSON(a),dialog({msg:a.msg}),!1})}function share(a){var b=a.attr("action-qrcode"),c=a.attr("action-url"),d=a.attr("action-img"),e=a.attr("action-title"),f='<div class="float-share-holder"><div class="float-share-content"><span class="float-close"><i class="fa fa-close"></i></span><div class="row"><div class="col-xs-6 text-center"><img src="'+b+'" class="img-responsive"/><p class="help-block">扫码分享到微信</p></div><div class="col-xs-6 float-btns"><a href="javascript:;" class="btn btn-default btn-block"><i class="fa fa-weibo"></i></a><a href="javascript:;" class="btn btn-default btn-block"><i class="fa fa-qq"></i></a><a href="javascript:;" class="btn btn-default btn-block">复制链接</a></div></div></div><div class="float-triangle"></div></div>',f='<div class="share-body"><p><img src="'+b+'" class="img-responsive"/></p><p class="help-block text-center">扫码分享到微信</p><div class="more-awesome"><span>或</span></div><p><a href="javascript:;" class="btn btn-default btn-block" action="shareToWeibo"><i class="fa fa-weibo"></i> 分享到微博</a><a href="javascript:;" class="btn btn-default btn-block" action="shareToQzone"><i class="fa fa-qq"></i> 分享到空间</a><a href="javascript:;" class="btn btn-default btn-block btn-copy" data-clipboard-text="'+c+'"><i class="fa fa-copy"></i> 复制此链接</a></p></div>';dialog({msg:f,title:"分享",modalSize:"modal-sm"}),$("a[action=shareToWeibo]").unbind("click").click(function(){window.open("http://service.weibo.com/share/share.php?title="+e+"&url="+c+"&appkey="+zmf.weiboAppkey+"&pic="+d+"&changweibo=yes&ralateUid="+zmf.weiboRalateUid,"_newtab")}),$("a[action=shareToQzone]").unbind("click").click(function(){window.open("http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title="+e+"&url="+c+"&pics="+d,"_newtab")})}function toggleChapters(){var a=$("#chapters-box");"none"!==a.css("display")?a.animate({right:-450},500).toggle():a.toggle().animate({right:0},500)}function playVideo(a,b,c,d){if(!a||!b||!c)return!1;var e=$("#"+c).width(),f=9*e/16,g="";"youku"===a?g='<iframe src="http://player.youku.com/embed/'+b+'" height="'+f+'" width="'+e+'" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"  height="'+f+'" width="'+e+'"></iframe>':"tudou"===a?g='<iframe src="http://www.tudou.com/programs/view/html5embed.action?type=2&'+b+'" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"  height="'+f+'" width="'+e+'"></iframe>':"qq"===a&&(g='<iframe src="http://v.qq.com/iframe/player.html?vid='+b+'&tiny=0&auto=1" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"  height="'+f+'" width="'+e+'"></iframe>'),$("#"+c).html(g),$(d).remove()}function myUploadify(){$("#uploadfile").uploadify({height:34,width:120,swf:zmf.baseUrl+"/common/uploadify/uploadify.swf",queueID:"fileQueue",auto:!0,multi:!0,fileObjName:"filedata",uploadLimit:zmf.perAddImgNum,fileSizeLimit:zmf.allowImgPerSize,fileTypeExts:zmf.allowImgTypes,fileTypeDesc:"Image Files",uploader:tipImgUploadUrl,buttonText:"请选择",buttonClass:"btn btn-success",debug:!1,formData:{PHPSESSID:zmf.currentSessionId,YII_CSRF_TOKEN:zmf.csrfToken},onUploadSuccess:function(file,data,response){if(data=eval("("+data+")"),1==data.status){var img="<p><img src='"+data.imgsrc+"' data='"+data.attachid+"' class='img-responsive'/></p>";myeditor.execCommand("inserthtml",img)}else dialog({msg:data.msg});tipsImgOrder++}})}function uploadByLimit(a){if("object"!=typeof a)return!1;var b=!0;b="undefined"==typeof a.multi?!0:a.multi,$("#"+a.placeHolder).uploadify({height:a.height?a.height:100,width:a.width?a.width:300,swf:zmf.baseUrl+"/common/uploadify/uploadify.swf",queueID:a.queueID?a.queueID:"singleFileQueue",auto:!0,multi:b,queueSizeLimit:zmf.perAddImgNum,fileObjName:a.filedata?a.filedata:"filedata",fileTypeExts:zmf.allowImgTypes,fileSizeLimit:zmf.allowImgPerSize,fileTypeDesc:"Image Files",uploader:a.uploadUrl,buttonText:a.buttonText?a.buttonText:null===a.buttonText?"":"添加图片",buttonClass:a.buttonClass?a.buttonClass:"btn btn-default",debug:!1,formData:{PHPSESSID:zmf.currentSessionId,YII_CSRF_TOKEN:zmf.csrfToken},onSelect:function(a){},onUploadSuccess:function(b,c,d){c=$.parseJSON(c),1==c.status?(a.inputId&&$("#"+a.inputId).val(c.imgsrc),a.targetHolder&&$("#"+a.targetHolder).attr("src",c.thumbnail)):dialog({msg:c.msg})}})}function singleUploadify(a){if("object"!=typeof a)return!1;var b=!0;b="undefined"==typeof a.multi?!0:a.multi,$("#"+a.placeHolder).uploadify({height:a.height?a.height:100,width:a.width?a.width:300,swf:zmf.baseUrl+"/common/uploadify/uploadify.swf",queueID:"singleFileQueue",auto:!0,multi:b,queueSizeLimit:zmf.perAddImgNum,fileObjName:a.filedata?a.filedata:"filedata",fileTypeExts:zmf.allowImgTypes,fileSizeLimit:zmf.allowImgPerSize,fileTypeDesc:"Image Files",uploader:a.uploadUrl,buttonText:a.buttonText?a.buttonText:null===a.buttonText?"":"添加图片",buttonClass:a.buttonClass?a.buttonClass:"btn btn-default",debug:!1,formData:{PHPSESSID:zmf.currentSessionId,YII_CSRF_TOKEN:zmf.csrfToken},onUploadStart:function(b){if("posts"===a.type){var c={},d=new Date,e=b.type,f=a.type+"/"+d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate()+"/"+uuid()+e;c.key=f,c.token=a.token,$("#"+a.placeHolder).uploadify("settings","formData",c)}},onUploadSuccess:function(b,c,d){if(c=$.parseJSON(c),"posts"===a.type){if(c.error)return alert(c.error),!1;var e={YII_CSRF_TOKEN:zmf.csrfToken,filePath:c.key,fileSize:b.size,type:a.type,action:"saveUploadImg"};$.post(zmf.ajaxUrl,e,function(b){return b=$.parseJSON(b),1!==b.status?(alert(b.msg),!1):($("#fileSuccess").append(b.html),a.inputId&&$("#"+a.inputId).val(b.attachid),rebind(),void 0)})}else{if(1!==c.status)return alert(c.msg),!1;$("#fileSuccess").html(c.html),a.inputId&&$("#"+a.inputId).val(c.attachid),rebind()}}})}function showChapters(){var a=$(window).height(),b=$(window).width(),c=$("#chapters-box");if(!c.width())return!1;var d=$("#post-fixed-actions"),e=$(".zazhi-page").width(),f=d.width();0>=b-e-f?d.css({"margin-left":480-f-5}).fadeIn(300):d.fadeIn(300);var g=b-(b/2+e/2+f)-15;400>g&&(g=400),c.css({width:g,height:a}),$("#chapters-holder").css({height:a})}function dialog(a){if("object"!=typeof a)return!1;var b=a.msg,c=a.id,d=a.title,e=a.action,f=a.actionName,g=a.time,h=a.modalSize;$("#"+beforeModal).modal("hide"),("undefined"==typeof d||""===d)&&(d="提示"),("undefined"==typeof c||""===c)&&(c="myDialog"),"undefined"==typeof e&&(e=""),"undefined"==typeof h&&(h=""),$("#"+c).remove();var i='<div class="modal fade mymodal" id="'+c+'" tabindex="-1" role="dialog" aria-labelledby="'+c+'Label" aria-hidden="true"><div class="modal-dialog '+h+'"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="'+c+'Label">'+d+'</h4></div><div class="modal-body">'+b+'</div><div class="modal-footer">';if(i+='<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>',""!==e&&"undefined"!=typeof e){var j;j=""!==f&&"undefined"!=typeof f?f:"确定",i+='<button type="button" class="btn btn-primary" action="'+e+'" data-loading-text="Loading...">'+j+"</button>"}i+="</div></div></div></div>",$("body").append(i),$("#"+c).modal({backdrop:!1,keyboard:!1}),beforeModal=c,g>0&&"undefined"!=typeof g&&setTimeout("closeDialog('"+c+"')",1e3*g)}function closeDialog(a){a||(a="myDialog"),$("#"+a).modal("hide"),$("#"+a).remove(),$("body").eq(0).removeClass("modal-open")}function simpleDialog(a){if("object"!=typeof a)return!1;var b=a.content,c='<div class="simpleDialog">'+b+"</div>";$("body").append(c);var d=$(".simpleDialog"),e=d.width(),f=d.height();d.css({"margin-left":-e/2,"margin-top":-f/2}),d.fadeIn(300),setTimeout("closeSimpleDialog()",2700)}function closeSimpleDialog(){$(".simpleDialog").fadeOut(100).remove()}function simpleLoading(a){if("object"!=typeof a)return!1;var b=a.title,c='<div class="simple-loading-box"><div class="loading-holder"><i class="fa fa-spinner"></i></div><div class="loading-title"><p>'+b+"</p></div></div>";$("body").append(c);var d=$(".simple-loading-box"),e=d.width(),f=d.height();d.css({"margin-left":-e/2,"margin-top":-f/2}),d.fadeIn(300),setTimeout("closeSimpleLoading()",2700)}function closeSimpleLoading(){$(".simple-loading-box").fadeOut(100).remove()}function checkAjax(){return ajaxReturn?(ajaxReturn=!1,!0):(dialog({msg:"请求正在发送中，请稍后"}),!1)}function checkLogin(){return"undefined"==typeof zmf.hasLogin?!1:"true"===zmf.hasLogin?!0:!1}function backToTop(){var a=$(window).width(),b=$(".zazhi-page").width();$("#back-to-top").width();if(b>a)var c=b+8;else var c=parseInt((a+b+16)/2);$("#back-to-top").css("left",c+"px");var d=$(window).height(),e=$(document.body).height();d>e&&$("body").css("height",d),$("#footer-bg").fadeIn(3e3)}function textareaAutoResize(){$("textarea").autoResize({onResize:function(){$(this).css({opacity:.8})},animateCallback:function(){$(this).css({opacity:1})},animateDuration:100,extraSpace:20})}function uuid(a,b){var c,d="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz".split(""),e=[];if(b=b||d.length,a)for(c=0;a>c;c++)e[c]=d[0|Math.random()*b];else{var f;for(e[8]=e[13]=e[18]=e[23]="-",e[14]="4",c=0;36>c;c++)e[c]||(f=0|16*Math.random(),e[c]=d[19===c?3&f|8:f])}return e.join("")}var tipsImgOrder=0,beforeModal,ajaxReturn=!0,url=window.location.href;$(window).scroll(function(){$(window).scrollTop()>100?$(".back-to-top").fadeIn():$(".back-to-top").fadeOut()}),$(".back-to-top").click(function(){return $("body,html").animate({scrollTop:0},200),!1}),$(window).resize(function(){backToTop()}),backToTop(),$(document).keydown(function(a){37==a.keyCode||39==a.keyCode});var flashTitle={time:0,title:document.title,timer:null,show:function(){var a=flashTitle.title.replace("【　　　】","").replace("【新消息】","");return flashTitle.timer=setTimeout(function(){flashTitle.time++,flashTitle.show(),flashTitle.time%2==0?document.title="【新消息】"+a:document.title="【　　　】"+a},600),[flashTitle.timer,flashTitle.title]},clear:function(){clearTimeout(flashTitle.timer),document.title=flashTitle.title}};!function(a){a.fn.autoResize=function(b){var c=a.extend({onResize:function(){},animate:!0,animateDuration:150,animateCallback:function(){},extraSpace:20,limit:1e3},b);return this.filter("textarea").each(function(){var b=a(this).css({resize:"none","overflow-y":"hidden"}),d=b.height(),e=function(){var c=["height","width","lineHeight","textDecoration","letterSpacing"],d={};return a.each(c,function(a,c){d[c]=b.css(c)}),b.clone().removeAttr("id").removeAttr("name").css({position:"absolute",top:0,left:-9999}).css(d).attr("tabIndex","-1").insertBefore(b)}(),f=null,g=function(){e.height(0).val(a(this).val()).scrollTop(1e4);var g=Math.max(e.scrollTop(),d)+c.extraSpace,h=a(this).add(e);if(f!==g){if(f=g,g>=c.limit)return void a(this).css("overflow-y","");c.onResize.call(this),c.animate&&"block"===b.css("display")?h.stop().animate({height:g},c.animateDuration,c.animateCallback):h.height(g)}};b.unbind(".dynSiz").bind("keyup.dynSiz",g).bind("keydown.dynSiz",g).bind("change.dynSiz",g)}),this}}(jQuery),!function(a,b,c,d){var e=a(b);a.fn.lazyload=function(f){function g(){var b=0;i.each(function(){var c=a(this);if(!j.skip_invisible||c.is(":visible"))if(a.abovethetop(this,j)||a.leftofbegin(this,j));else if(a.belowthefold(this,j)||a.rightoffold(this,j)){if(++b>j.failure_limit)return!1}else c.trigger("appear"),b=0})}var h,i=this,j={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:b,data_attribute:"original",skip_invisible:!0,appear:null,load:null};return f&&(d!==f.failurelimit&&(f.failure_limit=f.failurelimit,delete f.failurelimit),d!==f.effectspeed&&(f.effect_speed=f.effectspeed,delete f.effectspeed),a.extend(j,f)),h=j.container===d||j.container===b?e:a(j.container),0===j.event.indexOf("scroll")&&h.bind(j.event,function(){return g()}),this.each(function(){var b=this,c=a(b);b.loaded=!1,c.one("appear",function(){if(!this.loaded){if(j.appear){var d=i.length;j.appear.call(b,d,j)}a("<img />").bind("load",function(){c.hide().attr("src",c.data(j.data_attribute))[j.effect](j.effect_speed),b.loaded=!0;var d=a.grep(i,function(a){return!a.loaded});if(i=a(d),j.load){var e=i.length;j.load.call(b,e,j)}}).attr("src",c.data(j.data_attribute))}}),0!==j.event.indexOf("scroll")&&c.bind(j.event,function(){b.loaded||c.trigger("appear")})}),e.bind("resize",function(){g()}),/iphone|ipod|ipad.*os 5/gi.test(navigator.appVersion)&&e.bind("pageshow",function(b){b.originalEvent&&b.originalEvent.persisted&&i.each(function(){a(this).trigger("appear")})}),a(c).ready(function(){g()}),this},a.belowthefold=function(c,f){var g;return g=f.container===d||f.container===b?e.height()+e.scrollTop():a(f.container).offset().top+a(f.container).height(),g<=a(c).offset().top-f.threshold},a.rightoffold=function(c,f){var g;return g=f.container===d||f.container===b?e.width()+e.scrollLeft():a(f.container).offset().left+a(f.container).width(),g<=a(c).offset().left-f.threshold},a.abovethetop=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollTop():a(f.container).offset().top,g>=a(c).offset().top+f.threshold+a(c).height()},a.leftofbegin=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollLeft():a(f.container).offset().left,g>=a(c).offset().left+f.threshold+a(c).width()},a.inviewport=function(b,c){return!(a.rightoffold(b,c)||a.leftofbegin(b,c)||a.belowthefold(b,c)||a.abovethetop(b,c))},a.extend(a.expr[":"],{"below-the-fold":function(b){return a.belowthefold(b,{threshold:0})},"above-the-top":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-screen":function(b){return a.rightoffold(b,{threshold:0})},"left-of-screen":function(b){return!a.rightoffold(b,{threshold:0})},"in-viewport":function(b){return a.inviewport(b,{threshold:0})},"above-the-fold":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-fold":function(b){return a.rightoffold(b,{threshold:0})},"left-of-fold":function(b){return!a.rightoffold(b,{threshold:0})}})}(jQuery,window,document),!function(a){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=a();else if("function"==typeof define&&define.amd)define([],a);else{var b;b="undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self?self:this,b.Clipboard=a()}}(function(){return function a(b,c,d){function e(g,h){if(!c[g]){if(!b[g]){var i="function"==typeof require&&require;if(!h&&i)return i(g,!0);if(f)return f(g,!0);var j=new Error("Cannot find module '"+g+"'");throw j.code="MODULE_NOT_FOUND",j}var k=c[g]={exports:{}};b[g][0].call(k.exports,function(a){var c=b[g][1][a];return e(c?c:a)},k,k.exports,a,b,c,d)}return c[g].exports}for(var f="function"==typeof require&&require,g=0;g<d.length;g++)e(d[g]);return e}({1:[function(a,b,c){var d=a("matches-selector");b.exports=function(a,b,c){for(var e=c?a:a.parentNode;e&&e!==document;){if(d(e,b))return e;e=e.parentNode}}},{"matches-selector":2}],2:[function(a,b,c){function d(a,b){if(f)return f.call(a,b);for(var c=a.parentNode.querySelectorAll(b),d=0;d<c.length;++d)if(c[d]==a)return!0;return!1}var e=Element.prototype,f=e.matchesSelector||e.webkitMatchesSelector||e.mozMatchesSelector||e.msMatchesSelector||e.oMatchesSelector;b.exports=d},{}],3:[function(a,b,c){function d(a,b,c,d){var f=e.apply(this,arguments);return a.addEventListener(c,f),{destroy:function(){a.removeEventListener(c,f)}}}function e(a,b,c,d){return function(c){c.delegateTarget=f(c.target,b,!0),c.delegateTarget&&d.call(a,c)}}var f=a("closest");b.exports=d},{closest:1}],4:[function(a,b,c){c.node=function(a){return void 0!==a&&a instanceof HTMLElement&&1===a.nodeType},c.nodeList=function(a){var b=Object.prototype.toString.call(a);return void 0!==a&&("[object NodeList]"===b||"[object HTMLCollection]"===b)&&"length"in a&&(0===a.length||c.node(a[0]))},c.string=function(a){return"string"==typeof a||a instanceof String},c["function"]=function(a){var b=Object.prototype.toString.call(a);return"[object Function]"===b}},{}],5:[function(a,b,c){function d(a,b,c){if(!a&&!b&&!c)throw new Error("Missing required arguments");if(!h.string(b))throw new TypeError("Second argument must be a String");if(!h["function"](c))throw new TypeError("Third argument must be a Function");if(h.node(a))return e(a,b,c);if(h.nodeList(a))return f(a,b,c);
if(h.string(a))return g(a,b,c);throw new TypeError("First argument must be a String, HTMLElement, HTMLCollection, or NodeList")}function e(a,b,c){return a.addEventListener(b,c),{destroy:function(){a.removeEventListener(b,c)}}}function f(a,b,c){return Array.prototype.forEach.call(a,function(a){a.addEventListener(b,c)}),{destroy:function(){Array.prototype.forEach.call(a,function(a){a.removeEventListener(b,c)})}}}function g(a,b,c){return i(document.body,a,b,c)}var h=a("./is"),i=a("delegate");b.exports=d},{"./is":4,delegate:3}],6:[function(a,b,c){function d(a){var b;if("INPUT"===a.nodeName||"TEXTAREA"===a.nodeName)a.focus(),a.setSelectionRange(0,a.value.length),b=a.value;else{a.hasAttribute("contenteditable")&&a.focus();var c=window.getSelection(),d=document.createRange();d.selectNodeContents(a),c.removeAllRanges(),c.addRange(d),b=c.toString()}return b}b.exports=d},{}],7:[function(a,b,c){function d(){}d.prototype={on:function(a,b,c){var d=this.e||(this.e={});return(d[a]||(d[a]=[])).push({fn:b,ctx:c}),this},once:function(a,b,c){function d(){e.off(a,d),b.apply(c,arguments)}var e=this;return d._=b,this.on(a,d,c)},emit:function(a){var b=[].slice.call(arguments,1),c=((this.e||(this.e={}))[a]||[]).slice(),d=0,e=c.length;for(d;e>d;d++)c[d].fn.apply(c[d].ctx,b);return this},off:function(a,b){var c=this.e||(this.e={}),d=c[a],e=[];if(d&&b)for(var f=0,g=d.length;g>f;f++)d[f].fn!==b&&d[f].fn._!==b&&e.push(d[f]);return e.length?c[a]=e:delete c[a],this}},b.exports=d},{}],8:[function(a,b,c){"use strict";function d(a){return a&&a.__esModule?a:{"default":a}}function e(a,b){if(!(a instanceof b))throw new TypeError("Cannot call a class as a function")}c.__esModule=!0;var f=function(){function a(a,b){for(var c=0;c<b.length;c++){var d=b[c];d.enumerable=d.enumerable||!1,d.configurable=!0,"value"in d&&(d.writable=!0),Object.defineProperty(a,d.key,d)}}return function(b,c,d){return c&&a(b.prototype,c),d&&a(b,d),b}}(),g=a("select"),h=d(g),i=function(){function a(b){e(this,a),this.resolveOptions(b),this.initSelection()}return a.prototype.resolveOptions=function(){var a=arguments.length<=0||void 0===arguments[0]?{}:arguments[0];this.action=a.action,this.emitter=a.emitter,this.target=a.target,this.text=a.text,this.trigger=a.trigger,this.selectedText=""},a.prototype.initSelection=function(){if(this.text&&this.target)throw new Error('Multiple attributes declared, use either "target" or "text"');if(this.text)this.selectFake();else{if(!this.target)throw new Error('Missing required attributes, use either "target" or "text"');this.selectTarget()}},a.prototype.selectFake=function(){var a=this;this.removeFake(),this.fakeHandler=document.body.addEventListener("click",function(){return a.removeFake()}),this.fakeElem=document.createElement("textarea"),this.fakeElem.style.position="absolute",this.fakeElem.style.left="-9999px",this.fakeElem.style.top=(window.pageYOffset||document.documentElement.scrollTop)+"px",this.fakeElem.setAttribute("readonly",""),this.fakeElem.value=this.text,document.body.appendChild(this.fakeElem),this.selectedText=h["default"](this.fakeElem),this.copyText()},a.prototype.removeFake=function(){this.fakeHandler&&(document.body.removeEventListener("click"),this.fakeHandler=null),this.fakeElem&&(document.body.removeChild(this.fakeElem),this.fakeElem=null)},a.prototype.selectTarget=function(){this.selectedText=h["default"](this.target),this.copyText()},a.prototype.copyText=function(){var a=void 0;try{a=document.execCommand(this.action)}catch(b){a=!1}this.handleResult(a)},a.prototype.handleResult=function(a){a?this.emitter.emit("success",{action:this.action,text:this.selectedText,trigger:this.trigger,clearSelection:this.clearSelection.bind(this)}):this.emitter.emit("error",{action:this.action,trigger:this.trigger,clearSelection:this.clearSelection.bind(this)})},a.prototype.clearSelection=function(){this.target&&this.target.blur(),window.getSelection().removeAllRanges()},a.prototype.destroy=function(){this.removeFake()},f(a,[{key:"action",set:function(){var a=arguments.length<=0||void 0===arguments[0]?"copy":arguments[0];if(this._action=a,"copy"!==this._action&&"cut"!==this._action)throw new Error('Invalid "action" value, use either "copy" or "cut"')},get:function(){return this._action}},{key:"target",set:function(a){if(void 0!==a){if(!a||"object"!=typeof a||1!==a.nodeType)throw new Error('Invalid "target" value, use a valid Element');this._target=a}},get:function(){return this._target}}]),a}();c["default"]=i,b.exports=c["default"]},{select:6}],9:[function(a,b,c){"use strict";function d(a){return a&&a.__esModule?a:{"default":a}}function e(a,b){if(!(a instanceof b))throw new TypeError("Cannot call a class as a function")}function f(a,b){if("function"!=typeof b&&null!==b)throw new TypeError("Super expression must either be null or a function, not "+typeof b);a.prototype=Object.create(b&&b.prototype,{constructor:{value:a,enumerable:!1,writable:!0,configurable:!0}}),b&&(Object.setPrototypeOf?Object.setPrototypeOf(a,b):a.__proto__=b)}function g(a,b){var c="data-clipboard-"+a;return b.hasAttribute(c)?b.getAttribute(c):void 0}c.__esModule=!0;var h=a("./clipboard-action"),i=d(h),j=a("tiny-emitter"),k=d(j),l=a("good-listener"),m=d(l),n=function(a){function b(c,d){e(this,b),a.call(this),this.resolveOptions(d),this.listenClick(c)}return f(b,a),b.prototype.resolveOptions=function(){var a=arguments.length<=0||void 0===arguments[0]?{}:arguments[0];this.action="function"==typeof a.action?a.action:this.defaultAction,this.target="function"==typeof a.target?a.target:this.defaultTarget,this.text="function"==typeof a.text?a.text:this.defaultText},b.prototype.listenClick=function(a){var b=this;this.listener=m["default"](a,"click",function(a){return b.onClick(a)})},b.prototype.onClick=function(a){var b=a.delegateTarget||a.currentTarget;this.clipboardAction&&(this.clipboardAction=null),this.clipboardAction=new i["default"]({action:this.action(b),target:this.target(b),text:this.text(b),trigger:b,emitter:this})},b.prototype.defaultAction=function(a){return g("action",a)},b.prototype.defaultTarget=function(a){var b=g("target",a);return b?document.querySelector(b):void 0},b.prototype.defaultText=function(a){return g("text",a)},b.prototype.destroy=function(){this.listener.destroy(),this.clipboardAction&&(this.clipboardAction.destroy(),this.clipboardAction=null)},b}(k["default"]);c["default"]=n,b.exports=c["default"]},{"./clipboard-action":8,"good-listener":5,"tiny-emitter":7}]},{},[9])(9)}),rebind(),getNotice();