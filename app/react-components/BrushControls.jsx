import * as React from "react";
import {BrushControl} from './BrushControl';
export var BrushControls = ({brushes}) => {
    return (
        <div >
            Brushes
            {
                brushes.map((brush, index) => (<BrushControl key={index} index={index} brush={brush}/>))
            }
        </div>);
};

