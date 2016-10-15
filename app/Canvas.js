export const WIDTH = 10;
export const HEIGHT = 10;
const dimensionX = 100;
const dimensionY = 100;
const cellPerLayer = 500;
import Konva from "konva";
import {v4} from "uuid";
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
    let image = new Image();
    let imageLayer = new Konva.Layer();
    stage.add(imageLayer);
    image.onload = function () {
        let width = WIDTH * dimensionX;
        let height = HEIGHT * dimensionY;

        let imageRect = new Konva.Rect({
            x: 0,
            y: 0,
            width: width,
            height: height,
            fillPatternImage: image,
            fillPatternScaleX: width / image.width,
            fillPatternScaleY: height / image.height
        });
        imageLayer.add(imageRect);
        imageLayer.draw();
    };
    image.src = '/img/background.jpg';
    let rects = null;
    if (typeof(Storage) !== "undefined") {
        try {
            rects = JSON.parse(localStorage.getItem("rects"));
        }
        catch (e) {
            console.log(e);
        }
    } else {
        console.log('sad');
    }
    var colors = ['#fff', '#000'];
    if (!Array.isArray(rects)) {
        rects = [];
        for (let i = 0; i < dimensionX; i++) {
            rects[i] = new Array(dimensionX);
            for (let j = 0; j < dimensionY; j++) {
                rects[i][j] = 0;//Math.round(Math.random())
            }
        }
    }


    var nodeCount = 0;
    var layer = new Konva.Layer();
    for (let i = 0; i < dimensionX; i++) {
        for (let j = 0; j < dimensionY; j++) {

            let rect = new Konva.Rect({
                id: v4(),
                x: i * WIDTH,
                y: j * HEIGHT,
                width: WIDTH,
                height: HEIGHT,
                fill: colors[rects[i][j]],
                stroke: '#aaa',
                opacity: 0.5
            });
            layer.add(rect);
            nodeCount++;
            if (nodeCount >= cellPerLayer) {
                nodeCount = 0;
                stage.add(layer);
                layer = new Konva.Layer();
            }
        }
    }

    stage.on('mouseover click mousedown mouseout', function (evt) {
        if (store.getState().currentBrush.activated) {
            var node = evt.target;
            if (node) {
                // update tooltip
                if (!idBuffer.has(node.id())) {
                    nodeBuffer.set(
                        node.id(),
                        {
                            node: node, color: node.fill()
                        }
                    );
                    idBuffer.add(node.id());
                }

                console.log('X : %d, Y : %d', node.x() / WIDTH, node.y() / WIDTH);
                let color = store.getState().currentBrush.color;
                rects[node.x() / WIDTH][node.y() / HEIGHT] = color === '#000' ? 1 : 0;
                node.fill(color);
                node.getLayer().draw();
            }
        }
    });
    setInterval(function () {
        if (typeof(Storage) !== "undefined") {
            localStorage.setItem("rects", JSON.stringify(rects));
        } else {
            console.log('sad');
        }
    }, 1000);
    function undo(e) {
        if (e.keyCode == 90 && e.ctrlKey) {
            console.log(nodeBuffer);
            nodeBuffer.forEach((bufferEl) => {
                let node = bufferEl.node;
                let color = bufferEl.color;
                console.log('Node color: %s', node.fill());
                console.log('Previous node color: %s', color);
                node.fill(color);
                rects[node.x() / WIDTH][node.y() / HEIGHT] = color === '#000' ? 1 : 0;
                node.getLayer().draw();
            });
            nodeBuffer.clear();
            idBuffer.clear();
        }

    }

    document.removeEventListener('keydown', undo);
    document.addEventListener('keydown', undo);
}