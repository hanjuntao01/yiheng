<template>
  <div class="paycode">
    <van-row style="text-align: center; margin-top: 3rem;">
      <van-icon name="receive-gift" style="text-align: center; font-size: 4rem; color: red;"></van-icon>
      <div style="margin-top: 1rem;">上海商创网络科技有限公司</div>
    </van-row>

    <van-row style="margin-top: 2rem;">
      <van-cell-group>
        <van-cell title=" 付款金额" icon="edit"></van-cell>
        <currency-input
          v-model="amount"
        ></currency-input>
      </van-cell-group>
    </van-row>

    <van-row style="margin-top: 2rem;">
      <van-col offset="2" span="20">
        <van-button type="primary" block v-on:click="doPay">立即支付</van-button>
      </van-col>
    </van-row>
  </div>
</template>

<script>
  import {Toast} from 'vant'
  import currencyInput from '@/components/Currency'

  export default {
    name: 'paycode',
    data: function () {
      return {
        amount: 0
      }
    },
    components: {
      'currency-input': currencyInput
    },
    methods: {
      doPay: function () {
        let that = this
        let amount = parseFloat(that.amount)
        if (isNaN(amount)) {
          Toast({
            message: '金额错误',
            forbidClick: true
          })
        }

        Toast.message(amount)
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .price {
    font-size: 3rem;
    height: 5rem;
    line-height: 4rem;
  }
</style>
