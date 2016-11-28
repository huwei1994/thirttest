// 刷新组件
var myScroll,
pullDownEl, pullDownOffset,
pullUpEl, pullUpOffset,
generatedCount = 0;

// 数据源数组  page 分页页数  post_id 分页id
var dataSoure,page = 1,post_id = 1;

// 下拉刷新
function pullDownAction () {
    setTimeout(function () {    // <-- Simulate network congestion, remove setTimeout from production!
        page =1;
        if (dataSoure != undefined && dataSoure.length > 0) {
         post_id =dataSoure[0].id;
     }
     requestDataSoureData(null,null,true);
        // myScroll.refresh();     // Remember to refresh when contents are loaded (ie: on ajax completion)
    }, 1000);   // <-- Simulate network congestion, remove setTimeout from production!
}

// 上拉加载
function pullUpAction () {
    setTimeout(function () {    // <-- Simulate network congestion, remove setTimeout from production!
        if (dataSoure != undefined && dataSoure.length > 0) {
        // alert('shanglajiazai');
        post_id =dataSoure[dataSoure.length-1].id;
    }
    requestDataSoureData(null,null,false);
    // myScroll.refresh();     // Remember to refresh when contents are loaded (ie: on ajax completion)
    }, 1000);   // <-- Simulate network congestion, remove setTimeout from production!
}

// 刷新中
function loaded() {
    pullDownEl = document.getElementById('pullDown');
    pullDownOffset = pullDownEl.offsetHeight;
    pullUpEl = document.getElementById('pullUp');   
    pullUpOffset = pullUpEl.offsetHeight;
    
    myScroll = new iScroll('wrapper', {
        useTransition: true,
        topOffset: 45,
        // useTransition: true,
        // 刷新中回调的函数
        onRefresh: function () {
            if (pullDownEl.className.match('loading')) {
                pullDownEl.className = '';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉重新加载...';
            } else if (pullUpEl.className.match('loading')) {
                pullUpEl.className = '';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
            }
        },
        // 滑动中回调的函数
        onScrollMove: function () {
            if (this.y > 5 && !pullDownEl.className.match('flip')) {
                pullDownEl.className = 'flip';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '正在刷新中...';
                this.minScrollY = 0;
            } else if (this.y < 5 && pullDownEl.className.match('flip')) {
                pullDownEl.className = '';
                // pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Pull down to refresh...';
                this.minScrollY = -pullDownOffset;
            } else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
                pullUpEl.className = 'flip';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '松手开始刷新...';
                this.maxScrollY = this.maxScrollY;
            } else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
                pullUpEl.className = '';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '停止拖动,开始刷新...';
                this.maxScrollY = pullUpOffset;
            }
        },
        // 结束滑动回调的函数
        onScrollEnd: function () {
            if (pullDownEl.className.match('flip')) {
                pullDownEl.className = 'loading';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '正在加载中...';                
                pullDownAction();   // Execute custom function (ajax call?)
            } else if (pullUpEl.className.match('flip')) {
                pullUpEl.className = 'loading';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '正在加载中...';                
                pullUpAction(); // Execute custom function (ajax call?)
            }
        }

    });

    // 设置刷新图片 Label 居中 
    var pull_mar_left =$(window).width()/2- (($('.pullDownLabel').width()+25)/2);
    $('.pullDownIcon').css({'margin-left':pull_mar_left});
    $('.pullUpIcon').css({'margin-left':pull_mar_left});

    setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
}

//html Dom加载完成
window.onload =function(){
    // 默认搜索框影藏
    $(".nav-search").hide(true,500);
    $("#head-search-btn").click(function(){
        // 点击影藏按钮 展示搜索框
        $(".nav-normal").hide(true,500);
        $(".nav-search").show();    
    });

    // 添加刷新组件
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    loaded();

// document.addEventListener('DOMContentLoaded', function () { 
//     // setTimeout(loaded, 200);
//     loaded();
//      }, false);
updateLabelCSS();
    // 加载数据    
    requestDataSoureData();

    // $('#noSpinner').live( 'tap', function() {  
    // $.mobile.loadingMessageTextVisible = true;  
    //     $.mobile.showPageLoadingMsg( 'a', "Please wait...", true );  
    // } );  
}

// 修改部分标签css
function updateLabelCSS() {
    // 修改title 显示宽度
    var screenWidth =$(window).width();
    $('.item-title').css({'width':screen-95});
    $('.item-subtitle').css({'width':screen-95});
}

// 点击搜索btn
function searchButtonClick() {
    page =1;
    var search_text =$('#search-text').val();
    requestDataSoureData(null,search_text,true);

    //成功提示绑定
}

