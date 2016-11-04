import Brush from "../Brush";
export const brush = (state = new Brush(), action) => {
    switch (action.type) {
        case 'TOGGLE_BRUSH' :
            let brush = new Brush(state.color, state.activate, true);
            return brush;

        default :
            return state;
    }
};