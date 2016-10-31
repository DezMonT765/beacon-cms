export function makeImageRect(rect, layer) {
    let image = new Image();
    image.onload = function () {
        rect.fillPatternImage(image);
        rect.fillPatternScaleX(rect.width() / image.width);
        rect.fillPatternScaleY(rect.height() / image.height);
        layer.draw();
    };
    return image;
}

export function sortChildrenByZIndex(container) {
    container.children.sort(function (a, b) {
        a.zIndex = a.zIndex || 0;
        b.zIndex = b.zIndex || 0;
        return a.zIndex - b.zIndex;
    });
}

export function mapToObj(map) {
    let obj = Object.create(null);
    for (let [k,v] of map) {
        // We don’t escape the key '__proto__'
        // which can cause problems on older engines
        obj[k] = v;
    }
    return obj;
}

export function objToMap(obj) {
    let map = new Map();
    for (let k of Object.keys(obj)) {
        map.set(k, obj[k]);
    }
    return map;
}
