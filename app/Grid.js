import * as PIXI from "../node_modules/pixi.js/bin/pixi";
import Pin from "./Pin";
import * as states from "./states";
import * as helper from "./helper";
import {v4} from "uuid";
export default class Grid {
    /**
     *
     * @param stage
     * @param store
     * @param config
     * @param config.backgroundUrl
     * @param config.beaconPinSaveUrl
     * @param config.beaconPinListUrl
     * @param config.beaconPinDeleteUrl
     * @param config.beaconMapGetUrl
     * @param config.width
     * @param config.height
     * @param config.dimensionX
     * @param config.dimensionY
     */
    constructor(stage, store, config) {
        console.log(states.colors);
        this._colors = states.colors;

        this._stage = stage;
        this._width = config.width;
        this._height = config.height;
        this._dimensionX = config.dimensionX;
        this._dimensionY = config.dimensionY;
        this._beaconUrls = {
            beaconPinSaveUrl: config.beaconPinSaveUrl,
            beaconPinListUrl: config.beaconPinListUrl,
            beaconPinDeleteUrl: config.beaconPinDeleteUrl,
            beaconMapGetUrl: config.beaconMapGetUrl,
        };
        this._graphics = new PIXI.Graphics();
        this._stage.addChild(this._graphics);
        this._graphics.interactive = true;
        this._backgroundUrl = config.backgroundUrl;
        this._pins = new Map();
        this._store = store;
        this._promise = new Promise(function (resolve, reject) {
            PIXI.loader
                .add(config.backgroundUrl)
                .load(this.setupBackground.bind(this, resolve, reject));
        }.bind(this));

    }

    build() {
        this._graphics.clear();
        for (let [key,pin] of this._pins) {
            pin.destroy();
        }
        for (let i = 0; i < this._dimensionX; i++) {
            for (let j = 0; j < this._dimensionY; j++) {
                this.drawRect({
                    color: this._colors[this.rects[i][j]],
                    stroke: 0xAAAAAA,
                    x: i * this._width,
                    y: j * this._height,
                    width: this._width,
                    height: this._height,
                    opacity: this.rects[i][j] === states.EMPTY ? 0.5 : 1
                });

            }
        }
        let pins = this._store.getState().pins.pins;
        for (let [key,value] of pins) {
            this.addPin(value.x, value.y, value.id, value.name);
        }
        this._graphics.zIndex = 1;
        helper.sortChildrenByZIndex(this._stage);
    }

    addPin(x = 0, y = 0, id, name) {
        if (typeof name !== 'undefined') {
            if (!this._pins.has(name)) {
                this._store.dispatch({
                    type: 'ADD_PIN',
                    id: id,
                    name: name,
                    x: x,
                    y: y
                });
                this._pins.set(name, new Pin(x, y, id, name, this));
            }
        }
    }

    deletePin(name) {
        if(this._pins.has(name)) {
            this._store.dispatch({
                type: 'DELETE_PIN',
                name: name
            });
            this._pins.get(name).destroy();
            this._pins.delete(name);
        }
    }

    setupBackground(resolve, reject) {
        this._sprite = new PIXI.Sprite(
            PIXI.loader.resources[this._backgroundUrl].texture
        );
        this._sprite.width = this._width * this._dimensionX;
        this._sprite.height = this._height * this._dimensionY;
        this._sprite.x = 0;
        this._sprite.y = 0;
        this._stage.addChild(this._sprite);
        resolve();
    }

    drawRect(config) {
        this._graphics.beginFill(config.color, 0);
        this._graphics.drawRect(config.x, config.y, config.width, config.height);
        this._graphics.endFill();
        this._graphics.beginFill(config.color, config.opacity);
        this._graphics.lineStyle(1, config.stroke, 1);
        this._graphics.drawRect(config.x, config.y, config.width, config.height);
        this._graphics.endFill();
    }

    get rects() {
        if (this._rects === undefined) {
            this._rects = null;
            let self = this;
            $.ajax({
                url: self._beaconUrls.beaconMapGetUrl,
                type: 'GET',
                dataType : "json",
                async : false,
                success : function(data) {
                    self._rects = data;
                }
            });
            if (!Array.isArray(self._rects)) {
                self._rects = [];
                for (let i = 0; i < self._dimensionX; i++) {
                    self._rects[i] = new Array(self._dimensionX);
                    for (let j = 0; j < self._dimensionY; j++) {
                        self._rects[i][j] = 0;//Math.round(Math.random())
                    }
                }
            }
        }
        return this._rects;
    }

    set rects(rects) {
        this._rects = rects;
    }

    get stage() {
        return this._stage;
    }
}
