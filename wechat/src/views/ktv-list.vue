<template>
    <div class="page-ktvlist" :class="{loading:loadingStatus==1}">
        <header class="masthead list-selector" v-el:masthead>
            <ul class="selector-triggers">
                <li :class="{active:activeLayer=='area'}" @click="activeLayer=activeLayer=='area'?false:'area'"><span class="selector-trigger"><span class="text">{{filterKey.name}}</span><span class="icon icon-arrow"></span></span></li>
                <li :class="{active:activeLayer=='sort'}" @click="activeLayer=activeLayer=='sort'?false:'sort'"><span class="selector-trigger"><span class="text">{{sortKey.name}}</span><span class="icon icon-arrow"></span></span></li>
                <li :class="{active:activeLayer=='event'}" @click="activeLayer=activeLayer=='event'?false:'event'"><span class="selector-trigger"><span class="text">优惠筛选</span><span class="icon icon-arrow"></span></span></li>
            </ul>
            <span class="search-trigger" @click="showSearchform"><span class="icon icon-search"></span></span>
            <form class="search-form" v-show="activeLayer=='search'" transition="searchform" @submit.prevent="doSearch">
                <input type="search" autocomplete="off" placeholder="搜索…" v-model="keyword" v-el:search-input>
                <button type="submit"><span class="icon icon-search"></span></button>
            </form>

            <div class="selector-droplist droplist-filter" v-show="activeLayer=='area'" transition="flip">
               <nav class="tab">
                    <a :class="{active:filterBy=='distance'}" @click="filterBy='distance'">附近</a>
                    <a :class="{active:filterBy=='area'}" @click="filterBy='area'">商区</a>
               </nav>

                <div class="filter-panels">
                    <ul class="distance-list" v-show="filterBy=='distance'">
                        <li v-for="distance in distanceList" :class="{active:filterKey==distance}" @click="filterKey=distance">{{distance.name}}</li>
                    </ul>

                    <ul class="area-list" v-show="filterBy=='area'">
                        <li v-for="area in areaList" :class="{active:filterKey==area}" @click="filterKey=area">{{area.name}}</li>
                    </ul>
                </div>
            </div>

            <div class="selector-droplist droplist-sort" v-show="activeLayer=='sort'" transition="flip">
                <ul>
                    <li v-for="type in sortTypes" :class="{active:sortKey==type}" @click="sortKey=type">{{type.name}}</li>
                </ul>
            </div>

            <div class="selector-droplist droplist-event" v-show="activeLayer=='event'" transition="flip">
                <ul class="event-list">
                    <li v-for="event in eventTypes" :class="{active:event.selected}" @click="event.selected=!event.selected">{{event.name}}</li>
                </ul>

                <span class="ok" @click="updateList()">确定</span>
            </div>
        </header>

        <div id="banner">
            <div class="banner-inner">
                <simple-slider id="banner-slider" :slides="banners" :click="handleBanner" :auto="true"></simple-slider>
            </div>
        </div>

        <section class="ktv-list-container">
            <ul class="ktv-list">
                <li v-for="ktv in list | orderBy orderKey -1 | limitBy currLimit" :style="ktv.piclist[0].bigpicurl | backgroundImage" :data-weight="ktv.weight">
                    <a v-link="{ name: 'detail', params: { id: ktv.xktvid }}">
                        <span v-if="ktv.sjq" class="stamp stamp-djq"></span>
                        <span v-if="ktv.taocan" class="stamp stamp-goldpkg"></span>
                        <div class="content">
                            <h3 class="title">{{ktv.xktvname}}</h3>
                            <span class="rating"><span class="full"></span><span class="stars" :style="{width:ktv.rate*20+'%'}"></span></span>
                            <p class="address">{{ktv.address}}</p>
                            <span class="distance" v-if="ktv.distance != null">{{ktv.distance}}KM</span>
                        </div>
                    </a>
                </li>
            </ul>

            <loading v-show="loadingStatus==1" :inline-mode="list.length>0"></loading>
            <screen-message v-if="loadingStatus==-1" :inline-mode="list.length>0" message="加载失败, 点击重试" :info="errorMsg" @click="fetch"></screen-message>
            <screen-message v-if="loadingStatus==2&&list.length==0" message="结果为空"></screen-message>
        </section>

        <div class="dimmer" v-show="activeLayer" @click="activeLayer=false" transition="fade"></div>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";
@import "../scss/mixins";

