import React from "react";
import {v4} from "uuid";
import {pins} from "./reducers/pins";
import {pin} from "./reducers/pin";
import {brushes} from "./reducers/brushes";
import {createStore, combineReducers} from "redux";
import * as ReactDOM from "react/lib/ReactDOM";
import {Provider} from "react-redux";
import * as helper from "./helper";
import {App} from "./react-components/App";

let nodeBuffer = [];
let idBuffer = new Set();

export class BeaconMap {
    /**
     *
     * @param mapContainerId
     * @param config
     * @param config.backgroundUrl
     * @param config.beaconPinSaveUrl
     * @param config.beaconPinDeleteUrl
     * @param config.beaconPinListUrl
     * @param config.beaconMapSaveUrl
     * @param config.beaconMapGetUrl
     * @param config.beaconPin
     * @param config.width
     * @param config.height
     * @param config.dimensionX
     * @param config.dimensionY
     */
    constructor(mapContainerId, config) {
        this._mapContainerId = mapContainerId;
        this._gridConfig = config;
        let self = this;
        let mainReducer = combineReducers({brushes: brushes, pins: pins});
        $.ajax({
            url: config.beaconPinListUrl,
            async: false,
            dataType: "json",
            cache: false,
            success: function (data) {
                self._store = createStore(mainReducer, {
                    brushes: undefined, pins: {
                        pins: helper.objToMap(data.pins),
                        currentPin: {id : null,name : null,position : {x : null,y : null}}
                    }
                });
                self._store.subscribe(self.render.bind(self));
            }
        });


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
                <App gridConfig={this._gridConfig}/>
            </Provider>,
            document.getElementById(this._mapContainerId));
    }
}



