//获取应用实例
var app = getApp()
//调用接口
const api = require( '../../utils/api.js' )

Page( {
  data: {
    data: [],
    loading: true    
  },
  onLoad: function(options) {
    this.setData({
      loading: false
    })
    var that=this
    api.request('book/chapter',{id:options.cid}, (err, res) => {
        console.log(res)
      //更新数据
      this.setData({
        data: res, 
        loading: true
      })
      this.index=1
      wx.setNavigationBarTitle({
        title: '章节'
      })
    })
  }
})