.page-ktvlist {
    padding-top: 44px;

    .masthead {
        transition: .3s ease;

        &.hide {
            transform: translate3d(0, -100%, 0);
        }
    }

    &.loading {
        pointer-events: none;
    }
}

.list-selector {
    color: #EEE;

    .selector-triggers {
        font-size: 12px;
        margin-left: 4px;
        margin-right: 44px;

        li {
            position: relative;
            float: left;
            height: 44px;
            width: 33.3%;
            overflow: hidden;
            cursor: pointer;
            text-align: center;

            &:before,
            &:last-child:after {
                content: "";
                position: absolute;
                top: 50%;
                left: 0;
                width: 1px;
                height: 20px;
                background: rgba(0,0,0,.1);
                margin-top: -10px;
            }

            &:first-child:before {
                display: none;
            }

            &:last-child:after {
                left: auto;
                right: 0;
            }

            &.active {
                &:before,
                &:after,
                + li:before, {
                    display: none;
                }

                .selector-trigger {
                    background: #DE3341;
                    box-shadow: inset 0 1px 5px rgba(0,0,0,0.55);
                }
            }
        }
    }

    .selector-trigger {
        position: relative;
        display: block;
        height: 44px;
        line-height: 35px;
        padding: 0 8px;
        margin-top: 4px;

        .text {
            display: inline-block;
            max-width: 80%;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            vertical-align: middle;
        }

        .icon-arrow {
            @include rsprite($icon-dropdown-arrow-group);
            display: inline-block;
            position: relative;
            top: 1px;
            margin-left: .3em;
            vertical-align: middle;
        }
    }
}

.icon-search {
    @include rsprite($icon-search-group);
}

.search-trigger {
    width: 44px;
    height: 44px;
    float: right;
    text-align: center;

    .icon-search {
        margin-top: 12px;
    }
}

.search-form {
    background-image: linear-gradient(90deg, $main 0%, $mainDark 100%);
    height: 44px;
    padding: 6px;
    box-sizing: border-box;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    input {
        background: rgba(0,0,0,0.29);
        box-shadow: inset 0px 1px 4px 1px rgba(0,0,0,0.26);
        border-radius: 16px;
        width: 100%;
        height: 100%;
        padding-left: 10px;
        padding-right: 40px;
    }
    @include placeholder(rgba(255,255,255,0.4));

    button {
        position: absolute;
        top: 6px;
        right: 12px;
        width: 32px;
        height: 32px;
        opacity: .8;

        .icon-search {
            vertical-align: middle;
        }
    }
}

.searchform-transition {
    transition: .3s ease;

    &.searchform-enter,
    &.searchform-leave  {
        transform: translate3d(0, -100%, 0);
        opacity: 0;
    }
}

.flip-transition {
    transform: perspective(1000);
    transform-origin: 50% 0;
    transition: .3s ease;
    backface-visibility: hidden;

    &.flip-enter,
    &.flip-leave  {
        transform: perspective(1000) rotateX(-20deg);
        opacity: 0;
    }
}

.selector-droplist {
    position: absolute;
    background: $main;
    top: 100%;
    left: 0;
    width: 100%;
    box-shadow: 0px 10px 10px 0px rgba(0,0,0,0.92);

    .tab {
        height: 54px;
        width: 250px;
        margin: 0 auto;
        a {
            display: inline-block;
            width: 96px;
            height: 50px;
            line-height: 54px;
            font-size: 16px;
            text-align: center;
            &.active {
                color: #fff;
                border-bottom: 4px solid #FFF;
            }
            &:last-child {
                float: right;
            }
        }
    }

    ul {
        max-width: 320px;
        margin: 20px auto;
        overflow: hidden;
        clear: both;
    }

    li {
        border: 1px solid #E4E4E4;
        border-radius: 1px;
        line-height: 36px;
        float: left;
        padding: 0 11px;
        margin: 8px 13px;
        text-align: center;
        transition: .3s;
        cursor: pointer;

        &.active {
            background: #EDEDED;
            font-size: 14px;
            color: #4E4E4E;
            box-shadow: 0 3px 6px rgba(0,0,0,0.5);
        }
    }

    .distance-list {
        max-width: 280px;
        li {
            width: 5em;
            margin: 8px 20px;
        }
    }

    .area-list {
        li:nth-child(2) {
            clear: left;
        }
    }

    .event-list {
        li {
            margin-left: 8px;
            margin-right: 8px;
        }
    }

    .ok {
        display: block;
        background: $mainDark;
        line-height: 34px;
        text-align: center;
    }
}

