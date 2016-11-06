export const pin = (state = {id : null,name: null, x: null, y: null}, action) => {
    let new_state;
    switch (action.type) {
        case 'SET_PIN_POSITION' :
        case 'ADD_PIN' :
            new_state = {...state};
            new_state.id = action.id;
            new_state.name = action.name;
            new_state.x = action.x;
            new_state.y = action.y;
            return new_state;
        default :
            return state;
    }
};