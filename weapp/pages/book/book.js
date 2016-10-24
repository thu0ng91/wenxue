//获取应用实例
var app = getApp()
//调用接口
const api = require( '../../utils/api.js' )

Page( {
  data: {
    list: [],
    loading: false,
    windowHeight: 0,
    windowWidth: 0,
    imgHeight:0,
    imgWidth:0
  },
  onLoad: function() {
    this.setData({
      loading: false
    })
    var that=this
    api.request('book/all',null, (err, res) => {
        console.log(res)
      //更新数据
      this.setData({
        list: this.data.list.concat(res.posts), 
        loading: true
      })
      this.index=1
      wx.setNavigationBarTitle({
        title: '书城'
      })
    })
       
  },
  onShow: function(e) {
    wx.getSystemInfo({
      success: (res) => {
        this.setData({
          windowHeight: res.windowHeight,
          windowWidth: res.windowWidth,
          imgHeight: (res.windowWidth-12)*0.5625,//res.windowHeight
          imgWidth: res.windowWidth-12
        })
      }
    })    
  },
  pullDownRefresh: function(e) {
    // 下拉刷新，重新加载数据； 
    // TODO，不重新加载，只更新对应列表；
    //this.onLoad()
  },
  pullUpLoad: function (e) {
    if (this.data.list.length === 0) return;
    this.setData({
      loading: false
    })
    api.request('book/all',{page: ++this.index}, (err, res) => {
      //更新数据
      this.setData({list: this.data.list.concat(res.posts), loading: true})
    })
  },
  bindViewTap:function(options){
    wx.navigateTo({
      url: '../detail/detail?bid='+options.bid
    })
  }
})
