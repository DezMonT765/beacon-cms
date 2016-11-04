import React from "react";
import {v4} from "uuid";
import {pins} from "./reducers/pins";
import {brushes} from "./reducers/brushes";
import {createStore, combineReducers} from "redux";
import * as ReactDOM from "react/lib/ReactDOM";
import {Provider} from "react-redux";
import {App} from "./react-components/App";

let nodeBuffer = [];
let idBuffer = new Set();

export class BeaconMap {
    constructor(mapContainerId, backgroundUrl, cellWidth, cellHeight, columnCount, rowCount) {
        this._mapContainerId = mapContainerId;
        this._backgroundUrl = backgroundUrl;
        this._width = cellWidth;
        this._height = cellHeight;
        this._dimensionX = columnCount;
        this._dimensionY = rowCount;
        this._store = createStore(combineReducers({brushes: brushes, pins: pins}));
        this._store.subscribe(this.render.bind(this));
    }

    init() {
        document.addEventListener('contextmenu', (e)=> {
            e.preventDefault();
            return false;
        });
        document.addEventListener('mousedown', (e) => {
            nodeBuffer = [];
            idBuffer.clear();
            if (e.buttons == 1)
                this._store.dispatch({
                    type: 'TOGGLE_BRUSH',
                    index: 0
                });
            else if (e.buttons == 2) {
                this._store.dispatch({
                    type: 'TOGGLE_BRUSH',
                    index: 1
                });
            }
            this._store.getState().brushes.currentBrush.activated = true;
        });
        document.addEventListener('mouseup', (e) => {
            this._store.getState().brushes.currentBrush.activated = false;
        });

        document.addEventListener('dragend', (e) => {
            this._store.getState().brushes.currentBrush.activated = false;
        });
        this.render();
    }

    render() {
        ReactDOM.render(
            <Provider store={this._store}>
                <App backgroundUrl={this._backgroundUrl}
                     width={this._width}
                     height={this._height}
                     dimensionX={this._dimensionX}
                     dimensionY={this._dimensionY}
                />
            </Provider>,
            document.getElementById(this._mapContainerId));
    }
}



