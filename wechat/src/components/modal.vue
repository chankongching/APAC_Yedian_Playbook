<template>
    <div class="modal" :class="{'has-title':title}" v-show="show" transition="modal">
        <div class="modal-dialog">
            <span class="close" @click="close" v-if="closeable"><span class="icon icon-close"></span></span>
            <div class="modal-body">
                <h4 class="modal-title" v-if="title">{{title}}</h4>
                <slot></slot>
            </div>
            <div class="modal-footer" v-if="showBtn">
                <button type="button" class="btn" @click="submitHandler | debounce 300" :disabled="btnDisabled">{{btnText}}</button>
            </div>
        </div>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";
@import "../scss/mixins";

.modal {
    background: rgba(0,0,0,0.75);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 4;

    &.has-title {
        .modal-body {
            margin-top: 15px;
            margin-bottom: 20px;
        }
    }
}

.modal-dialog {
    width: 300px;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate3d(-50%, -50%, 0);
    background: $darkBg;
    box-shadow: 0px 2px 12px 6px rgba(0,0,0,0.92);
    color: $main;

    .close {
        position: absolute;
        top: 0;
        right: 0;
        width: 44px;
        height: 44px;
        line-height: 44px;
        cursor: pointer;
        text-align: center;
        z-index: 2;

        .icon {
            @include rsprite($icon-close-group);
            vertical-align: middle;
        }
    }
}

.modal-body {
    overflow: auto;
    margin: 50px 0;
    @include scrollable;

    > :first-child {
        margin-top: 0;
    }


    > :last-child {
        margin-bottom: 0;
    }

    .modal-title {
        margin-top: 2px;
        margin-left: 30px;
    }

    a {
        color: $main;
    }

    .icon {
        @extend %sprite;
        width: 100px;
        height: 100px;
        vertical-align: top;
    }

    .icon-favorite {
        @include sprite-position($bigicon-favorite);
    }
}

.modal-footer {
    .btn {
        display: block;
        width: 100%;
        height: 54px;
        background: $main;
        font-size: 18px;
        box-shadow: 0px -4px 8px 0px rgba(0,0,0,0.5);
        color: $text;

        &:disabled {
            color: $darkTextLight;
            background: none;
        }
    }
}

.modal {
    &.msg {
        font-size: 16px;
        .modal-dialog {
            text-align: center;
            color: $text;
            span {
                color: $main;
            }
        }
    }

    &.red-placeholder {
        @include placeholder(rgba(#DE3341, 0.8));
    }
}

.modal-transition {
    transition: opacity .3s ease;

    .modal-dialog {
        transition: transform .3s ease;
    }

    &.modal-enter,
    &.modal-leave  {
        opacity: 0;

        .modal-dialog {
            transform: translate3d(-50%, -50%, 0) scale(0.9);
        }
    }
}
</style>

<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: false,
            twoWay: true    
        },
        closeable: {
            type: Boolean,
            default: true
        },
        showBtn: {
            type: Boolean,
            default: true
        },
        btnText: {
            type: String,
            default: "确定"
        },
        btnDisabled: {
            type: Boolean,
            default: false
        },
        submit: Function,
        title: String,
        docTitle: String
    },
    data() {
        return {
            origDocTitle: document.title
        };
    },
    ready() {
        this.$el.querySelector(".modal-body").style.maxHeight = window.innerHeight - 160 + "px";
    },
    beforeDestroy() {
        document.body.classList.remove("no-scroll");
    },
    methods: {
        open(selector) {
            this.show = true;
            if (selector) {
                this.$nextTick(function(){
                    this.$el.querySelector(selector).scrollIntoView();
                });
            }
        },
        close() {
            this.show = false;
        },
        submitHandler() {
            this.submit? this.submit() : (this.show = false);
        },
        setLoading(loading) {
            if (loading) {
                this._btnText = this.btnText;
                this.btnText = "正在提交";
                this.btnDisabled = true;
            } else {
                this.btnText = this._btnText;
                this.btnDisabled = false;
            };
        }
    },
    watch: {
        show(show) {
            if (this.docTitle) {
                document.title = show ? this.docTitle : this.origDocTitle;
            };
            document.body.classList.toggle("no-scroll", show);
        }
    }
}
</script>