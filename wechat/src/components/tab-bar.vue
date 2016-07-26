<template>
    <nav id="tab-bar" v-show="show">
        <span v-for="item in items" :class="'item-'+item.id">
            <a v-if="item.link.indexOf('#!')===0" v-link="{path:item.link.substring(2),query:{fromTabBar:1},activeClass:'active'}">
                <span class="icon" :class="'icon-'+item.id"></span>
                <span class="text">{{item.name}}</span>
            </a>
            <a v-else :href="item.link">
                <span class="icon" :class="'icon-'+item.id"></span>
                <span class="text">{{item.name}}</span>
            </a>
        </span>
    </nav>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";

body.show-tabbar {
    padding-bottom: $tabBarHeight;
}

#tab-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: $tabBarHeight;
    z-index: 3;
    background: #1D1C22;
    text-align: center;
    display: flex;

    &:before {
        position: absolute;
        content: "";
        left: 50%;
        top: -17px;
        width: 62px;
        height: 62px;
        margin-left: -31px;
        background: #1D1C22;
        border-radius: 50%;
    }

    > span {
        flex: 1 auto;
        position: relative;
        float: left;
        width: 20%;
        color: #8A8A8A;
        font-size: 12px;
    }

    a {
        display: block;

        .text {
            display: block;
            transform: scale(0.8);
        }

        &.active {
            color: #DE959B;
            text-shadow: 0 0 7px $main, 0 0 7px $main, 0 0 7px $main, 0 0 7px $main;
        }
    }

    .icon {
        @extend %sprite;
        width: 45px;
        height: 45px;
        margin: -3px auto;
        vertical-align: top;
    }

    .item-ktv {
        margin-top: -10px;

        .icon {
            width: 48px;
            height: 48px;
            margin-bottom: 3px;
        }

        .text {
            transform: scale(0.9);
        }

        a {
            color: $main;

            &.active {
                color: #DE959B;
            }
        }
    }

    .icon-ktv { @include sprite-position($navicon-ktv); }
    .icon-news { @include sprite-position($navicon-news); }
    .icon-order { @include sprite-position($navicon-order); }
    .icon-store { @include sprite-position($navicon-store); }
    .icon-user { @include sprite-position($navicon-user); }
    .icon-contact { @include sprite-position($navicon-contact); }
    
    .active .icon-ktv { @include sprite-position($navicon-ktv-active); }
    .active .icon-news { @include sprite-position($navicon-news-active); }
    .active .icon-order { @include sprite-position($navicon-order-active); }
    .active .icon-store { @include sprite-position($navicon-store-active); }
    .active .icon-user { @include sprite-position($navicon-user-active); }
    .active .icon-contact { @include sprite-position($navicon-contact-active); }
}
</style>


<script>
export default {
    data() {
        return {
            show: true,
            items: [{
                id: "news",
                name: "夜点精彩",
                link: "#!/events"
            }, {
                id: "store",
                name: "礼品兑换",
                link: "#!/store"
            }, {
                id: "ktv",
                name: "KTV预订",
                link: "#!/ktv"
            }, {
                id: "user",
                name: "个人中心",
                link: "#!/user"
            }, {
                id: "contact",
                name: "联系我们",
                link: "tel:4006507351"
            }]
        }
    },
    ready() {
        var body = document.body;

        this.$router.afterEach(({to}) => {
            this.show = !to.hideBar;
            body.classList[this.show ? "add" : "remove"]("show-tabbar");
        });
    }
}
</script>