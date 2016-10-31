import Canvas from "./Canvas";
import Brush from "./Brush";
import React from "react";
import {v4} from "uuid";
import * as helper from "./helper";
import * as states from "./states";
import {createStore, combineReducers} from "redux";
import * as ReactDOM from "react/lib/ReactDOM";
import createLogger from "redux-logger";
let nodeBuffer = [];
let idBuffer = new Set();

document.addEventListener('contextmenu', (e)=> {
    e.preventDefault();
    return false;
});
document.addEventListener('mousedown', (e) => {
    nodeBuffer = [];
    idBuffer.clear();
    if (e.buttons == 1)
        store.dispatch({
            type: 'TOGGLE_BRUSH',
            index: 0
        });
    else if (e.buttons == 2) {
        store.dispatch({
            type: 'TOGGLE_BRUSH',
            index: 1
        });
    }
    store.getState().brushes.currentBrush.activated = true;
});
document.addEventListener('mouseup', (e) => {
    store.getState().brushes.currentBrush.activated = false;
});

document.addEventListener('dragend', (e) => {
    store.getState().brushes.currentBrush.activated = false;
});


const brush = (state = new Brush(), action) => {
    switch (action.type) {
        case 'TOGGLE_BRUSH' :
            let brush = state;
            brush.toggled = true;
            return brush;

        default :
            return state;
    }
};

const brushes = (state = {brushes: [new Brush(states.colors[states.WALL]), new Brush(states.colors[states.EMPTY])], currentBrush: new Brush(states.colors[states.EMPTY])}, action) => {
    switch (action.type) {
        case 'TOGGLE_BRUSH' :
            let new_state = state;
            new_state.currentBrush = new_state.brushes[action.index];
            new_state.brushes = [new Brush(states.colors[states.WALL]), new Brush(states.colors[states.EMPTY])];
            new_state.brushes[action.index] = brush(new_state.brushes[action.index], action);
            return new_state;
        default :
            return state;
    }
};


const pin = (state = {name: null, position: {x: null, y: null}}, action) => {
    let new_state;
    switch (action.type) {
        case 'SET_PIN_POSITION' :
            new_state = {...state};
            new_state.name = action.name;
            new_state.position = action.position;
            return new_state;
        case 'ADD_PIN' :
            new_state = {...state};
            new_state.name = action.name;
            new_state.position = action.position;
            return new_state;
        default :
            return state;
    }
};

const pins = (state, action) => {
    let new_state;
    if (typeof state == 'undefined') {
        let pins = null;
        if (typeof(Storage) !== "undefined") {
            try {
                pins = JSON.parse(localStorage.getItem("pins"));
                pins = helper.objToMap(pins);
            }
            catch (e) {
                console.log(e);
            }
        }
        if (pins == null) {
            pins = new Map;
        }
        state = {pins: pins, currentPin: pin(undefined, action)}
    }
    switch (action.type) {
        case 'TOGGLE_PIN' :
            new_state = {...state};
            new_state.currentPin = state.pins.get(action.name);
            return new_state;
        case 'ADD_PIN' :
            new_state = {...state};
            new_state.pins.set(action.name, pin(undefined, action));
            return new_state;
        case 'SET_PIN_POSITION' : {
            new_state = {...state};
            new_state.pins.set(action.name, pin(undefined, action));
            return new_state;
        }
        case 'CLEAR_PINS' :
            new_state = {...state};
            new_state.pins = new Map();
            return new_state;
        case 'DELETE_PIN' :
            new_state = {...state};
            new_state.pins.delete(action.name);
            new_state.currentPin = pin(undefined, action);
            return new_state;
        default :
            return state;

    }
};


var BrushControls = ({brushes}) => {
    return (
        <div >
            Brushes
            {
                brushes.map((brush, index) => (<BrushControl key={index} index={index} brush={brush}/>))
            }
            <button onClick={function () {
                store.dispatch({
                    type: 'CLEAR_PINS'
                });
                canvas.clear();
            }}>Clear
            </button>
        </div>);
};
class BrushControl extends React.Component {
    render() {
        return (
            <div className="cell"
                 style={{
                     background: states.web_colors[this.props.brush.color],
                     border: this.props.brush.toggled ? '3px solid #B92626' : 'none'
                 }}
                 onClick={() => {
                     store.dispatch({type: 'TOGGLE_BRUSH', index: this.props.index})
                 }}></div>);
    }
}


var PinControls = () => {
    return (<div>
        <button onClick={canvas._grid.addPin.bind(canvas._grid, 0, 0, v4())}>Add pin</button>
        {store.getState().pins.currentPin.name !== null ?
            <div>
                <span>{store.getState().pins.currentPin.name}</span>
                <button onClick={function () {
                    let pin_name = store.getState().pins.currentPin.name;
                    store.dispatch({
                        type: 'DELETE_PIN',
                        name: pin_name
                    });
                    canvas._grid.deletePin(pin_name);
                }}>Delete pin
                </button>
            </div> :
            ''
        }
    </div>)
};

class App extends React.Component {
    render() {
        return (
            <div className="container-fluid">
                <div className="row-fluid">
                    <div className="col-md-10" id="canvas" style={{background: 'url(/background.jpg'}}></div>
                    <div className="col-md-2">
                        <BrushControls brushes={this.props.brushes}/>
                        <PinControls/>
                    </div>
                </div>
            </div>
        );
    }
}

const logger = createLogger();
export const store = createStore(combineReducers({brushes: brushes, pins: pins}));
var canvas = new Canvas(store);
const render = () => {
    ReactDOM.render(<App brushes={store.getState().brushes.brushes}/>, document.getElementById('root'));
};
render();
store.subscribe(render);

