export const pin = (state = {name: null, position: {x: null, y: null}}, action) => {
    let new_state;
    switch (action.type) {
        case 'SET_PIN_POSITION' :
            new_state = {...state};
            new_state.name = action.name;
            new_state.position = action.position;
            return new_state;
        case 'ADD_PIN' :
            new_state = {...state};
            new_state.name = action.name;
            new_state.position = action.position;
            return new_state;
        default :
            return state;
    }
};