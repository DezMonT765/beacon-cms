import Grid from "./Grid";
import * as states from "./states";
import * as PIXI from "../node_modules/pixi.js/bin/pixi";
var stage = null;
export default class Canvas {
    /**
     *
     * @param store
     * @param canvasElement
     * @param config
     * @param config.backgroundUrl
     * @param config.beaconMapSaveUrl
     * @param config.width
     * @param config.height
     * @param config.dimensionX
     * @param config.dimensionY
     * @param config.dimension
     */
    constructor(store, canvasElement, config) {
        this._store = store;
        if (stage !== null) {
            stage.destroy();
        }
        this._width = config.width = config.width || 10;
        this._height = config.height = config.height || 10;
        this._dimensionX = config.dimensionX = config.dimensionX || 100;
        this._dimensionY = config.dimensionY = config.dimensionY || 100;
        this.beaconMapSaveUrl = config.beaconMapSaveUrl;
        var renderer = PIXI.autoDetectRenderer(this._width * config.dimensionX, this._height * config.dimensionY, {view: canvasElement});
        renderer.plugins.interaction.moveWhenInside = true;
        stage = new PIXI.Container();
        stage.interactive = true;

        this._grid = new Grid(stage, store, config);
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
        this.save();
    }

    save() {
        let jsonRects = JSON.stringify(this._grid.rects);
        $.ajax({
            url: this.beaconMapSaveUrl,
            type: 'POST',
            data: {
                data: jsonRects
            },
            success: function (data) {
                console.log(data['success']);
            },
            cache: false,
        });
    }
}