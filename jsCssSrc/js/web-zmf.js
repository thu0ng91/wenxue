var tipsImgOrder = 0;
var beforeModal;
var ajaxReturn = true;

var url = window.location.href;
$(window).scroll(function () {
    $(window).scrollTop() > 100 ? $(".back-to-top").fadeIn() : $(".back-to-top").fadeOut();
}), $(".back-to-top").click(function () {
    return $("body,html").animate({
        scrollTop: 0
    }, 200), !1;
}), $(window).resize(function () {
    backToTop();
}), backToTop();
$(document).keydown(function (b) {
    if (b.keyCode == 37) {
        //$("a[action=preChapter]").click();
    } else if (b.keyCode == 39) {        
        //$("a[action=nextChapter]").click();        
    }
});
function rebind() {
    $("img.lazy").lazyload({
        threshold:600
    });
    /**
    * 获取内容
    * @param {type} dom
    * @param {type} t 类型
    * @param {type} k keyid
    * @param {type} p 页码
    * @returns {Boolean}
    */
    $("a[action=getContents]").unbind('click').click(function () {
        var dom = $(this);
        var acdata = dom.attr("data-id");
        var t = dom.attr("data-type");
        var p = dom.attr("data-page");
        var targetBox = dom.attr('data-target');
        if (!targetBox) {
            return false;
        }
        if(dom.attr('data-loaded')==='1'){
            $('#' + targetBox+'-box').hide();
            dom.attr('data-loaded',0);
            return false;
        }
        if (!p) {
            p = 1;
        }
        var loading = '<div class="loading-holder"><a class="btn btn-default btn-sm disabled" href="javascript:;">拼命加载中...</a></div>';
        $('#' + targetBox).children('.loading-holder').each(function () {
            $(this).remove();
        });
        $('#' + targetBox).append(loading);
        $('#' + targetBox+'-box').show();
        $.post(zmf.ajaxUrl, {action:'getContents',type: t, page: p, data: acdata, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            ajaxReturn = true;
            dom.attr('data-loaded', 1);
            result = $.parseJSON(result);
            if (result.status === 1) {
                var data = result.msg;
                var pageHtml = '', dataHtml = '';
                if (data.html !== '') {
                    dataHtml += data.html;
                }
                if (data.loadMore === 1) {
                    var _p = parseInt(p) + 1;
                    pageHtml += '<div class="loading-holder"><a class="btn btn-default btn-sm"  href="javascript:;" action="getContents" data-type="' + t + '" data-id="' + acdata + '" data-page="' + _p + '" data-target="' + targetBox + '">加载更多</a></div>';
                } else {
                    if(data.html==='' && p===1){
                        pageHtml += '';
                    }else{
                        pageHtml += '<div class="loading-holder"><a class="btn btn-default btn-sm disabled" href="javascript:;">已全部加载</a></div>';
                    }
                }                
                $('#' + targetBox + ' .loading-holder').each(function () {
                    $(this).remove();
                });                
                if (p > 1) {
                    $('#' + targetBox).append(dataHtml);
                } else {
                    if(data.html === ''){
                        dataHtml='<div class="help-block text-center">暂无内容</div>';
                    }
                    $('#' + targetBox).html(dataHtml);
                }
                $('#' + targetBox).append(pageHtml);
                if(p===1){
                    $('#' + targetBox + '-form').html(data.formHtml);
                }
                rebind();
            } else {
                dialog({msg: result.msg});
            }
        });
    });
    $("a[action=delContent]").unbind('click').click(function () {
        var dom = $(this);
        var acdata = dom.attr("data-id");
        var t = dom.attr("data-type");
        var cf = dom.attr('data-confirm');
        var rurl = dom.attr('data-redirect');
        var targetBox = dom.attr('data-target');
        if (!acdata || !t) {
            return false;
        }
        var todo = true;
        if (parseInt(cf) === 1) {
            if (confirm('确定删除此内容？')) {
                todo = true;
            } else {
                todo = false;
            }
        }
        if (!todo) {
            return false;
        }
        if (!checkAjax()) {
            return false;
        }
        $.post(zmf.ajaxUrl, {action:'delContent',type: t, data: acdata, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            ajaxReturn = true;
            result = $.parseJSON(result);
            if (result.status === 1) {
                if (rurl) {
                    window.location.href = rurl;
                } else if (targetBox) {
                    $('#' + targetBox).fadeOut(500).remove();
                } else {
                    alert(result.msg);
                }
            } else {
                dialog({msg: result.msg});
            }
            return false;
        });
    });
    $("a[action=delBook]").unbind('click').click(function () {
        var bid = $(this).attr("data-id");
        var rurl = $(this).attr('data-redirect');
        if(!bid){
            dialog({msg: '请选择小说'});
            return false;
        }
        var html = '<div class="form-group"><label for="author-passwd">请输入密码</label><input type="password" id="author-passwd" class="form-control" placeholder="请输入登录作者中心的密码"/><p class="help-block">该操作不可逆，请谨慎考虑</p></div>';
        dialog({msg: html, title: '确定删除小说？', action: 'confirmDelBook'});
        $("button[action=confirmDelBook]").unbind('click').click(function () {
            var passwd = $('#author-passwd').val();
            if(!passwd){
                alert('请输入密码');
                return false;
            }            
            $.post(zmf.ajaxUrl, {action:'delBook',bid: bid,passwd:passwd,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {                
                result = $.parseJSON(result);
                if (result.status === 1) {
                    alert(result.msg);
                    window.location.href = rurl;
                    return false;              
                } else {
                    alert(result.msg);
                    return false;    
                }
                return false;
            });
        });
    });
    $("a[action=delChapter]").unbind('click').click(function () {
        var cid = $(this).attr("data-id");
        if(!cid){
            dialog({msg: '请选择小说章节'});
            return false;
        }
        var html = '<div class="form-group"><label for="author-passwd">请输入密码</label><input type="password" id="author-passwd" class="form-control" placeholder="请输入登录作者中心的密码"/><p class="help-block">该操作不可逆，请谨慎考虑</p></div>';
        dialog({msg: html, title: '确定删除本章节？', action: 'confirmDelChapter'});
        $("button[action=confirmDelChapter]").unbind('click').click(function () {
            var passwd = $('#author-passwd').val();
            if(!passwd){
                alert('请输入密码');
                return false;
            }            
            $.post(zmf.ajaxUrl, {action:'delChapter',cid: cid,passwd:passwd,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {                
                result = $.parseJSON(result);
                if (result.status === 1) {
                    alert(result.msg);
                    window.location.reload();
                    return false;              
                } else {
                    alert(result.msg);
                    return false;    
                }
                return false;
            });
        });
    });
    $("a[action=finishBook]").unbind('click').click(function () {
        var bid = $(this).attr("data-id");
        if(!bid){
            dialog({msg: '请选择小说'});
            return false;
        }
        var html = '<div class="form-group"><label for="author-passwd">请输入密码</label><input type="password" id="author-passwd" class="form-control" placeholder="请输入登录作者中心的密码"/><p class="help-block">完结小说后将不能再续写章节</p></div>';
        dialog({msg: html, title: '确定完结本小说？', action: 'confirmFinishBook'});
        $("button[action=confirmFinishBook]").unbind('click').click(function () {
            var passwd = $('#author-passwd').val();
            if(!passwd){
                alert('请输入密码');
                return false;
            }
            $.post(zmf.ajaxUrl, {action:'finishBook',bid: bid,passwd:passwd,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {                
                result = $.parseJSON(result);
                if (result.status === 1) {
                    alert(result.msg);
                    window.location.reload();
                    return false;              
                } else {
                    alert(result.msg);
                    return false;    
                }
                return false;
            });
        });
    });
    $("a[action=favorite]").unbind('click').click(function () {
        var dom = $(this);
        favorite(dom);
    });
    $(".tag-item").unbind('click').click(function () {
        var dom = $(this);
        if(dom.hasClass('active')){
            dom.removeClass('active');
            dom.children('input').removeAttr('checked');
        }else{
            dom.addClass('active');
            dom.children('input').attr('checked','checked');
        }
    });
    $(".comment-textarea").unbind('click').click(function () {
        $('.toggle-area').each(function () {
            $(this).fadeIn(500);
        });
    });
    $("a[action=add-comment]").unbind('click').click(function () {
        var dom = $(this);
        var k = dom.attr("action-data");
        var t = dom.attr("action-type");
        addComment(dom, t, k);
    });
    $("a[action=add-tips]").unbind('click').click(function () {
        var dom = $(this);
        var k = dom.attr("action-data");
        var t = dom.attr("action-type");
        var c = $('#content-' + t + '-' + k).val();
        var score=parseInt($('#add-tips-score input[name="score"]:checked ').val());        
        if (!k || !t || !c) {
            dialog({msg: '请填写内容'});
            return false;
        }
        if(c.length<20){
            dialog({msg: '点评内容不小于20字'});
            return false;
        }
        if (!score) {
            score = 0;
        }
        if((!score || (score<1 || score>5)) && to===0){
            dialog({msg: '请打一个分吧'});
            return false;
        }
        if (!checkAjax()) {
            return false;
        }
        $.post(zmf.ajaxUrl, {action:'addTip',k: k, t: t, c: c,score:score, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            ajaxReturn = true;
            result = eval('(' + result + ')');
            if (result['status'] == '1') {
                $('#content-' + t + '-' + k).val('');
                $("#comments-" + t + "-" + k).append(result['msg']);
                cancelReplyOne(k);
                rebind();
            } else {
                dialog({msg: result['msg']});
            }
        });
    });
    $("a[action=scroll]").unbind('click').click(function () {
        var dom = $(this);
        var to = dom.attr("action-target");
        if (!to) {
            return false;
        }
        $("body,html").animate({
            scrollTop: $('#' + to).offset().top
        }, 200);
    });
    $("a[action=share]").unbind('click').click(function () {
        var dom = $(this);
        share(dom);
    });
    $("a[action=setStatus]").unbind('click').click(function () {
        var dom = $(this);
        var id=parseInt(dom.attr('data-id'));
        if(!id){
            alert('缺少参数');
            return false;
        }
        var type=dom.attr('data-type');
        if(!type){
            alert('缺少参数');
            return false;
        }
        var action=dom.attr('data-action');
        if(!action){
            alert('缺少参数');
            return false;
        }
        if(confirm('确定该操作？')){
            $.post(zmf.ajaxUrl, {action:'setStatus',type:type,id:id,actype:action,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {                
                result = eval('(' + result + ')');
                if (result['status'] == '1') {
                    window.location.reload();                    
                } else {
                    dialog({msg:result['msg']});
                    return false;
                }
            });
        }
    });
    
    $("a[action=publishBook]").unbind('click').click(function () {
        var dom = $(this);
        var id=parseInt(dom.attr('data-id'));
        var type=parseInt(dom.attr('data-action'));
        if(!id){
            alert('缺少参数');
            return false;
        }
        if(!type){
            type='publish';
        }
        var msg='';
        if(type==='publish'){
            msg='确定立即发表作品么？';
        }else if(type==='finish'){
            msg='确定标记为已完结么，标记后将不能再续写新章节？';
        }
        if(confirm(msg)){
            $.post(zmf.ajaxUrl, {action:'publishBook',id:id,type:type,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {                
                result = eval('(' + result + ')');
                if (result['status'] == '1') {
                    window.location.reload();
                } else {
                    dialog({msg:result['msg']});
                    return false;
                }
            });
        }
    });
    $("a[action=publishChapter]").unbind('click').click(function () {
        var dom = $(this);
        var id=parseInt(dom.attr('data-id'));
        if(!id){
            alert('缺少参数');
            return false;
        }
        if(confirm('确定立即发表本章节么？')){
            $.post(zmf.ajaxUrl, {action:'publishChapter',id:id,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {                
                result = eval('(' + result + ')');
                if (result['status'] == '1') {
                    window.location.reload();
                } else {
                    dialog({msg:result['msg']});
                    return false;
                }
            });
        }
    });
    
    $('#add-post-btn').unbind('click').click(function () {
        $(window).unbind('beforeunload');
    });
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
    
    $("a[action=showChapters]").unbind('click').click(function () {
        var dom=$(this);
        if(dom.hasClass('active')){
            dom.removeClass('active');
            $('#fixed-chapters').hide();
        }else{
            dom.addClass('active');
            $('#fixed-chapters').show();
        }
    });
    $("a[action=nextChapter]").unbind('click').click(function () {
        var dom = $('#nextChapter');
        var title = dom.attr('title');
        title = title ? title : dom.attr('data-original-title');
        title = title ? title : '正在加载';
        var href = dom.attr('href');
        if (href !== 'javascript:;' && href !== '') {
            window.location.href = href;
        } else {
            simpleDialog({content: title});
        }
    });
    $("a[action=preChapter]").unbind('click').click(function () {
        var dom = $('#preChapter');
        var title = dom.attr('title');
        title = title ? title : dom.attr('data-original-title');
        title = title ? title : '正在加载';
        var href = dom.attr('href');
        if (href !== 'javascript:;' && href !== '') {
            window.location.href = href;
        } else {
            simpleDialog({content: title});
        }
    });
    $("a[action=dapipi]").unbind('click').click(function () {
        var dom = $(this);
        var k = dom.attr("action-data");     
        if (!k) {
            dialog({msg: '缺少参数'});
            return false;
        }
        if (!checkAjax()) {
            return false;
        }
        $.post(zmf.ajaxUrl, {action:'dapipi',k: k,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            ajaxReturn = true;
            result = eval('(' + result + ')');
            if (result['status'] == '1') {
                dialog({msg: result['msg']});
            } else {
                dialog({msg: result['msg']});
            }
        });
    });
    $("a[action=getProps]").unbind('click').click(function () {
        if (!checkLogin()) {
            //没有登录，判断是否包含fa-heart样式，包含则认为已收藏成功过
            dialog({msg: '请先登录哦~', modalSize: 'modal-sm'});
            return false;
        }
        var dom = $(this);
        var acdata = dom.attr("data-id");
        var t = dom.attr("data-type");        
        var targetBox = dom.attr('data-target');
        if (!targetBox) {
            return false;
        }
        var targetDom=$('#' + targetBox+'-box');
        if(dom.attr('data-loaded')==='1'){
            if(targetDom.css('display')=='none'){
                targetDom.show();
            }else{
                targetDom.hide();
            }            
            //dom.attr('data-loaded',0);
            return false;            
        }
        var loading = '<div class="loading-holder"><a class="btn btn-default btn-sm disabled" href="javascript:;">拼命加载中...</a></div>';
        $('#' + targetBox).children('.loading-holder').each(function () {
            $(this).remove();
        });
        $('#' + targetBox).append(loading);
        targetDom.show();
        $.post(zmf.ajaxUrl, {action:'getProps',type: t, data: acdata, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            ajaxReturn = true;
            dom.attr('data-loaded', 1);
            result = $.parseJSON(result);
            if (result.status === 1) {
                var data = result.msg;
                var dataHtml = '';
                if (data.html !== '') {
                    dataHtml += data.html;
                }               
                $('#' + targetBox + ' .loading-holder').each(function () {
                    $(this).remove();
                });  
                $('#' + targetBox).html(dataHtml);                
                rebind();
            } else {
                dialog({msg: result.msg});
            }
        });
    });
    $("a[action=useProp]").unbind('click').click(function () {
        var dom = $(this);
        var k = dom.attr("action-data");     
        if (!k) {
            dialog({msg: '缺少参数'});
            return false;
        }
        if (!checkAjax()) {
            return false;
        }
        $.post(zmf.ajaxUrl, {action:'useProp',k: k,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            ajaxReturn = true;
            result = eval('(' + result + ')');
            if (result['status'] == '1') {
                dialog({msg: result['msg']});
            } else {
                dialog({msg: result['msg']});
            }
        });
    });
    $('.tooltip').mouseover(function(){
        $(this).remove();
    });
    //输入框自动变大
    //textareaAutoResize();
    //意见反馈
    $("a[action=feedback]").unbind('click').click(function () {
        var html = '<div class="form-group"><label for="feedback-contact">联系方式</label><input type="text" id="feedback-contact" class="form-control" placeholder="常用联系方式(邮箱、QQ、微信等)，便于告知反馈处理进度(可选)"/></div><div class="form-group"><label for="feedback-content">反馈内容</label><textarea id="feedback-content" class="form-control" max-lenght="255" placeholder="您的意见或建议"></textarea></div>';
        dialog({msg: html, title: '意见反馈', action: 'feedback'});
        $("button[action=feedback]").unbind('click').click(function () {
            feedback();
        });
    });
    //举报
    $("a[action=report]").unbind('click').click(function () {
        var dom=$(this);
        var type=dom.attr('action-type');
        var id=dom.attr('action-id');
        var title=dom.attr('action-title');
        if(!type){
            alert('缺少参数');
            return false;
        }
        var html='<div class="form-group"><label>举报对象</label><p class="help-block ui-nowarp">'+title+'</p><input type="hidden" name="report-id" id="report-id" value="'+id+'"/></div>';
        if(type==='book' || type==='chapter'){
            html+= '<div class="form-group"><label for="feedback-reason">举报原因</label><select name="report-reason" id="report-reason" class="form-control"><option value="色情低俗">色情低俗</option><option value="暴力血腥">暴力血腥</option><option value="涉政违规">涉政违规</option><option value="欺诈广告">欺诈广告</option><option value="抄袭侵权">抄袭侵权</option><option value="">其他原因</option></select></div>';
        }else if(type==='tip' || type==='book' || type==='comment' || type==='post' || type==='user' || type==='author'){
            html+= '<div class="form-group"><label for="feedback-reason">举报原因</label><select name="report-reason" id="report-reason" class="form-control"><option value="恶意攻击">恶意攻击</option><option value="色情低俗">色情低俗</option><option value="暴力血腥">暴力血腥</option><option value="涉政违规">涉政违规</option><option value="欺诈广告">欺诈广告</option><option value="抄袭侵权">抄袭侵权</option><option value="">其他原因</option></select></div>';
        }else{
            alert('暂不支持该分类');
            return false;
        }
        html+='<div class="form-group displayNone" id="report-content-holder"><label for="report-content">其他原因</label><textarea id="report-content" class="form-control" max-lenght="255" placeholder="请描述你的举报原因"></textarea></div>';
        if(!checkLogin()){
            html+='<div class="form-group"><label for="report-contact">联系方式</label><input type="text" id="report-contact" class="form-control" placeholder="常用联系方式(邮箱、QQ、微信等)，便于告知处理进度(可选)"/></div>';
        }
        dialog({msg: html, title: '举报', action: 'doReport'});
        $('#report-reason').on('change',function(){
            var reason=$(this).val();
            if(!reason){
                $('#report-content-holder').show();
            }else{
                $('#report-content-holder').hide();
            }
        });
        $("button[action=doReport]").unbind('click').click(function () {
            var logid=$('#report-id').val();            
            var reason=$('#report-reason').val();
            var content=$('#report-content').val();
            var contact=$('#report-contact').val();
            if(!logid){
              alert('缺少参数');
              return false;
            }
            if(!reason && !content){
                simpleDialog({content:'请填写举报原因'});
                return false;
            }else{
                if(!reason){
                    reason=content;
                }
            }
            if(!contact){
                contact='';
            }
            $.post(zmf.ajaxUrl, {action:'report',logid:logid,type:type,reason:reason,contact:contact,url:window.location.href,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {                
                result = eval('(' + result + ')');
                if (result['status'] == '1') {
                    simpleDialog({content:result['msg']});
                    closeDialog();
                    return false;
                } else {
                    simpleDialog({content:result['msg']});
                    return false;
                }
            });
        });
    });
    $('.openGallery').unbind('click').click(function(){
        var dom=$(this);
        var holder=dom.attr('data-holder');
        var field=dom.attr('data-field');
        dialog({msg: '<div id="gallery-select-modal" class="gallery-body"></div>','title':'选择图片'});
        $.post(zmf.userGalleryUrl, {from:'selectImg',YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {                
            result = eval('(' + result + ')');
            if (result['status'] == '1') {
                $("#gallery-select-modal").html(result['msg']['html']);   
                $('.select-gallery-img').unbind('click').click(function(){
                    var _dom=$(this);
                    selectThisImg(_dom,holder,field);
                });
                rebind();
            } else {
                alert(result['msg']);
            }
        });
    });
    $('.sendSms-btn').unbind('click').click(function () {
        var dom = $(this);
        var _target = dom.attr('data-target');
        if (!_target) {
            dialog({msg: '请输入手机号'});
            return false;
        }
        var phone = $('#' + _target).val();
        if (!phone) {
            dialog({msg: '请输入手机号'});
            return false;
        }
        var type = dom.attr('data-type');
        if (!type) {
            dialog({msg: '缺少类型参数'});
            return false;
        }
        $.post(zmf.ajaxUrl, {action: 'sendSms', type: type, phone: phone, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            result = eval('(' + result + ')');
            if (result['status'] === 1) {
                var totalTime=60,times=0;
                dom.text('重新发送 '+totalTime+'s').attr('disabled','disabled');                
                var interval = setInterval(function(){
                    times+=1;
                    var time = totalTime-times;
                    dom.text('重新发送 '+time+'s');
                    if(time <= 0) {
                        clearInterval(interval);
                        dom.removeAttr('disabled').text('重新发送');
                    }
                }, 1000);
            } else {
                dialog({msg: result['msg']});
            }
        });
    });
    $('.nextStep-btn').unbind('click').click(function () {
        var dom = $(this);
        var hasError = false;
        $('#send-sms-form .bitian').each(function () {
            var _dom = $(this);
            if (!_dom.val()) {
                hasError = true;
                dialog({msg: _dom.attr('placeholder') + '不能为空'});
                return false;
            }
        });
        var type = dom.attr('data-type');
        if (!type) {
            dialog({msg: '缺少类型参数'});
            return false;
        }
        var _target = dom.attr('data-target');
        if (!_target) {
            dialog({msg: '请输入手机号'});
            return false;
        }
        var phone = $('#' + _target).val();
        if (!phone) {
            dialog({msg: '请输入手机号'});
            return false;
        }
        var vcode = $('#verifycode').val();
        if (hasError) {
            return false;
        }
        var passData={
            action:'checkSms',
            type: type, 
            phone: phone, 
            code: vcode, 
            YII_CSRF_TOKEN: zmf.csrfToken
        };
        var passwd='';
        if(type==='forget' || type==='authorPass'){
            if(phone.length!==11){
                dialog({msg: '请输入有效的11位手机号码'});
                return false;
            }            
            passwd=$('#password').val();
            if(!passwd || passwd.length<6){
                dialog({msg: '请输入长度不小于6位的有效密码'});
                return false;
            }
            passData.password=passwd;
        }
        $.post(zmf.ajaxUrl, passData, function (result) {
            result = eval('(' + result + ')');
            if (result.status === 1) {
                if(type==='exphone'){
                    dialog({msg: '手机号修改成功'});
                    window.location.href = result.msg;
                }else if(type==='checkPhone'){
                    dialog({msg: '手机号已验证'});
                    window.location.href = result.msg;   
                }else if(type==='forget'){
                    dialog({msg: result.msg});
                    setTimeout(function(){
                        location.href=zmf.loginUrl;
                    },1000);
                }else if(type==='authorPass'){
                    dialog({msg: '密码修改成功，正在跳转至登录页面'});
                    setTimeout(function(){
                        location.href=result.msg;
                    },1000);    
                }else{
                    $('#hashCode').val(result.msg);
                    $('#send-sms-form').submit();
                }
            } else {
                dialog({msg: result.msg});
            }
        });
    });
    //弹出浮框
    $("a[action=float]").unbind('click').click(function () {
        var dom=$(this);
        var data=dom.attr('action-data');
        if(!data){
            return false;
        }
        var width=dom.outerWidth();
        var height=dom.outerHeight();
        var domTop=dom.offset().top;
        var leftDom=dom.offset().left;
        var widthWindow=$(window).width();
        var widthBody=960;
        var w=(widthBody/2)-(leftDom-widthWindow/2)-width/2-7;
        $('#float-triangle').css({right:w+'px'});
        $('#float-holder').css({top:(domTop+height)+'px'}).show();
        $.post(zmf.ajaxUrl, {action: 'float', data: data,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            result = eval('(' + result + ')');
            if (result['status'] === 1) {
                $('#float-content').html(result['msg']);
                rebind();
            } else {
                dialog({msg: result['msg']});
            }
        });
    });
    //ajax请求
    $("a[action=ajax]").unbind('click').click(function () {
        var dom=$(this);
        var data=dom.attr('action-data');
        var input=dom.attr('action-input');
        if(!data){
            alert('缺少参数');
            return false;
        }
        var passData = {
            YII_CSRF_TOKEN: zmf.csrfToken,
            action: 'ajax',
            data: data
        };
        if(input){
            var inputDom=$('#'+input);
            var inputVal=inputDom.val();
            if(!inputVal || parseInt(inputVal)<1){
                simpleDialog({content: '请完善输入'});
                inputDom.focus();
                return false;
            }else{
                passData.extra=inputVal;
            }
        }
        $.post(zmf.ajaxUrl, passData, function (result) {
            result = eval('(' + result + ')');
            if (result['status'] === 1) {
                dialog({msg: result['msg']});
            } else {
                dialog({msg: result['msg']});
            }
        });
    });
    $("a[action=gotoBuy]").unbind('click').click(function () {
        var dom=$(this);
        var data=dom.attr('action-data');
        if(!data){
            alert('缺少参数');
            return false;
        }
        var passData = {
            YII_CSRF_TOKEN: zmf.csrfToken,
            action: 'gotoBuy',
            data: data
        };
        
        var inputDom=$('#amount-input');
        var inputVal=inputDom.val();
        if(!inputVal || parseInt(inputVal)<1){
            simpleDialog({content: '请选择兑换数量'});
            inputDom.focus();
            return false;
        }else{
            passData.num=inputVal;
        }
        
        $.post(zmf.ajaxUrl, passData, function (result) {
            result = eval('(' + result + ')');
            if (result['status'] === 1) {
                dialog({msg: result['msg'], title: '确认兑换？', action: 'confirmBuy'});
                $("button[action=confirmBuy]").unbind('click').click(function () {
                    var data=$('#confirm-buy-data').val();     
                    var passDom=$('#user-password');
                    var pass=passDom.val();
                    if(!data){
                        simpleDialog({content:'缺少参数'});
                        return false;
                    }
                    if(!pass){
                        simpleDialog({content:'请输入密码'});
                        passDom.focus();
                        return false;
                    }
                    passData.data=data;
                    passData.action='confirmBuy';
                    passData.password=pass;
                    
                    $.post(zmf.ajaxUrl, passData, function (result) {
                        result = eval('(' + result + ')');
                        if (result['status'] == '1') {
                            simpleDialog({content:result['msg']});
                            closeDialog();
                            return false;
                        } else {
                            simpleDialog({content:result['msg']});
                            return false;
                        }
                    });
                });
            }else if(result['status'] === 2){
                dialog({msg: result['msg'], title: '确认兑换？'});
            } else {
                dialog({msg: result['msg']});
            }
        });
    });
    
    //调用复制
    var clipboard = new Clipboard('.btn-copy');
    clipboard.on('success', function (e) {
        simpleDialog({content: '复制成功'});
    });
    clipboard.on('error', function (e) {
        dialog({msg: '复制失败，请手动复制浏览器链接'});
    });
}
function searchType(type,title){
    if(!type || !title){
        return false;
    }
    $('#searchTypeBtn').html(title+' <span class="caret"></span>');
    $('#search-type').val(type);
}

function selectThisImg(dom,holder,field){
    var _origin=dom.attr('data-original');
    if(!_origin){
        alert('获取图片地址失败');
        return false;
    }
    if(holder){
        $('#'+holder).attr('src',_origin);
    }
    if(field){
        $('#'+field).val(_origin);
    }
}
function favorite(dom) {
    var acdata = dom.attr("action-data");
    var t = dom.attr("action-type");
    var tmp = dom.html();
    var num = parseInt(dom.text());
    if (!acdata || !t) {
        return false;
    }
    if (!checkLogin()) {
        //没有登录，判断是否包含fa-heart样式，包含则认为已收藏成功过
        dialog({msg: '请先登录哦~', modalSize: 'modal-sm'});
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    var childDom=dom.children('i');
    if(t==='tip'){
        if(childDom.hasClass('fa-thumbs-up')){
            dom.html('<i class="fa fa-thumbs-o-up"></i> '+(--num));
        }else{
            dom.html('<i class="fa fa-thumbs-up"></i> '+(++num));
        }
    }else if(t==='author'){
        if(dom.hasClass('btn-default')){
            dom.removeClass('btn-default').addClass('btn-danger').html('<i class="fa fa-plus"></i> 关注');
        }else{
            dom.removeClass('btn-danger').addClass('btn-default').html('<i class="fa fa-check"></i> 已关注');
        }
    }else if(t==='book'){
        if(dom.hasClass('btn-default')){
            dom.removeClass('btn-default').addClass('btn-danger').html('<i class="fa fa-heart-o"></i> 收藏');
        }else{
            dom.removeClass('btn-danger').addClass('btn-default').html('<i class="fa fa-heart"></i> 已收藏');
        }
    }else if(t==='post'){
        if(dom.hasClass('btn-default')){
            dom.removeClass('btn-default').addClass('btn-danger').html('<i class="fa fa-thumbs-o-up"></i> 赞');
        }else{
            dom.removeClass('btn-danger').addClass('btn-default').html('<i class="fa fa-thumbs-up"></i> 已赞');
        }
    }else if(t==='user'){
        if(dom.hasClass('btn-default')){
            dom.removeClass('btn-default').addClass('btn-danger').html('<i class="fa fa-star-o"></i> 赞');
        }else{
            dom.removeClass('btn-danger').addClass('btn-default').html('<i class="fa fa-star"></i> 已赞');
        }
    }else if(t==='comment'){
        if(childDom.hasClass('fa-thumbs-up')){
            dom.html('<i class="fa fa-thumbs-o-up"></i> '+(--num));
        }else{
            dom.html('<i class="fa fa-thumbs-up"></i> '+(++num));
        }
    }else if(t==='forum'){
        if(dom.hasClass('btn-success')){
            dom.removeClass('btn-success').addClass('btn-default').html('<i class="fa fa-check"></i> 已关注');
        }else{
            dom.removeClass('btn-default').addClass('btn-success').html('<i class="fa fa-plus"></i> 关注');
        }
    }
    $.post(zmf.favoriteUrl, {type: t, data: acdata, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
        ajaxReturn = true;
        result = $.parseJSON(result);
        if (result.status === 1) {//收藏成功            
            
        } else if (result.status === 2) {//收藏失败
            dom.html(tmp);
            dialog({msg: result.msg});
        } else if (result.status === 3) {//取消成功
            
        } else if (result.status === 4) {//取消失败
            dom.html(tmp);
            dialog({msg: result.msg});
        } else {
            dom.html(tmp);
            dialog({msg: result.msg});
        }
        return false;
    });
}
function addComment(dom) {
    var k = dom.attr("action-data");
    var t = dom.attr("action-type");
    var to = parseInt($('#replyoneHolder-' + k).attr('tocommentid'));
    var isAuthor = parseInt($('#isAuthor-' + t + "-" + k+':checked').val());
    var c = $('#content-' + t + '-' + k).val();
    if (!k || !t || !c) {
        dialog({msg: '请填写内容'});
        return false;
    }
    if (!to) {
        to = 0;
    }
    if(!isAuthor){
        isAuthor=0;
    }
    if (!checkAjax()) {
        return false;
    }
    var targetBox="comments-" + t + "-" + k;
    $.post(zmf.addCommentUrl, {k: k, t: t, c: c, to: to,isAuthor:isAuthor, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            $('#content-' + t + '-' + k).val('');  
            var loadingDom=$('#' + targetBox + ' .loading-holder');
            var loadingHtml=loadingDom.html();
            if(!loadingHtml){
                loadingHtml='';
            }else{
                loadingHtml='<p class="loading-holder">'+loadingHtml+'</p>';
            }
            loadingDom.remove();
            $("#comments-" + t + "-" + k).append(result['msg']+loadingHtml);
            cancelReplyOne(k);
            rebind();
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function replyOne(id, logid, title) {
    var longstr = "<span class='label label-success'><span class='fa fa-share'></span>回复“" + title + "”<a href='javascript:' onclick='cancelReplyOne(" + logid + ")' title='取消设置'> <span class='fa fa-remove'></span></a></span>";
    var pos = $("#replyoneHolder-" + logid).offset().top;
    $("html,body").animate({scrollTop: pos}, 1000);
    $("#replyoneHolder-" + logid).attr('tocommentid', id).html(longstr);
}
function cancelReplyOne(logid) {
    $("#replyoneHolder-" + logid).attr('tocommentid', '').html('');
}
function setStatus(a, b, c) {
    if (!checkLogin()) {
        dialog({msg: '请先登录'});
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    $.post(zmf.setStatusUrl, {a: a, b: b, c: c, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == 1) {
            dialog({msg: result['msg'], time: 3});
        } else if (result['status'] == 2) {
            window.location.href = zmf.userLoginUrl + "&redirect=" + window.location.href;
        } else {
            dialog({msg: result['msg']});
        }
    });
}
// 使用message对象封装消息  
var flashTitle = {  
    time: 0,  
    title: document.title,  
    timer: null,  
    // 显示新消息提示  
    show: function () {  
        var title = flashTitle.title.replace("【　　　】", "").replace("【新消息】", "");  
        // 定时器，设置消息切换频率闪烁效果就此产生  
        flashTitle.timer = setTimeout(function () {  
            flashTitle.time++;  
            flashTitle.show();  
            if (flashTitle.time % 2 == 0) {  
                document.title = "【新消息】" + title  
            } else {  
                document.title = "【　　　】" + title  
            };  
        }, 600);  
        return [flashTitle.timer, flashTitle.title];  
    },  
    // 取消新消息提示  
    clear: function () {  
        clearTimeout(flashTitle.timer);  
        document.title = flashTitle.title;  
    }  
};  
var notice_interval=null;
function getNotice(){
    doGetNotice();
    window.onblur = function() {
        clearInterval(notice_interval);
    };
    window.onfocus = function() {
        clearInterval(notice_interval);
        notice_interval=window.setInterval("doGetNotice()",10000);
    }    
}
function doGetNotice(){
    if(!checkLogin()){
        return false;
    }
    $.post(zmf.ajaxUrl, {action:'getNotice',YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
        result = $.parseJSON(result);
        if (result.status === 1) {
            var _num=parseInt(result.msg);
            if(_num>0){
                $('#top-nav-count').html(_num).css('display','inline-block');
                if(flashTitle.timer===null){
                    flashTitle.show();
                }                
            }else{
                $('#top-nav-count').hide();
                flashTitle.clear();
            }
        }else{
            
        }
    })
}
/**
 * 意见反馈
 */
function feedback() {
    var c = $('#feedback-content').val();
    if (!c) {
        alert('内容不能为空哦~');
        return false;
    }
    if (!checkAjax()) {
        return false;
    }
    var url = window.location.href, email = $("#feedback-contact").val();
    $.post(zmf.feedbackUrl, {content: c, email: email, url: url, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
        ajaxReturn = true;
        result = $.parseJSON(result);
        dialog({msg: result['msg']});
        return false;
    });
}
function share(dom) {
    var qr = dom.attr("action-qrcode");
    var url = dom.attr("action-url");
    var img = dom.attr("action-img");
    var title = dom.attr("action-title");
    var html = '<div class="float-share-holder"><div class="float-share-content"><span class="float-close"><i class="fa fa-close"></i></span><div class="row"><div class="col-xs-6 text-center"><img src="' + qr + '" class="img-responsive"/><p class="help-block">扫码分享到微信</p></div><div class="col-xs-6 float-btns"><a href="javascript:;" class="btn btn-default btn-block"><i class="fa fa-weibo"></i></a><a href="javascript:;" class="btn btn-default btn-block"><i class="fa fa-qq"></i></a><a href="javascript:;" class="btn btn-default btn-block">复制链接</a></div></div></div><div class="float-triangle"></div></div>';
    var html = '<div class="share-body"><p><img src="' + qr + '" class="img-responsive"/></p><p class="help-block text-center">扫码分享到微信</p><div class="more-awesome"><span>或</span></div><p><a href="javascript:;" class="btn btn-default btn-block" action="shareToWeibo"><i class="fa fa-weibo"></i> 分享到微博</a><a href="javascript:;" class="btn btn-default btn-block" action="shareToQzone"><i class="fa fa-qq"></i> 分享到空间</a><a href="javascript:;" class="btn btn-default btn-block btn-copy" data-clipboard-text="'+url+'"><i class="fa fa-copy"></i> 复制此链接</a></p></div>';
    dialog({msg: html, title: '分享', modalSize: 'modal-sm'});
    $("a[action=shareToWeibo]").unbind('click').click(function () {
        window.open('http://service.weibo.com/share/share.php?title=' + title + '&url=' + url + '&appkey=' + zmf.weiboAppkey + '&pic=' + img + '&changweibo=yes&ralateUid=' + zmf.weiboRalateUid, '_newtab');
    });
    $("a[action=shareToQzone]").unbind('click').click(function () {
        window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title=' + title + '&url=' + url + '&pics=' + img, '_newtab');
    });
}
function toggleChapters(){
    var dom=$('#chapters-box');
    var right=0;
    if(dom.css('display')!=='none'){
        dom.animate({
            right:-450
        },500).toggle();
    }else{
        dom.toggle().animate({
            right:0
        },500);
    }
}
function playVideo(company, videoid, targetHolder, dom) {        
    if (!company || !videoid || !targetHolder) {
        return false;
    }
    var w=$('#'+targetHolder).width();
    var h=w*9/16;
    var html = '';
    if (company === 'youku') {
        html = '<iframe src="http://player.youku.com/embed/' + videoid + '" height="'+h+'" width="'+w+'" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"  height="'+h+'" width="'+w+'"></iframe>';
    } else if (company === 'tudou') {
        html = '<iframe src="http://www.tudou.com/programs/view/html5embed.action?type=2&' + videoid + '" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"  height="'+h+'" width="'+w+'"></iframe>';
    } else if (company === 'qq') {
        html = '<iframe src="http://v.qq.com/iframe/player.html?vid=' + videoid + '&tiny=0&auto=1" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"  height="'+h+'" width="'+w+'"></iframe>';
    }
    $('#' + targetHolder).html(html);
    $(dom).remove();
}
function myUploadify() {
    $("#uploadfile").uploadify({
        height: 34,
        width: 120,
        swf: zmf.baseUrl + '/common/uploadify/uploadify.swf',
        queueID: 'fileQueue',
        auto: true,
        multi: true,
        fileObjName: 'filedata',
        uploadLimit: zmf.perAddImgNum,
        fileSizeLimit: zmf.allowImgPerSize,
        fileTypeExts: zmf.allowImgTypes,
        fileTypeDesc: 'Image Files',
        uploader: tipImgUploadUrl,
        buttonText: '请选择',
        buttonClass: 'btn btn-success',
        debug: false,
        formData: {'PHPSESSID': zmf.currentSessionId, 'YII_CSRF_TOKEN': zmf.csrfToken},
        onUploadSuccess: function (file, data, response) {
            data = eval("(" + data + ")");
            if (data['status'] == 1) {
                var img = "<p><img src='" + data['thumbnail'] + "' data='" + data['attachid'] + "' class='img-responsive'/></p>";
                myeditor.execCommand("inserthtml", img);
            } else {
                dialog({msg: data.msg});
            }
            tipsImgOrder++;
        }
    });
}
function uploadByLimit(params) {
    if (typeof params !== "object") {
        return false;
    }
    var multi = true;
    if (typeof params.multi === 'undefined') {
        multi = true;
    } else {
        multi = params.multi;
    }
    $("#"+params.placeHolder).uploadify({
        height: params.height ? params.height : 100,
        width: params.width ? params.width : 300,
        swf: zmf.baseUrl + '/common/uploadify/uploadify.swf',
        queueID: params.queueID ? params.queueID : 'singleFileQueue',
        auto: true,
        multi: multi,
        queueSizeLimit: zmf.perAddImgNum,
        fileObjName: params.filedata ? params.filedata : 'filedata',
        fileTypeExts: zmf.allowImgTypes,
        fileSizeLimit: zmf.allowImgPerSize,
        fileTypeDesc: 'Image Files',
        uploader: params.uploadUrl,
        buttonText: params.buttonText ? params.buttonText : (params.buttonText === null ? '' : '添加图片'),
        buttonClass: params.buttonClass ? params.buttonClass : 'btn btn-default',
        debug: false,
        formData: {'PHPSESSID': zmf.currentSessionId, 'YII_CSRF_TOKEN': zmf.csrfToken},
        onSelect: function(fileObj){
//            console.log(fileObj);
//            alert(
//                  "文件名：" + fileObj.name + "\r\n" +
//                  "文件大小：" + fileObj.size + "\r\n" +
//                  "创建时间：" + fileObj.creationDate + "\r\n" +
//                  "最后修改时间：" + fileObj.modificationDate + "\r\n" +
//                  "文件类型：" + fileObj.type
//            );
//            $("#"+params.placeHolder).uploadify('cancel');
        },        
        onUploadSuccess: function (file, data, response) {
            data = $.parseJSON(data);
            if (data['status'] == 1) {
                if (params.inputId) {
                    $('#' + params.inputId).val(data.imgsrc);
                }
                if (params.targetHolder) {
                    $('#' + params.targetHolder).attr('src',data.thumbnail);
                }
            } else {
                dialog({msg: data.msg});
            }
        }
    });
}
/**
 * placeHolder, inputId, limit,multi
 * @returns {undefined}
 */
function singleUploadify(params) {
    if (typeof params !== "object") {
        return false;
    }
    var multi = true;
    if (typeof params.multi === 'undefined') {
        multi = true;
    } else {
        multi = params.multi;
    }
    $("#" + params.placeHolder).uploadify({
        height: params.height ? params.height : 100,
        width: params.width ? params.width : 300,
        swf: zmf.baseUrl + '/common/uploadify/uploadify.swf',
        queueID: 'singleFileQueue',
        auto: true,
        multi: multi,
        queueSizeLimit: zmf.perAddImgNum,
        fileObjName: params.filedata ? params.filedata : 'filedata',
        fileTypeExts: zmf.allowImgTypes,
        fileSizeLimit: zmf.allowImgPerSize,
        fileTypeDesc: 'Image Files',
        uploader: params.uploadUrl,
        buttonText: params.buttonText ? params.buttonText : (params.buttonText === null ? '' : '添加图片'),
        buttonClass: params.buttonClass ? params.buttonClass : 'btn btn-default',
        debug: false,
        formData: {'PHPSESSID': zmf.currentSessionId, 'YII_CSRF_TOKEN': zmf.csrfToken},
        onUploadStart: function (file) {
            if (params.type === 'posts') {
                var _params = {};
                var myDate = new Date();
                var _type = file.type;
                var _name = params.type + '/' + myDate.getFullYear() + '/' + (myDate.getMonth() + 1) + '/' + myDate.getDate() + '/' + uuid() + _type;
                _params.key = _name;
                _params.token = params.token;
                $("#" + params.placeHolder).uploadify('settings', 'formData', _params);
            }
        },
        onUploadSuccess: function (file, data, response) {
            data = $.parseJSON(data);
            if (params.type === 'posts') {
                if (!data.error) {
                    var passData = {
                        YII_CSRF_TOKEN: zmf.csrfToken,
                        filePath: data.key,
                        fileSize: file.size,
                        type: params.type,
                        action: 'saveUploadImg'
                    };
                    $.post(zmf.ajaxUrl,passData, function (reJson) {
                        reJson = $.parseJSON(reJson);
                        if (reJson.status === 1) {
                            $("#fileSuccess").append(reJson.html);
                            if (params.inputId) {
                                $('#' + params.inputId).val(reJson.attachid);
                            }
                            rebind();
                        } else {
                            alert(reJson.msg);
                            return false;
                        }
                    });
                } else {
                    alert(data.error);
                    return false;
                }
            } else {
                if (data.status === 1) {
                    $("#fileSuccess").html(data.html);
                    if (params.inputId) {
                        $('#' + params.inputId).val(data.attachid);
                    }
                    rebind();
                } else {
                    alert(data.msg);
                    return false;
                }
            }
        }
    });
}
function showChapters(){
    var h=$(window).height();
    var w=$(window).width();
    var dom=$('#chapters-box');
    if(!dom.width()){
        return false;
    }
    var domBtn=$('#post-fixed-actions');
    var left=$('.zazhi-page').width();
    var btnWidth=domBtn.width();
    if((w-left-btnWidth)<=0){
        domBtn.css({
            'margin-left':480-btnWidth-5
        }).fadeIn(300);
    }else{
        domBtn.fadeIn(300);
    }
    var _w=w-(w/2+left/2+btnWidth)-15;
    if(_w<400){
        _w=400;
    }
    dom.css({
        width:_w,
        height:h
    });   
    $('#chapters-holder').css({
        height:h
    });
}
/*
 * a:对话框id
 * t:提示
 * c:对话框内容
 * ac:下一步的操作名
 * time:自动关闭
 */
function dialog(diaObj) {
    if (typeof diaObj !== "object") {
        return false;
    }
    var c = diaObj.msg;
    var a = diaObj.id;
    var t = diaObj.title;
    var ac = diaObj.action;
    var acn = diaObj.actionName;
    var time = diaObj.time;
    var size = diaObj.modalSize;
    $('#' + beforeModal).modal('hide');
    if (typeof t === 'undefined' || t === '') {
        t = '提示';
    }
    if (typeof a === 'undefined' || a === '') {
        a = 'myDialog';
    }
    if (typeof ac === 'undefined') {
        ac = '';
    }
    if (typeof size === 'undefined') {
        size = '';
    }
    $('#' + a).remove();
    var longstr = '<div class="modal fade mymodal" id="' + a + '" tabindex="-1" role="dialog" aria-labelledby="' + a + 'Label" aria-hidden="true"><div class="modal-dialog ' + size + '"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="' + a + 'Label">' + t + '</h4></div><div class="modal-body">' + c + '</div><div class="modal-footer">';
    longstr += '<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>';
    if (ac !== '' && typeof ac !== 'undefined') {
        var _t;
        if (acn !== '' && typeof acn !== 'undefined') {
            _t = acn;
        } else {
            _t = '确定';
        }
        longstr += '<button type="button" class="btn btn-primary" action="' + ac + '" data-loading-text="Loading...">' + _t + '</button>';
    }
    longstr += '</div></div></div></div>';
    $("body").append(longstr);
    $('#' + a).modal({
        backdrop: false,
        keyboard: false
    });
    beforeModal = a;
    if (time > 0 && typeof time !== 'undefined') {
        setTimeout("closeDialog('" + a + "')", time * 1000);
    }
}
function closeDialog(a) {
    if (!a) {
        a = 'myDialog';
    }
    $('#' + a).modal('hide');
    $('#' + a).remove();
    $("body").eq(0).removeClass('modal-open');
}
function simpleDialog(diaObj) {
    if (typeof diaObj !== "object") {
        return false;
    }
    var c = diaObj.content;
    var longstr = '<div class="simpleDialog">' + c + '</div>';
    $("body").append(longstr);
    var dom = $('.simpleDialog');
    var w = dom.width();
    var h = dom.height();
    dom.css({
        'margin-left': -w / 2,
        'margin-top': -h / 2
    });
    dom.fadeIn(300);
    setTimeout("closeSimpleDialog()", 2700);
}
function closeSimpleDialog() {
    $('.simpleDialog').fadeOut(100).remove();
}
function simpleLoading(diaObj) {
    if (typeof diaObj !== "object") {
        return false;
    }
    var c = diaObj.title;
    var longstr = '<div class="simple-loading-box"><div class="loading-holder"><i class="fa fa-spinner"></i></div><div class="loading-title"><p>' + c + '</p></div></div>';
    $("body").append(longstr);
    var dom = $('.simple-loading-box');
    var w = dom.width();
    var h = dom.height();
    dom.css({
        'margin-left': -w / 2,
        'margin-top': -h / 2
    });
    dom.fadeIn(300);
    setTimeout("closeSimpleLoading()", 2700);
}
function closeSimpleLoading() {
    $('.simple-loading-box').fadeOut(100).remove();
}
function checkAjax() {
    if (!ajaxReturn) {
        dialog({msg: '请求正在发送中，请稍后'});
        return false;
    }
    ajaxReturn = false;
    return true;
}
function checkLogin() {
    if (typeof zmf.hasLogin === 'undefined') {
        return false;
    } else if (zmf.hasLogin === 'true') {
        return true;
    } else {
        return false;
    }
}
function backToTop() {
    var x = $(window).width();
    var x1 = $(".zazhi-page").width();
    var x2 = $("#back-to-top").width();
    if (x < x1) {
        var x3 = x1 + 8;
    } else {
        var x3 = parseInt((x + x1 + 16) / 2);
    }
    $("#back-to-top").css('left', x3 + 'px');
    //让body至少为窗口高度
    var wh=$(window).height();
    var dh=$(document.body).height();
    if(wh>dh){
        $("body").css('height',wh);
    }
    $('#footer-bg').fadeIn(3000);
}
function textareaAutoResize() {
    $('textarea').autoResize({
        // On resize:  
        onResize: function () {
            $(this).css({opacity: 0.8});
        },
        // After resize:  
        animateCallback: function () {
            $(this).css({opacity: 1});
        },
        // Quite slow animation:  
        animateDuration: 100,
        // More extra space:  
        extraSpace: 20
    });
}
function uuid(len, radix) {
    var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
    var uuid = [], i;
    radix = radix || chars.length;

    if (len) {
        for (i = 0; i < len; i++)
            uuid[i] = chars[0 | Math.random() * radix];
    } else {
        var r;
        // rfc4122 requires these characters
        uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
        uuid[14] = '4';
        // Fill in random data.  At i==19 set the high bits of clock sequence as
        // per rfc4122, sec. 4.1.5
        for (i = 0; i < 36; i++) {
            if (!uuid[i]) {
                r = 0 | Math.random() * 16;
                uuid[i] = chars[(i === 19) ? (r & 0x3) | 0x8 : r];
            }
        }
    }
    return uuid.join('');
}
/*
 * jQuery autoResize (textarea auto-resizer)
 * @copyright James Padolsey http://james.padolsey.com
 * @version 1.04
 */
(function ($) {

    $.fn.autoResize = function (options) {

        // Just some abstracted details,
        // to make plugin users happy:
        var settings = $.extend({
            onResize: function () {
            },
            animate: true,
            animateDuration: 150,
            animateCallback: function () {
            },
            extraSpace: 20,
            limit: 1000
        }, options);

        // Only textarea's auto-resize:
        this.filter('textarea').each(function () {

            // Get rid of scrollbars and disable WebKit resizing:
            var textarea = $(this).css({resize: 'none', 'overflow-y': 'hidden'}),
                    // Cache original height, for use later:
                    origHeight = textarea.height(),
                    // Need clone of textarea, hidden off screen:
                    clone = (function () {

                        // Properties which may effect space taken up by chracters:
                        var props = ['height', 'width', 'lineHeight', 'textDecoration', 'letterSpacing'],
                                propOb = {};

                        // Create object of styles to apply:
                        $.each(props, function (i, prop) {
                            propOb[prop] = textarea.css(prop);
                        });

                        // Clone the actual textarea removing unique properties
                        // and insert before original textarea:
                        return textarea.clone().removeAttr('id').removeAttr('name').css({
                            position: 'absolute',
                            top: 0,
                            left: -9999
                        }).css(propOb).attr('tabIndex', '-1').insertBefore(textarea);

                    })(),
                    lastScrollTop = null,
                    updateSize = function () {

                        // Prepare the clone:
                        clone.height(0).val($(this).val()).scrollTop(10000);

                        // Find the height of text:
                        var scrollTop = Math.max(clone.scrollTop(), origHeight) + settings.extraSpace,
                                toChange = $(this).add(clone);

                        // Don't do anything if scrollTip hasen't changed:
                        if (lastScrollTop === scrollTop) {
                            return;
                        }
                        lastScrollTop = scrollTop;

                        // Check for limit:
                        if (scrollTop >= settings.limit) {
                            $(this).css('overflow-y', '');
                            return;
                        }
                        // Fire off callback:
                        settings.onResize.call(this);

                        // Either animate or directly apply height:
                        settings.animate && textarea.css('display') === 'block' ?
                                toChange.stop().animate({height: scrollTop}, settings.animateDuration, settings.animateCallback)
                                : toChange.height(scrollTop);
                    };

            // Bind namespaced handlers to appropriate events:
            textarea
                    .unbind('.dynSiz')
                    .bind('keyup.dynSiz', updateSize)
                    .bind('keydown.dynSiz', updateSize)
                    .bind('change.dynSiz', updateSize);

        });

        // Chain:
        return this;

    };



})(jQuery);
/*lazyload*/
!function (c, b, d, f) {
    var a = c(b);
    c.fn.lazyload = function (h) {
        function i() {
            var k = 0;
            e.each(function () {
                var l = c(this);
                if (!j.skip_invisible || l.is(":visible")) {
                    if (c.abovethetop(this, j) || c.leftofbegin(this, j)) {
                    } else {
                        if (c.belowthefold(this, j) || c.rightoffold(this, j)) {
                            if (++k > j.failure_limit) {
                                return !1
                            }
                        } else {
                            l.trigger("appear"), k = 0
                        }
                    }
                }
            })
        }
        var g, e = this, j = {threshold: 0, failure_limit: 0, event: "scroll", effect: "show", container: b, data_attribute: "original", skip_invisible: !0, appear: null, load: null};
        return h && (f !== h.failurelimit && (h.failure_limit = h.failurelimit, delete h.failurelimit), f !== h.effectspeed && (h.effect_speed = h.effectspeed, delete h.effectspeed), c.extend(j, h)), g = j.container === f || j.container === b ? a : c(j.container), 0 === j.event.indexOf("scroll") && g.bind(j.event, function () {
            return i()
        }), this.each(function () {
            var k = this, l = c(k);
            k.loaded = !1, l.one("appear", function () {
                if (!this.loaded) {
                    if (j.appear) {
                        var m = e.length;
                        j.appear.call(k, m, j)
                    }
                    c("<img />").bind("load", function () {
                        l.hide().attr("src", l.data(j.data_attribute))[j.effect](j.effect_speed), k.loaded = !0;
                        var p = c.grep(e, function (n) {
                            return !n.loaded
                        });
                        if (e = c(p), j.load) {
                            var o = e.length;
                            j.load.call(k, o, j)
                        }
                    }).attr("src", l.data(j.data_attribute))
                }
            }), 0 !== j.event.indexOf("scroll") && l.bind(j.event, function () {
                k.loaded || l.trigger("appear")
            })
        }), a.bind("resize", function () {
            i()
        }), /iphone|ipod|ipad.*os 5/gi.test(navigator.appVersion) && a.bind("pageshow", function (k) {
            k.originalEvent && k.originalEvent.persisted && e.each(function () {
                c(this).trigger("appear")
            })
        }), c(d).ready(function () {
            i()
        }), this
    }, c.belowthefold = function (h, e) {
        var g;
        return g = e.container === f || e.container === b ? a.height() + a.scrollTop() : c(e.container).offset().top + c(e.container).height(), g <= c(h).offset().top - e.threshold
    }, c.rightoffold = function (h, e) {
        var g;
        return g = e.container === f || e.container === b ? a.width() + a.scrollLeft() : c(e.container).offset().left + c(e.container).width(), g <= c(h).offset().left - e.threshold
    }, c.abovethetop = function (h, e) {
        var g;
        return g = e.container === f || e.container === b ? a.scrollTop() : c(e.container).offset().top, g >= c(h).offset().top + e.threshold + c(h).height()
    }, c.leftofbegin = function (h, e) {
        var g;
        return g = e.container === f || e.container === b ? a.scrollLeft() : c(e.container).offset().left, g >= c(h).offset().left + e.threshold + c(h).width()
    }, c.inviewport = function (e, g) {
        return !(c.rightoffold(e, g) || c.leftofbegin(e, g) || c.belowthefold(e, g) || c.abovethetop(e, g))
    }, c.extend(c.expr[":"], {"below-the-fold": function (e) {
            return c.belowthefold(e, {threshold: 0})
        }, "above-the-top": function (e) {
            return !c.belowthefold(e, {threshold: 0})
        }, "right-of-screen": function (e) {
            return c.rightoffold(e, {threshold: 0})
        }, "left-of-screen": function (e) {
            return !c.rightoffold(e, {threshold: 0})
        }, "in-viewport": function (e) {
            return c.inviewport(e, {threshold: 0})
        }, "above-the-fold": function (e) {
            return !c.belowthefold(e, {threshold: 0})
        }, "right-of-fold": function (e) {
            return c.rightoffold(e, {threshold: 0})
        }, "left-of-fold": function (e) {
            return !c.rightoffold(e, {threshold: 0})
        }})
}(jQuery, window, document);
/*!
 * clipboard.js v1.5.5
 * https://zenorocha.github.io/clipboard.js
 *
 * Licensed MIT © Zeno Rocha
 */
!function (t) {
    if ("object" == typeof exports && "undefined" != typeof module)
        module.exports = t();
    else if ("function" == typeof define && define.amd)
        define([], t);
    else {
        var e;
        e = "undefined" != typeof window ? window : "undefined" != typeof global ? global : "undefined" != typeof self ? self : this, e.Clipboard = t()
    }
}(function () {
    var t, e, n;
    return function t(e, n, r) {
        function o(a, c) {
            if (!n[a]) {
                if (!e[a]) {
                    var s = "function" == typeof require && require;
                    if (!c && s)
                        return s(a, !0);
                    if (i)
                        return i(a, !0);
                    var u = new Error("Cannot find module '" + a + "'");
                    throw u.code = "MODULE_NOT_FOUND", u
                }
                var l = n[a] = {exports: {}};
                e[a][0].call(l.exports, function (t) {
                    var n = e[a][1][t];
                    return o(n ? n : t)
                }, l, l.exports, t, e, n, r)
            }
            return n[a].exports
        }
        for (var i = "function" == typeof require && require, a = 0; a < r.length; a++)
            o(r[a]);
        return o
    }({1: [function (t, e, n) {
                var r = t("matches-selector");
                e.exports = function (t, e, n) {
                    for (var o = n ? t : t.parentNode; o && o !== document; ) {
                        if (r(o, e))
                            return o;
                        o = o.parentNode
                    }
                }
            }, {"matches-selector": 2}], 2: [function (t, e, n) {
                function r(t, e) {
                    if (i)
                        return i.call(t, e);
                    for (var n = t.parentNode.querySelectorAll(e), r = 0; r < n.length; ++r)
                        if (n[r] == t)
                            return!0;
                    return!1
                }
                var o = Element.prototype, i = o.matchesSelector || o.webkitMatchesSelector || o.mozMatchesSelector || o.msMatchesSelector || o.oMatchesSelector;
                e.exports = r
            }, {}], 3: [function (t, e, n) {
                function r(t, e, n, r) {
                    var i = o.apply(this, arguments);
                    return t.addEventListener(n, i), {destroy: function () {
                            t.removeEventListener(n, i)
                        }}
                }
                function o(t, e, n, r) {
                    return function (n) {
                        n.delegateTarget = i(n.target, e, !0), n.delegateTarget && r.call(t, n)
                    }
                }
                var i = t("closest");
                e.exports = r
            }, {closest: 1}], 4: [function (t, e, n) {
                n.node = function (t) {
                    return void 0 !== t && t instanceof HTMLElement && 1 === t.nodeType
                }, n.nodeList = function (t) {
                    var e = Object.prototype.toString.call(t);
                    return void 0 !== t && ("[object NodeList]" === e || "[object HTMLCollection]" === e) && "length"in t && (0 === t.length || n.node(t[0]))
                }, n.string = function (t) {
                    return"string" == typeof t || t instanceof String
                }, n.function = function (t) {
                    var e = Object.prototype.toString.call(t);
                    return"[object Function]" === e
                }
            }, {}], 5: [function (t, e, n) {
                function r(t, e, n) {
                    if (!t && !e && !n)
                        throw new Error("Missing required arguments");
                    if (!c.string(e))
                        throw new TypeError("Second argument must be a String");
                    if (!c.function(n))
                        throw new TypeError("Third argument must be a Function");
                    if (c.node(t))
                        return o(t, e, n);
                    if (c.nodeList(t))
                        return i(t, e, n);
                    if (c.string(t))
                        return a(t, e, n);
                    throw new TypeError("First argument must be a String, HTMLElement, HTMLCollection, or NodeList")
                }
                function o(t, e, n) {
                    return t.addEventListener(e, n), {destroy: function () {
                            t.removeEventListener(e, n)
                        }}
                }
                function i(t, e, n) {
                    return Array.prototype.forEach.call(t, function (t) {
                        t.addEventListener(e, n)
                    }), {destroy: function () {
                            Array.prototype.forEach.call(t, function (t) {
                                t.removeEventListener(e, n)
                            })
                        }}
                }
                function a(t, e, n) {
                    return s(document.body, t, e, n)
                }
                var c = t("./is"), s = t("delegate");
                e.exports = r
            }, {"./is": 4, delegate: 3}], 6: [function (t, e, n) {
                function r(t) {
                    var e;
                    if ("INPUT" === t.nodeName || "TEXTAREA" === t.nodeName)
                        t.focus(), t.setSelectionRange(0, t.value.length), e = t.value;
                    else {
                        t.hasAttribute("contenteditable") && t.focus();
                        var n = window.getSelection(), r = document.createRange();
                        r.selectNodeContents(t), n.removeAllRanges(), n.addRange(r), e = n.toString()
                    }
                    return e
                }
                e.exports = r
            }, {}], 7: [function (t, e, n) {
                function r() {
                }
                r.prototype = {on: function (t, e, n) {
                        var r = this.e || (this.e = {});
                        return(r[t] || (r[t] = [])).push({fn: e, ctx: n}), this
                    }, once: function (t, e, n) {
                        function r() {
                            o.off(t, r), e.apply(n, arguments)
                        }
                        var o = this;
                        return r._ = e, this.on(t, r, n)
                    }, emit: function (t) {
                        var e = [].slice.call(arguments, 1), n = ((this.e || (this.e = {}))[t] || []).slice(), r = 0, o = n.length;
                        for (r; o > r; r++)
                            n[r].fn.apply(n[r].ctx, e);
                        return this
                    }, off: function (t, e) {
                        var n = this.e || (this.e = {}), r = n[t], o = [];
                        if (r && e)
                            for (var i = 0, a = r.length; a > i; i++)
                                r[i].fn !== e && r[i].fn._ !== e && o.push(r[i]);
                        return o.length ? n[t] = o : delete n[t], this
                    }}, e.exports = r
            }, {}], 8: [function (t, e, n) {
                "use strict";
                function r(t) {
                    return t && t.__esModule ? t : {"default": t}
                }
                function o(t, e) {
                    if (!(t instanceof e))
                        throw new TypeError("Cannot call a class as a function")
                }
                n.__esModule = !0;
                var i = function () {
                    function t(t, e) {
                        for (var n = 0; n < e.length; n++) {
                            var r = e[n];
                            r.enumerable = r.enumerable || !1, r.configurable = !0, "value"in r && (r.writable = !0), Object.defineProperty(t, r.key, r)
                        }
                    }
                    return function (e, n, r) {
                        return n && t(e.prototype, n), r && t(e, r), e
                    }
                }(), a = t("select"), c = r(a), s = function () {
                    function t(e) {
                        o(this, t), this.resolveOptions(e), this.initSelection()
                    }
                    return t.prototype.resolveOptions = function t() {
                        var e = arguments.length <= 0 || void 0 === arguments[0] ? {} : arguments[0];
                        this.action = e.action, this.emitter = e.emitter, this.target = e.target, this.text = e.text, this.trigger = e.trigger, this.selectedText = ""
                    }, t.prototype.initSelection = function t() {
                        if (this.text && this.target)
                            throw new Error('Multiple attributes declared, use either "target" or "text"');
                        if (this.text)
                            this.selectFake();
                        else {
                            if (!this.target)
                                throw new Error('Missing required attributes, use either "target" or "text"');
                            this.selectTarget()
                        }
                    }, t.prototype.selectFake = function t() {
                        var e = this;
                        this.removeFake(), this.fakeHandler = document.body.addEventListener("click", function () {
                            return e.removeFake()
                        }), this.fakeElem = document.createElement("textarea"), this.fakeElem.style.position = "absolute", this.fakeElem.style.left = "-9999px", this.fakeElem.style.top = (window.pageYOffset || document.documentElement.scrollTop) + "px", this.fakeElem.setAttribute("readonly", ""), this.fakeElem.value = this.text, document.body.appendChild(this.fakeElem), this.selectedText = c.default(this.fakeElem), this.copyText()
                    }, t.prototype.removeFake = function t() {
                        this.fakeHandler && (document.body.removeEventListener("click"), this.fakeHandler = null), this.fakeElem && (document.body.removeChild(this.fakeElem), this.fakeElem = null)
                    }, t.prototype.selectTarget = function t() {
                        this.selectedText = c.default(this.target), this.copyText()
                    }, t.prototype.copyText = function t() {
                        var e = void 0;
                        try {
                            e = document.execCommand(this.action)
                        } catch (n) {
                            e = !1
                        }
                        this.handleResult(e)
                    }, t.prototype.handleResult = function t(e) {
                        e ? this.emitter.emit("success", {action: this.action, text: this.selectedText, trigger: this.trigger, clearSelection: this.clearSelection.bind(this)}) : this.emitter.emit("error", {action: this.action, trigger: this.trigger, clearSelection: this.clearSelection.bind(this)})
                    }, t.prototype.clearSelection = function t() {
                        this.target && this.target.blur(), window.getSelection().removeAllRanges()
                    }, t.prototype.destroy = function t() {
                        this.removeFake()
                    }, i(t, [{key: "action", set: function t() {
                                var e = arguments.length <= 0 || void 0 === arguments[0] ? "copy" : arguments[0];
                                if (this._action = e, "copy" !== this._action && "cut" !== this._action)
                                    throw new Error('Invalid "action" value, use either "copy" or "cut"')
                            }, get: function t() {
                                return this._action
                            }}, {key: "target", set: function t(e) {
                                if (void 0 !== e) {
                                    if (!e || "object" != typeof e || 1 !== e.nodeType)
                                        throw new Error('Invalid "target" value, use a valid Element');
                                    this._target = e
                                }
                            }, get: function t() {
                                return this._target
                            }}]), t
                }();
                n.default = s, e.exports = n.default
            }, {select: 6}], 9: [function (t, e, n) {
                "use strict";
                function r(t) {
                    return t && t.__esModule ? t : {"default": t}
                }
                function o(t, e) {
                    if (!(t instanceof e))
                        throw new TypeError("Cannot call a class as a function")
                }
                function i(t, e) {
                    if ("function" != typeof e && null !== e)
                        throw new TypeError("Super expression must either be null or a function, not " + typeof e);
                    t.prototype = Object.create(e && e.prototype, {constructor: {value: t, enumerable: !1, writable: !0, configurable: !0}}), e && (Object.setPrototypeOf ? Object.setPrototypeOf(t, e) : t.__proto__ = e)
                }
                function a(t, e) {
                    var n = "data-clipboard-" + t;
                    if (e.hasAttribute(n))
                        return e.getAttribute(n)
                }
                n.__esModule = !0;
                var c = t("./clipboard-action"), s = r(c), u = t("tiny-emitter"), l = r(u), f = t("good-listener"), d = r(f), h = function (t) {
                    function e(n, r) {
                        o(this, e), t.call(this), this.resolveOptions(r), this.listenClick(n)
                    }
                    return i(e, t), e.prototype.resolveOptions = function t() {
                        var e = arguments.length <= 0 || void 0 === arguments[0] ? {} : arguments[0];
                        this.action = "function" == typeof e.action ? e.action : this.defaultAction, this.target = "function" == typeof e.target ? e.target : this.defaultTarget, this.text = "function" == typeof e.text ? e.text : this.defaultText
                    }, e.prototype.listenClick = function t(e) {
                        var n = this;
                        this.listener = d.default(e, "click", function (t) {
                            return n.onClick(t)
                        })
                    }, e.prototype.onClick = function t(e) {
                        var n = e.delegateTarget || e.currentTarget;
                        this.clipboardAction && (this.clipboardAction = null), this.clipboardAction = new s.default({action: this.action(n), target: this.target(n), text: this.text(n), trigger: n, emitter: this})
                    }, e.prototype.defaultAction = function t(e) {
                        return a("action", e)
                    }, e.prototype.defaultTarget = function t(e) {
                        var n = a("target", e);
                        return n ? document.querySelector(n) : void 0
                    }, e.prototype.defaultText = function t(e) {
                        return a("text", e)
                    }, e.prototype.destroy = function t() {
                        this.listener.destroy(), this.clipboardAction && (this.clipboardAction.destroy(), this.clipboardAction = null)
                    }, e
                }(l.default);
                n.default = h, e.exports = n.default
            }, {"./clipboard-action": 8, "good-listener": 5, "tiny-emitter": 7}]}, {}, [9])(9)
});
rebind();
getNotice();