/**
 * Created by Dezmont on 23.10.2016.
 */
import {v4} from "uuid";
import * as states from "./states";
import * as PIXI from "../node_modules/pixi.js/bin/pixi";
const WIDTH_SCALE = 0.2;
const HEIGHT_SCALE = 0.15;
const X_POSITION_MULTIPLIER = 0.27;
const Y_POSITION_MULTIPLIER = 0.84;
const PIN_IMAGE_SRC = '/img/blue pin.png';
export default class Pin {
    constructor(x, y, id, name, grid) {


        this._id = id;
        this._name = name;
        this._x = x;
        this._y = y;
        this._width = grid._width;
        this._height = grid._height;
        this._dimensionX = grid._dimensionX;
        this._dimensionY = grid._dimensionY;
        this._beaconPinWidth = this._width * this._dimensionX * WIDTH_SCALE;
        this._beaconPinHeight = this._height * this._dimensionY * HEIGHT_SCALE;



        this._grid = grid;
        this._rects = grid.rects;
        this._stage = grid._stage;

        this.save(x,y);


        this._counter = 0;

        this._group = new PIXI.Container();
        this._group.zIndex = 2;
        this._stage.addChild(this._group);
        x = x * this._width;
        y = y * this._height;
        this._group.x = x;
        this._group.y = y;
        // this._group.anchor.set(0.3);
        this._rect = new PIXI.Graphics;
        Pin.drawRect(this._rect, {
            x: 0,
            y: 0,
            width: this._width,
            height: this._height,
            color: states.PIN_COLOR
        });

        if (!Pin.promise) {
            Pin.promise = new Promise(function (resolve) {
                PIXI.loader
                    .add(PIN_IMAGE_SRC)
                    .load(() => {
                        resolve();
                    });
            });
        }
        Pin.promise.then(this.setup.bind(this, x, y));


        this._group.interactive = true;
        this._group.buttonMode = true;
        // this._group.pivot.set(this._beaconPinWidth.width * 0.3, this._beaconPinHeight * 0.3);
        this._group
            .on('mousedown', this.onDragStart)
            .on('mousedown', this.onPinSelected.bind(this))
            .on('mouseup', this.onDragEnd())
            .on('mousedown', this.onPinSelected.bind(this))
            .on('mouseupoutside', this.onDragEnd())
            // events for drag move
            .on('mousemove', this.onDragMove());
        this._group.addChild(this._rect);
        // this._group.anchor.set(0.3)

    }

    static drawRect(graphics, config) {
        graphics.beginFill(config.color);
        graphics.lineStyle(1, config.stroke, 1);
        graphics.drawRect(config.x, config.y, config.width, config.height);
        graphics.endFill();
    }

    save(x,y) {
        $.ajax({
            url: this._grid._beaconUrls.beaconPinSaveUrl,
            type: 'POST',
            data: {
                'BeaconPins[canvas_height]': this._stage.height,
                'BeaconPins[canvas_width]': this._stage.width,
                'BeaconPins[id]': this._id,
                'BeaconPins[name]': this._name,
                'BeaconPins[x]': x,
                'BeaconPins[y]': y,
            }
        });
    }

    setup(x, y) {
        this._sprite = new PIXI.Sprite(
            PIXI.loader.resources[PIN_IMAGE_SRC].texture
        );
        this._beaconPinWidth = this._width * this._dimensionX * WIDTH_SCALE;
        this._beaconPinHeight = this._height * this._dimensionY * HEIGHT_SCALE;
        this._sprite.width = this._beaconPinWidth;
        this._sprite.height = this._beaconPinHeight;
        this._sprite.x = -(this._beaconPinWidth * X_POSITION_MULTIPLIER);
        this._sprite.y = -(this._beaconPinHeight * Y_POSITION_MULTIPLIER);


        // events for drag start
        this._group.addChild(this._sprite);
    }

    set counter(counter) {
        this._counter = counter;
    }

    get counter() {
        return this._counter;
    }

    onDragStart(event) {
        // store a reference to the data
        // the reason for this is because of multitouch
        // we want to track the movement of this particular touch
        this.data = event.data;
        this.dragging = true;
    }

