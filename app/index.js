import Konva from "konva";
import Brush from "./Brush";
import React from "react";
import {createStore,applyMiddleware} from "redux";
import * as ReactDOM from "react/lib/ReactDOM";
var blackBrush = new Brush('#000');
var whiteBrush = new Brush('#fff');
const brush = (state = new Brush(), action) => {
    console.log('brush');
    switch (action.type) {
        case 'TOGGLE_BRUSH' :
            currentBrush = state;
            let brush = state;
            brush.toggled = true;
            return brush;
        default :
            return state;
    }
};

const brushes = (state = [blackBrush, whiteBrush], action) => {
    console.log('brushes');
    switch (action.type) {
        case 'TOGGLE_BRUSH' :
            let new_state = state;
            new_state[action.index] = brush(new_state[action.index], action);
            return new_state;
        default :
            return state;
    }
};


const width = 15;
const height = 15;
const dimensionX = 100;
const dimensionY = 100;
const cellPerLayer = 500;
var currentBrush = whiteBrush;



document.addEventListener('mousedown', () => {
    currentBrush.activated = true;

});
document.addEventListener('mouseup', () => {
    currentBrush.activated = false;
});

document.addEventListener('dragend', () => {
    currentBrush.activated = false;
});
var stage = null;
function initCanvas() {
    if (stage !== null) {
        stage.destroy();
    }
    stage = new Konva.Stage({
        container: 'canvas',   // id of container <div>
        width: 1280,
        height: 960
    });


    var nodeCount = 0;
    var layer = new Konva.Layer();
    for (let i = 0; i < dimensionX; i++) {
        for (let j = 0; j < dimensionY; j++) {
            let rect = new Konva.Rect({
                x: i * width,
                y: j * height, 'width': width,
                height: height,
                fill: '#fff',
                stroke: '#aaa'
            });
            layer.add(rect);
            nodeCount++;
            if (nodeCount >= cellPerLayer) {
                nodeCount = 0;
                stage.add(layer);
                layer = new Konva.Layer();
            }
        }
    }

    stage.on('mouseover click mousedown', function (evt) {
        if (currentBrush.activated) {
            var node = evt.target;
            if (node) {
                // update tooltip
                node.setFill(currentBrush.color);
                node.getLayer().draw();

            }
        }
    });
}


var BrushControls = ({className, brushes}) => {
    return (
        <div className={className}>
            Brushes
            {
                brushes.map((brush, index) => (<BrushControl key={index} index={index} brush={brush}/>))
            }
            <button onClick={initCanvas}>Clear</button>
        </div>);
};
class BrushControl extends React.Component {
    render() {
        return (
            <div className="cell"
                 style={{
                     background: this.props.brush.color,
                     border: this.props.brush.toggled ? '1px solid red' : 'none'
                 }}
                 onClick={() => {
                     store.dispatch({type: 'TOGGLE_BRUSH', index: this.props.index})
                 }}></div>);
    }
}


class App extends React.Component {
    componentDidMount() {
        initCanvas();
    }

    render() {
        return (
            <div className="container-fluid">
                <div className="row-fluid">
                    <div className="col-md-10" id="canvas"></div>
                </div>
                <BrushControls brushes={this.props.brushes} className="col-md-2"/>
            </div>
        );
    }
}
import createLogger from 'redux-logger';

const logger = createLogger();
export const store = createStore(brushes,applyMiddleware(logger));
const render = () => {
    console.log('render');
    ReactDOM.render(<App brushes={store.getState()}/>, document.getElementById('root'));
};
render();
store.subscribe(render);

