import {createStore, combineReducers} from "redux";
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


const row = (state = [], action) => {
    switch (action.type) {
        case 'ADD_CELL' :
            return [
                ...state,
                cell(undefined, action)
            ];
        case 'TOGGLE_CELL' :
            return state.map((c, index) => {
                if (index !== action.x) {
                    return c;
                }
                return cell(c, action);
            });
        default :
            return state;
    }
};

const table = (state = [], action) => {
    switch (action.type) {
        case 'ADD_ROW' :
            return [
                ...state,
                row(undefined, action)
            ];
        case 'ADD_CELL' :
            return [
                ...state.slice(0, action.index),
                row(...state[action.index], action),
                ...state.slice(action.index + 1)
            ];
        case 'TOGGLE_CELL' :
            return state.map((r, index) => {
                if (index !== action.y) {
                    return r;
                }
                return row(r, action);
            });
        case 'SET_TABLE_DIMENSIONS' :
            let table = state;
            if (state.tableDimensions.height <= state.length) {
                table.length = state.tableDimensions.height;
            }
            else {
                let diff =  state.tableDimensions.height - state.length;
                for (let i = 0; i++; i < diff) {
                    table.push(row(undefined, action));
                }
            }
            table.forEach(r => {
                if (state.tableDimensions.width <= r.length) {
                    r.length = state.tableDimensions.width;
                }
                else {
                    let diff =  state.tableDimensions.width - state.length;
                    for (let i = 0; i++; i < diff) {
                        r.push(cell(undefined, action));
                    }
                }
            });
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
        <button onClick={() => {store.dispatch({
            type: 'SET_TABLE_DIMENSIONS',
            width: widthInput.value,
            height: heightInput.value
        })}}> Set dimensions
        </button>
    </div>)
};


class CellApp extends Component {
    render() {
        return (<div>
            <TableDimensionSetters/>
            <table>
                {this.props.table.forEach((r) => (
                    <tr>
                        {r.forEach((c)=> (
                           <td className={'cell' + (c.toggled ? ' toggled' : '')}> </td>
                        ))}
                    </tr>
                ))}
            </table>
        </div>);
    }
}



const store = createStore(combineReducers({table, tableDimensions}));
const render = () => {
    ReactDom.render(<CellApp
            table={store.getState().table}
        />,
        document.getElementById('root')
    )
};
store.subscribe(render);
render();
