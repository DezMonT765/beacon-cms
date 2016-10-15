import {createStore,combineReducers} from "redux";
import {v4} from "uuid";
import * as React from "react";
import {Component} from "react";
import * as ReactDom from "react/lib/ReactDOM";
const cell = (state = {id: v4(), toggled: false}, action) => {
    switch (action.type) {
        case 'TOGGLE_CELL' :
            return {
                ...state,
                toggled: !state.toggled
            };

        default :
            return state;
    }
};


const row = (state, action) => {
    if (state === undefined) {
        return [cell(undefined, action)]
    }

    switch (action.type) {
        case 'ADD_CELL' :
            return [
                ...state,
                cell(undefined, action)
            ];
        case 'TOGGLE_CELL' :
                let new_state = state;
                new_state[action.x] = cell(new_state[action.x],action);
                return new_state;
        default :
            return state;
    }
};

const brush = (state = false,action) => {
    switch(action.type) {
        case 'BRUSH_DOWN'  :
            return true;
        case 'BRUSH_UP' :
            return false;
        default :
            return state;
    }
};

const table = (state, action) => {
    if (state === undefined) {
        state = [row(undefined, action)];
    }
    switch (action.type) {
        case 'ADD_ROW' :
            return [
                ...state,
                row(undefined, action)
            ];
        case 'ADD_CELL' :
            return state.map((r) => {
                return row(r, action)
            });
        case 'TOGGLE_CELL' :
                let new_state = state;
                new_state[action.y] = row(new_state[action.y],action);
                return new_state;
        case 'SET_TABLE_DIMENSIONS' :
            let table = state;

            if (action.height <= table.length) {
                table.length = action.height;
            }
            else {
                var diff = action.height - table.length;
                for (let i = 0;i < diff; i++ ) {
                    table.push(row(undefined,action));
                }
            }
           table.forEach(r => {
                if (action.width <= r.length) {
                    r.length = action.width;
                }
                else {
                    let diff = action.width - r.length;
                    for (let i = 0;i < diff; i++) {
                        r.push(cell(undefined, action));
                    }
                }
            });
            console.log(table);
            return table;
        default :
            return state;
    }
};

const tableDimensions = (state = {width: 1, height: 1}, action) => {
    switch (action.type) {
        case 'SET_TABLE_DIMENSIONS' :
            return {
                width: action.width,
                height: action.height
            };
        default:
            return state;
    }
};

const TableDimensionSetters = () => {
    let heightInput;
    let widthInput;

    return (<div>
        <label>Height</label>
        <input ref={node => heightInput = node} type="text"/>
        <label>Width</label>
        <input ref={node => widthInput = node} type="text"/>
        <button onClick={() => {
            store.dispatch({
                type: 'SET_TABLE_DIMENSIONS',
                width: widthInput.value,
                height: heightInput.value
            })
        }}> Set dimensions
        </button>
    </div>)
};

document.addEventListener('mousedown',() => {
    store.dispatch({
        type : 'BRUSH_DOWN'
    });

});
document.addEventListener('mouseup',() => {
    store.dispatch({
        type : 'BRUSH_UP'
    });
});

document.addEventListener('dragend',() => {
    store.dispatch({
        type : 'BRUSH_UP'
    });
});

class CellApp extends Component {
    toggleCell(x,y)  {
        return (e) => {
            if (store.getState().brush) {
            console.log(e.target);
                store.dispatch({
                    type: 'TOGGLE_CELL',
                    x: x,
                    y: y
                })
            }
        }
    }
    render() {
        return (<div>
            <TableDimensionSetters/>
            <button onClick={() => {
                store.dispatch({
                    type: 'ADD_ROW'
                })
            }}>Add row
            </button>
            <button onClick={() => {
                store.dispatch({
                    type: 'ADD_CELL'
                })
            }}>Add cell
            </button>

            <svg height={this.props.table.length*30} width={this.props.table[0].length*30}>
                {this.props.table.map((r, y) => (
                        r.map((c, x)=> (
                            <rect x={x * 30} y={y*30} width="30" height="30" key={x} fill={c.toggled ? '#000' : '#fff'} onMouseDown={this.toggleCell(x,y)} onMouseOver={this.toggleCell(x,y)}> </rect>
                        ))
                ))}
            </svg>
        </div>);
    };
}
export const store = createStore(combineReducers({table :table ,brush : brush,tableDimensions:tableDimensions}));
const render = () => {
    ReactDom.render(<CellApp
            table={store.getState().table}
        />,
        document.getElementById('root')
    )
};

store.subscribe(render);
render();


