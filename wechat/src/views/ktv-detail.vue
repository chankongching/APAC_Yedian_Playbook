<template>
    <div class="page-ktvdetail">
        <simple-slider class="slider" :slides="ktv.piclist | formatSlides"></simple-slider>

        <section class="baseinfo">
            <span class="distance" v-if="ktv | distance">{{ktv | distance}}KM</span>
            <h2 class="name">{{ktv.xktvname}}</h2>
            <span class="rating"><span class="full"></span><span class="stars" :style="{width:ktv.rate*20+'%'}"></span></span>

            <div class="events" @click="showEventsLayer=true" v-if="ktv.taocan || ktv.sjq">
                <table>
                    <tbody>
                        <tr v-if="ktv.taocan">
                            <th><span class="icon icon-goldpkg"></span></th>
                            <td>夜点承诺始终提供价格最优的KTV黄金档套餐。通过夜点预订KTV，即可享受全城重点KTV的会员价优惠。<span class="more">更多>></span></td>
                        </tr>
                        <tr v-if="ktv.sjq">
                            <th><span class="icon icon-djq"></span></th>
                            <td>只要通过夜点预订KTV即可获得一张含6罐百威啤酒的二次兑酒券。下次预订KTV的时候即可使用。<span class="more">更多>></span></td>
                        </tr>
                        <tr v-if="ktv.online_pay">
                            <th><span class="icon icon-zxf"></span></th>
                            <td>无<span class="more">更多>></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <ul class="ranklist">
                <li><span class="icon icon-decoration"></span><span class="text">环境{{ktv.DecorationRating}}星</span></li>
                <li><span class="icon icon-sound"></span><span class="text">音效{{ktv.SoundRating}}星</span></li>
                <li><span class="icon icon-service"></span><span class="text">服务{{ktv.ServiceRating}}星</span></li>
                <!--li><span class="icon icon-consumer"></span><span class="text">消费{{ktv.ConsumerRating}}星</span></li-->
                <li><span class="icon icon-food"></span><span class="text">美食{{ktv.FoodRating}}星</span></li>
            </ul>

            <p class="desc">{{ktv.description}}</p>

            <ul class="services" v-if="ktv.devices">
                <li v-for="device in ktv.devices | limitBy deviceLimit"><span class="icon" :class="'icon-'+device.toLowerCase()"></span><span class="text">{{device | deviceName}}</span></li>
                <li class="more" v-if="ktv.devices.length>4" @click="$refs.servicesModal.open()">+{{ktv.devices.length-3}}<span class="icon icon-more"></span></li>
            </ul>
        </section>

        <list-view class="bg" v-if="ktv.xktvid">
            <li><a :href="'tel:'+ktv.telephone"><span class="icon lineicon-phone"></span>{{ktv.telephone}}</a></li>
            <li class="item-address"><a @click="openMap"><span class="icon lineicon-location"></span><span class="value">{{ktv.address}}</span></a></li>
        </list-view>

        <section class="business" v-if="ktv.openhours">
            <span class="openhours">营业时间 周一至周日 {{ktv.openhours}}</span>
            <span class="responsetime">平均等待时间：{{ktv.responsetime}}分钟</span>
        </section>

        <div v-if="tcInfo && tcInfo.courses.length && tcInfo.roomtypes.length">
            <h4 class="booking-title">KTV预订</h4>
            <div class="booking-table">
                <div class="booking-table-inner">
                    <div class="days">
                        <ul>
                            <li v-for="day in tcInfo.days" :class="{active:bookDay==day}" @click="bookDay=day">{{day.line1}}<em>{{day.line2}}</em></li>
                        </ul>
                    </div>

                    <ul class="courses">
                        <li v-for="course in filteredCourses | limitBy 3" :class="{active:bookCourse==course,disabled:course.expired}" @click="bookCourse=course">{{course.name}}<em>{{course.line2}}</em></li>
                        <li class="more" @click="$refs.coursesModal.open()"></li>
                    </ul>

                    <ul class="rooms">
                        <li v-for="room in tcInfo.roomtypes | limitBy 3" :class="{active:bookRoomType==room}" @click="bookRoomType=room">{{room.name}}<em>（{{room.desc}}）</em></li>
                        <li class="more" @click="$refs.roomtypesModal.open()"></li>
                    </ul>
                </div>
            </div>

            <div class="booking-packages" :class="{loading:loadingPackages}">
                <div class="goldpkg" v-if="ktv.taocan && packages.length">
                    <h4 class="packages-title"><span></span></h4>
                    <ul>
                        <li v-for="pkg in packages | limitBy packagesLimit" :class="{active:bookPkg==pkg}" @click="bookPkg=pkg">
                            <span class="check"></span>
                            <h5 class="name">{{pkg.pre_txt}}{{pkg.name}}</h5>
                            <div class="secrow">
                                <span v-if="ktv.online_pay">
                                    <span class="price price-dd">到店价¥{{pkg.price_yd}}</span>
                                    <span class="price price-online">在线价¥{{pkg.price_yd_online}}</span>
                                </span>
                                <span v-else>
                                    <span class="price price-orig">原价{{pkg.price}}</span>
                                    <span class="price price-yd">夜点价¥{{pkg.price_yd}}</span>
                                </span>
                                <button type="button" class="btn btn-booking" @click="goBook(pkg)">预订</button>
                            </div>
                        </li>
                    </ul>
                    <div class="more" @click="packagesLimit=99" v-if="packages.length>3 && packagesLimit==3">更多</div>
                    <div class="less" @click="packagesLimit=3" v-if="packagesLimit==99">收起</div>
                </div>
                <div class="haunchangpkg" v-if="hcPackages.length">
                    <h4 class="packages-title huanchang"><span></span></h4>
                    <ul>
                        <li v-for="pkg in hcPackages | limitBy hcPackagesLimit" :class="{active:bookPkg==pkg}" @click="bookPkg=pkg">
                            <span class="check"></span>
                            <h5 class="name">{{pkg.name}}</h5>
                            <button type="button" class="btn btn-booking" @click="goBook(pkg)">预订</button>
                        </li>
                    </ul>
                    <div class="more" @click="hcPackagesLimit=99" v-if="hcPackages.length>3 && hcPackagesLimit==3">更多</div>
                    <div class="less" @click="hcPackagesLimit=3" v-if="hcPackagesLimit==99">收起</div>
                </div>
            </div>

            <button type="button" class="btn-float btn-book" :disabled="!bookPkg" @click="goBook"><span></span></button>

            <modal id="courses-modal" title="选择时间段" :closeable="false" v-ref:courses-modal>
                <list-view>
                    <li v-for="course in tcInfo.courses | orderBy 'weight'" :class="{active:bookCourse==course,disabled:course.expired}" @click="bookCourse=course">{{course.name}} {{course.line2}}</li>
                </list-view>
            </modal>

            <modal id="roomtypes-modal" title="选择房间类型" :closeable="false" v-ref:roomtypes-modal>
                <list-view>
                    <li v-for="room in tcInfo.roomtypes | orderBy 'weight'" :class="{active:bookRoomType==room}" @click="bookRoomType=room">{{room.name}} {{room.desc}}</li>
                </list-view>
            </modal>
        </div>

        <list-view class="bg" v-if="ktv.xktvid">
            <li><a @click="doFavorite"><span class="icon lineicon-favorite"></span>收藏</a></li>
            <li><a @click="$refs.feedbackModal.open()"><span class="icon lineicon-feedback"></span>纠错</a></li>
        </list-view>

        <div id="events-layer" class="flash-message" v-if="showEventsLayer" transition="fade" v-el:events-layer>
            <div class="flash-message-dialog">
                <ul>
                    <li class="section" v-if="ktv.taocan">
                        <span class="icon icon-goldpkg"></span>
                        <h4>最强黄金档说明</h4>
                        <ul>
                            <li>夜点承诺始终提供价格最优的KTV黄金档套餐。通过夜点预订KTV，即可享受全城重点KTV的会员价优惠。</li>
                        </ul>
                    </li>
                    <li class="section" v-if="ktv.sjq">
                        <span class="icon icon-djq"></span>
                        <h4>兑酒券使用说明</h4>
                        <ol>
                            <li>在预订KTV的时候选择使用兑酒券，到店后，由前台收银员扫描订单中的二维码，即可确认到店并获得相应数量的啤酒。</li>
                            <li>每次预订只能使用一张兑酒券。</li>
                            <li>啤酒的品牌和数量以兑酒券的标识为准。</li>
                            <li>兑酒券的使用时间以兑酒券的标识为准。</li>
                            <li>通过完成订单所获得的兑酒券，次日可用，有效期14天。</li>
                            <li>以任何形式取消订单，兑酒券都将会返还到用户账户，下次预订还可继续使用。</li>
                            <li>每位用户每天可使用两张兑酒券。</li>
                        </ol>
                    </li>
                    <li class="section" v-if="ktv.online_pay">
                        <span class="icon icon-zxf"></span>
                        <h4>在线付说明</h4>
                        <ol>
                            <li>无</li>
                        </ol>
                    </li>
                    <li class="section" v-if="ktv.taocan">
                        <h4>预订说明</h4>
                        <ul>
                            <li>通过夜点预订KTV，只需要在夜点提交订单，到达KTV现场支付，即可享受夜点最强黄金档的价格优惠。</li>
                        </ul>
                    </li>
                </ul>
                <button type="button" class="btn" @click="showEventsLayer=false">确定</button>
            </div>
        </div>

        <a href="http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter" class="feb" v-track-link.literal="FEB,Click,兑酒券疯抢中"></a>

        <modal id="favorite-modal" class="msg" v-ref:favorite-modal>
            <span class="icon icon-favorite"></span>
            <p>{{ktv.xktvname}}</p>
            <p v-show="favorite">已添加至 <a>我的收藏</a></p>
            <p v-else>已取消收藏</p>
        </modal>

        <modal id="services-modal" :show-btn="false" v-if="ktv.devices" v-ref:services-modal>
            <table>
                <tbody>
                    <tr v-for="device in ktv.devices">
                        <td class="col-icon"><span class="icon" :class="'icon-'+device.toLowerCase()"></span></td>
                        <td class="col-name">{{device | deviceName}}</td>
                    </tr>
                </tbody>
            </table>
        </modal>

        <modal id="feedback-modal" :btn-disabled="selectedErrors.length==0" :submit="submitFeedback" btn-text="提交" v-ref:feedback-modal>
            <list-view>
                <li v-for="error in feedbackErrors"><a :class="{selected:error.selected==true}" @click="error.selected=!error.selected"><span class="icon" :class="'icon-'+error.id"></span>{{error.msg}}</a></li>
            </list-view>
        </modal>

        <flash-message v-ref:flash-message></flash-message>

        <loading v-if="loadingStatus==1"></loading>
        <screen-message v-if="loadingStatus==-1" message="加载失败, 点击重试" :info="errorMsg" @click="fetch"></screen-message>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";
