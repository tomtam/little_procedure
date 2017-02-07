接口名称：活动列表页面
接口地址：/api/campaign/list
方式：post
参数：
    locationName   热门地区，多个地区以“|”分割，默认是''
    campType       热门种类id，多个地区以“|”分割，默认是''
    keyword        搜索关键字，默认是''
    page           页数，默认是1
返回值：json
    code   200是正常值，非200会在info字段里有报错信息。
    info   接口信息
    data   接口数据，code值非200时，无此字段。正常值时包含以下俩字段：
        list    活动列表
            id  活动id  int
            title  活动id  string
            destination  目的地  string
            rendezvous  集合地  string
            price       价格  float
            origin      来源  string
            totalNum    总数  int
            createTime  创建时间  int
            beginTime   开始时间  int
            endTime     结束时间  int
            dayNum      持续时间  int  单位：天
            locationName  所属城市  string
            headImg     列表页面显示的小图片  string
        img     轮播图
            id  活动id  int
            img  图片url  string
        hotArea  热门地区
            locationName  活动地区名称 string
        campTypeArr  活动主题种类
            areaId => areaName  string
        
接口名称：活动详情
接口地址：/api/campaign/detail
方式：post
参数：
    id  活动id  int
    page  评价页码，默认1  int  
返回值：json
    imageArr  图片数组 
        content  图片名称  string
    lineIntroduction  线路介绍   string
    scheduling        行程安排   string
    expenseExplanation  费用说明  string
    moreIntroduction  更多介绍  string
    evaluateArr  评论数据数组
        starLevel  星级数量  int
        content    评论      string
        createTime 创建时间  int
        userId     用户userId  string
    price  价格  float
    destination  目的地  string
    beginTime   开始时间  int
    endTime     结束时间  int

接口名称：创建订单
接口地址：/order/
    
