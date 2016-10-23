import Konva from "konva";
export default class Grid {
    constructor(stage,width,height,dimensionX,dimensionY) {
        this._colors = ['#fff', '#000'];
        this._stage = stage;
        this._width = width;
        this._height = height;
        this._dimensionX = dimensionX;
        this._dimensionY = dimensionY;
    }
    buildGrid() {
        var nodeCount = 0;
        var layer = new Konva.Layer();
        for (let i = 0; i < this._dimensionX; i++) {
            for (let j = 0; j < this._dimensionY; j++) {

                let rect = new Konva.Rect({
                    id: v4(),
                    x: i * this._width,
                    y: j * this._height,
                    width: this._width,
                    height: this._height,
                    fill: this._colors[this._rects[i][j]],
                    stroke: '#aaa',
                    opacity: 0.5
                });
                if (this._rects[i][j] == 2) {
                    pin.add(groupLayer, i * WIDTH, j * HEIGHT);
                }
                layer.add(rect);
                nodeCount++;
                if (nodeCount >= cellPerLayer) {
                    nodeCount = 0;
                    this._stage.add(layer);
                    layer = new Konva.Layer();
                }
            }
        }
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
    }
    set rects(rects) {
        this._rects = rects;
    }
}
