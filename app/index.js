import initCanvas from "./Canvas";
import Brush from "./Brush";
import React from "react";
import {createStore, applyMiddleware} from "redux";
import * as ReactDOM from "react/lib/ReactDOM";
import createLogger from "redux-logger";
let nodeBuffer = new Map();
let idBuffer = new Set();
document.addEventListener('contextmenu', (e)=> {
    e.preventDefault();
    return false;
});
document.addEventListener('mousedown', (e) => {
    nodeBuffer = [];
    if (e.buttons == 1)
        store.dispatch({
            type: 'TOGGLE_BRUSH',
            index : 0
        });
    else if (e.buttons == 2) {
        store.dispatch({
            type: 'TOGGLE_BRUSH',
            index : 1
        });
    }
    store.getState().currentBrush.activated = true;
});
document.addEventListener('mouseup', (e) => {
    store.getState().currentBrush.activated = false;
});

document.addEventListener('dragend', (e) => {
    store.getState().currentBrush.activated = false;
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

const brushes = (state = {brushes: [new Brush('#000'), new Brush('#fff')], currentBrush: new Brush('#fff')}, action) => {
    switch (action.type) {
        case 'TOGGLE_BRUSH' :
            let new_state = state;
            new_state.currentBrush = new_state.brushes[action.index];
            new_state.brushes = [new Brush('#000'), new Brush('#fff')];
            new_state.brushes[action.index] = brush(new_state.brushes[action.index], action);
            return new_state;
        default :
            return state;
    }
};


var BrushControls = ({className, brushes}) => {
    return (
        <div className={className}>
            Brushes
            {
                brushes.map((brush, index) => (<BrushControl key={index} index={index} brush={brush}/>))
            }
            <button onClick={initCanvas.bind(null, store,nodeBuffer,idBuffer)}>Clear</button>
        </div>);
};
class BrushControl extends React.Component {
    render() {
        return (
            <div className="cell"
                 style={{
                     background: this.props.brush.color,
                     border: this.props.brush.toggled ? '3px solid #B92626' : 'none'
                 }}
                 onClick={() => {
                     store.dispatch({type: 'TOGGLE_BRUSH', index: this.props.index})
                 }}></div>);
    }
}


class App extends React.Component {
    componentDidMount() {
        initCanvas(store,nodeBuffer,idBuffer);
    }

    render() {
        return (
            <div className="container-fluid">
                <div className="row-fluid">
                    <div className="col-md-10" id="canvas" style={{background: 'url(/background.jpg'}}></div>
                </div>
                <BrushControls brushes={this.props.brushes} className="col-md-2"/>
            </div>
        );
    }
}

const logger = createLogger();
export const store = createStore(brushes);
const render = () => {
    ReactDOM.render(<App brushes={store.getState().brushes}/>, document.getElementById('root'));
};
render();
store.subscribe(render);

