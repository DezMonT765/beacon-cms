import {pin} from "./pin";
import * as helper from "../helper";
export const pins = (state, action) => {
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