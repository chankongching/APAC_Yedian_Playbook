<template>
    <div class="page-ktvlist" :class="{loading:loadingStatus==1}">
        <header class="masthead list-selector" v-el:masthead>
            <ul class="selector-triggers">
                <li :class="{active:activeLayer=='area'}" @click="activeLayer=activeLayer=='area'?false:'area'"><span class="selector-trigger"><span class="text">{{areaText}}</span><span class="icon icon-arrow"></span></span></li>
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
                    <a :class="{active:activePanel=='city'}" @click="activePanel='city'">城市</a>
                    <a :class="{active:activePanel=='area'}" @click="activePanel='area'">商区</a>
                    <a :class="{active:activePanel=='distance'}" @click="activePanel='distance'">附近</a>
               </nav>

                <div class="filter-panels">
                    <ul class="city-list" v-show="activePanel=='city'">
                        <li class="gps" :class="{na:!gpsCity.code,active:activeCity==gpsCity}" @click="activeCity=gpsCity,activePanel='area'">GPS定位：{{gpsCity.name}}</li>

                        <li v-for="city in cityList" :class="{active:activeCity==city}" @click="activeCity=city,activePanel='area'">{{city.name}}</li>
                    </ul>

                    <ul class="area-list" v-show="activePanel=='area'">
                        <li class="all" :class="{active:filterKey==allAreas}" @click="filterKey=allAreas,activeLayer=false">{{allAreas.name}}</li>

                        <li v-for="area in areaList" :class="{active:filterKey==area}" @click="filterKey=area,activeLayer=false">{{area.name}}</li>
                    </ul>

                    <ul class="distance-list" v-show="activePanel=='distance'">
                        <li v-for="distance in distanceList" :class="{active:filterKey==distance}" @click="filterKey=distance,activeLayer=false">{{distance.name}}</li>
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

                <span class="ok" @click="activeLayer=false,updateList()">确定</span>
            </div>
        </header>

        <div id="banner">
            <div class="banner-inner">
                <simple-slider id="banner-slider" :slides="banners" :click="handleBanner" :auto="true"></simple-slider>
            </div>
        </div>

        <section class="ktv-list-container">
            <ul class="ktv-list">
                <li v-if="stickKtv" :style="stickKtv.pic | backgroundImage">
                    <a @click="$handleLink(stickKtv.link, 'stick')">
                        <div class="content">
                            <h3 class="title">{{stickKtv.event_name}}</h3>
                            <span class="rating"><span class="full"></span><span class="stars" :style="{width:stickKtv.rating*20+'%'}"></span></span>
                            <p class="address">{{stickKtv.address}}</p>
                            <span class="distance" v-if="stickKtv.distance != null">{{stickKtv.distance}}KM</span>
                        </div>
                    </a>
                </li>
                <li v-for="ktv in list | orderBy orderKey -1 | limitBy currLimit" track-by="xktvid" :style="ktv.piclist[0].bigpicurl | backgroundImage">
                    <a v-link="{ name: 'detail', params: { id: ktv.xktvid }}">
                        <span v-if="ktv.taocan" class="stamp stamp-goldpkg"></span>
                        <div class="content">
                            <div class="marks">
                                <span v-if="ktv.sjq" class="mark mark-djq"></span>
                                <span v-if="ktv.online_pay" class="mark mark-zxf"></span>
                            </div>
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
        width: 300px;
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

    .city-list {
        .gps {
            width: auto;
        }

        .na {
            opacity: 0.5;
            pointer-events: none;
        }
    }

    .city-list .gps,
    .area-list .all {
         + li {
            clear: left;
        }
    }

    .distance-list {
        max-width: 280px;
        li {
            width: 5em;
            margin: 8px 20px;
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
        overflow: auto;
        @include scrollable;
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
import store from "../libs/store";
import Vue from "vue";

export default {
    data() {
        let distanceList = [
            { distance: 500,  name: "离我500M" },
            { distance: 1000, name: "离我1KM" },
            { distance: 2000, name: "离我2KM" },
            { distance: 3000, name: "离我3KM" }
        ];

        let allAreasObj = { code: 0, name: "全部商区" };

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
            banners: store.baseinfo.banner.lists || [],
            stickKtv: null,

            activeLayer: false,
            activePanel: "area",

            keyword: "",

            cityList: [],
            gpsCity: {
                name: "正在定位…"
            },
            activeCity: null,

            distanceList: distanceList,
            areaList: [],
            allAreas: allAreasObj,
            filterKey: allAreasObj,

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
            let query = this.$route.query;

            if (query.event == "jq") this.eventTypes[0].selected = true;
            if (query.event == "goldpkg") this.eventTypes[1].selected = true;

            utils.getLocation(null, null, coords => {
                this.getCityList().then(function() {
                    if (store.city) {
                        this.activeCity = this.cityList.filter(item => item.code == store.city)[0];
                        this.gpsCity.name = store.gpsCity;
                    } else {
                        return this.reverseGeocodeLocation(coords).then(function(city) {
                            if (this.isCityAvailable(city)) {
                                city.code = this.cityList.filter(item => item.name == city.name)[0].code;
                                this.activeCity = this.gpsCity = city;
                            } else {
                                store.gpsCity = this.gpsCity.name = city.name;
                                throw new Error("检测到您当前城市没有可预订的KTV，是否手动切换城市？");
                            }
                        }, error => {
                            store.gpsCity = this.gpsCity.name = "定位失败";
                            throw new Error("系统检测不到当前位置信息，是否需要手动切换城市？");
                        }).catch(error => {
                            this.activeCity = this.cityList[0];

                            if (this.cityList.length > 1 && confirm(error.message)) {
                                this.activePanel = "city";
                                this.activeLayer = "area";
                            }
                        });
                    }
                }).then(function(){
                    this.getDistrictList().then(function() {
                        let districtCode = query.district || store.district;

                        if (districtCode) {
                            let found = false;
                            let area;

                            for (let i=0,len=this.areaList.length; i<len; i++) {
                                area = this.areaList[i];

                                if (area.code == districtCode) {
                                    this.filterKey = area;
                                    found = true;
                                    break;
                                }
                            }

                            if (found) return;
                        }

                        this.fetch();
                    });

                    this.$watch("activeCity", function(value) {
                        this.fetchCoords().then(function() {
                            if (!this.filterKey.distance && this.filterKey.code !== this.allAreas.code) {
                                this.filterKey = this.allAreas;
                            } else {
                                store.city = this.activeCity.code;
                                store.district = this.filterKey.code;
                                this.updateList();
                            }
                        });
                        this.getDistrictList();
                    });
                })
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

        $(".filter-panels").css("max-height", winHeight - 152);
    },
    beforeDestroy() {
        $(document).off(".page");
        $(window).off("scroll");
    },
    methods: {
        buildQuery(source) {
            let query = {
                city: this.activeCity.code,
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
                    this.fetchCoords().then(function() {
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
            this.$api.get("booking/Xktvlist", this.buildQuery({
                offset: this.offset,
                limit: this.limit,
            }), true).then(function (data) {
                if (data.total == 0) {
                    this.loadingStatus = 2;
                    return false;
                };

                if (data.event && !this.stickKtv) {
                    data.event.distance = utils.getDistance(data.event.lat, data.event.lng);
                    this.stickKtv = data.event;
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

            let items = this.coords || [];

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

            this.$api.post("booking/Xktvlist", this.buildQuery({
                list: items.map(item => item.xktvid),
            }), true).then(function (data) {
                if (data.total == 0) {
                    this.loadingStatus = 2;
                    return false;
                };

                if (data.event && !this.stickKtv) {
                    data.event.distance = utils.getDistance(data.event.lat, data.event.lng);
                    this.stickKtv = data.event;
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
        fetchCoords() {
            this.loadingStatus = 1;
            
            return this.$api.get("booking/xktvcoords", {
                city: this.activeCity.code
            }, true).then(function(data) {
                data.list.forEach(function(item) {
                    item.distance = utils.getDistance(item.lat, item.lng);
                });
                data.list.sort(function(a, b) {
                    return a.distance - b.distance;
                });
                this.coords = data.list;
                this.loadingStatus = 0;
            }, function(data) {
                this.errorMsg = data.message;
                this.loadingStatus = -1;
            });
        },
        getCityList() {
            return this.$api.get("booking/CityList", {}, true).then(function(data) {
                let list = data.lists.map(function(item) {
                    return {
                        name: item.name,
                        code: item.area_id
                    }
                });
                this.cityList = list;
            });
        },
        getDistrictList() {
            return this.$api.get("booking/Xktvdistrict", {
                parent: this.activeCity.code
            }, true).then(function(data) {
                this.areaList = data.list;
            });
        },
        updateList() {
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
                query: {
                    q: this.keyword,
                    city: this.activeCity.code
                }
            });
        },
        handleBanner(link) {
            this.$handleLink(link, "banner");
        },
        reverseGeocodeLocation(coords) {
            if (!coords) return Vue.Promise.reject(new Error("missing parameter"));

            return this.$http.jsonp("http://apis.map.qq.com/ws/geocoder/v1/", {
                params: {
                    key: "4QMBZ-YW5AR-AAZWF-WXXP2-QWJ3V-3DFGN",
                    location: coords.lat + "," + coords.lng,
                    get_poi: 0,
                    output: "jsonp"
                }
            }).then(function({data}) {
                if (data.status === 0 && data.result.ad_info.adcode) {
                    return {
                        name: data.result.ad_info.city,
                        code: data.result.ad_info.adcode
                    };
                } else {
                    throw new Error(data.message);
                }
            });
        },
        isCityAvailable(target) {
            return this.cityList.some(city => target.name == city.name);
        }
    },
    computed: {
        areaText() {
            if (this.filterKey.distance) {
                return this.filterKey.name;
            } else {
                return this.activeCity ? (this.filterKey.code ? this.filterKey.name : this.activeCity.name) : "城市-商区";
            }
        },
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
            if (value.code) {
                store.city = this.activeCity.code;
                store.district = value.code;
            };
            this.updateList();
        },
        sortKey() {
            this.activeLayer = false;
            this.updateList();
        },
        activeLayer(show) {
            document.body.classList.toggle("no-scroll", show);
        }
    }
}
</script>