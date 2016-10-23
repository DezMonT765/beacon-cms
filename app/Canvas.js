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
    function makeImageRect(rect, layer) {
        let image = new Image();
        image.onload = function () {
            rect.fillPatternImage(image);
            rect.fillPatternScaleX(rect.width() / image.width);
            rect.fillPatternScaleY(rect.height() / image.height);
            layer.draw();
        };
        return image;
    }

    class PinAdder {
        constructor() {
            this._counter = 0;
        }

        set counter(counter) {
            this._counter = counter;
        }

        get counter() {
            return this._counter;
        }

        add(groupLayer, x, y) {
            this.counter++;

            let group = new Konva.Group({
                draggable: true, dragBoundFunc: function (pos) {
                    let x = Math.round(pos.x / WIDTH) * WIDTH;
                    let y = Math.round(pos.y / HEIGHT) * HEIGHT;

                    return {
                        x: x,
                        y: y
                    };
                }
            });

            let beaconRect = new Konva.Rect({
                id: v4(),
                x: x,
                y: y,
                width: WIDTH,
                height: HEIGHT,
                fill: colors[2],
                stroke: '#aaa',
                opacity: 0.5
            });
            group.add(beaconRect);
            let beaconPinWidth = WIDTH * dimensionX * 0.2;
            let beaconPinHeight = HEIGHT * dimensionY * 0.15;
            let beaconPin = new Konva.Rect({
                x: x - (beaconPinWidth * 0.27),
                y: y - (beaconPinHeight * 0.84),
                width: beaconPinWidth,
                height: beaconPinHeight,
            });
            group.add(beaconPin);

            groupLayer.add(group);
            let beaconPinImage = makeImageRect(beaconPin, groupLayer);
            beaconPinImage.src = '/img/blue pin.svg';
            group.on('dragstart', function (e) {
                e.cancelBubble = true;
                let resultX = beaconRect.x() + group.x();
                let resultY = beaconRect.y() + group.y();
                // console.log('START');
                // console.log('X:%d', beaconRect.x());
                // console.log('Y:%d', beaconRect.y());
                // console.log('Group X:%d', group.x());
                // console.log('Group Y:%d', group.y());
                // console.log('Result X:%d', resultX);
                // console.log('Result Y:%d', resultY);
                rects[resultX / WIDTH][resultY / HEIGHT] = 0;
            });
            group.on('dragend', function (e) {
                e.cancelBubble = true;
                let resultX = beaconRect.x() + group.x();
                let resultY = beaconRect.y() + group.y();
                // console.log('END');
                // console.log('X:%d', beaconRect.x());
                // console.log('Y:%d', beaconRect.y());
                // console.log('Group X:%d', group.x());
                // console.log('Group Y:%d', group.y());
                // console.log('Result X:%d', resultX);
                // console.log('Result Y:%d', resultY);
                rects[resultX / WIDTH][resultY / HEIGHT] = 2;
            });
            group.on('mouseover click mousedown',function(e){
                e.cancelBubble = true;
                return false;
            });
        };
    }
    var addPin = new PinAdder();


    stage = new Konva.Stage({
        container: 'canvas',   // id of container <div>
        width: WIDTH * dimensionX,
        height: HEIGHT * dimensionY
    });
    let groupLayer = new Konva.Layer();
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
    var colors = ['#fff', '#000', '#007FFF'];
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
            if (rects[i][j] == 2) {
                // addPin.add(groupLayer, i * WIDTH, j * HEIGHT);
            }
            layer.add(rect);
            nodeCount++;
            if (nodeCount >= cellPerLayer) {
                nodeCount = 0;
                stage.add(layer);
                layer = new Konva.Layer();
            }
        }
    }
    console.log('Counter : %d', addPin.counter);
    if (addPin.counter == 0) {
        addPin.add(groupLayer, 0, 0);
    }
    stage.add(groupLayer);

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
            for (let i = 0; i < nodeBuffer.length; i++) {
                let node = nodeBuffer[i].node;
                let color = nodeBuffer[i].color;
                node.fill(color);
                rects[node.x() / WIDTH][node.y() / HEIGHT] = color === '#000' ? 1 : 0;
                node.getLayer().draw();
            }
            nodeBuffer = [];
            idBuffer.clear();
        }

    }

    document.removeEventListener('keydown', undo);
    document.addEventListener('keydown', undo);
}