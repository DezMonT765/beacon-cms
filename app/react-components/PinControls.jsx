import * as React from "react";
import {v4} from "uuid";
export default class PinControls extends React.Component {
    constructor(props, context) {
        super(props, context);

    }

    render() {
        var {store} = this.context;
        var state = store.getState();
        var canvas = this.props.canvas;
        var currentPinName = state.pins.currentPin.name;
        return (<div>

            <div className="row">
                <div className="form-group">
                    <button className="btn btn-default" onClick={function () {
                        store.dispatch({
                            type: 'CLEAR_PINS'
                        });
                        if (typeof  canvas !== 'undefined' && canvas !== null) {
                            canvas.clear();
                        }
                    }.bind(this)}>Clear
                    </button>
                    <button className="btn btn-default" onClick={function () {
                        if (typeof  canvas !== 'undefined' && canvas !== null) {
                            canvas.save();
                        }
                    }.bind(this)}>Save
                    </button>
                </div>
                <legend>Manage panel</legend>
                <div className="form-group">
                    <input id="beacon-pin" type="text" value=""/>
                </div>
                <div className="form-group">
                    <button className="btn btn-default" id="add-pin" onClick={
                        function () {
                            if (typeof  canvas !== 'undefined' && canvas !== null) {
                                var beacon = $('#beacon-pin');
                                if (beacon.select2('data') !== null) {
                                    var id = beacon.select2('data').id;
                                    var name = beacon.select2('data').text;
                                    beacon.attr('value', '');
                                    beacon.select2('val', '');
                                    canvas._grid.addPin(0, 0, id, name);
                                }
                            }
                        }.bind(this)}>
                        Add pin
                    </button>

                </div>
                <div className="form-group">
                    { currentPinName !== null ?
                        <div>
                            <span className="form-control">{ currentPinName}</span>
                            <button className="btn btn-default" onClick={function () {

                                if (typeof  canvas !== 'undefined' || canvas !== null) {
                                    canvas._grid.deletePin(currentPinName);
                                }
                            }.bind(this)}>Delete pin
                            </button>
                        </div> :
                        ''
                    }
                </div>
            </div>

        </div>);
    }
};

PinControls.contextTypes = {
    store: React.PropTypes.object
};