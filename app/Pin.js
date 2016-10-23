/**
 * Created by Dezmont on 23.10.2016.
 */
import Konva from "konva";
import {makeImageRect} from "./helper";
import {v4} from 'uuid';
import * as states from "./states";
const WIDTH_SCALE = 0.2;
const HEIGHT_SCALE = 0.15;
const X_POSITION_MULTIPLIER = 0.27;
const Y_POSITION_MULTIPLIER = 0.84;
const PIN_IMAGE_SRC = '/img/blue pin.svg';
export default class Pin {
    constructor(width, height, dimensionX, dimensionY, rects) {
        this._width = width;
        this._height = height;
        this._dimensionX = dimensionX;
        this._dimensionY = dimensionY;
        this._counter = 0;
        this._rects = rects;
    }

    set counter(counter) {
        this._counter = counter;
    }

    get counter() {
        return this._counter;
    }

    add(groupLayer, x, y) {
        var self = this;
        this.counter++;
        let group = new Konva.Group({
            draggable: true, dragBoundFunc: function (pos) {
                let x = Math.round(pos.x / self._width) * self._width;
                let y = Math.round(pos.y / self._height) * self._height;

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
            width: this._width,
            height: this._height,
            fill: states.PIN_COLOR,
            stroke: '#aaa',
            opacity: 0.5
        });
        group.add(beaconRect);
        let beaconPinWidth = this._width * this._dimensionX * WIDTH_SCALE;
        let beaconPinHeight = this._height * this._dimensionY * HEIGHT_SCALE;
        let beaconPin = new Konva.Rect({
            x: x - (beaconPinWidth * X_POSITION_MULTIPLIER),
            y: y - (beaconPinHeight * Y_POSITION_MULTIPLIER),
            width: beaconPinWidth,
            height: beaconPinHeight,
        });
        group.add(beaconPin);

        groupLayer.add(group);
        let beaconPinImage = makeImageRect(beaconPin, groupLayer);
        beaconPinImage.src = PIN_IMAGE_SRC;
        group.on('dragstart', function (e) {
            e.cancelBubble = true;
            let resultX = beaconRect.x() + group.x();
            let resultY = beaconRect.y() + group.y();
            self._rects[resultX / self._width][resultY / self._height] = states.EMPTY;
        });
        group.on('dragend', function (e) {
            e.cancelBubble = true;
            let resultX = beaconRect.x() + group.x();
            let resultY = beaconRect.y() + group.y();
            self._rects[resultX / self._width][resultY / self._height] = states.PIN;
        });
        group.on('mouseover click mousedown', function (e) {
            e.cancelBubble = true;
            return false;
        });
    };
}