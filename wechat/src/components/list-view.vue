<template>
    <ul class="list-view">
        <slot></slot>
    </ul>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";
@import "../scss/mixins";

.list-view {
    display: table;
    table-layout:fixed;
    width: 100%;
    margin: 20px 0;
    clear: both;

    li {
        display: table-row;
    }

    a {
        padding: 0 40px 0 80px;
        height: 50px;
        position: relative;
        border-bottom: 1px transparent;
        line-height: 1.2;
        display: table-cell;
        vertical-align: middle;

        &:before {
            content: "";
            position: absolute;
            top: 100%;
            left: 80px;
            right: 0;
            height: 1px;
            background: rgba(255,255,255,0.2);
        }

        &:after {
            content: "";
            position: absolute;
            top: 50%;
            right: 20px;
            margin-top: -7px;
            @include rsprite($icon-listview-arrow-group);
        }

        .icon {
            @extend %sprite;
            height: 24px;
            width: 24px;
            position: absolute;
            top: 13px;
            left: 35px;
        }
    }

    .icon-phone { @include sprite-position($icon-phone); }
    .icon-home { @include sprite-position($icon-home); }
    .icon-order { @include sprite-position($icon-order); }
    .icon-favorite { @include sprite-position($icon-favorite); }
    .icon-service { @include sprite-position($icon-service); }
    .icon-clock { @include sprite-position($icon-clock); }
    .icon-user { @include sprite-position($icon-user); }
    .icon-coupon { @include sprite-position($icon-coupon); }

    .lineicon-phone { @include sprite-position($lineicon-phone); }
    .lineicon-location { @include sprite-position($lineicon-location); }
    .lineicon-favorite { @include sprite-position($lineicon-favorite); }
    .lineicon-feedback { @include sprite-position($lineicon-feedback); }

    &.no-arrow {
        a {
            padding-right: 30px;
            &:after {
                display: none;
            }
        }
    }

    &.bg {
        background: $mainDarker;
        a {
           &:before {
               background: #BD2A36;
           }
        }
        li:last-child {
            a {
                border-bottom-width: 0;
                &:before {
                    display: none;
                }
            }
        }
    }
}

.modal {
    .list-view {
        background: none;
        font-size: 14px;
        font-weight: lighter;

        a {
            color: $main;
            padding-left: 65px;
            padding-right: 35px;

            .icon {
                left: 30px;
            }

            &:before {
                background: $darkBorder;
                left: 65px;
            }

            &:after {
                @include sprite-position($redicon-listview-arrow);
                right: 15px;
            }
        }

        input {
            border: 0;
            height: 100%;
            width: 130px;
            color: rgba(#fff, .67);
            font-weight: lighter;

            &[type="radio"],
            &[type="checkbox"] {
                width: 20px!important;
                height: 20px!important;
                vertical-align: middle;
                border: 2px solid rgba($main,0.42);
                border-radius: 50%;
                box-shadow: inset 0 0 0 3px $darkBg;

                &:checked {
                    background: $main;
                    border-color: $main;
                }

                + label {
                    vertical-align: middle;
                    padding-left: 7px;
                    padding-right: 50px;
                }
            }
        }

        @include placeholder(rgba(255, 255, 255, .2)) {
            font-weight: lighter;
        };

        .icon-phone { @include sprite-position($redicon-phone) }
        .icon-user { @include sprite-position($redicon-user); }
        .icon-home { @include sprite-position($redicon-home); }

        .item-phone {
            input {
                width: 168px;
            }
        }
        .item-captcha {
            a {
                padding-right: 30px;

                &:before,
                &:after {
                    display: none;
                }
            }
            input {
                width: 102px!important;
                height: 35px;
                margin-top: 15px;
                border-bottom: 1px solid $darkBorder;
                float: left;
            }
            .btn-sendcaptcha {
                float: right;
                border: 1px solid rgba($main, 0.42);
                width: 90px;
                height: 35px;
                margin-top: 15px;
                color: $main;
            }
        }

        &.no-arrow {
            width: 270px;
            a {
                padding-right: 0;
                &:after {
                    display: none;
                }
            }
            input {
                width: 100%;
            }
            .item-captcha {
                a {
                    padding-right: 0;
                }
            }
            .item-city {
                a {
                    padding-left: 30px;
                    padding-bottom: 10px;
                    &:before {
                        display: none;
                    }
                }
                .city-selector {
                    margin-top: 18px;
                }
                input {
                    border: 1px solid rgba($main,0.42);
                    width: 73px;
                    height: 35px;
                    padding: 0 10px;
                    text-align: center;
                    color: $main;
                }
                @include placeholder(rgba(#DE3341, .8));
            }
        }
    }
}
</style>