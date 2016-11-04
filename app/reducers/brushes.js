import Brush from "../Brush";
import {brush} from "./brush";
import * as states from "../states";
export const brushes = (state = {
    brushes: [
        new Brush(states.colors[states.WALL]),
        new Brush(states.colors[states.EMPTY])],
    currentBrush: new Brush(states.colors[states.EMPTY])
}, action) => {
    switch (action.type) {
        case 'TOGGLE_BRUSH' :
            let new_state = {...state};
            new_state.currentBrush = new_state.brushes[action.index];
            new_state.brushes = [new Brush(states.colors[states.WALL]), new Brush(states.colors[states.EMPTY])];
            new_state.brushes[action.index] = brush(new_state.brushes[action.index], action);
            return new_state;
        default :
            return state;
    }
};