.droplist-filter {
    li {
        width: 4em;
    }
    .filter-panels {
        border-top: 1px solid $borderDark;
    }
}

.dimmer {
    background: #000;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 2;
    opacity: .5;
}

#banner {
    background: #111;
    padding: 5px 0;
    display: block;

    .banner-inner {
        position: relative;
        padding-bottom: 25.78%;        
    }
}

#banner-slider {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    .slider-indicators {
        bottom: 5px;
    }
}
</style>

<script>
import utils from "../libs/utils";

export default {
    data() {
        let distanceList = [
            { distance: 500,  name: "离我500M" },
            { distance: 1000, name: "离我1KM" },
            { distance: 2000, name: "离我2KM" },
            { distance: 3000, name: "离我3KM" }
        ];
        let areaList = [
            { code: 0, name: "全部商区" }
        ];
        let sortTypes = [
            { code: "distance",         name: "离我最近" },
            { code: "smart",            name: "智能排序" },
            { code: "DecorationRating", name: "环境最佳" },
            { code: "SoundRating",      name: "音效最好" },
            // { code: "ConsumerRating",   name: "性价比最高" },
            { code: "ServiceRating",    name: "服务最好" },
            { code: "FoodRating",       name: "美食最佳" },
            { code: "responsetime",     name: "订单处理最快" }
        ];

        let eventTypes = [
            { code: "sjq", name: "兑酒券", selected: false },
            { code: "hjd", name: "黄金档", selected: false }
        ];

        return {
            banners: [{
                pic: "./assets/img/banner/20160623.jpg",
                link: "http://letsktv.chinacloudapp.cn/dist/oneyuan"
            }, {
                pic: "./assets/img/banner/20160620.jpg",
                link: "http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter"
            }],

            activeLayer: false,

            keyword: "",

            filterBy: "area",
            distanceList: distanceList,
            areaList: areaList,
            filterKey: areaList[0],

            sortTypes: sortTypes,
            sortKey: sortTypes[1],

            eventTypes: eventTypes,
            eventKey: null,

            list: [],

            type: this.$route.query.type || "",
            offset: 0,
            limit: 27,
            page: 0,
            perPage: 9,

            loadingStatus: 1, // -1:加载失败, 0:加载完毕, 1:正在加载, 2:已全部加载
            errorMsg: ""
        }
    },
    route: {
        data() {
            let vm = this;
            let query = this.$route.query;

            if (query.event == "jq") this.eventTypes[0].selected = true;
            if (query.event == "goldpkg") this.eventTypes[1].selected = true;

            utils.getLocation(null, null, function(){
                if (query.district) {
                    vm.getDistrictList(query.district);
                } else {
                    vm.fetch();
                    vm.getDistrictList();
                }
            });
        }
    },
    ready() {
        if (this.$route.query.fromTabBar) window.scrollTo(0, 0);

        let masthead = this.$els.masthead;
        let $win = $(window);
        let $doc = $(document);
        let winHeight = $win.height();

        $doc.on("touchstart.page", function(event) {
            let start = $win.scrollTop();

            $win.one("scroll", function(event) {
                let scrollTop = $win.scrollTop();
                let distance = scrollTop - start;

                if (scrollTop > 44 && distance > 0) {
                    masthead.classList.add("hide");
                } else {
                    masthead.classList.remove("hide");
                };
            });
        });

        $win.on("scroll", utils.throttle(() => {
            let scrollTop = $win.scrollTop();
            if (this.loadingStatus == 1) return false;
            if ($doc.height() - winHeight - scrollTop < 400) {
                this.fetch();
            };
            if (scrollTop < 44) {
                masthead.classList.remove("hide");
            };
        }, 100));
    },
    beforeDestroy() {
        $(document).off(".page");
        $(window).off("scroll");
    },
    methods: {
        buildQuery(source) {
            let query = {
                code: this.filterKey.code,
                best: this.sortKey.code == "smart" ? "distance" : this.sortKey.code,
                type: this.type,
                sjq: this.eventTypes[0].selected ? 1 : 0,
                taocan: this.eventTypes[1].selected ? 1 : 0
            };

            if (source) {
                Object.keys(source).forEach(key => {
                    query[key] = source[key];
                });
            };

            return query;
        },
        fetch() {
            if (((this.sortKey.code == "distance" || this.sortKey.code == "smart") && this.filterKey.code === 0) || this.filterKey.distance) {
                if (this.coords) {
                    this.fetchByDistance();
                } else {
                    this.fetchCoords(function() {
                        this.fetchByDistance();
                    });
                };
            } else {
                this.fetchList();
            };
        },
        fetchList() {
            if (this.offset > this.currLimit) {
                this.page++;
                return false;
            } else if (this.loadingStatus == 2) {
                return false;
            };
            this.loadingStatus = 1;
            this.$api.get("booking/xktvlist", this.buildQuery({
                offset: this.offset,
                limit: this.limit,
            })).then(function (data) {
                if (data.total == 0) {
                    this.loadingStatus = 2;
                    return false;
                };
                let isSmartSort = this.sortKey.code == "smart";
                data.list.forEach(item => {
                    item.distance = utils.getDistance(item.lat, item.lng);
                    if (isSmartSort) {
                        item.weight = utils.calcWeight(item, this.sortKey.code);
                    } else {
                        item.weight = 1e4 - item.distance;
                    }
                });
                if (isSmartSort && utils.getLocation(true) && this.page == 0) data.list[0].weight = 9e3;
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
        },
        fetchByDistance() {
            if (this.offset > this.currLimit) {
                this.page++;
                return false;
            } else if (this.loadingStatus == 2) {
                return false;
            };
            this.loadingStatus = 1;

            let items = this.coords;

            if (this.eventTypes[0].selected) {
                items = items.filter(item => item.sjq);
            };
            if (this.eventTypes[1].selected) {
                items = items.filter(item => item.taocan);
            };

            if (this.filterKey.distance) {
                let distanceInKM = this.filterKey.distance / 1000;
                items = items.filter(item => item.distance < distanceInKM);
            };

            items = items.slice(this.offset, this.offset + this.limit);

            this.$api.post("booking/xktvlist", this.buildQuery({
                list: items.map(item => item.xktvid),
            })).then(function (data) {
                if (data.total == 0) {
                    this.loadingStatus = 2;
                    return false;
                };
                let isSmartSort = this.sortKey.code == "smart";
                data.list.forEach(item => {
                    item.distance = utils.getDistance(item.lat, item.lng);
                    if (isSmartSort) {
                        item.weight = utils.calcWeight(item);
                    } else {
                        item.weight = 1e4 - item.distance;
                    }
                });
                if (isSmartSort && utils.getLocation(true) && this.page == 0) data.list[0].weight = 9e3;
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
        },
        fetchCoords(callback) {
            this.loadingStatus = 1;
            this.$api.get("booking/xktvcoords").then(function(data) {
                data.list.forEach(function(item) {
                    item.distance = utils.getDistance(item.lat, item.lng);
                });
                data.list.sort(function(a, b) {
                    return a.distance - b.distance;
                });
                this.coords = data.list;
            }, function(data) {
                throw new Error(data.msg);
            }).then(callback, function(error) {
                this.errorMsg = error.message;
                this.loadingStatus = -1;
            });
        },
        getDistrictList(districtCode) {
            this.$api.get("booking/Xktvdistrict", {
                parent: 440100
            }).then(function(data) {
                this.areaList = this.areaList.concat(data.list);
                if (districtCode) {
                    for (let i=1,len=this.areaList.length;i<len;i++) {
                        let area = this.areaList[i];
                        if (area.code == districtCode) {
                            this.filterKey = area;
                            break;
                        }
                    }
                };
            }, function(data) {
                this.errorMsg = data.msg;
            });
        },
        updateList() {
            this.activeLayer = false;
            this.loadingStatus = 0;
            this.list = [];
            this.page = this.offset = 0;
            this.fetch();
        },
        showSearchform() {
            this.activeLayer = "search";

            this.$nextTick(function() {
                this.$els.searchInput.focus();
            });
        },
        doSearch() {
            document.body.classList.remove("no-scroll");
            
            this.$router.go({
                name: "search",
                params: {
                    keyword: this.keyword
                }
            });
        },
        handleBanner(link) {
            this.$trackEvent("banner", "click", link);
            setTimeout(function(){
                location.href = link;
            }, 300);
        }
    },
    computed: {
        currLimit() {
            return this.page * this.perPage;
        },
        orderKey() {
            return (this.sortKey.code == "distance" || this.sortKey.code == "smart") ? "weight" : null;
        }
    },
    watch: {
        filterKey(value) {
            if (!!value.code && this.sortKey.code == "distance") {
                this.sortKey = this.sortTypes[0];
            };
            this.updateList();
        },
        sortKey: "updateList",
        activeLayer(show) {
            document.body.classList.toggle("no-scroll", show);
        }
    }
}
</script>