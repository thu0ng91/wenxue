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
    $("a[action=get-contents]").unbind('click').click(function () {
        var dom = $(this);
        getContents(dom);
    });
    $("a[action=favorite]").unbind('click').click(function () {
        var dom = $(this);
        favorite(dom);
    });
    $("a[action=vote]").unbind('click').click(function () {
        cancelBindLink();
        var dom = $(this);        
        addVote(dom);        
    });
    $("a[action=add-comment]").click(function () {
        var dom = $(this);
        addComment(dom);
    });
    $("a[action=zoom]").click(function () {
        var dom = $(this);
        zoomThis(dom);
    });
    $(".comment-textarea").unbind('click').click(function () {        
        setTimeout("scollToComment()", 500);
    });
    $("img.lazy").lazyload({
        threshold:600
    });
    bindLink();    
}
function bindLink(){
    $('.ui-grid-full li,.ui-list li,.ui-row li,.ui-avatar,.ui-col').unbind('click').click(function () {
        if ($(this).attr('data-href')) {
            location.href = $(this).attr('data-href');
        }
    });
}

function cancelBindLink(){
    $('.ui-grid-full li,.ui-list li,.ui-row li,.ui-avatar,.ui-col').unbind('click');
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
function favorite(dom) {
    var acdata = dom.attr("action-data");
    var t = dom.attr("action-type");
    var dt = dom.text();
    var num = parseInt(dt);
    if (!acdata || !t) {
        return false;
    }
    if (!checkLogin()) {
        //没有登录，判断是否包含fa-heart样式，包含则认为已收藏成功过
        if (dom.children('i').hasClass('fa-heart')) {
            dialog({msg: '已点赞', modalSize: 'modal-sm'});
            return false;
        }
    }
    if (!checkAjax()) {
        return false;
    }
    $.post(zmf.favoriteUrl, {type: t, data: acdata, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
        ajaxReturn = true;
        result = $.parseJSON(result);
        if (result.status === 1) {//收藏成功
            //dom.text((num + 1) + ' 赞').removeClass('btn-default').addClass('btn-success');
            dom.children('i').removeClass('fa-heart-o').addClass('fa-heart');
        } else if (result.status === 2) {//收藏失败
            //dom.text(dt);
            dialog({msg: result.msg});
        } else if (result.status === 3) {//取消成功
            //dom.text((num - 1) + ' 赞').removeClass('btn-success').addClass('btn-default');
            dom.children('i').removeClass('fa-heart').addClass('fa-heart-o');
        } else if (result.status === 4) {//取消失败
            //dom.text(dt);
            dialog({msg: result.msg});
        } else {
            dialog({msg: result.msg});
        }
        return false;
    });
}
function playVideo(company, videoid, targetHolder, dom) {
    var w=$(window).width()-10;//有边框
    var h=w*9/16;
    if (!company || !videoid || !targetHolder) {
        return false;
    }
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
function replyOne(id, logid, title) {
    var longstr = "<span class='reply-one'>回复“" + title + "”<a href='javascript:' onclick='cancelReplyOne(" + logid + ")' title='取消设置'> <i class='ui-icon-close-page'></i></a></span>";
    var pos = $("#replyoneHolder-" + logid).offset().top;
    $("html,body").animate({scrollTop: pos}, 1000);
    $("#replyoneHolder-" + logid).attr('tocommentid', id).html(longstr);
}
function cancelReplyOne(logid) {
    $("#replyoneHolder-" + logid).attr('tocommentid', '').html('');
}
function showChapters() {
    var h = document.documentElement.clientHeight;
    var w = document.documentElement.clientWidth;
    var d = window.orientation;
    var btnsDom = $('#next-prev-btns');
    if (Math.abs(d) === 0 && h >= w) {
        //dialog({msg:'横屏效果更佳'});
        //btnsDom.fadeOut(500);
    } else {

    }
    var btnsW = btnsDom.width();
    btnsDom.css({
        left: (w - btnsW) / 2
    }).fadeIn(500);
}
function toggleChapters() {
    var dom = $('#chapters-box');
    var html = dom.html();
    dialog({msg: html, title: '跳转章节'});
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