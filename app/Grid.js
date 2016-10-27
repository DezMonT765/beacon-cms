import Konva from "konva";
import {Graphics} from 'pixi.js';
import Pin from './Pin';
import * as states from './states'
import {v4} from "uuid";
export default class Grid {
    constructor(renderer,stage,width,height,dimensionX,dimensionY,cellPerLayer) {
        console.log(states.colors);
        this._colors = states.colors;
        this._stage = stage;
        this._width = width;
        this._height = height;
        this._dimensionX = dimensionX;
        this._dimensionY = dimensionY;
        this._pin = new Pin(width, height, dimensionX, dimensionY, this.rects);
        this._cellPerLayer = cellPerLayer;
        this._renderer = renderer;
        this._graphics = new Graphics();
    }
    build() {
        // let groupLayer = new Konva.Layer();
        // var nodeCount = 0;
        // var layer = new Konva.Layer();
        for (let i = 0; i < this._dimensionX; i++) {
            for (let j = 0; j < this._dimensionY; j++) {


                this.drawRect({
                    color : this._colors[this.rects[i][j]],
                    stroke : 0xAAAAAA,
                    x : i * this._width,
                    y : j * this._height,
                    width : this._width,
                    height :  this._height

                });
                // let rect = new Konva.Rect({
                //     id: v4(),
                //     x: i * this._width,
                //     y: j * this._height,
                //     width: this._width,
                //     height: this._height,
                //     fill: this._colors[this.rects[i][j]],
                //     stroke: '#aaa',
                //     opacity: 0.5
                // });
                // if (this.rects[i][j] == states.PIN) {
                //     this._pin.add(groupLayer, i * this._width, j * this._height);
                // }
                // layer.add(rect);
                // nodeCount++;
                // if (nodeCount >=  this._cellPerLayer) {
                //     nodeCount = 0;
                //     this._stage.add(layer);
                //     layer = new Konva.Layer();
                // }
            }
        }
        this._stage.addChild(this._graphics);
        this._renderer.render(this._stage);


    }
    drawRect(config) {
        this._graphics.beginFill(config.color);
        this._graphics.lineStyle(1, config.stroke, 1);
        this._graphics.drawRect(config.x,config.y,config.width,config.height);
        this._graphics.endFill();
    }
    get rects() {
        if(this._rects === undefined) {
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
    get renderer() {
        return this._renderer;
    }
    get stage() {
        return this._stage;
    }
}
