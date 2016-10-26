//获取应用实例
var app = getApp()
//调用接口
const api = require( '../../utils/api.js' )
const WxParse = require('../../wxParse/wxParse.js');
Page( {
  data: {
    data: [],
    loading: false,
    wxParseData:[]
  },
  onLoad: function(options) {
    this.setData({
        loading: false
    })
    var that=this
    api.request('posts/detail',{id:options.tid}, (err, res) => {
        console.log(res)
      //更新数据
      this.setData({
        data: res, 
        loading: true,
        wxParseData:WxParse('html',res.info.content.content)
      })
      this.index=1
      wx.setNavigationBarTitle({
        title: '详情'
      })
    })
  }
})
