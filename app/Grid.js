import * as PIXI from "../node_modules/pixi.js/bin/pixi";
import Pin from "./Pin";
import * as states from "./states";
import * as helper from "./helper";
import {v4} from "uuid";
export default class Grid {
    constructor(stage, width, height, dimensionX, dimensionY, src, store) {
        console.log(states.colors);
        this._colors = states.colors;
        this._stage = stage;
        this._width = width;
        this._height = height;
        this._dimensionX = dimensionX;
        this._dimensionY = dimensionY;
        this._graphics = new PIXI.Graphics();
        this._stage.addChild(this._graphics);
        this._graphics.interactive = true;
        this._src = src;
        this._pins = new Map();
        this._store = store;
        this._promise = new Promise(function (resolve, reject) {
            PIXI.loader
                .add(src)
                .load(this.setupBackground.bind(this, resolve, reject));
            // resolve();
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
            this.addPin(value.position.x, value.position.y, value.name);
        }
        this._graphics.zIndex = 1;
        helper.sortChildrenByZIndex(this._stage);
    }

    addPin(x = 0, y = 0, name = v4()) {
        if (!this._pins.has(name))
            this._pins.set(name, new Pin(x, y, name, this));
    }

    deletePin(name) {
        this._pins.get(name).destroy();
        this._pins.delete(name);
    }

    setupBackground(resolve, reject) {
        this._sprite = new PIXI.Sprite(
            PIXI.loader.resources[this._src].texture
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
            if (typeof(Storage) !== "undefined") {
                try {
                    this._rects = JSON.parse(localStorage.getItem("rects"));
                }
                catch (e) {
                    console.log(e);
                }
            }
            if (!Array.isArray(this._rects)) {
                this._rects = [];
                for (let i = 0; i < this._dimensionX; i++) {
                    this._rects[i] = new Array(this._dimensionX);
                    for (let j = 0; j < this._dimensionY; j++) {
                        this._rects[i][j] = 0;//Math.round(Math.random())
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