@import "../scss/svg-sprite";
@import "../scss/mixins";

.page-ktvdetail {
    margin-bottom: 74px;

    .slider {
        height: 200px;
        background: $mainDarker url(../img/icon-xktv@2x.png) no-repeat 50%;
        background-size: 70px 70px;
    }

    .baseinfo {
        margin: 30px 20px;
        font-size: 12px;

        .name {
            font-size: 16px;
            color: #fff;
            min-height: 1em;
            margin-bottom: 10px;
        }

        .rating {
            width: 80px;
            height: 12px;
            display: inline-block;
            margin-bottom: 20px;
            position: relative;

            .full,
            .stars {
                @include rsprite($icon-stars-group, false);
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .full {
                opacity: .5;
            }
        }

        .distance {
            color: #fff;
            line-height: 22px;
            padding: 0 8px;
            border: 1px solid $border;
            border-radius: 11px;
            float: right;
        }

        .events {
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid $border;

            th, td {
                padding: 8px 0;
            }

            th {
                width: 100px;
                padding-right: 10px;
                color: #fff;
                font-weight: normal;
                text-align: center;
            }

            .icon {
                display: inline-block;
                @extend %sprite;
            }
            .icon-goldpkg { @include rsprite($stamp-goldpkg-group, true, false); }
            .icon-djq { @include svg-icon($mark-djq-noborder); }
            .icon-zxf { @include svg-icon($mark-zxf-noborder); }

            td {
                line-height: 1.5;
            }

            .more {
                display: block;
                text-align: right;
                line-height: 1;
            }
        }

        .ranklist {
            margin-bottom: 20px;
            overflow: hidden;

            li {
                text-align: center;
                width: 25%;
                float: left;
            }
            .icon {
                @extend %sprite;
                width: 26px;
                height: 26px;
                vertical-align: top;
                margin-bottom: 15px;
            }
            .text {
                display: block;
                color: #fff;
            }
        }
        .icon-decoration { @include sprite-position($ratingicon-decoration); }
        .icon-sound { @include sprite-position($ratingicon-sound); }
        .icon-service { @include sprite-position($ratingicon-service); }
        .icon-consumer { @include sprite-position($ratingicon-consumer); }
        .icon-food { @include sprite-position($ratingicon-food); }

        .desc {
            line-height: 1.5;
            font-weight: lighter;
            margin: 0 0 20px;
        }

        .services {
            display: table;
            width: 100%;
            font-weight: lighter;
            margin-bottom: 20px;

            li {
                display: table-cell;
                vertical-align: middle;
                text-align: center;
                width: 25%;

                &.more {
                    font-size: 30px;
                    font-weight: normal;
                    .icon {
                        @include rsprite($serviceicon-more-group);
                        vertical-align: middle;
                        margin-left: 7px;
                    }
                }
            }
            .icon {
                @extend %sprite;
                width: 31px;
                height: 31px;
                vertical-align: top;
                margin-bottom: 7px;
            }
            .text {
                display: block;
                font-size: 12px;
            }
        }
        .icon-wifi { @include sprite-position($serviceicon-wifi); }
        .icon-themerooms { @include sprite-position($serviceicon-themerooms); }
        .icon-xktv { @include sprite-position($serviceicon-xktv); }
        .icon-bathroom { @include sprite-position($serviceicon-bathroom); }
        .icon-yedianpad { @include sprite-position($serviceicon-yedianpad); }
        .icon-wirelessmicrophones { @include sprite-position($serviceicon-wirelessmicrophones); }
        .icon-parking { @include sprite-position($serviceicon-parking); }
        .icon-buffet { @include sprite-position($serviceicon-buffet); }
        .icon-tm { @include sprite-position($serviceicon-tm); }
        .icon-zxgy { @include sprite-position($serviceicon-zxgy); }
        .icon-sjdg { @include sprite-position($serviceicon-sjdg); }
        .icon-zqss { @include sprite-position($serviceicon-zqss); }
    }

    .business {
        border-top: 1px solid $border;
        border-bottom: 1px solid $border;
        line-height: 52px;
        overflow: hidden;
        text-align: center;
        font-size: 12px;
        margin-bottom: 30px;
        font-weight: lighter;
        color: #fff;

        .openhours {
            float: left;
            width: 60%;
            border-right: 1px solid $border;
            box-sizing: border-box;
        }
        .responsetime {
            width: 40%;
        }
    }

    > .list-view {
        font-weight: lighter;
        
        a {
            color: #fff;
        }

        .item-address .value {
            display: block;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
    }

    .btn-book {
        background-color: #900D1E;

        span {
            @include rsprite($btn-ljyd-group);
        }
    }

    .feb {
        position: fixed;
        bottom: 80px;
        right: 5px;
        @include rsprite($feb-djq-group);
    }
}

#services-modal {
    .modal-body {
        margin-left: 50px;
        margin-right: 50px;
    }
    table {
        width: 100%;
    }
    td {
        padding: 0;
        height: 50px;
    }
    .col-icon {
        text-align: center;
        width: 1px;
        .icon {
            @extend %sprite;
        }
    }
    .icon-wifi { @include rsprite($serviceicon-wifi-red-group, true, false); }
    .icon-themerooms { @include rsprite($serviceicon-themerooms-red-group, true, false); }
    .icon-xktv { @include rsprite($serviceicon-xktv-red-group, true, false); }
    .icon-bathroom { @include rsprite($serviceicon-bathroom-red-group, true, false); }
    .icon-yedianpad { @include rsprite($serviceicon-yedianpad-red-group, true, false); }
    .icon-wirelessmicrophones { @include rsprite($serviceicon-wirelessmicrophones-red-group, true, false); }
    .icon-parking { @include rsprite($serviceicon-parking-red-group, true, false); }
    .icon-buffet { @include rsprite($serviceicon-buffet-red-group, true, false); }
    .icon-tm { @include rsprite($serviceicon-tm-red-group, true, false); }
    .icon-zxgy { @include rsprite($serviceicon-zxgy-red-group, true, false); }
    .icon-sjdg { @include rsprite($serviceicon-sjdg-red-group, true, false); }
    .icon-zqss { @include rsprite($serviceicon-zqss-red-group, true, false); }

    .col-name {
        text-align: right;
        font-size: 16px;
    }
}

#feedback-modal {
    .list-view {
        a {
            &:after {
                margin-top: -5px;
                @include rsprite($icon-checked-group);
                display: none;
            }

            &.selected:after { 
                display: block;
            }
        }
    }
    .icon {
        left: 25px;
    }
    .icon-1 { @include sprite-position($feedbackicon-1); }
    .icon-2 { @include sprite-position($feedbackicon-2); }
    .icon-3 { @include sprite-position($feedbackicon-3); }
    .icon-4 { @include sprite-position($feedbackicon-4); }
    .icon-5 { @include sprite-position($feedbackicon-5); }
    .icon-6 {
        @include sprite-position($feedbackicon-6);
        width: 26px;
        height: 26px;
        left: 24px;
    }
}

