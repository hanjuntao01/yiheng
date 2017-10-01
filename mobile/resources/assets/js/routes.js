import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  // mode: 'history',
  routes: [
    {path: '/', name: 'home', component: require('./pages/Home')},
    {path: '/catelog', name: 'catelog', component: require('./pages/Catelog')},
    {path: '/category', name: 'category', component: require('./pages/Category')},
    {path: '/search', name: 'search', component: require('./pages/Search')},
    {path: '/goods', name: 'goods', component: require('./pages/Goods')},
    {path: '/cart', name: 'cart', component: require('./pages/Cart')},
    {path: '/checkout', name: 'checkout', component: require('./pages/Checkout')},
    {path: '/done', name: 'done', component: require('./pages/Done')},
    {path: '/respond', name: 'respond', component: require('./pages/Respond')},
    // activity
    {path: '/activity', name: 'activity', component: require('./pages/activity/Activity')},
    {path: '/activity/detail', name: 'activity-detail', component: require('./pages/activity/Detail')},
    // article
    {path: '/article', name: 'article', component: require('./pages/article/Article')},
    {path: '/article/detail', name: 'article-detail', component: require('./pages/article/Detail')},
    {path: '/article/wechat', name: 'article-wechat', component: require('./pages/article/Wechat')},
    // auction
    {path: '/auction', name: 'auction', component: require('./pages/auction/Auction')},
    {path: '/auction/detail', name: 'auction-detail', component: require('./pages/auction/Detail')},
    {path: '/auction/log', name: 'auction-log', component: require('./pages/auction/Log')},
    // brand
    {path: '/brand', name: 'brand', component: require('./pages/brand/Brand')},
    {path: '/brand/detail', name: 'brand-detail', component: require('./pages/brand/Detail')},
    /*
    // chat
    {path: '/chat', name: 'chat', component: require('./pages/chat/Chat')},
    // coupon
    {path: '/coupon', name: 'coupon', component: require('./pages/coupon/Coupon')},
    // crowd funding
    {path: '/crowd-funding', name: 'crowd-funding', component: require('./pages/crowd-funding/CrowdFunding')},
    {path: '/crowd-funding/detail', name: 'crowd-funding-detail', component: require('./pages/crowd-funding/Detail')},
    {path: '/crowd-funding/checkout', name: 'crowd-funding-checkout', component: require('./pages/crowd-funding/Checkout')},
    {path: '/crowd-funding/done', name: 'crowd-funding-done', component: require('./pages/crowd-funding/Done')},
    // exchange
    {path: '/exchange', name: 'exchange', component: require('./pages/exchange/Exchange')},
    {path: '/exchange/detail', name: 'exchange-detail', component: require('./pages/exchange/Detail')},
    // forum
    {path: '/forum', name: 'forum', component: require('./pages/forum/Forum')},
    {path: '/forum/list', name: 'forum-list', component: require('./pages/forum/List')},
    {path: '/forum/detail', name: 'forum-detail', component: require('./pages/forum/Detail')},
    {path: '/forum/me', name: 'forum-me', component: require('./pages/forum/Me')},
    {path: '/forum/reply', name: 'forum-reply', component: require('./pages/forum/Reply')},
    {path: '/forum/new', name: 'forum-new', component: require('./pages/forum/New')},
    // group buy
    {path: '/group-buy', name: 'group-buy', component: require('./pages/group-buy/GroupBuy')},
    {path: '/group-buy/detail', name: 'group-buy-detail', component: require('./pages/group-buy/Detail')},
    // location
    {path: '/location', name: 'location', component: require('./pages/location/Location')},
    // located
    {path: '/located', name: 'located', component: require('./pages/located/Located')},
    // oauth
    {path: '/oauth', name: 'oauth', component: require('./pages/oauth/OAuth')},
    // store
    {path: '/store', name: 'store', component: require('./pages/store/Store')},
    {path: '/store/detail', name: 'store-detail', component: require('./pages/store/Detail')},
    {path: '/store/map', name: 'store-map', component: require('./pages/store/Map')},
    // package
    {path: '/package', name: 'package', component: require('./pages/package/Package')},
    // presale
    {path: '/presale', name: 'presale', component: require('./pages/presale/Presale')},
    {path: '/presale/detail', name: 'presale-detail', component: require('./pages/presale/Detail')},
    // shop
    {path: '/shop', name: 'shop', component: require('./pages/shop/Shop')},
    {path: '/shop/detail', name: 'shop-detail', component: require('./pages/shop/Detail')},
    {path: '/shop/goods', name: 'shop-goods', component: require('./pages/shop/Goods')},
    {path: '/shop/about', name: 'shop-about', component: require('./pages/shop/About')},
    {path: '/shop/map', name: 'shop-map', component: require('./pages/shop/Map')},
    {path: '/shop/nearby', name: 'shop-nearby', component: require('./pages/shop/Nearby')},
    // topic
    {path: '/topic', name: 'topic', component: require('./pages/topic/Topic')},
    {path: '/topic/detail', name: 'topic-detail', component: require('./pages/topic/Detail')},
    // wholesale
    {path: '/wholesale', name: 'wholesale', component: require('./pages/wholesale/Wholesale')},
    {path: '/wholesale/detail', name: 'wholesale-detail', component: require('./pages/wholesale/Detail')},
    {path: '/wholesale/cart', name: 'wholesale-cart', component: require('./pages/wholesale/Cart')},
    // user auth
    {path: '/register', name: 'register', component: require('./pages/user/auth/Register')},
    {path: '/login', name: 'login', component: require('./pages/user/auth/Login')},
    {path: '/forgot', name: 'forgot', component: require('./pages/user/auth/Forgot')},
    {path: '/reset', name: 'reset', component: require('./pages/user/auth/Reset')},
    // user center
    {path: '/user/account', name: 'user-account', component: require('./pages/user/account/Account')},
    {path: '/user/address', name: 'user-address', component: require('./pages/user/address/Address')},
    {path: '/user/affiliate', name: 'user-affiliate', component: require('./pages/user/affiliate/Affiliate')},
    {path: '/user/booking', name: 'user-booking', component: require('./pages/user/booking/Booking')},
    {path: '/user/collection', name: 'user-collection', component: require('./pages/user/collection/Collection')},
    {path: '/user/comment', name: 'user-comment', component: require('./pages/user/comment/Comment')},
    {path: '/user/message', name: 'user-message', component: require('./pages/user/message/Message')},
    {path: '/user/order', name: 'user-order', component: require('./pages/user/order/Order')},
    {path: '/user/order', name: 'user-order-detail', component: require('./pages/user/order/Detail')},
    {path: '/user/profile', name: 'user-profile', component: require('./pages/user/profile/Profile')},
    {path: '/user/refound', name: 'user-refound', component: require('./pages/user/refound/Refound')},
    // qrpay
    {path: '/paycode', name: 'paycode', component: require('./pages/qrpay/Paycode')}
    */
  ]
})
