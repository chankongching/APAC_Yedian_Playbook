<template>
    <div class="flash-message" v-show="!hidden" transition="fade">
        <div class="flash-message-dialog">
            <div class="icon" v-if="icon" :class="'icon-'+icon"></div>
            <div class="content">{{{message}}}<slot></slot></div>
            <button type="button" class="btn" @click="hide">{{btnText}}</button>
        </div>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";

.flash-message {
    background: rgba(0,0,0,.88);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 4;
}

.flash-message-dialog {
    width: 280px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate3d(-50%, -50%, 0);
    color: #F2F2F2;
    text-align: center;

    .icon {
        @extend %sprite;
        width: 100px;
        height: 100px;
        vertical-align: top;
        margin-bottom: 20px;
    }
    .icon-smile { @include sprite-position($bigicon-smile) }
    .icon-success { @include sprite-position($bigicon-success) }
    
    .content {
        line-height: 2;
        h4 {
            color: $main;
            font-size: 18px;
            margin-top: 30px;
        }
    }

    .btn {
        border: 1px solid $main;
        font-size: 16px;
        color: rgba($main, 0.8);
        width: 110px;
        height: 48px;
        margin-top: 50px;
    }
}
</style>

<script>
export default {
    props: {
        hidden: {
            type: Boolean,
            default: true  
        },
        icon: {
            type: String,
            default: "success"
        },
        message: {
            type: String,
            default: ""
        },
        btnText: {
            type: String,
            default: "确定"
        },
        callback: Function
    },
    methods: {
        show(icon, message, callback) {
            this.icon = icon;
            this.message = message;
            this.callback = callback;
            this.hidden = false;
        },
        hide() {
            this.hidden = (this.callback && this.callback() === false) ? false : true;
        }
    }
}
</script>