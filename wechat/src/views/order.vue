<template>
    <div class="page-order">
        <header class="masthead order-navbar">
            <span class="filter-trigger" v-show="activeView=='ktv'" @click="showStatusFilter=!showStatusFilter"><span class="icon icon-setting"></span></span>

            <nav class="segmented-control">
                <a :class="{active:activeView=='ktv'}" v-link="'/order/ktv'" @click="activeView='ktv'">KTV订单</a>
                <a :class="{active:activeView=='gift'}" v-link="'/order/gift'" @click="activeView='gift'">礼品订单</a>
            </nav>

            <ul class="dropdown dropdown-ktv" transition="fade" v-show="activeView=='ktv'&&showStatusFilter" v-el:filter-dropndown>
                <li @click="status=0">全部订单</li>
                <li @click="status=1">预约中订单</li>
                <li @click="status=3">已确认订单</li>
                <li @click="status=7">已取消订单</li>
            </ul>
        </header>

        <router-view></router-view>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";

.page-order {
    padding-top: 44px;
}

.order-navbar {
    .segmented-control {
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);

        a {
            width: 80px;
        }
    }
    .filter-trigger {
        width: 44px;
        height: 44px;
        float: right;
        text-align: center;
        margin-right: 6px;

        .icon-setting {
            margin-top: 11px;
            @include rsprite($icon-setting-group);
        }
    }
    .dropdown {
        background: rgba(0,0,0,0.8);
        border-radius: 5px;
        box-shadow: 0 8px 13px rgba(0,0,0,.5);
        position: absolute;
        top: 50px;
        right: 8px;
        width: 120px;
        padding: 5px 0;

        &:before {
            content: "";
            position: absolute;
            top: -12px;
            right: 14px;
            border: 6px solid transparent;
            border-bottom-color: rgba(0,0,0,0.8);
        }

        li {
            line-height: 36px;
            padding: 0 20px;
            color: #C7C7C7;
            &:active {
                background: rgba(0,0,0,0.8);
            }
        }
    }
}

.order-list {
    margin-top: 24px;

    li {
        margin: 0 8px 24px;
        background: $main;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
        animation: fadeIn .3s;
        transition: .3s ease-out;

        .header {
            padding: 20px 24px;
            .status{
                float: right;
                color: $darkBg;
            }
        }

        .content {
            padding: 20px 24px;
            font-size: 12px;
            
            .btn {
                float: right;
                border: 1px solid currentColor;
                width: 60px;
                height: 24px;
                margin-top: 3px;
            }
            .ordertime {
                display: block;
                margin: 0 0 7px;
            }
            .info {
                margin: 0;
            }
            .couponinfo {
                margin-bottom: 0;
                line-height: 1.4;
            }
        }

        .pic {
            height: 120px;
            background: $mainDarker no-repeat 50%;
            background-size: cover;
        }

        &[data-status='7'],
        &[data-status='14'],
        &[data-status='4'],
        &[data-status='8'] {
            .status {
                color: #B52A35;
            }
        }

        &.deleted {
            height: 0!important;
            margin-bottom: 0;
            opacity: 0;
        }
    }
}
</style>

<script>
export default {
    data(transform) {
        return {
            activeView: this.$route.path.indexOf("gift") == -1 ? "ktv" : "gift",
            status: 0,
            showStatusFilter: false
        }
    },
    ready() {
        if (this.$route.query.fromTabBar) window.scrollTo(0, 0);
    },
    watch: {
        showStatusFilter(show) {
            if (show) {
                setTimeout(() => {
                    $(document).one("click", () => {
                        this.showStatusFilter = false;
                    });
                }, 10);
            };
        }
    }
}
</script>