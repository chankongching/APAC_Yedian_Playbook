<template>
	<div class="page-favorite">
		<ul class="ktv-list">
            <li v-for="ktv in list" :style="ktv.piclist[0].bigpicurl | backgroundImage">
                <a v-link="{ name: 'detail', params: { id: ktv.xktvid }}">
                    <div class="content">
                        <h3 class="title">{{ktv.xktvname}}</h3>
                        <span class="rating"><span class="full"></span><span class="stars" :style="{width:ktv.rate*20+'%'}"></span></span>
                        <p class="address">{{ktv.address}}</p>
                        <span class="distance" v-if="ktv | distance">{{ktv | distance}}KM</span>
                    </div>
                </a>
            </li>
        </ul>

        <loading v-show="loadingStatus==1" :inline-mode="list.length>0"></loading>
        <screen-message v-if="loadingStatus==-1" :inline-mode="list.length>0" message="加载失败, 点击重试" :info="errorMsg" @click="fetch"></screen-message>
        <screen-message v-if="loadingStatus==2&&list.length==0" message="收藏为空"></screen-message>
	</div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";
</style>

<script>
import utils from "../libs/utils";

export default {
    data() {
        return {
        	list: [],

            offset: 0,
            limit: 27,
            page: 0,
            perPage: 9,

            loadingStatus: 1,
            errorMsg: ""
        }
    },
    route: {
        data() {
            utils.getLocation(null, null, this.fetch);
        }
    },
    ready() {
        let $win = $(window);
        let $doc = $(document);
        let winHeight = $win.height();

        $win.on("scroll", utils.throttle(() => {
            if (this.loadingStatus == 1) return false;
            if ($doc.height() - winHeight - $win.scrollTop() < 400) {
                this.fetch();
            };
        }, 100));
    },
    beforeDestroy() {
        $(window).off("scroll");
    },
    methods: {
        fetch() {
            if (this.offset > this.currLimit) {
                this.page++;
                return false;
            } else if (this.loadingStatus == 2) {
                return false;
            };
            this.loadingStatus = 1;
            this.$api.get("user/collectionlist", {
                offset: this.offset,
                limit: this.limit
            }).then(function (data) {
                if (data.total == 0) {
                    this.loadingStatus = 2;
                    return false;
                };
                this.list = this.list.concat(data.list);
                this.offset += data.total;
                this.page++;
                if (data.total < this.limit) {
                    this.loadingStatus = 2;
                } else {
                    this.loadingStatus = 0;
                };
            }, function (data) {
                this.errorMsg = data.msg;
                this.loadingStatus = -1;
            });
        }
    }
}
</script>