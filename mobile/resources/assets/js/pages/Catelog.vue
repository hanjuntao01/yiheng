<template>
    <div class="catelog">
        <van-search placeholder="搜索商品/店铺" @search="goSearch" @change="handleChange" @cancel="handleCancel"></van-search>

        <van-tree-select
                :items="items"
                :main-active-index="mainActiveIndex"
                :active-id="activeId"
                @navclick="onNavClick"
                @itemclick="onItemClick"
        ></van-tree-select>

        <router-link :to="{path:'/category', query:{id:1}}" tag="a">
            男装&女装
        </router-link>
    </div>
</template>

<script>
    import Vue from 'vue'
    import {Search, TreeSelect} from 'vant'

    Vue.component(Search.name, Search);
    Vue.component(TreeSelect.name, TreeSelect);

    export default {
        name: 'catelog',
        data: function () {
            return {
                items: [],
                // 左侧高亮元素的index
                mainActiveIndex: 0,
                // 被选中元素的id
                activeId: 0
            };
        },
        mounted() {
            this.items = [
                {
                    // 导航名称
                    text: '所有城市',
                    // 该导航下所有的可选项
                    children: [
                        {
                            // 可选项的名称
                            text: '温州',
                            // 可选项的id，高亮的时候是根据id是否和选中的id是否相同进行判断的
                            id: 1002
                        },
                        {
                            // 可选项的名称
                            text: '杭州',
                            // 可选项的id，高亮的时候是根据id是否和选中的id是否相同进行判断的
                            id: 1001
                        }
                    ]
                }
            ]
        },
        methods: {
            goSearch(value) {
                alert(value)
            },
            handleChange(value) {
                console.log(value);
            },
            handleCancel() {
                alert('cancel');
            },
            onNavClick(index) {
                this.mainActiveIndex = index;
            },
            onItemClick(data) {
                console.log(data);
                this.activeId = data.id;
            }
        }
    }
</script>
