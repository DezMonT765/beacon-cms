export const WIDTH = 10;
export const HEIGHT = 10;
const dimensionX = 100;
const dimensionY = 100;
const cellPerLayer = 500;
import Konva from "konva";
import {makeImageRect} from './helper';
import Grid from './Grid';
import * as states from './states';
var stage = null;
export default function initCanvas(store, nodeBuffer, idBuffer) {
    if (stage !== null) {
        stage.destroy();
    }
    stage = new Konva.Stage({
        container: 'canvas',   // id of container <div>
        width: WIDTH * dimensionX,
        height: HEIGHT * dimensionY
    });

    let imageLayer = new Konva.Layer();
    let backgroundRect = new Konva.Rect({
        x: 0,
        y: 0,
        width: WIDTH * dimensionX,
        height: HEIGHT * dimensionY,
    });
    imageLayer.add(backgroundRect);
    let image = makeImageRect(backgroundRect, imageLayer);
    stage.add(imageLayer);
    image.src = '/img/background.jpg';

    let grid = new Grid(stage,WIDTH,HEIGHT,dimensionX,dimensionY,cellPerLayer);
    grid.build();

    stage.on('mouseover click mousedown', function (evt) {
        if (store.getState().currentBrush.activated) {
            var node = evt.target;
            if (node) {
                // update tooltip
                if (!idBuffer.has(node.id())) {
                    if (nodeBuffer.length >= 20) {
                        nodeBuffer.pop();
                    }
                    nodeBuffer.unshift({node: node, color: node.fill()});
                    idBuffer.add(node.id());
                }

                let color = store.getState().currentBrush.color;
                grid.rects[node.x() / WIDTH][node.y() / HEIGHT] = color === '#000' ? states.WALL : states.EMPTY;
                node.fill(color);
                node.getLayer().batchDraw();
            }
        }
    });

    setInterval(function () {
        if (typeof(Storage) !== "undefined") {
            let jsonRects = JSON.stringify(grid.rects);
            localStorage.setItem("rects", jsonRects);
        } else {
            console.log('sad');
        }
    }, 1000);
    function undo(e) {
        if (e.keyCode == 90 && e.ctrlKey) {
            for (let i = 0; i < nodeBuffer.length; i++) {
                let node = nodeBuffer[i].node;
                let color = nodeBuffer[i].color;
                node.fill(color);
                grid.rects[node.x() / WIDTH][node.y() / HEIGHT] = color === '#000' ? states.WALL : states.EMPTY;
                node.getLayer().batchDraw();
            }
            nodeBuffer = [];
            idBuffer.clear();
        }

    }

    document.removeEventListener('keydown', undo);
    document.addEventListener('keydown', undo);
}