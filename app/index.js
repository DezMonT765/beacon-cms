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
    constructor(mapContainerId) {
        this._store =  createStore(combineReducers({brushes: brushes, pins: pins}));
        this._store.subscribe(this.render.bind(this));
        this._mapContainerId = mapContainerId
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
                <App/>
            </Provider>,
            document.getElementById(this._mapContainerId));
    }
}



