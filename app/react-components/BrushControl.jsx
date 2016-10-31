import * as React from "react";
import * as states from "../states";
export class BrushControl extends React.Component {
    constructor(props,context) {
        super(props,context);
        const {store} = context;
        const {index} = props;
        this._store = store;
        this._index = index;
    }

    onClick() {
        this._store.dispatch({type: 'TOGGLE_BRUSH', index: this._index})
    }

    render() {
        const {brush} = this.props;
        return (
            <div className="cell"
                 style={{
                     background: states.web_colors[brush.color],
                     border: brush.toggled ? '3px solid #B92626' : 'none'
                 }}
                 onClick={this.onClick.bind(this)}></div>);
    }
}

BrushControl.contextTypes = {
    store: React.PropTypes.object
};