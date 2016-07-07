<template>
    <div class="spinbox">
        <div class="btn" @click="adjust(-this.step)"><span class="icon icon-minus"></span></div>
        <div><input type="number" :min="min" :max="max" :step="step" :value="value" v-el:input></div>
        <div class="btn" @click="adjust(this.step)"><span class="icon icon-plus"></span></div>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";

.spinbox {
    display: table;
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    height: 46px;
    margin-bottom: 15px;

    > div {
        display: table-cell;
        border: 1px solid #fff;
    }
    .btn {
        width: 46px;
        line-height: 0;
        vertical-align: middle;
        text-align: center;
        cursor: pointer;
    }
    .icon-minus {
        @include rsprite($icon-minus-group);
    }
    .icon-plus {
        @include rsprite($icon-plus-group);
    }
    input {
        width: 100%;
        height: 100%;
        text-align: center;
        font-size: 16px;
        color: #fff;
    }
}

.modal {
    .spinbox {
        > div {
            border-color: #631E27;
        }
        input {
            color: $main;
        }
        .icon-minus {
            @include rsprite($redicon-minus-group);
        }
        .icon-plus {
            @include rsprite($redicon-plus-group);
        }
    }
}
</style>

<script>
export default {
    props: {
        value: {
            type: Number,
            default: 1,
            twoWay: true
        },
        min: {
            type: Number,
            default: 1
        },
        max: {
            type: Number,
            default: 9999
        },
        step: {
            type: Number,
            default: 1
        }
    },
    ready() {
        let vm = this;
        let data = [];

        for (let i = this.min, max = this.max; i <= max; i+= this.step) {
            data.push({
                text: i,
                value: i
            });
        };
        this.scroller = $(this.$els.input).mobiscroll().select({
            data: data,
            onSelect(value) {
                vm.value = value;
            }
        }).mobiscroll("getInst");
    },
    methods: {
        adjust(number) {
            let newValue = this.value + number;
            if (this.min <= newValue && newValue <= this.max) {
                this.value = newValue;
                this.scroller.setVal(newValue, true);
            };
        }
    },
    watch: {
        value(value) {
            this.scroller.setVal(value, true);
        }
    }
}
</script>