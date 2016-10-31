import {PinControls} from "./PinControls";
import {BrushControls} from "./BrushControls";
import * as React from "react";
export class App extends React.Component {
    render() {
        const {canvas} = this.props;
        const {store} = this.context;
        const brushes = store.getState().brushes.brushes;
        return (
            <div className="container-fluid">
                <div className="row-fluid">
                    <div className="col-md-10" id="canvas"></div>
                    <div className="col-md-2">
                        <BrushControls brushes={brushes}/>
                        <PinControls canvas={canvas}/>
                    </div>
                </div>
            </div>
        );
    }
}
App.contextTypes = {
    store: React.PropTypes.object
};