#events-layer {
    color: #fff;
    font-size: 12px;

    .flash-message-dialog {
        width: 280px;
        text-align: left;
    }

    .section {
        padding-bottom: 15px;
        margin-bottom: 15px;
        line-height: 1.4;
        border-bottom: 1px solid rgba(#FFF, 0.2);

        h4 {
            font-size: 14px;
            margin-bottom: 12px;
            color: $main;
        }

        ol {
            list-style: inside decimal;
        }

        li {
            margin-bottom: 1em;

            &:last-child {
                margin-bottom: 0;
            }
        }

        &:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
    }
    .icon {
        display: block;
        margin: 0 auto 20px;
        @extend %sprite;
    }
    .icon-goldpkg { @include rsprite($stamp-goldpkg-group, true, false); }
    .icon-djq { @include svg-icon($mark-djq); }
    .icon-zxf { @include svg-icon($mark-zxf); }

    .btn {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    &.scroll {
        .flash-message-dialog {
            position: static;
            transform: none;

            > ul {
                position: absolute;
                top: 30px;
                left: 40px;
                right: 40px;
                bottom: 100px;
                overflow: auto;
                @include scrollable;
            }
        }
        .btn {
            position: absolute;
            bottom: 25px;
            left: 50%;
            margin-left: -55px;
        }
    }
}

.booking-title {
    margin-left: 10px;
    margin-bottom: 10px;
    padding-left: 5px;

    &:before {
        float: left;
        content: '';
        margin-right: 5px;
        margin-top: -5px;
        @include rsprite($icon-microphone-group);
    }
}
.booking-table {
    background: $mainDarker;
    overflow: hidden;
    font-size: 12px;

    .booking-table-inner {
        margin: 0 8px 10px;        
    }

    .days {
        margin: 0 -8px 10px;
        border-bottom: 2px solid $borderDark;

        ul {
            margin: 0 8px;
            height: 55px;
        }

        li {
            float: left;
            width: 14.285%;
            text-align: center;
            padding: 14px 0;

            em {
                display: block;
                font-style: normal;
                margin-top: 3px;
            }
        }
        .active {
            border-bottom: 2px solid #fff;
        }
    }

    .courses,
    .rooms {
        display: table;
        border-collapse: separate;
        border-spacing: 5px;
        width: 100%;
        height: 62px;

        li {
            display: table-cell;
            border: 1px solid #98252F;
            text-align: center;
            border-radius: 4px;
            vertical-align: middle;
            em {
                display: block;
                font-style: normal;
                margin: 4px auto 0;
                width: 8em;
            }
        }
        .disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        .more {
            width: 11%;

            &:after {
                content: "";
                display: block;
                margin: 0 auto;
                width: 4px;
                height: 4px;
                border-radius: 50%;
                background: #fff;
                box-shadow: -8px 0 0 #fff, 8px 0 0 #fff;
            }
        }
        .active {
            background: #98252F;
        }
    }
}

.packages-title {
    text-align: center;
    background: $mainDarker;
    padding: 8px 0;
    box-shadow: 0 0 15px rgba(#000, 0.2);

    span {
        display: block;
        margin: 0 auto;
        @include rsprite($title-zqhjd-group);
    }

    &.huanchang {
        span {
            @include rsprite($title-huanchang-group);
        }
    }
}
.booking-packages {
    margin-bottom: 50px;
    box-shadow: 0 10px 30px rgba(#000, 0.2);

    ul {
        margin-left: 25px;
    }
    li {
        position: relative;
        padding: 15px 20px 15px 60px;
        border-bottom: 1px solid $borderDark;
    }
    .check {
        position: absolute;
        top: 15px;
        left: 15px;
        border: 1px solid #fff;
        background: rgba(#fff, 0.5);
        width: 18px;
        height: 18px;
        border-radius: 50%;
        opacity: 0.7;
        transform: scale(0.7);
    }
    .name {
        font-size: 14px;
        margin-top: 1px;
        margin-bottom: 10px;
        line-height: 1.3;
    }
    .secrow {
        text-align: right;
    }
    .price {
        margin-left: .5em;

        &:first-child {
            margin-left: 0;
        }
    }
    .price-orig {
        font-size: 12px;
        text-decoration: line-through;
    }
    .price-dd {
        color: $brown;
        font-size: 12px;
    }
    .price-yd,
    .price-online {
        color: $brown;
    }
    .btn {
        border-radius: 3px;
        background: #900D1E;
        color: #FFF;
        width: 56px;
        height: 24px;
        margin-left: 30px;
        font-size: 12px;
        box-shadow: 0 1px 5px rgba(#000,0.2);
    }

    .active {
        .check {
            opacity: 1;
            transform: none;
            &:after {
                content: '';
                border: 1px solid #fff;
                border-top-width: 0;
                border-right-width: 0;
                width: 7px;
                height: 4px;
                transform: rotate(-45deg);
                float: left;
                margin: 5px 0 0 5px;
            }
        }
    }

    .more,
    .less {
        text-align: center;
        line-height: 40px;

        &:after {
            content: '';
            position: relative;
            top: -3px;
            display: inline-block;
            width: 6px;
            height: 6px;
            border-bottom: 2px solid #fff;
            border-right: 2px solid #fff;
            margin-left: 5px;
            transform: rotate(45deg);
            opacity: 0.8;
        }
    }
    .less {
        &:after {
            top: 1px;
            transform: rotate(-135deg);
        }
    }

    .haunchangpkg {
        .name {
            display: inline-block;
            margin-top: 1px;
            margin-bottom: 0;
        }
        .btn {
            margin-top: -2px;
            float: right;
        }
    }


    &.loading {
        pointer-events: none;
        opacity: 0.5;
    }
}

#courses-modal,
#roomtypes-modal {
    .list-view {
        li {
            display: block;
            color: #fff;
            border-left: 3px solid $main;
            line-height: 50px;
            margin-bottom: 5px;
            padding-left: 45px;
        }
        .active {
            background: $main;
        }
        .disabled {
            opacity: 0.5;
            pointer-events: none;
        }
    }
}

@media (max-width: 320px) {
    .booking-table {
        .courses,
        .rooms {
            li {
                em {
                    width: auto;
                }
            }
        }
    }
    .booking-packages {
        .btn {
            margin-left: 5px;
        }
    }
}
</style>

<script>
import utils from "../libs/utils";
import store from "../libs/store";

export default {
    data() {
        return {
            ktv: {},
            now: null,

            favorite: false,

            tcInfo:  null,
            bookDay: null,
            bookCourse: null,
            bookRoomType: null,
            bookPkg: null,

            loadingPackages: false,
            packages: [],
            packagesLimit: 3,
            hcPackages: [],
            hcPackagesLimit: 3,

            duration: 0,

            deviceLimit: 4,
            deviceNames: {
                bathroom           : "独立卫生间",
                yedianpad          : "夜点PAD",
                wirelessmicrophones: "无线麦克风",
                themerooms         : "主题包房",
                parking            : "停车场",
                xktv               : "XKTV触摸桌",
                wifi               : "免费WiFi",
                tm                 : "弹幕",
                zxgy               : "观影",
                sjdg               : "手机点歌",
                zqss               : "足球赛事"
            },

            feedbackErrors: [{
                id: 1,
                msg: "地址位置错误",
                selected: false,
            }, {
                id: 2,
                msg: "商户联系电话错误",
                selected: false,
            }, {
                id: 3,
                msg: "房型有误",
                selected: false,
            }, {
                id: 4,
                msg: "价格有误",
                selected: false,
            }, {
                id: 5,
                msg: "服务信息有误",
                selected: false,
            }, {
                id: 6,
                msg: "KTV已关闭",
                selected: false,
            }],

            showEventsLayer: false,

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
        window.scrollTo(0, 0);
    },
    methods: {
        fetch() {
            this.loadingStatus = 1;
            this.$api.get("booking/xktv", {
                xktvid: this.$route.params.id
            }).then(function (data) {
                this.time = data.now * 1e3;
                this.now = new Date(this.time);

                let todayDateString = this.now.toDateString();
                let yesterdayDateString = new Date(this.time - 86400000).toDateString();
                let daysText = "日一二三四五六".split("");
                let nowHour = this.now.getHours();
                let taocaninfo = this.taocaninfo = data.data.taocaninfo;

                delete data.data.taocaninfo;

                this.ktv = data.data;

                taocaninfo.roomtype.forEach(room => room.weight=parseInt(room.desc));

                this.tcInfo = {
                    days: taocaninfo.days.map((day, idx) => {
                        let date = new Date(day);
                        let dateString = date.toDateString();
                        return {
                            line1: todayDateString == dateString ? "今天" : yesterdayDateString == dateString ? "昨天" : "周" + daysText[date.getDay()],
                            line2: day.substring(5),
                            date: date,
                            id: idx,
                            today: todayDateString == dateString,
                            yesterday: yesterdayDateString == dateString
                        };
                    }),
                    courses: taocaninfo.course.map(course => {
                        course.line2 = "";
                        course.expired = false;
                        course.weight = parseInt(course.starttime.time) + (course.starttime.ciri ? 24 : 0);
                        course.crossday = course.starttime.ciri + course.endtime.ciri;
                        course.starttime = course.starttime.time;
                        course.endtime = course.endtime.time;

                        course.tableWeight = course.weight < 0 ? course.weight - 24 : 24 - course.weight;
                        if (course.is_hjd) course.tableWeight += 1000;
                        if (course.show) course.tableWeight += 100;

                        return course;
                    }).sort(function(a, b) {
                        return b.tableWeight - a.tableWeight;
                    }),
                    roomtypes: taocaninfo.roomtype.sort((a, b) => {
                        return b.show == a.show ? parseInt(a.desc) - parseInt(b.desc) : b.show - a.show;
                    })
                };
                this.bookDay = this.tcInfo.days[0];
                if (!this.filteredCourses.filter(course => !course.expired).length) {
                    this.tcInfo.days.shift();
                    this.bookDay = this.tcInfo.days[0];
                }
                this.bookRoomType = this.tcInfo.roomtypes.filter(room => room.show)[0];

                this.updatePackages();

                if (this.ktv.devices.length > 4) this.deviceLimit = 3;
                this.loadingStatus = 2;

                this.$wxShare({
                    title: this.ktv.xktvname,
                    desc: "我在夜点看到这个KTV不错哦，大家都来看看吧！",
                    titleTL: "我在夜点看到［" + this.ktv.xktvname + "］不错哦，大家都来看看吧！",
                    imgUrl: this.ktv.piclist[0].smallpicurl
                });
            }, function (data) {
                this.errorMsg = data.msg;
                this.loadingStatus = -1;
            });
        },
        openMap() {
            if (window.isWXReady) {
                wx.openLocation({
                    latitude: this.ktv.lat,
                    longitude: this.ktv.lng,
                    name: this.ktv.xktvname,
                    address: this.ktv.address,
                    scale: 14
                });
            } else {
                this.$router.go({
                    name: "map",
                    query: {
                        lat: this.ktv.lat,
                        lng: this.ktv.lng,
                        name: this.ktv.xktvname,
                        address: this.ktv.address
                    }
                });
            };
        },
        doFavorite() {
            let id = this.ktv.xktvid;
            let add = this.$user.collectionids.indexOf(id) == -1;

            this.$api.post("user/" + (add ? "addcollection" : "delcollection"), {
                "xktvid": id
            }).then(function(data) {
                if (add) {
                    this.$user.collectionids += "," + id;
                } else {
                    this.$user.collectionids = this.$user.collectionids.replace(id, "");
                };
                this.favorite = add;
                this.$refs.favoriteModal.open();
            }, function(data) {
                alert(data.msg || "提交失败");
            });
        },
        submitFeedback() {
            let errorIDs = this.selectedErrors.map(error => error.id);

            this.$api.post("feedback/feedback", {
                "ktvid": this.ktv.xktvid,
                "openid": this.$user.openid,
                "errortype": errorIDs.join(",")
            }).then(function(data) {
                this.$refs.feedbackModal.close();
                this.$refs.flashMessage.show("smile", "<p>感谢您的反馈<br>我们会对您提出的问题尽快做出调整<br>再次谢谢您对夜点娱乐的支持！</p>");
                this.feedbackErrors.forEach(error => {error.selected = false});
            }, function(data) {
                alert(data.msg || "提交失败");
            });
        },
        updatePackages: utils.debounce(function(){
            if (this.loadingPackages || !this.bookCourse || !this.bookRoomType) return false;

            this.bookPkg = null;

            let packages = [];
            let startTime;

            if (this.bookDay.yesterday) {
                startTime = this.nowTime;
            } else if (this.bookDay.today) {
                if (this.bookCourse.crossday == 2) {
                    startTime = utils.minTime(this.nowTime, this.bookCourse.starttime);
                } else {
                    startTime = utils.maxTime(this.nowTime, this.bookCourse.starttime);
                }
            } else {
                startTime = this.bookCourse.starttime;
            }
    
            let startMinute = parseInt(startTime.split(":")[1]);
            if (startMinute > 30) {
                startMinute = 30;
            } else if (startMinute < 30) {
                startMinute = 0;
            }
            this.startTime = startTime.split(":")[0] + ":" + utils.padZero(startMinute);

            let maxHours = utils.diffTime(this.startTime, this.bookCourse.endtime);

            for (let hours=1; hours<=maxHours; hours++) {
                packages.push({
                    name: "欢唱" + hours + "小时",
                    duration: hours,
                    huanchang: true
                });
            }

            packages.push({
                name: "欢唱至该场次结束",
                duration: 0,
                huanchang: true
            });
            this.hcPackages = packages;

            if (this.bookCourse.is_hjd) {
                this.loadingPackages = true;
                this.$api.get("booking/gettaocanlist", {
                    days: this.bookDay.id,
                    course: this.bookCourse ? this.bookCourse.id : -1,
                    roomtype: this.bookRoomType.id,
                    ktvid: this.ktv.xktvid
                }).then(function({data}) {
                    this.packages = data.list.sort((a, b) => b.show - a.show);
                    this.bookPkg = data.total ? this.packages[0] : this.hcPackages[0];
                    this.loadingPackages = false;
                }, function(data) {
                    alert(data.msg || "获取套餐信息失败");
                    this.loadingPackages = false;
                });
            } else {
                this.packages = [];
                this.bookPkg = this.hcPackages[0];
            }
        }, 200),
        goBook(pkg) {
            if (pkg && !pkg.type) {
                this.bookPkg = pkg;
            };

            store.bookdata = JSON.parse(JSON.stringify({
                now: this.now,
                ktv: this.ktv,
                day: this.bookDay,
                course: this.bookCourse,
                roomtype: this.bookRoomType,
                package: this.bookPkg,
                lastorder: this.ktv.lastofjiedan ? this.ktv.lastofjiedan.time : null,
                terms: this.taocaninfo.tiaokuan.map(term => term.name),
                termsForOnlinePay: this.taocaninfo.tiaokuan_online.map(term => term.name),
                starttime: this.startTime
            }));

            this.$router.go({
                name: "book"
            });
        }
    },
    computed: {
        nowTime() {
            return utils.padZero(this.now.getHours()) + ":" + utils.padZero(this.now.getMinutes());
        },
        filteredCourses() {
            if (this.bookDay.today) {
                return this.tcInfo.courses.filter(course => {
                    return course.is_hjd || !course.expired;
                });
            } else {
                return this.tcInfo.courses;
            }
        },
        selectedErrors() {
            return this.feedbackErrors.filter(error => error.selected);
        }
    },
    filters: {
        deviceName(device) {
            return this.deviceNames[device.toLowerCase()];
        },
        formatSlides(list) {
            return list ? list.map(function(item){
                return {
                    pic: item.bigpicurl
                }
            }) : [];
        }
    },
    watch: {
        showEventsLayer(show) {
            if (show && this.$els.eventsLayer) {
                this.$els.eventsLayer.classList.toggle("scroll", this.$els.eventsLayer.querySelector(".flash-message-dialog").scrollHeight > window.innerHeight - 100 - 30);
            };
            document.body.classList.toggle("no-scroll", show);
        },
        bookDay(value) {
            let isToday = value.today;
            let isYesterday = value.yesterday;

            this.tcInfo.courses = this.tcInfo.courses.map(course => {
                if (course.crossday == 2) {
                    course.line2 = "次日" + course.starttime + "-" + course.endtime;
                } else if (course.crossday == 1) {
                    course.line2 = course.starttime + "-次日" + course.endtime;
                } else {
                    course.line2 = course.starttime + "-" + course.endtime;
                }

                if (isYesterday) {
                    course.expired = !course.crossday;
                } else if (isToday) {
                    if (course.crossday) {
                        course.expired = false;
                    } else {
                        course.expired = !utils.isBeforeTime(this.nowTime, utils.adjustTime(course.endtime, -1));
                    }
                } else {
                    course.expired = false;
                }
                return course;
            });
            this.bookCourse = this.filteredCourses.filter(course => !course.expired)[0];

            this.updatePackages();
        },
        bookCourse: "updatePackages",
        bookRoomType: "updatePackages"
    }
}
</script>