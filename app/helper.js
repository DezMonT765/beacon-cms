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
