export const WIDTH = 10;
export const HEIGHT = 10;
const dimensionX = 100;
const dimensionY = 100;
const cellPerLayer = 500;
import Grid from "./Grid";
import * as states from "./states";
import * as helper from "./helper";
import * as PIXI from "../node_modules/pixi.js/bin/pixi";
var stage = null;
export default class Canvas {
    constructor(store, nodeBuffer, idBuffer) {
        this._store = store;
        if (stage !== null) {
            stage.destroy();
        }
        var renderer = PIXI.autoDetectRenderer(WIDTH * dimensionX, HEIGHT * dimensionY);
        renderer.plugins.interaction.moveWhenInside = true;
        document.body.appendChild(renderer.view);

        stage = new PIXI.Container();
        stage.interactive = true;

        this._grid = new Grid(stage, WIDTH, HEIGHT, dimensionX, dimensionY, '/img/background.jpg', store);
        this._grid._promise.then(function () {
            this._grid.build();
            // this._grid.addPin(0, 0);
        }.bind(this));
        var onInteract = function (evt) {
            if (store.getState().brushes.currentBrush.activated) {
                let x = Math.round(evt.data.global.x / WIDTH);
                let y = Math.round(evt.data.global.y / HEIGHT);
                let color = store.getState().brushes.currentBrush.color;
                this._grid.drawRect({
                    color: color,
                    stroke: 0xAAAAAA,
                    x: x * WIDTH,
                    y: y * HEIGHT,
                    width: WIDTH,
                    height: HEIGHT,
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
        for (let i = 0; i < dimensionX; i++) {
            this._grid.rects[i] = new Array(100);
            for (let j = 0; j < dimensionY; j++) {
                this._grid.rects[i][j] = 0;
            }
        }
        this._grid.build();
    }
}