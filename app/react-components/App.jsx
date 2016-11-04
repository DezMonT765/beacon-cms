import Canvas from "../Canvas";
import {PinControls} from "./PinControls";
import {BrushControls} from "./BrushControls";
import * as React from "react";
export class App extends React.Component {
    constructor(props, context) {
        super(props, context);
        this._canvas = null;
        this._store = context.store;
    }

    componentDidMount() {
        this._canvas = new Canvas(this._store, document.getElementById('canvas'))
    }

    render() {
        const brushes = this._store.getState().brushes.brushes;
        return (
            <div className="container-fluid">
                <div className="row-fluid">
                    <div className="col-md-10">
                        <canvas id="canvas"/>
                    </div>
                </div>
                <div className="col-md-2">
                    <BrushControls brushes={brushes}/>
                    <PinControls canvas={this._canvas}/>
                </div>
            </div>
        );
    }
}
App.contextTypes = {
    store: React.PropTypes.object
};