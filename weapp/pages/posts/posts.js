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
    api.request('posts/all',null, (err, res) => {
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
  threadTap:function(options){
      wx.navigateTo({
        url: '../pDetail/pDetail?tid='+options.currentTarget.dataset.tid
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
    api.request('posts/all',{page: ++this.index}, (err, res) => {
      //更新数据
      this.setData({list: this.data.list.concat(res.posts), loading: true})
    })
  },
})
