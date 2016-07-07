<template>
    <div class="c-rating">
        <span class="star" :class="{active:i<value}" v-for="i in max" :style="{fontSize:size+'px'}" :data-stars="i+1" @touchstart="handleTouchStart($event)" @touchmove.prevent="handleTouchMove($event)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M8 12.4l-4.39 2.29.87-4.84L1 6.43l4.79-.71L8 1.31l2.18 4.4 4.82.71-3.48 3.42.87 4.85z"/></svg>
        </span>
    </div>
</template>

<style lang="sass">
.c-rating {
    text-align: center;
    overflow: hidden;

    .star {
        display: inline-block;
        vertical-align: middle;
        width: 1em;
        height: 1em;
        margin: 0 .2em;

        svg {
            width: 100%;
            height: 100%;
            stroke: #fff;
        }

        &.active {
            svg {
                fill: #fff;
            }
        }
    }
}
</style>

<script>
export default {
    props: {
        max: {
            type: Number,
            default: 5
        },
        value: {
            type: Number,
            default: 0,
            twoWay: true
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        size: {
            type: Number,
            default: 19
        }
    },
    methods: {
        handleTouchStart(event) {
            let stars = $(event.target).closest(".star").data("stars");
            this.value = stars;
        },
        handleTouchMove(event) {
            let touch = event.changedTouches[0];
            let realTarget = document.elementFromPoint(touch.clientX, touch.clientY);
            let $span = $(realTarget).closest(".star");
            if ($span.length) {
                let stars = $span.data("stars");
                this.value = stars;
            }
        }
    }
}
</script>