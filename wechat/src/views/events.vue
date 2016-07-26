<template>
    <div class="page-events">
        <ul class="blurred-background" v-el:background>
            <li v-for="event in events" :class="{active:$index==currentBg}"></li>
        </ul>
 
        <div class="slider">
            <ul class="carousel">
                <li class="slide">
                    <div class="card" :style="events[events.length-1].pic | backgroundImage"></div>
                </li>
                <li class="slide" v-for="event in events">
                    <a class="card" @click="$handleLink(event.link, 'events')" :style="event.pic | backgroundImage"></a>
                </li>
                <li class="slide">
                    <div class="card" :style="events[0].pic | backgroundImage"></div>
                </li>
            </ul>

            <nav class="pagination">
                 <span v-for="event in events" :class="{active:$index==current}" :title="$index+1"></span>
            </nav>
        </div>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";

.page-events {
    .blurred-background {
        position: absolute;
        width: 100%;
        height: 100%;

        li {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: 0.7s;

            canvas {
                width: 100%;
                height: 100%;
            }

            &.loaded.active {
                opacity: 1;
            }
        }
    }

    .slider {
        overflow: hidden;
        background-size: cover;
    }

    .carousel {
        position: relative;
        height: 100%;
        margin-top: 40px;

        &.transition {
            transition: 0.3s ease-out;
        }
    }

    .slide {
        float: left;

        .card {
            display: block;
            margin: 0 auto;
            min-width: 240px;
            background: no-repeat;
            background-size: cover;
            box-shadow: 0 0 13px rgba(0, 0, 0, 0.6);
        }
    }

    .pagination {
        position: relative;
        margin-top: 26px;
        text-align: center;

        span {
            display: inline-block;
            width: 6px;
            height: 6px;
            margin: 0 4px;
            background: #fff;
            border-radius: 50%;
            transition: .3s;
            box-shadow: 0 0 5px $main;
            vertical-align: top;

            &.active {
                background: $main;
                box-shadow: none;
                transform: scale(1.2);
            }
        }
    }
}
</style>

<script>
import stackBlur from "../libs/StackBlur";
import store from "../libs/store";

export default {
    data() {
        return {
            events: store.baseinfo.poster.lists || [],
            current: 0,
            currentBg: 0
        }
    },
    ready() {
        window.scrollTo(0, 0);

        this.init();
    },
    beforeDestroy() {
        $(this.$el).find(".card").off(".ss");
        $(document).off(".ss");
    },
    methods: {
        init() {
            let width = window.innerWidth;
            let height = window.innerHeight - 54;

            this.carousel = $(".carousel").get(0);
            this.width = width;
            this.startX = 0;
            this.startY = 0;
            this.distance = 0;
            this.isMoving = false;

            $(".page-events").css({
                height: height
            });

            $(this.carousel).css({
                width: width * (this.events.length + 2),
                height: height - 110,
                left: -width
            }).find(".slide").css({
                width: width
            }).find(".card").css({
                width: (height - 170) * 560 / 770,
                height: height - 110
            });

            this.bindEvents();
            this.blurBackground();
        },
        bindEvents() {
            let $doc = $(document);

            $(this.$el).find(".card").on("touchstart.ss", event => {
                if (this.isMoving) return false;

                let touch = event.originalEvent.touches[0];
                this.startX = touch.pageX;
                this.startY = touch.pageY;

                $doc.off(".temp").one("touchmove.ss", event => {
                    touch = event.originalEvent.changedTouches[0];
                    if (Math.abs(this.startX - touch.pageX) > Math.abs(this.startY - touch.pageY)) {
                        event.preventDefault();
                        this.isMoving = true;
                        this.oldX = -this.width * this.current;
                        this.startTime = Date.now();
                        this.startX = touch.pageX;
                        this.startY = touch.pageY;
                        $doc.on("touchmove.ss.temp", this.touchmoveHandler).one("touchend.ss.temp touchcancel.ss.temp", this.touchendHandler);
                    };
                });
            });

            $(this.carousel).on("webkitTransitionEnd transitionend", event => {
                if (event.originalEvent.target == this.carousel) {
                    this.carousel.classList.remove("transition");
                    if (this.index == -1) {
                        this.translateX(-this.width * this.current);
                    } else if (this.index == this.events.length) {
                        this.translateX(-this.width * this.current);
                    };
                    this.currentBg = this.current;
                    this.isMoving = false;
                };
            });
        },
        translateX(x) {
            this.carousel.style.WebkitTransform = this.carousel.style.transform = "translate3d(" + x + "px,0,0)";
        },
        touchmoveHandler(event) {
            let touch = event.originalEvent.changedTouches[0];
            this.distance = touch.pageX - this.startX;
            this.translateX(this.oldX + this.distance);
        },
        touchendHandler() {
            let pct = this.distance / this.width;
            let speed = this.distance / (Date.now() - this.startTime);
            let index = this.current;

            if (pct < -0.2 || speed < -0.5) {
                index++;
            } else if (pct > 0.2 || speed > 0.5) {
                index--;
            };

            this.carousel.classList.add("transition");
            this.translateX(-this.width * index);
            this.current = index == -1 ? this.events.length - 1 : index % this.events.length;
            this.index = index;
            $(document).off(".temp");
        },
        blurBackground() {
            let vm = this;
            let els = this.$els.background.children;
            let len = this.events.length;
            let idx = 0;
            let w = 140;
            let h = 220;

            (function blurImage(){
                let img = new Image();

                img.onload = img.onerror = function(event) {
                    if (event.type == "load") {
                        let canvas = document.createElement("canvas");
                        let ctx = canvas.getContext("2d");

                        canvas.id = "blurred-background-" + idx;
                        canvas.width = w;
                        canvas.height = h;
                        els[idx].appendChild(canvas);

                        ctx.drawImage(img, 0, 0, w, h);
                        stackBlur.stackBlurCanvasRGB(canvas.id, 0, 0, w, h, 10);

                        els[idx].classList.add("loaded");
                    }

                    if (++idx <= vm.events.length - 1) blurImage();
                }

                img.src = vm.events[idx].pic;
            })();
        }
    }
}
</script>