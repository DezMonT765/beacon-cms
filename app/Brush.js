export default class Brush {
    constructor(color = '#fff',activated = false,toggled = false) {
        this._color = color;
        this._activated = activated;
        this._toggled = toggled;
    }

    get color() {
        return this._color
    }

    set color(color) {
        this._color = color;
    }

    get activated() {
        return this._activated;
    }

    set activated(activated) {
        this._activated = activated;
    }

    get toggled() {
        return this._toggled;
    }

    set toggled(toggled) {
        this._toggled = toggled;
    }
}