/*
*   request_url url
*   is_search   是否是刷新
*   search_key  搜索关键字
*   is_pull     是否是下拉刷新
*   获取列表数据
*/
function requestDataSoureData(request_url,search_key,is_pull) {

     var params; // 参数
     // 判断request_url 是否为空 为空的话默认是list接口
     if (request_url == null) {
        request_url ='/scms/api/post/list';
    };
     // 判断search_key 是否为空 如果不为空 是搜索
     if (search_key != null) {
         params ={'page':page,"length":'20','keywords':search_key,'post_id':post_id};
     }else {
         params ={'page':page,"length":'20','post_id':post_id};
     };
     // alert(request_url + 'page ='+ page +'post_id ='+ post_id + 'search_key =' +search_key);
     $.ajax({
        url:request_url,
        type:'POST',
        data:params,
        dataType:'JSON',
        success:function(response,status,xhr){
            // console.log('请求成功后执行');
            if (response.retcode == 1) {
                    // 如果数据源数组为空 初始化数据源数组
                    if (dataSoure == undefined) {
                        dataSoure =new Array;
                    }
                    // alert(response.retcode);
                    appendHtml(response.data.posts,search_key);
                } else{
                    alert(response.info);
                };
                myScroll.refresh();
                setPullUpRefreshState(dataSoure);
            },
            error:function(xhr,errorText,errorType){
                alert('加载失败'+ errorText+errorType);
                myScroll.refresh();
                setPullUpRefreshState(dataSoure);
            },
            timeout:3000
        });
 }

/*
 *  html 数据拼装
 *  dataSoure_arr 数据源数组
 *  isPullDown 是否是下拉刷新
 */
 function appendHtml(dataSoure_arr,is_search) {
    if ((dataSoure != undefined) && (dataSoure_arr.length > 0)) {
        page ++;
    }
    //获取列表容器
    var  contentList =$('#thelist');
     //下拉加载
     if (is_search != null) {
        contentList.html('');
        dataSoure.splice(0,dataSoure.length);
    } 

    var html = '';
    for(var i in dataSoure_arr){
        var data_model =dataSoure_arr[i];
        dataSoure.push(data_model);
        var title_class,subTitle_class,image_class;
        var is_image =imageUrlIsNull(data_model.preview_image);
        title_class =is_image == true ? 'item-title':'item-title-screen';
        subTitle_class =is_image == true ? 'item-subtitle':'item-subtitle-screen';
        image_class =is_image == true ? 'item-image':'item-image-hide';

        console.log(image_class);
        html += '<li>'
        +'<div data-role ="content" class="item-content-class">'
        +'<p class='+title_class+'>' +data_model.title+'</p>'
        +'<img src="'+data_model.preview_image+'" class="'+image_class+'">'
        +'<p class='+subTitle_class+'>' +data_model.summary+'</p>'
        +'<p class="item-date">'+timeTransformation(data_model.published_time,true)+'</p>'
        +'</div>';
        +'</li>'
    }
    $('#thelist').append(html);
    setPullUpRefreshState(dataSoure);

      // 遍历所有的li添加click事件
      $('#thelist').find('li').each(function(i){
          $(this).click(function(){
            window.location.href ="http://www.baidu.com";
        });
      });
  }

// 当前显示数目小于5 影藏上拉加载
// count 数据源数组数量
function setPullUpRefreshState(data_arr) {
    if (data_arr == undefined || data_arr == null || data_arr.length < 5) {
       $('#pullUp').hide();
   } else {
       $('#pullUp').show();
   };
}

$(function(){
    //参数设置，若用默认值可以省略以下面代
    toastr.options = {
        "closeButton": true, //是否显示关闭按钮
        "debug": false, //是否使用debug模式
        "positionClass": "toast-top-full-width",//弹出窗的位置
        "showDuration": "1000",//显示的动画时间
        "hideDuration": "1000",//消失的动画时间
        "timeOut": "5000", //展现时间
        "extendedTimeOut": "1000",//加长展示时间
        "showEasing": "swing",//显示时的动画缓冲方式
        "hideEasing": "linear",//消失时的动画缓冲方式
        "showMethod": "fadeIn",//显示时的动画方式
        "hideMethod": "fadeOut" //消失时的动画方式
    };
})

// 时间戳转时间 
// time 时间戳
// is pull 是否更新到分秒
function timeTransformation(time,isPull) {
    return $.myTime.UnixToDate(time,isPull,8);
} 

// 列表图片链接是否为空
function imageUrlIsNull(image_url) {
   if ((image_url !=null) && (image_url != undefined) && (image_url != '') && (image_url.length >=0)) {
        return true;
   }
   return false;
}

//显示加载器  
function showLoader() {  
    //显示加载器.for jQuery Mobile 1.1.0  
    $.mobile.loadingMessage = '加载中...';     //显示的文字  
    $.mobile.loadingMessageTextVisible = true; //是否显示文字  
    $.mobile.loadingMessageTheme = 'a';        //加载器主题样式a-e  
    $.mobile.showPageLoadingMsg();             //显示加载器  
}  

//隐藏加载器.for jQuery Mobile 1.1.0  
function hideLoader() {  
    //隐藏加载器   
    $.mobile.hidePageLoadingMsg();  
}  
