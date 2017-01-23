//选项卡切换
function setTab(name,cursel,n){
                for(i=1;i<=n;i++){
                var menu=document.getElementById(name+i);
                var con=document.getElementById("con_"+name+"_"+i);
                menu.className=i==cursel?"active":"";
                con.style.display=i==cursel?"block":"none";
                }
                }
function resizeLeft()
{
    if ($(".layout_rightmain") && $(".layout_leftnav")) {
        var leftHeight = $(".layout_rightmain").height();
        $(".layout_leftnav").css('height', leftHeight);
    }
}
//模拟下拉菜单
$(document).ready(function(){
        $(".btn-select, .i-num, .h-outer, .table-select").click(function(event){   
                event.stopPropagation();
                $(this).find(".option").toggle();
        });
        $(document).click(function(event){
                var eo=$(event.target);
                if($(".btn-select, .i-num, .h-outer, .table-select").is(":visible") && eo.attr("class")!="option" && !eo.parent(".option").length)
                $('.option').hide();									  
        });
        /*赋值给文本框*/
        $(".option a").click(function(){
                var value=$(this).text();
                $(this).parent().siblings(".select-txt").text(value);
                $(this).parent().parent().next("#select_value").val(value)
         })

     $(".accordion").find("a").first().bind("click", function(){
         if ($(".nav-vertical").css("width") == "46px") {
             $(".nav-vertical").css("width", "195px");
             $(this).css("width", "149px");
             if ($(".layout_leftnav")) {
                 $(".layout_leftnav").css("width", "195px");
             }
             if ($(".layout_rightmain")) {
                 $(".layout_rightmain").css("margin-left", "210px");
             }
         }
         else {
             $(".nav-vertical").css("width", "46px");
             $(this).css("width", "0px");
             if ($(".layout_leftnav")) {
                 $(".layout_leftnav").css("width", "46px");
             }
             if ($(".layout_rightmain")) {
                 $(".layout_rightmain").css("margin-left", "61px");
             }
         }
         $(this).next().hide();
         resizeLeft();   
     }); 
     $(".accordion").find("a").mouseenter(function(){
         if($(this).css("width") == '0px') {
             $(this).next().show();
         }
     }); 
     $(".accordion").find("a").mouseleave(function(){
         if($(this).css("width") == '0px') {
             $(this).next().hide();
         }
     });
     
     resizeLeft();
})
//媒体-轮播图片
$(function () {
    var sWidth = $("#focus").width();
    var len = $("#focus ul li").length;
    var index = 0;
    var picTimer;
    var btn = "<div class='btnBg'></div><div class='btn'>";
    for (var i = 0; i < len; i++) {
        btn += "<span></span>";
    }
    btn += "</div><div class='preNext pre'></div><div class='preNext next'></div>";
    $("#focus").append(btn);
    $("#focus .btnBg").css("opacity", 0);
    $("#focus .btn span").css("opacity", 0.4).mouseenter(function () {
        index = $("#focus .btn span").index(this);
        showPics(index);
    }).eq(0).trigger("mouseenter");
    $("#focus .preNext").css("opacity", 0.0).hover(function () {
        $(this).stop(true, false).animate({ "opacity": "0.5" }, 300);
    }, function () {
        $(this).stop(true, false).animate({ "opacity": "0" }, 300);
    });
    $("#focus .pre").click(function () {
        index -= 1;
        if (index == -1) { index = len - 1; }
        showPics(index);
    });
    $("#focus .next").click(function () {
        index += 1;
        if (index == len) { index = 0; }
        showPics(index);
    });
    $("#focus ul").css("width", sWidth * (len));
    $("#focus").hover(function () {
        clearInterval(picTimer);
    }, function () {
        picTimer = setInterval(function () {
            showPics(index);
            index++;
            if (index == len) { index = 0; }
        }, 2800);
    }).trigger("mouseleave");
    function showPics(index) {
        var nowLeft = -index * sWidth;
        $("#focus ul").stop(true, false).animate({ "left": nowLeft }, 300);
        $("#focus .btn span").stop(true, false).animate({ "opacity": "0.4" }, 300).eq(index).stop(true, false).animate({ "opacity": "1" }, 300);
    }
});
