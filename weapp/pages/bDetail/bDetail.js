//获取应用实例
var app = getApp()
//调用接口
const api = require( '../../utils/api.js' )

Page( {
  data: {
    data: [],
    loading: false
  },
  onLoad: function(options) {
    this.setData({
        loading: false
    })
    var that=this
    api.request('book/detail',{id:options.bid}, (err, res) => {
        console.log(res)
      //更新数据
      this.setData({
        data: res, 
        loading: true
      })
      this.index=1
      wx.setNavigationBarTitle({
        title: '详情'
      })
    })
  },
  chapterTap:function(options){
      wx.navigateTo({
        url: '../chapter/chapter?cid='+options.currentTarget.dataset.cid
      })
  }
})
