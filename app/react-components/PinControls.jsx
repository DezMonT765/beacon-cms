import * as React from "react";
import {v4} from "uuid";
export var PinControls = ({canvas}, {store}) => {
    const state = store.getState();
    const currentPinName = state.pins.currentPin.name;
    return (<div>
        <button onClick={function () {
            store.dispatch({
                type: 'CLEAR_PINS'
            });

        }}>Clear
        </button>
        <button onClick={
            canvas._grid.addPin.bind(canvas._grid, 0, 0, v4())
        }>Add pin</button>
        {currentPinName !== null ?
            <div>
                <span>{currentPinName}</span>
                <button onClick={function () {
                    store.dispatch({
                        type: 'DELETE_PIN',
                        name: currentPinName
                    });
                }}>Delete pin
                </button>
            </div> :
            ''
        }
    </div>)
};

PinControls.contextTypes = {
    store: React.PropTypes.object
};