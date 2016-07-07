<template>
    <div class="page-map">
        <header class="map-info"><span class="icon"></span><p class="address">{{$route.query.address}}</p></header>
        <div class="map-container"></div>
    </div>
</template>

<style lang="sass">
@import "../scss/rsprite";

.map-info {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    padding: 15px;
    background: rgba(29,28,34,0.90);
    color: #fff;
    z-index: 1;
    
    .icon {
        @include rsprite($icon-map-location-group);
        position: absolute;
        top: 13px;
        left: 20px;
    }
    .address {
        margin: 0 0 0 35px;
        line-height: 1.5;
        font-weight: lighter;
    }
}

.BMapLib_trans_text,
.BMapLib_SearchInfoWindow .iw_bt {
    color: #333; 
}
</style>

<script>
export default {
    ready(){
        if ("BMap" in window) {
            this.initBMap();
        } else {
            this.loadBMap();
        };
    },
    methods: {
        loadStyle(src) {
            let link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = src;
            document.head.appendChild(link);
        },
        loadScript(src, onLoad) {
            let script = document.createElement("script");
            script.src = src;
            if (onLoad) script.onload = onLoad;
            document.body.appendChild(script);
        },
        loadBMap() {
            window.initializeBMap = () => {
                delete window.initializeBMap;
                this.loadStyle("http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.css");
                this.loadScript("http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js", this.initBMap);
            };
            this.loadScript("http://api.map.baidu.com/api?v=2.0&ak=0f2LhYmwstCU8q1lk9IOBctx&callback=initializeBMap");
        },
        initBMap() {
            let container = document.querySelector(".map-container");
            container.style.height = window.innerHeight + "px";

            let map = new BMap.Map(container);
            let point = new BMap.Point(this.$route.query.lng, this.$route.query.lat);
            let searchInfoWindow = new BMapLib.SearchInfoWindow(map, this.$route.query.address, {
                title: this.$route.query.name,
                width: 280,
                height: 50,
                panel: "panel",
                enableAutoPan: true,
                searchTypes: [
                    BMAPLIB_TAB_TO_HERE
                ]
            });

            map.addControl(new BMap.NavigationControl());
            map.addControl(new BMap.GeolocationControl());
            map.addControl(new BMap.ScaleControl());
            map.centerAndZoom(point, 15);

            let convertor = new BMap.Convertor();
            convertor.translate([point], 3, 5, data => {
                if (data.status == 0) {
                    let marker = new BMap.Marker(data.points[0]);

                    marker.addEventListener("click", function() {
                        searchInfoWindow.open(marker);
                    });

                    map.addOverlay(marker);
                    map.setCenter(data.points[0]);
                };
            });
        }
    }
};
</script>