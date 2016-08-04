var ajaxReturn = true;
var beforeModal;
if ($.support.pjax) {    
    $(document).pjax('a:not([data-remote]):not([data-skip-pjax]):not([data-ajax=false]):not([target=_blank])', '#pjax-container', {
        push:true,
        scrollTo:0,
        fragment:'#pjax-container', 
        timeout:5000
    });
    $(document).on('pjax:send', function() {
       simpleLoading({title:'正在加载中...'});
    });
    $(document).on('pjax:complete', function() {
        closeSimpleLoading();
        closeDialog();
        rebind();
    });
};
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
                $('#forgot-hidden').show();
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
    bindLink();    
}
function bindLink(){
    $('.ui-grid-full li,.ui-list li,.ui-row li,.ui-avatar,.ui-col,.ui-tiled li').unbind('click').click(function () {
        if ($(this).attr('data-href')) {
            location.href = $(this).attr('data-href');
        }
    });
}

function cancelBindLink(){
    $('.ui-grid-full li,.ui-list li,.ui-row li,.ui-avatar,.ui-col').unbind('click');
}
function showUserSide(){    
    var dom = $(this);
    var _dom = $('#user-side-holder');        
    if (_dom.css('display') == 'none') {            
        //_dom.show().animate({top: 0}, 800);
        _dom.show();
        $('body').addClass('menu-on');
    } else {
        //_dom.hide().animate({top: -800}, 300);
        _dom.hide();
        $('body').removeClass('menu-on');            
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
        dialog({msg: '请先登录哦~'});
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
        if(dom.text()==='已收藏'){
            dom.text('加入收藏');
        }else{
            dom.text('已收藏');
        }
    }else if(t==='post'){
        if(childDom.hasClass('fa-thumbs-up')){
            dom.html('<i class="fa fa-thumbs-o-up"></i><sup>'+(--num))+'</sup>';
        }else{
            dom.html('<i class="fa fa-thumbs-up"></i><sup>'+(++num))+'</sup>';
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
/**
 * 获取内容
 * @param {type} dom
 * @param {type} t 类型
 * @param {type} k keyid
 * @param {type} p 页码
 * @returns {Boolean}
 */
function getContents(dom) {
    var acdata = dom.attr("action-data");
    var t = dom.attr("action-type");
    var p = dom.attr("action-page");
    var targetBox = dom.attr('action-target');
    if (!checkAjax()) {
        return false;
    }
    if (!targetBox) {
        return false;
    }
    if (!p) {
        p = 1;
    }
    var btnHtml='',loadingCss='';
    if(zmf.module==='magazine'){
        btnHtml='btn btn-default btn-block';
    }else{
        btnHtml='ui-btn-lg';
        loadingCss='ui-btn-wrap';
    }
    var loading = '<div class="loading-holder '+loadingCss+'"><a class="'+btnHtml+' disabled" href="javascript:;">拼命加载中...</a></div>';
    $('#' + targetBox + '-box').children('.loading-holder').each(function () {
        $(this).remove();
    });
    $('#' + targetBox + '-box').append(loading);
    $.post(zmf.contentsUrl, {type: t, page: p, data: acdata, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
        ajaxReturn = true;
        dom.attr('loaded', '1');
        result = $.parseJSON(result);
        if (result.status === 1) {
            var data = result.msg;

            var pageHtml = '', dataHtml = '';

            if (data.html !== '') {
                dataHtml += data.html;
            }

            if (data.loadMore === 1) {
                var _p = parseInt(p) + 1;
                pageHtml += '<div class="loading-holder '+loadingCss+'"><a class="'+btnHtml+'"  href="javascript:;" action="get-contents" action-type="' + t + '" action-data="' + acdata + '" action-page="' + _p + '" action-target="' + targetBox + '">加载更多</a></div>';
            } else {
                pageHtml += '<div class="loading-holder '+loadingCss+'"><a class="'+btnHtml+' disabled" href="javascript:;">已全部加载</a></div>';
            }

            if (p === 1) {
                $('#' + targetBox + '-box').append(data.formHtml);
                $('#' + targetBox + '-box .loading-holder').each(function () {
                    $(this).remove();
                });
            } else {
                $('#' + targetBox + '-box .loading-holder').each(function () {
                    $(this).remove();
                });
            }
            if (p > 1) {
                $('#' + targetBox).append(dataHtml);
            } else {
                $('#' + targetBox).html(dataHtml);
            }
            $('#' + targetBox + '-box').append(pageHtml);

            rebind();
        } else {
            dialog({msg: result.msg});
        }
    });

}
function dialog(diaObj) {
    if (typeof diaObj !== "object") {
        return false;
    }
    closeSimpleDialog();
    var c = diaObj.msg;
    var longstr = '<div class="simpleDialog" id="simpleDialog">' + c + '</div>';
    $("body").append(longstr);
    var dom = $('#simpleDialog');
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
    $('#simpleDialog').fadeOut(100).remove();
}
function scollToComment(){
    $('.toggle-area').each(function(){
        $(this).fadeIn(500);
    });
    var h=$(window).height();
    var _h=$(".comment-textarea").offset().top;        
    $("body,html").animate({
        scrollTop: _h
    }, 200);    
}
function simpleDialog(diaObj) {
    if (typeof diaObj !== "object") {
        return false;
    }
    closeSimpleDialog();
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
function getNotice(){
    doGetNotice();
    window.setInterval("doGetNotice()",10000);
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
function closeSimpleDialog() {
    $('.simpleDialog').fadeOut(100).remove();
}
function checkAjax() {
    if (!ajaxReturn) {
        dialog({msg: '请求正在发送中，请稍后'});
        return false;
    }
    ajaxReturn = false;
    return true;
}
function addComment(dom) {
    var k = dom.attr("action-data");
    var t = dom.attr("action-type");
    var to = parseInt($('#replyoneHolder-' + k).attr('tocommentid'));
    var c = $('#content-' + t + '-' + k).val();
    var username=$('#username-' + t + '-' + k).val();
    var email=$('#email-' + t + '-' + k).val();
    if (!k || !t || !c) {
        dialog({msg: '请填写内容'});
        return false;
    }
    if (!username) {
        username = '';
    }
    if (!email) {
        email = '';
    }
    if(!checkLogin()){
        if(!username){
            dialog({msg: '请填写称呼'});
            return false;
        }
        if(email!=''){
            var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
            if(!reg.test(email)){
                dialog({msg: '请填写正确的邮箱地址'});
                return false;
            }
        }
    }
    if (!to) {
        to = 0;
    }    
    if (!checkAjax()) {
        return false;
    }
    $.post(zmf.addCommentUrl, {k: k, t: t, c: c, to: to,email:email,username:username, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
        ajaxReturn = true;
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            $('#content-' + t + '-' + k).val('');
            $("#comments-" + t + "-" + k).append(result['msg']);
            cancelReplyOne(k);
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function replyOne(id, logid, title) {
    var longstr = "<span class='reply-one'>回复“" + title + "”<a href='javascript:' onclick='cancelReplyOne(" + logid + ")' title='取消设置'> <i class='ui-icon-close-page'></i></a></span>";
    var pos = $("#replyoneHolder-" + logid).offset().top;
    $("html,body").animate({scrollTop: pos}, 1000);
    $("#replyoneHolder-" + logid).attr('tocommentid', id).html(longstr);
}
function cancelReplyOne(logid) {
    $("#replyoneHolder-" + logid).attr('tocommentid', '').html('');
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
function checkLogin() {
    if (typeof zmf.hasLogin === 'undefined') {
        return false;
    } else if (zmf.hasLogin === 'true') {
        return true;
    } else {
        return false;
    }
}
function zoomThis(dom){
    var type=dom.attr('action-type');
    var data=dom.attr('action-data');
    if(!type || !data){
        return false;
    }
    closeZoom();
    var longstr = '<div class="zoom-box" id="zoom-box"><div class="zoom-close" onclick="closeZoom()"><i class="fa fa-remove"></i></div><div class="zoom-holder"><div class="zoom-content" id="zoom-holder"></div></div></div>';
    $("body").append(longstr);    
    var html='';
    if(type=='img'){
        html='<img src="'+data+'"/>';
    }
    var zbdom=$('#zoom-box');
    var w=$(window).width();
    var h=$(window).height();
    $('#zoom-holder').css({
        width:w,
        height:h-20-15
    }).html(html);
    
    zbdom.css({
        height:h-20
    }).animate({
        bottom:0
    },300);
    
}
function closeZoom(){
    var dom=$('#zoom-box');
    dom.animate({
        bottom:-1000
    },150,'linear',function(){
        dom.remove();
    });
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
!function (a, b, c, d) {
    var e = a(b);
    a.fn.lazyload = function (c) {
        function i() {
            var b = 0;
            f.each(function () {
                var c = a(this);
                if (!h.skip_invisible || "none" !== c.css("display"))
                    if (a.abovethetop(this, h) || a.leftofbegin(this, h))
                        ;
                    else if (a.belowthefold(this, h) || a.rightoffold(this, h)) {
                        if (++b > h.failure_limit)
                            return!1
                    } else
                        c.trigger("appear"), b = 0
            })
        }
        var g, f = this, h = {threshold: 0, failure_limit: 0, event: "scroll", effect: "show", container: b, data_attribute: "original", skip_invisible: !0, appear: null, load: null};
        return c && (d !== c.failurelimit && (c.failure_limit = c.failurelimit, delete c.failurelimit), d !== c.effectspeed && (c.effect_speed = c.effectspeed, delete c.effectspeed), a.extend(h, c)), g = h.container === d || h.container === b ? e : a(h.container), 0 === h.event.indexOf("scroll") && g.on(h.event, function () {
            return i()
        }), this.each(function () {
            var b = this, c = a(b);
            b.loaded = !1, c.one("appear", function () {
                if (!this.loaded) {
                    if (h.appear) {
                        var d = f.length;
                        h.appear.call(b, d, h)
                    }
                    a("<img />").on("load", function () {
                        var d, e;
                        c.hide().attr("src", c.data(h.data_attribute))[h.effect](h.effect_speed), b.loaded = !0, d = a.grep(f, function (a) {
                            return!a.loaded
                        }), f = a(d), h.load && (e = f.length, h.load.call(b, e, h))
                    }).attr("src", c.data(h.data_attribute))
                }
            }), 0 !== h.event.indexOf("scroll") && c.on(h.event, function () {
                b.loaded || c.trigger("appear")
            })
        }), e.on("resize", function () {
            i()
        }), /iphone|ipod|ipad.*os 5/gi.test(navigator.appVersion) && e.on("pageshow", function (b) {
            b = b.originalEvent || b, b.persisted && f.each(function () {
                a(this).trigger("appear")
            })
        }), a(b).on("load", function () {
            i()
        }), this
    }, a.belowthefold = function (c, f) {
        var g;
        return g = f.container === d || f.container === b ? e.height() + e.scrollTop() : a(f.container).offset().top + a(f.container).height(), g <= a(c).offset().top - f.threshold
    }, a.rightoffold = function (c, f) {
        var g;
        return g = f.container === d || f.container === b ? e.width() + e[0].scrollX : a(f.container).offset().left + a(f.container).width(), g <= a(c).offset().left - f.threshold
    }, a.abovethetop = function (c, f) {
        var g;
        return g = f.container === d || f.container === b ? e.scrollTop() : a(f.container).offset().top, g >= a(c).offset().top + f.threshold + a(c).height()
    }, a.leftofbegin = function (c, f) {
        var g;
        return g = f.container === d || f.container === b ? e[0].scrollX : a(f.container).offset().left, g >= a(c).offset().left + f.threshold + a(c).width()
    }, a.inviewport = function (b, c) {
        return!(a.rightoffold(b, c) || a.leftofbegin(b, c) || a.belowthefold(b, c) || a.abovethetop(b, c))
    }, a.extend(a.fn, {"below-the-fold": function (b) {
            return a.belowthefold(b, {threshold: 0})
        }, "above-the-top": function (b) {
            return!a.belowthefold(b, {threshold: 0})
        }, "right-of-screen": function (b) {
            return a.rightoffold(b, {threshold: 0})
        }, "left-of-screen": function (b) {
            return!a.rightoffold(b, {threshold: 0})
        }, "in-viewport": function (b) {
            return a.inviewport(b, {threshold: 0})
        }, "above-the-fold": function (b) {
            return!a.belowthefold(b, {threshold: 0})
        }, "right-of-fold": function (b) {
            return a.rightoffold(b, {threshold: 0})
        }, "left-of-fold": function (b) {
            return!a.rightoffold(b, {threshold: 0})
        }})
}($, window, document);

rebind();
getNotice();