    onPinSelected() {
        this._grid._store.dispatch({
            type: 'TOGGLE_PIN',
            name: this._name
        });
    }

    onDragEnd() {
        var self = this;
        return function (event) {
            // e.stopPropagation();

            this.dragging = false;

            // set the interaction data to null
            this.data = null;
            let newPosition = event.data.getLocalPosition(this.parent);
            let x = Math.round(newPosition.x / self._width);
            let y = Math.round(newPosition.y / self._height);
            self._grid._store.dispatch({
                type: 'SET_PIN_POSITION',
                id: self._id,
                name: self._name,
                x: x,
                y: y
            });
            self.save(x,y);
        }
    }

    onDragMove() {
        var width = this._width;
        var height = this._height;
        var self = this;
        return function (e) {
            e.stopPropagation();
            if (this.dragging == true) {
                let newPosition = e.data.getLocalPosition(this.parent);
                let maxWidth = width * self._dimensionX;
                let maxHeight = height * self._dimensionY;
                if (newPosition.x > 0 && newPosition.y > 0 && newPosition.x < maxWidth && newPosition.y < maxHeight) {
                    let x = Math.round(newPosition.x / width);
                    let y = Math.round(newPosition.y / height);
                    this.position.x = x * width;
                    this.position.y = y * height;
                }
                else {
                    if (newPosition.x >= maxWidth) {
                        this.position.x = maxWidth - width;
                    }
                    if (newPosition.y >= maxHeight) {
                        this.position.y = maxHeight - height;
                    }
                    if (newPosition.x <= 0) {
                        this.position.x = width;
                    }
                    if (newPosition.y <= 0) {
                        this.position.y = height;
                    }
                }
            }
        }
    }

    destroy() {
        $.ajax({
            url: this._grid._beaconUrls.beaconPinDeleteUrl,
            type: 'POST',
            data: {
                'id': this._id,
            }
        });
        this._group.destroy();
    }

    add(x, y) {
        var self = this;
        this.counter++;


        // let group = new Konva.Group({
        //     draggable: true, dragBoundFunc: function (pos) {
        //         let x = Math.round(pos.x / self._width) * self._width;
        //         let y = Math.round(pos.y / self._height) * self._height;
        //
        //         return {
        //             x: x,
        //             y: y
        //         };
        //     }
        // });

        // let beaconRect = new Konva.Rect({
        //     id: v4(),
        //     x: x,
        //     y: y,
        //     width: this._width,
        //     height: this._height,
        //     fill: states.PIN_COLOR,
        //     stroke: '#aaa',
        //     opacity: 0.5
        // });
        // group.add(beaconRect);
        // let beaconPinWidth = this._width * this._dimensionX * WIDTH_SCALE;
        // let beaconPinHeight = this._height * this._dimensionY * HEIGHT_SCALE;
        // let beaconPin = new Konva.Rect({
        //     x: x - (beaconPinWidth * X_POSITION_MULTIPLIER),
        //     y: y - (beaconPinHeight * Y_POSITION_MULTIPLIER),
        //     width: beaconPinWidth,
        //     height: beaconPinHeight,
        // });
        // group.add(beaconPin);
        //
        // groupLayer.add(group);
        // let beaconPinImage = makeImageRect(beaconPin, groupLayer);
        // beaconPinImage.src = PIN_IMAGE_SRC;
        // group.on('dragstart', function (e) {
        //     e.cancelBubble = true;
        //     let resultX = beaconRect.x() + group.x();
        //     let resultY = beaconRect.y() + group.y();
        //     self._rects[resultX / self._width][resultY / self._height] = states.EMPTY;
        // });
        // group.on('dragend', function (e) {
        //     e.cancelBubble = true;
        //     let resultX = beaconRect.x() + group.x();
        //     let resultY = beaconRect.y() + group.y();
        //     self._rects[resultX / self._width][resultY / self._height] = states.PIN;
        // });
        // group.on('mouseover click mousedown', function (e) {
        //     e.cancelBubble = true;
        //     return false;
        // });
    };
}