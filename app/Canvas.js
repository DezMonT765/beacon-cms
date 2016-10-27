export const WIDTH = 10;
export const HEIGHT = 10;
const dimensionX = 100;
const dimensionY = 100;
const cellPerLayer = 500;
import Konva from "konva";
import {makeImageRect} from "./helper";
import Grid from "./Grid";
import * as states from "./states";
import * as PIXI from "../node_modules/pixi.js/bin/pixi";
var stage = null;
export default function initCanvas(store, nodeBuffer, idBuffer) {
    if (stage !== null) {
        stage.destroy();
    }
    var renderer = PIXI.autoDetectRenderer(WIDTH * dimensionX, HEIGHT * dimensionY);

    document.body.appendChild(renderer.view);

    stage = new PIXI.Container();
    stage.interactive = true;

    // stage = new Konva.Stage({
    //     container: 'canvas',   // id of container <div>
    //     width: WIDTH * dimensionX,
    //     height: HEIGHT * dimensionY
    // });

    // let imageLayer = new Konva.Layer();
    // let backgroundRect = new Konva.Rect({
    //     x: 0,
    //     y: 0,
    //     width: WIDTH * dimensionX,
    //     height: HEIGHT * dimensionY,
    // });
    // imageLayer.add(backgroundRect);
    // let image = makeImageRect(backgroundRect, imageLayer);
    // stage.add(imageLayer);
    // image.src = '/img/background.jpg';

    let grid = new Grid(renderer,stage, WIDTH, HEIGHT, dimensionX, dimensionY, cellPerLayer);
    grid.build();
    var onInteract = function(evt){
            if (store.getState().currentBrush.activated) {
                let x = Math.round(evt.data.global.x / WIDTH);
                let y = Math.round(evt.data.global.y / HEIGHT);
                let color = store.getState().currentBrush.color;
                grid.drawRect({
                    color : color,
                    stroke : 0xAAAAAA,
                    x : x * WIDTH,
                    y : y * HEIGHT,
                    width : WIDTH,
                    height :  HEIGHT

                });
                grid.rects[x][y] = color === 0x000000 ? states.WALL : states.EMPTY;
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
    };
    function update() {
        renderer.render(stage);
        requestAnimationFrame(update);
    }
    update();
    stage.on('mousedown',onInteract);
    stage.on('mousemove',onInteract);
    stage.on('click',onInteract);

    setInterval(function () {
        if (typeof(Storage) !== "undefined") {
            let jsonRects = JSON.stringify(grid.rects);
            localStorage.setItem("rects", jsonRects);
        } else {
            console.log('sad');
        }
    }, 1000);
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