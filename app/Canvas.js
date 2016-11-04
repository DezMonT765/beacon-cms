import Grid from "./Grid";
import * as states from "./states";
import * as helper from "./helper";
import * as PIXI from "../node_modules/pixi.js/bin/pixi";
var stage = null;
export default class Canvas {
    constructor(store, canvasElement, backgroundUrl, width = 10, height = 10, dimensionX = 100, dimensionY = 100, nodeBuffer, idBuffer) {
        this._store = store;
        if (stage !== null) {
            stage.destroy();
        }
        this._width = width;
        this._height = height;
        this._dimensionX = dimensionX;
        this._dimensionY = dimensionY;
        var renderer = PIXI.autoDetectRenderer(this._width * dimensionX, this._height * dimensionY, {view: canvasElement});
        renderer.plugins.interaction.moveWhenInside = true;
        stage = new PIXI.Container();
        stage.interactive = true;

        this._grid = new Grid(stage, this._width, this._height, dimensionX, dimensionY, backgroundUrl, store);
        this._grid._promise.then(function () {
            this._grid.build();
        }.bind(this));
        var onInteract = function (evt) {
            if (store.getState().brushes.currentBrush.activated) {
                let x = Math.floor(evt.data.global.x / this._width);
                let y = Math.floor(evt.data.global.y / this._height);
                let color = store.getState().brushes.currentBrush.color;
                this._grid.drawRect({
                    color: color,
                    stroke: 0xAAAAAA,
                    x: x * this._width,
                    y: y * this._height,
                    width: this._width,
                    height: this._height,
                });
                if (Array.isArray(this._grid.rects[x]) && typeof this._grid.rects[x][y] !== 'undefined') {
                    this._grid.rects[x][y] = (color === 0x000000 ? states.WALL : states.EMPTY);
                }
                // renderer.render(stage);
                // var node = evt.target;
                // if (node) {
                //     // update tooltip
                //     if (!idBuffer.has(node.id())) {
                //         if (nodeBuffer.length >= 20) {
                //             nodeBuffer.pop();
                //         }
                //         nodeBuffer.unshift({node: node, color: node.fill()});
                //         idBuffer.add(node.id());
                //     }
                //
                //     let color = store.getState().currentBrush.color;
                //     grid.rects[node.x() / WIDTH][node.y() / HEIGHT] = color === '#000' ? states.WALL : states.EMPTY;
                //     node.fill(color);
                //     node.getLayer().batchDraw();
                // }
            }
        }.bind(this);

        function update() {
            renderer.render(stage);
            requestAnimationFrame(update);
        }

        update();
        this._grid._graphics.on('mousedown', onInteract);
        this._grid._graphics.on('mousemove', onInteract);
        this._grid._graphics.on('click', onInteract);

        setInterval(function () {
            if (typeof(Storage) !== "undefined") {
                let jsonRects = JSON.stringify(this._grid.rects);
                let jsonPins = JSON.stringify(helper.mapToObj(this._store.getState().pins.pins));
                localStorage.setItem("rects", jsonRects);
                localStorage.setItem("pins", jsonPins);
            } else {
                console.log('sad');
            }
        }.bind(this), 1000);
// function undo(e) {
//     if (e.keyCode == 90 && e.ctrlKey) {
//         for (let i = 0; i < nodeBuffer.length; i++) {
//             let node = nodeBuffer[i].node;
//             let color = nodeBuffer[i].color;
//             node.fill(color);
//             grid.rects[node.x() / WIDTH][node.y() / HEIGHT] = color === '#000' ? states.WALL : states.EMPTY;
//             node.getLayer().batchDraw();
//         }
//         nodeBuffer = [];
//         idBuffer.clear();
//     }
//
// }
//
// document.removeEventListener('keydown', undo);
// document.addEventListener('keydown', undo);
    }

    clear() {
        this._grid.rects = [];
        for (let i = 0; i < this._dimensionX; i++) {
            this._grid.rects[i] = new Array(100);
            for (let j = 0; j < this._dimensionY; j++) {
                this._grid.rects[i][j] = 0;
            }
        }
        this._grid.build();
    }
}