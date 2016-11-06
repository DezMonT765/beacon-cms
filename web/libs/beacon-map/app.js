var lib =
webpackJsonplib([0,4],{

/***/ 0:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.BeaconMap = undefined;
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _react = __webpack_require__(1);
	
	var _react2 = _interopRequireDefault(_react);
	
	var _uuid = __webpack_require__(34);
	
	var _pins = __webpack_require__(36);
	
	var _pin = __webpack_require__(37);
	
	var _brushes = __webpack_require__(38);
	
	var _redux = __webpack_require__(42);
	
	var _ReactDOM = __webpack_require__(56);
	
	var ReactDOM = _interopRequireWildcard(_ReactDOM);
	
	var _reactRedux = __webpack_require__(193);
	
	var _helper = __webpack_require__(202);
	
	var helper = _interopRequireWildcard(_helper);
	
	var _App = __webpack_require__(203);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	var nodeBuffer = [];
	var idBuffer = new Set();
	
	var BeaconMap = exports.BeaconMap = function () {
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
	    function BeaconMap(mapContainerId, config) {
	        _classCallCheck(this, BeaconMap);
	
	        this._mapContainerId = mapContainerId;
	        this._gridConfig = config;
	        var self = this;
	        var mainReducer = (0, _redux.combineReducers)({ brushes: _brushes.brushes, pins: _pins.pins });
	        $.ajax({
	            url: config.beaconPinListUrl,
	            async: false,
	            dataType: "json",
	            cache: false,
	            success: function success(data) {
	                self._store = (0, _redux.createStore)(mainReducer, {
	                    brushes: undefined, pins: {
	                        pins: helper.objToMap(data.pins),
	                        currentPin: { id: null, name: null, position: { x: null, y: null } }
	                    }
	                });
	                self._store.subscribe(self.render.bind(self));
	            }
	        });
	    }
	
	    _createClass(BeaconMap, [{
	        key: "init",
	        value: function init() {
	            var _this = this;
	
	            document.addEventListener('contextmenu', function (e) {
	                e.preventDefault();
	                return false;
	            });
	            document.addEventListener('mousedown', function (e) {
	                nodeBuffer = [];
	                idBuffer.clear();
	                if (e.buttons == 1) _this._store.dispatch({
	                    type: 'TOGGLE_BRUSH',
	                    index: 0
	                });else if (e.buttons == 2) {
	                    _this._store.dispatch({
	                        type: 'TOGGLE_BRUSH',
	                        index: 1
	                    });
	                }
	                _this._store.getState().brushes.currentBrush.activated = true;
	            });
	            document.addEventListener('mouseup', function (e) {
	                _this._store.getState().brushes.currentBrush.activated = false;
	            });
	
	            document.addEventListener('dragend', function (e) {
	                _this._store.getState().brushes.currentBrush.activated = false;
	            });
	            this.render();
	        }
	    }, {
	        key: "render",
	        value: function render() {
	            ReactDOM.render(_react2.default.createElement(
	                _reactRedux.Provider,
	                { store: this._store },
	                _react2.default.createElement(_App.App, { gridConfig: this._gridConfig })
	            ), document.getElementById(this._mapContainerId));
	        }
	    }]);

	    return BeaconMap;
	}();

/***/ },

/***/ 34:
/***/ function(module, exports, __webpack_require__) {

	//     uuid.js
	//
	//     Copyright (c) 2010-2012 Robert Kieffer
	//     MIT License - http://opensource.org/licenses/mit-license.php
	
	// Unique ID creation requires a high quality random # generator.  We feature
	// detect to determine the best RNG source, normalizing to a function that
	// returns 128-bits of randomness, since that's what's usually required
	var _rng = __webpack_require__(35);
	
	// Maps for number <-> hex string conversion
	var _byteToHex = [];
	var _hexToByte = {};
	for (var i = 0; i < 256; i++) {
	  _byteToHex[i] = (i + 0x100).toString(16).substr(1);
	  _hexToByte[_byteToHex[i]] = i;
	}
	
	// **`parse()` - Parse a UUID into it's component bytes**
	function parse(s, buf, offset) {
	  var i = (buf && offset) || 0, ii = 0;
	
	  buf = buf || [];
	  s.toLowerCase().replace(/[0-9a-f]{2}/g, function(oct) {
	    if (ii < 16) { // Don't overflow!
	      buf[i + ii++] = _hexToByte[oct];
	    }
	  });
	
	  // Zero out remaining bytes if string was short
	  while (ii < 16) {
	    buf[i + ii++] = 0;
	  }
	
	  return buf;
	}
	
	// **`unparse()` - Convert UUID byte array (ala parse()) into a string**
	function unparse(buf, offset) {
	  var i = offset || 0, bth = _byteToHex;
	  return  bth[buf[i++]] + bth[buf[i++]] +
	          bth[buf[i++]] + bth[buf[i++]] + '-' +
	          bth[buf[i++]] + bth[buf[i++]] + '-' +
	          bth[buf[i++]] + bth[buf[i++]] + '-' +
	          bth[buf[i++]] + bth[buf[i++]] + '-' +
	          bth[buf[i++]] + bth[buf[i++]] +
	          bth[buf[i++]] + bth[buf[i++]] +
	          bth[buf[i++]] + bth[buf[i++]];
	}
	
	// **`v1()` - Generate time-based UUID**
	//
	// Inspired by https://github.com/LiosK/UUID.js
	// and http://docs.python.org/library/uuid.html
	
	// random #'s we need to init node and clockseq
	var _seedBytes = _rng();
	
	// Per 4.5, create and 48-bit node id, (47 random bits + multicast bit = 1)
	var _nodeId = [
	  _seedBytes[0] | 0x01,
	  _seedBytes[1], _seedBytes[2], _seedBytes[3], _seedBytes[4], _seedBytes[5]
	];
	
	// Per 4.2.2, randomize (14 bit) clockseq
	var _clockseq = (_seedBytes[6] << 8 | _seedBytes[7]) & 0x3fff;
	
	// Previous uuid creation time
	var _lastMSecs = 0, _lastNSecs = 0;
	
	// See https://github.com/broofa/node-uuid for API details
	function v1(options, buf, offset) {
	  var i = buf && offset || 0;
	  var b = buf || [];
	
	  options = options || {};
	
	  var clockseq = options.clockseq !== undefined ? options.clockseq : _clockseq;
	
	  // UUID timestamps are 100 nano-second units since the Gregorian epoch,
	  // (1582-10-15 00:00).  JSNumbers aren't precise enough for this, so
	  // time is handled internally as 'msecs' (integer milliseconds) and 'nsecs'
	  // (100-nanoseconds offset from msecs) since unix epoch, 1970-01-01 00:00.
	  var msecs = options.msecs !== undefined ? options.msecs : new Date().getTime();
	
	  // Per 4.2.1.2, use count of uuid's generated during the current clock
	  // cycle to simulate higher resolution clock
	  var nsecs = options.nsecs !== undefined ? options.nsecs : _lastNSecs + 1;
	
	  // Time since last uuid creation (in msecs)
	  var dt = (msecs - _lastMSecs) + (nsecs - _lastNSecs)/10000;
	
	  // Per 4.2.1.2, Bump clockseq on clock regression
	  if (dt < 0 && options.clockseq === undefined) {
	    clockseq = clockseq + 1 & 0x3fff;
	  }
	
	  // Reset nsecs if clock regresses (new clockseq) or we've moved onto a new
	  // time interval
	  if ((dt < 0 || msecs > _lastMSecs) && options.nsecs === undefined) {
	    nsecs = 0;
	  }
	
	  // Per 4.2.1.2 Throw error if too many uuids are requested
	  if (nsecs >= 10000) {
	    throw new Error('uuid.v1(): Can\'t create more than 10M uuids/sec');
	  }
	
	  _lastMSecs = msecs;
	  _lastNSecs = nsecs;
	  _clockseq = clockseq;
	
	  // Per 4.1.4 - Convert from unix epoch to Gregorian epoch
	  msecs += 12219292800000;
	
	  // `time_low`
	  var tl = ((msecs & 0xfffffff) * 10000 + nsecs) % 0x100000000;
	  b[i++] = tl >>> 24 & 0xff;
	  b[i++] = tl >>> 16 & 0xff;
	  b[i++] = tl >>> 8 & 0xff;
	  b[i++] = tl & 0xff;
	
	  // `time_mid`
	  var tmh = (msecs / 0x100000000 * 10000) & 0xfffffff;
	  b[i++] = tmh >>> 8 & 0xff;
	  b[i++] = tmh & 0xff;
	
	  // `time_high_and_version`
	  b[i++] = tmh >>> 24 & 0xf | 0x10; // include version
	  b[i++] = tmh >>> 16 & 0xff;
	
	  // `clock_seq_hi_and_reserved` (Per 4.2.2 - include variant)
	  b[i++] = clockseq >>> 8 | 0x80;
	
	  // `clock_seq_low`
	  b[i++] = clockseq & 0xff;
	
	  // `node`
	  var node = options.node || _nodeId;
	  for (var n = 0; n < 6; n++) {
	    b[i + n] = node[n];
	  }
	
	  return buf ? buf : unparse(b);
	}
	
	// **`v4()` - Generate random UUID**
	
	// See https://github.com/broofa/node-uuid for API details
	function v4(options, buf, offset) {
	  // Deprecated - 'format' argument, as supported in v1.2
	  var i = buf && offset || 0;
	
	  if (typeof(options) == 'string') {
	    buf = options == 'binary' ? new Array(16) : null;
	    options = null;
	  }
	  options = options || {};
	
	  var rnds = options.random || (options.rng || _rng)();
	
	  // Per 4.4, set bits for version and `clock_seq_hi_and_reserved`
	  rnds[6] = (rnds[6] & 0x0f) | 0x40;
	  rnds[8] = (rnds[8] & 0x3f) | 0x80;
	
	  // Copy bytes to buffer, if provided
	  if (buf) {
	    for (var ii = 0; ii < 16; ii++) {
	      buf[i + ii] = rnds[ii];
	    }
	  }
	
	  return buf || unparse(rnds);
	}
	
	// Export public API
	var uuid = v4;
	uuid.v1 = v1;
	uuid.v4 = v4;
	uuid.parse = parse;
	uuid.unparse = unparse;
	
	module.exports = uuid;


/***/ },

/***/ 35:
/***/ function(module, exports) {

	/* WEBPACK VAR INJECTION */(function(global) {
	var rng;
	
	var crypto = global.crypto || global.msCrypto; // for IE 11
	if (crypto && crypto.getRandomValues) {
	  // WHATWG crypto-based RNG - http://wiki.whatwg.org/wiki/Crypto
	  // Moderately fast, high quality
	  var _rnds8 = new Uint8Array(16);
	  rng = function whatwgRNG() {
	    crypto.getRandomValues(_rnds8);
	    return _rnds8;
	  };
	}
	
	if (!rng) {
	  // Math.random()-based (RNG)
	  //
	  // If all else fails, use Math.random().  It's fast, but is of unspecified
	  // quality.
	  var  _rnds = new Array(16);
	  rng = function() {
	    for (var i = 0, r; i < 16; i++) {
	      if ((i & 0x03) === 0) r = Math.random() * 0x100000000;
	      _rnds[i] = r >>> ((i & 0x03) << 3) & 0xff;
	    }
	
	    return _rnds;
	  };
	}
	
	module.exports = rng;
	
	
	/* WEBPACK VAR INJECTION */}.call(exports, (function() { return this; }())))

/***/ },

/***/ 36:
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.pins = undefined;
	
	var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };
	
	var _pin = __webpack_require__(37);
	
	var pins = exports.pins = function pins(state, action) {
	    var new_state = void 0;
	    if (typeof state == 'undefined') {
	        var _pins = new Map();
	        state = { pins: _pins, currentPin: (0, _pin.pin)(undefined, action) };
	    }
	    switch (action.type) {
	        case 'TOGGLE_PIN':
	            new_state = _extends({}, state);
	            new_state.currentPin = state.pins.get(action.name);
	            return new_state;
	        case 'ADD_PIN':
	            new_state = _extends({}, state);
	            new_state.pins.set(action.name, (0, _pin.pin)(undefined, action));
	            return new_state;
	        case 'SET_PIN_POSITION':
	            {
	                new_state = _extends({}, state);
	                new_state.pins.set(action.name, (0, _pin.pin)(undefined, action));
	                return new_state;
	            }
	        case 'CLEAR_PINS':
	            new_state = _extends({}, state);
	            new_state.pins = new Map();
	            return new_state;
	        case 'DELETE_PIN':
	            new_state = _extends({}, state);
	            new_state.pins.delete(action.name);
	            new_state.currentPin = (0, _pin.pin)(undefined, action);
	            return new_state;
	        default:
	            return state;
	
	    }
	};

/***/ },

/***/ 37:
/***/ function(module, exports) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };
	
	var pin = exports.pin = function pin() {
	    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : { id: null, name: null, x: null, y: null };
	    var action = arguments[1];
	
	    var new_state = void 0;
	    switch (action.type) {
	        case 'SET_PIN_POSITION':
	        case 'ADD_PIN':
	            new_state = _extends({}, state);
	            new_state.id = action.id;
	            new_state.name = action.name;
	            new_state.x = action.x;
	            new_state.y = action.y;
	            return new_state;
	        default:
	            return state;
	    }
	};

/***/ },

/***/ 38:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.brushes = undefined;
	
	var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };
	
	var _Brush = __webpack_require__(39);
	
	var _Brush2 = _interopRequireDefault(_Brush);
	
	var _brush = __webpack_require__(40);
	
	var _states = __webpack_require__(41);
	
	var states = _interopRequireWildcard(_states);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	var brushes = exports.brushes = function brushes() {
	    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {
	        brushes: [new _Brush2.default(states.colors[states.WALL]), new _Brush2.default(states.colors[states.EMPTY])],
	        currentBrush: new _Brush2.default(states.colors[states.EMPTY])
	    };
	    var action = arguments[1];
	
	    switch (action.type) {
	        case 'TOGGLE_BRUSH':
	            var new_state = _extends({}, state);
	            new_state.currentBrush = new_state.brushes[action.index];
	            new_state.brushes = [new _Brush2.default(states.colors[states.WALL]), new _Brush2.default(states.colors[states.EMPTY])];
	            new_state.brushes[action.index] = (0, _brush.brush)(new_state.brushes[action.index], action);
	            return new_state;
	        default:
	            return state;
	    }
	};

/***/ },

/***/ 39:
/***/ function(module, exports) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	var Brush = function () {
	    function Brush() {
	        var color = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0xFFFFFF;
	        var activated = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
	        var toggled = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
	
	        _classCallCheck(this, Brush);
	
	        this._color = color;
	        this._activated = activated;
	        this._toggled = toggled;
	    }
	
	    _createClass(Brush, [{
	        key: "color",
	        get: function get() {
	            return this._color;
	        },
	        set: function set(color) {
	            this._color = color;
	        }
	    }, {
	        key: "activated",
	        get: function get() {
	            return this._activated;
	        },
	        set: function set(activated) {
	            this._activated = activated;
	        }
	    }, {
	        key: "toggled",
	        get: function get() {
	            return this._toggled;
	        },
	        set: function set(toggled) {
	            this._toggled = toggled;
	        }
	    }]);
	
	    return Brush;
	}();
	
	exports.default = Brush;

/***/ },

/***/ 40:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.brush = undefined;
	
	var _Brush = __webpack_require__(39);
	
	var _Brush2 = _interopRequireDefault(_Brush);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	var brush = exports.brush = function brush() {
	    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : new _Brush2.default();
	    var action = arguments[1];
	
	    switch (action.type) {
	        case 'TOGGLE_BRUSH':
	            var _brush = new _Brush2.default(state.color, state.activate, true);
	            return _brush;
	
	        default:
	            return state;
	    }
	};

/***/ },

/***/ 41:
/***/ function(module, exports) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	var EMPTY = exports.EMPTY = 0;
	var WALL = exports.WALL = 1;
	var PIN = exports.PIN = 2;
	var PIN_COLOR = exports.PIN_COLOR = 0x007FFF;
	var colors = exports.colors = [];
	colors[EMPTY] = 0xFFFFFF;
	colors[WALL] = 0x000000;
	var web_colors = exports.web_colors = [];
	web_colors[0xFFFFFF] = '#fff';
	web_colors[0x000000] = '#000';

/***/ },

/***/ 42:
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	exports.__esModule = true;
	exports.compose = exports.applyMiddleware = exports.bindActionCreators = exports.combineReducers = exports.createStore = undefined;
	
	var _createStore = __webpack_require__(43);
	
	var _createStore2 = _interopRequireDefault(_createStore);
	
	var _combineReducers = __webpack_require__(51);
	
	var _combineReducers2 = _interopRequireDefault(_combineReducers);
	
	var _bindActionCreators = __webpack_require__(53);
	
	var _bindActionCreators2 = _interopRequireDefault(_bindActionCreators);
	
	var _applyMiddleware = __webpack_require__(54);
	
	var _applyMiddleware2 = _interopRequireDefault(_applyMiddleware);
	
	var _compose = __webpack_require__(55);
	
	var _compose2 = _interopRequireDefault(_compose);
	
	var _warning = __webpack_require__(52);
	
	var _warning2 = _interopRequireDefault(_warning);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }
	
	/*
	* This is a dummy function to check if the function name has been altered by minification.
	* If the function has been minified and NODE_ENV !== 'production', warn the user.
	*/
	function isCrushed() {}
	
	if (("dev") !== 'production' && typeof isCrushed.name === 'string' && isCrushed.name !== 'isCrushed') {
	  (0, _warning2['default'])('You are currently using minified code outside of NODE_ENV === \'production\'. ' + 'This means that you are running a slower development build of Redux. ' + 'You can use loose-envify (https://github.com/zertosh/loose-envify) for browserify ' + 'or DefinePlugin for webpack (http://stackoverflow.com/questions/30030031) ' + 'to ensure you have the correct code for your production build.');
	}
	
	exports.createStore = _createStore2['default'];
	exports.combineReducers = _combineReducers2['default'];
	exports.bindActionCreators = _bindActionCreators2['default'];
	exports.applyMiddleware = _applyMiddleware2['default'];
	exports.compose = _compose2['default'];

/***/ },

/***/ 43:
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	exports.__esModule = true;
	exports.ActionTypes = undefined;
	exports['default'] = createStore;
	
	var _isPlainObject = __webpack_require__(44);
	
	var _isPlainObject2 = _interopRequireDefault(_isPlainObject);
	
	var _symbolObservable = __webpack_require__(48);
	
	var _symbolObservable2 = _interopRequireDefault(_symbolObservable);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }
	
	/**
	 * These are private action types reserved by Redux.
	 * For any unknown actions, you must return the current state.
	 * If the current state is undefined, you must return the initial state.
	 * Do not reference these action types directly in your code.
	 */
	var ActionTypes = exports.ActionTypes = {
	  INIT: '@@redux/INIT'
	};
	
	/**
	 * Creates a Redux store that holds the state tree.
	 * The only way to change the data in the store is to call `dispatch()` on it.
	 *
	 * There should only be a single store in your app. To specify how different
	 * parts of the state tree respond to actions, you may combine several reducers
	 * into a single reducer function by using `combineReducers`.
	 *
	 * @param {Function} reducer A function that returns the next state tree, given
	 * the current state tree and the action to handle.
	 *
	 * @param {any} [preloadedState] The initial state. You may optionally specify it
	 * to hydrate the state from the server in universal apps, or to restore a
	 * previously serialized user session.
	 * If you use `combineReducers` to produce the root reducer function, this must be
	 * an object with the same shape as `combineReducers` keys.
	 *
	 * @param {Function} enhancer The store enhancer. You may optionally specify it
	 * to enhance the store with third-party capabilities such as middleware,
	 * time travel, persistence, etc. The only store enhancer that ships with Redux
	 * is `applyMiddleware()`.
	 *
	 * @returns {Store} A Redux store that lets you read the state, dispatch actions
	 * and subscribe to changes.
	 */
	function createStore(reducer, preloadedState, enhancer) {
	  var _ref2;
	
	  if (typeof preloadedState === 'function' && typeof enhancer === 'undefined') {
	    enhancer = preloadedState;
	    preloadedState = undefined;
	  }
	
	  if (typeof enhancer !== 'undefined') {
	    if (typeof enhancer !== 'function') {
	      throw new Error('Expected the enhancer to be a function.');
	    }
	
	    return enhancer(createStore)(reducer, preloadedState);
	  }
	
	  if (typeof reducer !== 'function') {
	    throw new Error('Expected the reducer to be a function.');
	  }
	
	  var currentReducer = reducer;
	  var currentState = preloadedState;
	  var currentListeners = [];
	  var nextListeners = currentListeners;
	  var isDispatching = false;
	
	  function ensureCanMutateNextListeners() {
	    if (nextListeners === currentListeners) {
	      nextListeners = currentListeners.slice();
	    }
	  }
	
	  /**
	   * Reads the state tree managed by the store.
	   *
	   * @returns {any} The current state tree of your application.
	   */
	  function getState() {
	    return currentState;
	  }
	
	  /**
	   * Adds a change listener. It will be called any time an action is dispatched,
	   * and some part of the state tree may potentially have changed. You may then
	   * call `getState()` to read the current state tree inside the callback.
	   *
	   * You may call `dispatch()` from a change listener, with the following
	   * caveats:
	   *
	   * 1. The subscriptions are snapshotted just before every `dispatch()` call.
	   * If you subscribe or unsubscribe while the listeners are being invoked, this
	   * will not have any effect on the `dispatch()` that is currently in progress.
	   * However, the next `dispatch()` call, whether nested or not, will use a more
	   * recent snapshot of the subscription list.
	   *
	   * 2. The listener should not expect to see all state changes, as the state
	   * might have been updated multiple times during a nested `dispatch()` before
	   * the listener is called. It is, however, guaranteed that all subscribers
	   * registered before the `dispatch()` started will be called with the latest
	   * state by the time it exits.
	   *
	   * @param {Function} listener A callback to be invoked on every dispatch.
	   * @returns {Function} A function to remove this change listener.
	   */
	  function subscribe(listener) {
	    if (typeof listener !== 'function') {
	      throw new Error('Expected listener to be a function.');
	    }
	
	    var isSubscribed = true;
	
	    ensureCanMutateNextListeners();
	    nextListeners.push(listener);
	
	    return function unsubscribe() {
	      if (!isSubscribed) {
	        return;
	      }
	
	      isSubscribed = false;
	
	      ensureCanMutateNextListeners();
	      var index = nextListeners.indexOf(listener);
	      nextListeners.splice(index, 1);
	    };
	  }
	
	  /**
	   * Dispatches an action. It is the only way to trigger a state change.
	   *
	   * The `reducer` function, used to create the store, will be called with the
	   * current state tree and the given `action`. Its return value will
	   * be considered the **next** state of the tree, and the change listeners
	   * will be notified.
	   *
	   * The base implementation only supports plain object actions. If you want to
	   * dispatch a Promise, an Observable, a thunk, or something else, you need to
	   * wrap your store creating function into the corresponding middleware. For
	   * example, see the documentation for the `redux-thunk` package. Even the
	   * middleware will eventually dispatch plain object actions using this method.
	   *
	   * @param {Object} action A plain object representing “what changed”. It is
	   * a good idea to keep actions serializable so you can record and replay user
	   * sessions, or use the time travelling `redux-devtools`. An action must have
	   * a `type` property which may not be `undefined`. It is a good idea to use
	   * string constants for action types.
	   *
	   * @returns {Object} For convenience, the same action object you dispatched.
	   *
	   * Note that, if you use a custom middleware, it may wrap `dispatch()` to
	   * return something else (for example, a Promise you can await).
	   */
	  function dispatch(action) {
	    if (!(0, _isPlainObject2['default'])(action)) {
	      throw new Error('Actions must be plain objects. ' + 'Use custom middleware for async actions.');
	    }
	
	    if (typeof action.type === 'undefined') {
	      throw new Error('Actions may not have an undefined "type" property. ' + 'Have you misspelled a constant?');
	    }
	
	    if (isDispatching) {
	      throw new Error('Reducers may not dispatch actions.');
	    }
	
	    try {
	      isDispatching = true;
	      currentState = currentReducer(currentState, action);
	    } finally {
	      isDispatching = false;
	    }
	
	    var listeners = currentListeners = nextListeners;
	    for (var i = 0; i < listeners.length; i++) {
	      listeners[i]();
	    }
	
	    return action;
	  }
	
	  /**
	   * Replaces the reducer currently used by the store to calculate the state.
	   *
	   * You might need this if your app implements code splitting and you want to
	   * load some of the reducers dynamically. You might also need this if you
	   * implement a hot reloading mechanism for Redux.
	   *
	   * @param {Function} nextReducer The reducer for the store to use instead.
	   * @returns {void}
	   */
	  function replaceReducer(nextReducer) {
	    if (typeof nextReducer !== 'function') {
	      throw new Error('Expected the nextReducer to be a function.');
	    }
	
	    currentReducer = nextReducer;
	    dispatch({ type: ActionTypes.INIT });
	  }
	
	  /**
	   * Interoperability point for observable/reactive libraries.
	   * @returns {observable} A minimal observable of state changes.
	   * For more information, see the observable proposal:
	   * https://github.com/zenparsing/es-observable
	   */
	  function observable() {
	    var _ref;
	
	    var outerSubscribe = subscribe;
	    return _ref = {
	      /**
	       * The minimal observable subscription method.
	       * @param {Object} observer Any object that can be used as an observer.
	       * The observer object should have a `next` method.
	       * @returns {subscription} An object with an `unsubscribe` method that can
	       * be used to unsubscribe the observable from the store, and prevent further
	       * emission of values from the observable.
	       */
	      subscribe: function subscribe(observer) {
	        if (typeof observer !== 'object') {
	          throw new TypeError('Expected the observer to be an object.');
	        }
	
	        function observeState() {
	          if (observer.next) {
	            observer.next(getState());
	          }
	        }
	
	        observeState();
	        var unsubscribe = outerSubscribe(observeState);
	        return { unsubscribe: unsubscribe };
	      }
	    }, _ref[_symbolObservable2['default']] = function () {
	      return this;
	    }, _ref;
	  }
	
	  // When a store is created, an "INIT" action is dispatched so that every
	  // reducer returns their initial state. This effectively populates
	  // the initial state tree.
	  dispatch({ type: ActionTypes.INIT });
	
	  return _ref2 = {
	    dispatch: dispatch,
	    subscribe: subscribe,
	    getState: getState,
	    replaceReducer: replaceReducer
	  }, _ref2[_symbolObservable2['default']] = observable, _ref2;
	}

/***/ },

/***/ 44:
/***/ function(module, exports, __webpack_require__) {

	var getPrototype = __webpack_require__(45),
	    isObjectLike = __webpack_require__(47);
	
	/** `Object#toString` result references. */
	var objectTag = '[object Object]';
	
	/** Used for built-in method references. */
	var funcProto = Function.prototype,
	    objectProto = Object.prototype;
	
	/** Used to resolve the decompiled source of functions. */
	var funcToString = funcProto.toString;
	
	/** Used to check objects for own properties. */
	var hasOwnProperty = objectProto.hasOwnProperty;
	
	/** Used to infer the `Object` constructor. */
	var objectCtorString = funcToString.call(Object);
	
	/**
	 * Used to resolve the
	 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
	 * of values.
	 */
	var objectToString = objectProto.toString;
	
	/**
	 * Checks if `value` is a plain object, that is, an object created by the
	 * `Object` constructor or one with a `[[Prototype]]` of `null`.
	 *
	 * @static
	 * @memberOf _
	 * @since 0.8.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a plain object, else `false`.
	 * @example
	 *
	 * function Foo() {
	 *   this.a = 1;
	 * }
	 *
	 * _.isPlainObject(new Foo);
	 * // => false
	 *
	 * _.isPlainObject([1, 2, 3]);
	 * // => false
	 *
	 * _.isPlainObject({ 'x': 0, 'y': 0 });
	 * // => true
	 *
	 * _.isPlainObject(Object.create(null));
	 * // => true
	 */
	function isPlainObject(value) {
	  if (!isObjectLike(value) || objectToString.call(value) != objectTag) {
	    return false;
	  }
	  var proto = getPrototype(value);
	  if (proto === null) {
	    return true;
	  }
	  var Ctor = hasOwnProperty.call(proto, 'constructor') && proto.constructor;
	  return (typeof Ctor == 'function' &&
	    Ctor instanceof Ctor && funcToString.call(Ctor) == objectCtorString);
	}
	
	module.exports = isPlainObject;


/***/ },

/***/ 45:
/***/ function(module, exports, __webpack_require__) {

	var overArg = __webpack_require__(46);
	
	/** Built-in value references. */
	var getPrototype = overArg(Object.getPrototypeOf, Object);
	
	module.exports = getPrototype;


/***/ },

/***/ 46:
/***/ function(module, exports) {

	/**
	 * Creates a unary function that invokes `func` with its argument transformed.
	 *
	 * @private
	 * @param {Function} func The function to wrap.
	 * @param {Function} transform The argument transform.
	 * @returns {Function} Returns the new function.
	 */
	function overArg(func, transform) {
	  return function(arg) {
	    return func(transform(arg));
	  };
	}
	
	module.exports = overArg;


/***/ },

/***/ 47:
/***/ function(module, exports) {

	/**
	 * Checks if `value` is object-like. A value is object-like if it's not `null`
	 * and has a `typeof` result of "object".
	 *
	 * @static
	 * @memberOf _
	 * @since 4.0.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
	 * @example
	 *
	 * _.isObjectLike({});
	 * // => true
	 *
	 * _.isObjectLike([1, 2, 3]);
	 * // => true
	 *
	 * _.isObjectLike(_.noop);
	 * // => false
	 *
	 * _.isObjectLike(null);
	 * // => false
	 */
	function isObjectLike(value) {
	  return value != null && typeof value == 'object';
	}
	
	module.exports = isObjectLike;


/***/ },

/***/ 48:
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__(49);


/***/ },

/***/ 49:
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(global) {'use strict';
	
	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	
	var _ponyfill = __webpack_require__(50);
	
	var _ponyfill2 = _interopRequireDefault(_ponyfill);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }
	
	var root = undefined; /* global window */
	
	if (typeof global !== 'undefined') {
		root = global;
	} else if (typeof window !== 'undefined') {
		root = window;
	}
	
	var result = (0, _ponyfill2['default'])(root);
	exports['default'] = result;
	/* WEBPACK VAR INJECTION */}.call(exports, (function() { return this; }())))

/***/ },

/***/ 50:
/***/ function(module, exports) {

	'use strict';
	
	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports['default'] = symbolObservablePonyfill;
	function symbolObservablePonyfill(root) {
		var result;
		var _Symbol = root.Symbol;
	
		if (typeof _Symbol === 'function') {
			if (_Symbol.observable) {
				result = _Symbol.observable;
			} else {
				result = _Symbol('observable');
				_Symbol.observable = result;
			}
		} else {
			result = '@@observable';
		}
	
		return result;
	};

/***/ },

/***/ 51:
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	exports.__esModule = true;
	exports['default'] = combineReducers;
	
	var _createStore = __webpack_require__(43);
	
	var _isPlainObject = __webpack_require__(44);
	
	var _isPlainObject2 = _interopRequireDefault(_isPlainObject);
	
	var _warning = __webpack_require__(52);
	
	var _warning2 = _interopRequireDefault(_warning);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }
	
	function getUndefinedStateErrorMessage(key, action) {
	  var actionType = action && action.type;
	  var actionName = actionType && '"' + actionType.toString() + '"' || 'an action';
	
	  return 'Given action ' + actionName + ', reducer "' + key + '" returned undefined. ' + 'To ignore an action, you must explicitly return the previous state.';
	}
	
	function getUnexpectedStateShapeWarningMessage(inputState, reducers, action, unexpectedKeyCache) {
	  var reducerKeys = Object.keys(reducers);
	  var argumentName = action && action.type === _createStore.ActionTypes.INIT ? 'preloadedState argument passed to createStore' : 'previous state received by the reducer';
	
	  if (reducerKeys.length === 0) {
	    return 'Store does not have a valid reducer. Make sure the argument passed ' + 'to combineReducers is an object whose values are reducers.';
	  }
	
	  if (!(0, _isPlainObject2['default'])(inputState)) {
	    return 'The ' + argumentName + ' has unexpected type of "' + {}.toString.call(inputState).match(/\s([a-z|A-Z]+)/)[1] + '". Expected argument to be an object with the following ' + ('keys: "' + reducerKeys.join('", "') + '"');
	  }
	
	  var unexpectedKeys = Object.keys(inputState).filter(function (key) {
	    return !reducers.hasOwnProperty(key) && !unexpectedKeyCache[key];
	  });
	
	  unexpectedKeys.forEach(function (key) {
	    unexpectedKeyCache[key] = true;
	  });
	
	  if (unexpectedKeys.length > 0) {
	    return 'Unexpected ' + (unexpectedKeys.length > 1 ? 'keys' : 'key') + ' ' + ('"' + unexpectedKeys.join('", "') + '" found in ' + argumentName + '. ') + 'Expected to find one of the known reducer keys instead: ' + ('"' + reducerKeys.join('", "') + '". Unexpected keys will be ignored.');
	  }
	}
	
	function assertReducerSanity(reducers) {
	  Object.keys(reducers).forEach(function (key) {
	    var reducer = reducers[key];
	    var initialState = reducer(undefined, { type: _createStore.ActionTypes.INIT });
	
	    if (typeof initialState === 'undefined') {
	      throw new Error('Reducer "' + key + '" returned undefined during initialization. ' + 'If the state passed to the reducer is undefined, you must ' + 'explicitly return the initial state. The initial state may ' + 'not be undefined.');
	    }
	
	    var type = '@@redux/PROBE_UNKNOWN_ACTION_' + Math.random().toString(36).substring(7).split('').join('.');
	    if (typeof reducer(undefined, { type: type }) === 'undefined') {
	      throw new Error('Reducer "' + key + '" returned undefined when probed with a random type. ' + ('Don\'t try to handle ' + _createStore.ActionTypes.INIT + ' or other actions in "redux/*" ') + 'namespace. They are considered private. Instead, you must return the ' + 'current state for any unknown actions, unless it is undefined, ' + 'in which case you must return the initial state, regardless of the ' + 'action type. The initial state may not be undefined.');
	    }
	  });
	}
	
	/**
	 * Turns an object whose values are different reducer functions, into a single
	 * reducer function. It will call every child reducer, and gather their results
	 * into a single state object, whose keys correspond to the keys of the passed
	 * reducer functions.
	 *
	 * @param {Object} reducers An object whose values correspond to different
	 * reducer functions that need to be combined into one. One handy way to obtain
	 * it is to use ES6 `import * as reducers` syntax. The reducers may never return
	 * undefined for any action. Instead, they should return their initial state
	 * if the state passed to them was undefined, and the current state for any
	 * unrecognized action.
	 *
	 * @returns {Function} A reducer function that invokes every reducer inside the
	 * passed object, and builds a state object with the same shape.
	 */
	function combineReducers(reducers) {
	  var reducerKeys = Object.keys(reducers);
	  var finalReducers = {};
	  for (var i = 0; i < reducerKeys.length; i++) {
	    var key = reducerKeys[i];
	
	    if (true) {
	      if (typeof reducers[key] === 'undefined') {
	        (0, _warning2['default'])('No reducer provided for key "' + key + '"');
	      }
	    }
	
	    if (typeof reducers[key] === 'function') {
	      finalReducers[key] = reducers[key];
	    }
	  }
	  var finalReducerKeys = Object.keys(finalReducers);
	
	  if (true) {
	    var unexpectedKeyCache = {};
	  }
	
	  var sanityError;
	  try {
	    assertReducerSanity(finalReducers);
	  } catch (e) {
	    sanityError = e;
	  }
	
	  return function combination() {
	    var state = arguments.length <= 0 || arguments[0] === undefined ? {} : arguments[0];
	    var action = arguments[1];
	
	    if (sanityError) {
	      throw sanityError;
	    }
	
	    if (true) {
	      var warningMessage = getUnexpectedStateShapeWarningMessage(state, finalReducers, action, unexpectedKeyCache);
	      if (warningMessage) {
	        (0, _warning2['default'])(warningMessage);
	      }
	    }
	
	    var hasChanged = false;
	    var nextState = {};
	    for (var i = 0; i < finalReducerKeys.length; i++) {
	      var key = finalReducerKeys[i];
	      var reducer = finalReducers[key];
	      var previousStateForKey = state[key];
	      var nextStateForKey = reducer(previousStateForKey, action);
	      if (typeof nextStateForKey === 'undefined') {
	        var errorMessage = getUndefinedStateErrorMessage(key, action);
	        throw new Error(errorMessage);
	      }
	      nextState[key] = nextStateForKey;
	      hasChanged = hasChanged || nextStateForKey !== previousStateForKey;
	    }
	    return hasChanged ? nextState : state;
	  };
	}

/***/ },

/***/ 52:
/***/ function(module, exports) {

	'use strict';
	
	exports.__esModule = true;
	exports['default'] = warning;
	/**
	 * Prints a warning in the console if it exists.
	 *
	 * @param {String} message The warning message.
	 * @returns {void}
	 */
	function warning(message) {
	  /* eslint-disable no-console */
	  if (typeof console !== 'undefined' && typeof console.error === 'function') {
	    console.error(message);
	  }
	  /* eslint-enable no-console */
	  try {
	    // This error was thrown as a convenience so that if you enable
	    // "break on all exceptions" in your console,
	    // it would pause the execution at this line.
	    throw new Error(message);
	    /* eslint-disable no-empty */
	  } catch (e) {}
	  /* eslint-enable no-empty */
	}

/***/ },

/***/ 53:
/***/ function(module, exports) {

	'use strict';
	
	exports.__esModule = true;
	exports['default'] = bindActionCreators;
	function bindActionCreator(actionCreator, dispatch) {
	  return function () {
	    return dispatch(actionCreator.apply(undefined, arguments));
	  };
	}
	
	/**
	 * Turns an object whose values are action creators, into an object with the
	 * same keys, but with every function wrapped into a `dispatch` call so they
	 * may be invoked directly. This is just a convenience method, as you can call
	 * `store.dispatch(MyActionCreators.doSomething())` yourself just fine.
	 *
	 * For convenience, you can also pass a single function as the first argument,
	 * and get a function in return.
	 *
	 * @param {Function|Object} actionCreators An object whose values are action
	 * creator functions. One handy way to obtain it is to use ES6 `import * as`
	 * syntax. You may also pass a single function.
	 *
	 * @param {Function} dispatch The `dispatch` function available on your Redux
	 * store.
	 *
	 * @returns {Function|Object} The object mimicking the original object, but with
	 * every action creator wrapped into the `dispatch` call. If you passed a
	 * function as `actionCreators`, the return value will also be a single
	 * function.
	 */
	function bindActionCreators(actionCreators, dispatch) {
	  if (typeof actionCreators === 'function') {
	    return bindActionCreator(actionCreators, dispatch);
	  }
	
	  if (typeof actionCreators !== 'object' || actionCreators === null) {
	    throw new Error('bindActionCreators expected an object or a function, instead received ' + (actionCreators === null ? 'null' : typeof actionCreators) + '. ' + 'Did you write "import ActionCreators from" instead of "import * as ActionCreators from"?');
	  }
	
	  var keys = Object.keys(actionCreators);
	  var boundActionCreators = {};
	  for (var i = 0; i < keys.length; i++) {
	    var key = keys[i];
	    var actionCreator = actionCreators[key];
	    if (typeof actionCreator === 'function') {
	      boundActionCreators[key] = bindActionCreator(actionCreator, dispatch);
	    }
	  }
	  return boundActionCreators;
	}

/***/ },

/***/ 54:
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	exports.__esModule = true;
	
	var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };
	
	exports['default'] = applyMiddleware;
	
	var _compose = __webpack_require__(55);
	
	var _compose2 = _interopRequireDefault(_compose);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }
	
	/**
	 * Creates a store enhancer that applies middleware to the dispatch method
	 * of the Redux store. This is handy for a variety of tasks, such as expressing
	 * asynchronous actions in a concise manner, or logging every action payload.
	 *
	 * See `redux-thunk` package as an example of the Redux middleware.
	 *
	 * Because middleware is potentially asynchronous, this should be the first
	 * store enhancer in the composition chain.
	 *
	 * Note that each middleware will be given the `dispatch` and `getState` functions
	 * as named arguments.
	 *
	 * @param {...Function} middlewares The middleware chain to be applied.
	 * @returns {Function} A store enhancer applying the middleware.
	 */
	function applyMiddleware() {
	  for (var _len = arguments.length, middlewares = Array(_len), _key = 0; _key < _len; _key++) {
	    middlewares[_key] = arguments[_key];
	  }
	
	  return function (createStore) {
	    return function (reducer, preloadedState, enhancer) {
	      var store = createStore(reducer, preloadedState, enhancer);
	      var _dispatch = store.dispatch;
	      var chain = [];
	
	      var middlewareAPI = {
	        getState: store.getState,
	        dispatch: function dispatch(action) {
	          return _dispatch(action);
	        }
	      };
	      chain = middlewares.map(function (middleware) {
	        return middleware(middlewareAPI);
	      });
	      _dispatch = _compose2['default'].apply(undefined, chain)(store.dispatch);
	
	      return _extends({}, store, {
	        dispatch: _dispatch
	      });
	    };
	  };
	}

/***/ },

/***/ 55:
/***/ function(module, exports) {

	"use strict";
	
	exports.__esModule = true;
	exports["default"] = compose;
	/**
	 * Composes single-argument functions from right to left. The rightmost
	 * function can take multiple arguments as it provides the signature for
	 * the resulting composite function.
	 *
	 * @param {...Function} funcs The functions to compose.
	 * @returns {Function} A function obtained by composing the argument functions
	 * from right to left. For example, compose(f, g, h) is identical to doing
	 * (...args) => f(g(h(...args))).
	 */
	
	function compose() {
	  for (var _len = arguments.length, funcs = Array(_len), _key = 0; _key < _len; _key++) {
	    funcs[_key] = arguments[_key];
	  }
	
	  if (funcs.length === 0) {
	    return function (arg) {
	      return arg;
	    };
	  }
	
	  if (funcs.length === 1) {
	    return funcs[0];
	  }
	
	  var last = funcs[funcs.length - 1];
	  var rest = funcs.slice(0, -1);
	  return function () {
	    return rest.reduceRight(function (composed, f) {
	      return f(composed);
	    }, last.apply(undefined, arguments));
	  };
	}

/***/ },

/***/ 193:
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	exports.__esModule = true;
	exports.connect = exports.Provider = undefined;
	
	var _Provider = __webpack_require__(194);
	
	var _Provider2 = _interopRequireDefault(_Provider);
	
	var _connect = __webpack_require__(197);
	
	var _connect2 = _interopRequireDefault(_connect);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }
	
	exports.Provider = _Provider2["default"];
	exports.connect = _connect2["default"];

/***/ },

/***/ 194:
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	exports.__esModule = true;
	exports["default"] = undefined;
	
	var _react = __webpack_require__(1);
	
	var _storeShape = __webpack_require__(195);
	
	var _storeShape2 = _interopRequireDefault(_storeShape);
	
	var _warning = __webpack_require__(196);
	
	var _warning2 = _interopRequireDefault(_warning);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	var didWarnAboutReceivingStore = false;
	function warnAboutReceivingStore() {
	  if (didWarnAboutReceivingStore) {
	    return;
	  }
	  didWarnAboutReceivingStore = true;
	
	  (0, _warning2["default"])('<Provider> does not support changing `store` on the fly. ' + 'It is most likely that you see this error because you updated to ' + 'Redux 2.x and React Redux 2.x which no longer hot reload reducers ' + 'automatically. See https://github.com/reactjs/react-redux/releases/' + 'tag/v2.0.0 for the migration instructions.');
	}
	
	var Provider = function (_Component) {
	  _inherits(Provider, _Component);
	
	  Provider.prototype.getChildContext = function getChildContext() {
	    return { store: this.store };
	  };
	
	  function Provider(props, context) {
	    _classCallCheck(this, Provider);
	
	    var _this = _possibleConstructorReturn(this, _Component.call(this, props, context));
	
	    _this.store = props.store;
	    return _this;
	  }
	
	  Provider.prototype.render = function render() {
	    var children = this.props.children;
	
	    return _react.Children.only(children);
	  };
	
	  return Provider;
	}(_react.Component);
	
	exports["default"] = Provider;
	
	if (true) {
	  Provider.prototype.componentWillReceiveProps = function (nextProps) {
	    var store = this.store;
	    var nextStore = nextProps.store;
	
	    if (store !== nextStore) {
	      warnAboutReceivingStore();
	    }
	  };
	}
	
	Provider.propTypes = {
	  store: _storeShape2["default"].isRequired,
	  children: _react.PropTypes.element.isRequired
	};
	Provider.childContextTypes = {
	  store: _storeShape2["default"].isRequired
	};

/***/ },

/***/ 195:
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	exports.__esModule = true;
	
	var _react = __webpack_require__(1);
	
	exports["default"] = _react.PropTypes.shape({
	  subscribe: _react.PropTypes.func.isRequired,
	  dispatch: _react.PropTypes.func.isRequired,
	  getState: _react.PropTypes.func.isRequired
	});

/***/ },

/***/ 196:
/***/ function(module, exports) {

	'use strict';
	
	exports.__esModule = true;
	exports["default"] = warning;
	/**
	 * Prints a warning in the console if it exists.
	 *
	 * @param {String} message The warning message.
	 * @returns {void}
	 */
	function warning(message) {
	  /* eslint-disable no-console */
	  if (typeof console !== 'undefined' && typeof console.error === 'function') {
	    console.error(message);
	  }
	  /* eslint-enable no-console */
	  try {
	    // This error was thrown as a convenience so that you can use this stack
	    // to find the callsite that caused this warning to fire.
	    throw new Error(message);
	    /* eslint-disable no-empty */
	  } catch (e) {}
	  /* eslint-enable no-empty */
	}

/***/ },

/***/ 197:
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };
	
	exports.__esModule = true;
	exports["default"] = connect;
	
	var _react = __webpack_require__(1);
	
	var _storeShape = __webpack_require__(195);
	
	var _storeShape2 = _interopRequireDefault(_storeShape);
	
	var _shallowEqual = __webpack_require__(198);
	
	var _shallowEqual2 = _interopRequireDefault(_shallowEqual);
	
	var _wrapActionCreators = __webpack_require__(199);
	
	var _wrapActionCreators2 = _interopRequireDefault(_wrapActionCreators);
	
	var _warning = __webpack_require__(196);
	
	var _warning2 = _interopRequireDefault(_warning);
	
	var _isPlainObject = __webpack_require__(44);
	
	var _isPlainObject2 = _interopRequireDefault(_isPlainObject);
	
	var _hoistNonReactStatics = __webpack_require__(200);
	
	var _hoistNonReactStatics2 = _interopRequireDefault(_hoistNonReactStatics);
	
	var _invariant = __webpack_require__(201);
	
	var _invariant2 = _interopRequireDefault(_invariant);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	var defaultMapStateToProps = function defaultMapStateToProps(state) {
	  return {};
	}; // eslint-disable-line no-unused-vars
	var defaultMapDispatchToProps = function defaultMapDispatchToProps(dispatch) {
	  return { dispatch: dispatch };
	};
	var defaultMergeProps = function defaultMergeProps(stateProps, dispatchProps, parentProps) {
	  return _extends({}, parentProps, stateProps, dispatchProps);
	};
	
	function getDisplayName(WrappedComponent) {
	  return WrappedComponent.displayName || WrappedComponent.name || 'Component';
	}
	
	var errorObject = { value: null };
	function tryCatch(fn, ctx) {
	  try {
	    return fn.apply(ctx);
	  } catch (e) {
	    errorObject.value = e;
	    return errorObject;
	  }
	}
	
	// Helps track hot reloading.
	var nextVersion = 0;
	
	function connect(mapStateToProps, mapDispatchToProps, mergeProps) {
	  var options = arguments.length <= 3 || arguments[3] === undefined ? {} : arguments[3];
	
	  var shouldSubscribe = Boolean(mapStateToProps);
	  var mapState = mapStateToProps || defaultMapStateToProps;
	
	  var mapDispatch = undefined;
	  if (typeof mapDispatchToProps === 'function') {
	    mapDispatch = mapDispatchToProps;
	  } else if (!mapDispatchToProps) {
	    mapDispatch = defaultMapDispatchToProps;
	  } else {
	    mapDispatch = (0, _wrapActionCreators2["default"])(mapDispatchToProps);
	  }
	
	  var finalMergeProps = mergeProps || defaultMergeProps;
	  var _options$pure = options.pure;
	  var pure = _options$pure === undefined ? true : _options$pure;
	  var _options$withRef = options.withRef;
	  var withRef = _options$withRef === undefined ? false : _options$withRef;
	
	  var checkMergedEquals = pure && finalMergeProps !== defaultMergeProps;
	
	  // Helps track hot reloading.
	  var version = nextVersion++;
	
	  return function wrapWithConnect(WrappedComponent) {
	    var connectDisplayName = 'Connect(' + getDisplayName(WrappedComponent) + ')';
	
	    function checkStateShape(props, methodName) {
	      if (!(0, _isPlainObject2["default"])(props)) {
	        (0, _warning2["default"])(methodName + '() in ' + connectDisplayName + ' must return a plain object. ' + ('Instead received ' + props + '.'));
	      }
	    }
	
	    function computeMergedProps(stateProps, dispatchProps, parentProps) {
	      var mergedProps = finalMergeProps(stateProps, dispatchProps, parentProps);
	      if (true) {
	        checkStateShape(mergedProps, 'mergeProps');
	      }
	      return mergedProps;
	    }
	
	    var Connect = function (_Component) {
	      _inherits(Connect, _Component);
	
	      Connect.prototype.shouldComponentUpdate = function shouldComponentUpdate() {
	        return !pure || this.haveOwnPropsChanged || this.hasStoreStateChanged;
	      };
	
	      function Connect(props, context) {
	        _classCallCheck(this, Connect);
	
	        var _this = _possibleConstructorReturn(this, _Component.call(this, props, context));
	
	        _this.version = version;
	        _this.store = props.store || context.store;
	
	        (0, _invariant2["default"])(_this.store, 'Could not find "store" in either the context or ' + ('props of "' + connectDisplayName + '". ') + 'Either wrap the root component in a <Provider>, ' + ('or explicitly pass "store" as a prop to "' + connectDisplayName + '".'));
	
	        var storeState = _this.store.getState();
	        _this.state = { storeState: storeState };
	        _this.clearCache();
	        return _this;
	      }
	
	      Connect.prototype.computeStateProps = function computeStateProps(store, props) {
	        if (!this.finalMapStateToProps) {
	          return this.configureFinalMapState(store, props);
	        }
	
	        var state = store.getState();
	        var stateProps = this.doStatePropsDependOnOwnProps ? this.finalMapStateToProps(state, props) : this.finalMapStateToProps(state);
	
	        if (true) {
	          checkStateShape(stateProps, 'mapStateToProps');
	        }
	        return stateProps;
	      };
	
	      Connect.prototype.configureFinalMapState = function configureFinalMapState(store, props) {
	        var mappedState = mapState(store.getState(), props);
	        var isFactory = typeof mappedState === 'function';
	
	        this.finalMapStateToProps = isFactory ? mappedState : mapState;
	        this.doStatePropsDependOnOwnProps = this.finalMapStateToProps.length !== 1;
	
	        if (isFactory) {
	          return this.computeStateProps(store, props);
	        }
	
	        if (true) {
	          checkStateShape(mappedState, 'mapStateToProps');
	        }
	        return mappedState;
	      };
	
	      Connect.prototype.computeDispatchProps = function computeDispatchProps(store, props) {
	        if (!this.finalMapDispatchToProps) {
	          return this.configureFinalMapDispatch(store, props);
	        }
	
	        var dispatch = store.dispatch;
	
	        var dispatchProps = this.doDispatchPropsDependOnOwnProps ? this.finalMapDispatchToProps(dispatch, props) : this.finalMapDispatchToProps(dispatch);
	
	        if (true) {
	          checkStateShape(dispatchProps, 'mapDispatchToProps');
	        }
	        return dispatchProps;
	      };
	
	      Connect.prototype.configureFinalMapDispatch = function configureFinalMapDispatch(store, props) {
	        var mappedDispatch = mapDispatch(store.dispatch, props);
	        var isFactory = typeof mappedDispatch === 'function';
	
	        this.finalMapDispatchToProps = isFactory ? mappedDispatch : mapDispatch;
	        this.doDispatchPropsDependOnOwnProps = this.finalMapDispatchToProps.length !== 1;
	
	        if (isFactory) {
	          return this.computeDispatchProps(store, props);
	        }
	
	        if (true) {
	          checkStateShape(mappedDispatch, 'mapDispatchToProps');
	        }
	        return mappedDispatch;
	      };
	
	      Connect.prototype.updateStatePropsIfNeeded = function updateStatePropsIfNeeded() {
	        var nextStateProps = this.computeStateProps(this.store, this.props);
	        if (this.stateProps && (0, _shallowEqual2["default"])(nextStateProps, this.stateProps)) {
	          return false;
	        }
	
	        this.stateProps = nextStateProps;
	        return true;
	      };
	
	      Connect.prototype.updateDispatchPropsIfNeeded = function updateDispatchPropsIfNeeded() {
	        var nextDispatchProps = this.computeDispatchProps(this.store, this.props);
	        if (this.dispatchProps && (0, _shallowEqual2["default"])(nextDispatchProps, this.dispatchProps)) {
	          return false;
	        }
	
	        this.dispatchProps = nextDispatchProps;
	        return true;
	      };
	
	      Connect.prototype.updateMergedPropsIfNeeded = function updateMergedPropsIfNeeded() {
	        var nextMergedProps = computeMergedProps(this.stateProps, this.dispatchProps, this.props);
	        if (this.mergedProps && checkMergedEquals && (0, _shallowEqual2["default"])(nextMergedProps, this.mergedProps)) {
	          return false;
	        }
	
	        this.mergedProps = nextMergedProps;
	        return true;
	      };
	
	      Connect.prototype.isSubscribed = function isSubscribed() {
	        return typeof this.unsubscribe === 'function';
	      };
	
	      Connect.prototype.trySubscribe = function trySubscribe() {
	        if (shouldSubscribe && !this.unsubscribe) {
	          this.unsubscribe = this.store.subscribe(this.handleChange.bind(this));
	          this.handleChange();
	        }
	      };
	
	      Connect.prototype.tryUnsubscribe = function tryUnsubscribe() {
	        if (this.unsubscribe) {
	          this.unsubscribe();
	          this.unsubscribe = null;
	        }
	      };
	
	      Connect.prototype.componentDidMount = function componentDidMount() {
	        this.trySubscribe();
	      };
	
	      Connect.prototype.componentWillReceiveProps = function componentWillReceiveProps(nextProps) {
	        if (!pure || !(0, _shallowEqual2["default"])(nextProps, this.props)) {
	          this.haveOwnPropsChanged = true;
	        }
	      };
	
	      Connect.prototype.componentWillUnmount = function componentWillUnmount() {
	        this.tryUnsubscribe();
	        this.clearCache();
	      };
	
	      Connect.prototype.clearCache = function clearCache() {
	        this.dispatchProps = null;
	        this.stateProps = null;
	        this.mergedProps = null;
	        this.haveOwnPropsChanged = true;
	        this.hasStoreStateChanged = true;
	        this.haveStatePropsBeenPrecalculated = false;
	        this.statePropsPrecalculationError = null;
	        this.renderedElement = null;
	        this.finalMapDispatchToProps = null;
	        this.finalMapStateToProps = null;
	      };
	
	      Connect.prototype.handleChange = function handleChange() {
	        if (!this.unsubscribe) {
	          return;
	        }
	
	        var storeState = this.store.getState();
	        var prevStoreState = this.state.storeState;
	        if (pure && prevStoreState === storeState) {
	          return;
	        }
	
	        if (pure && !this.doStatePropsDependOnOwnProps) {
	          var haveStatePropsChanged = tryCatch(this.updateStatePropsIfNeeded, this);
	          if (!haveStatePropsChanged) {
	            return;
	          }
	          if (haveStatePropsChanged === errorObject) {
	            this.statePropsPrecalculationError = errorObject.value;
	          }
	          this.haveStatePropsBeenPrecalculated = true;
	        }
	
	        this.hasStoreStateChanged = true;
	        this.setState({ storeState: storeState });
	      };
	
	      Connect.prototype.getWrappedInstance = function getWrappedInstance() {
	        (0, _invariant2["default"])(withRef, 'To access the wrapped instance, you need to specify ' + '{ withRef: true } as the fourth argument of the connect() call.');
	
	        return this.refs.wrappedInstance;
	      };
	
	      Connect.prototype.render = function render() {
	        var haveOwnPropsChanged = this.haveOwnPropsChanged;
	        var hasStoreStateChanged = this.hasStoreStateChanged;
	        var haveStatePropsBeenPrecalculated = this.haveStatePropsBeenPrecalculated;
	        var statePropsPrecalculationError = this.statePropsPrecalculationError;
	        var renderedElement = this.renderedElement;
	
	        this.haveOwnPropsChanged = false;
	        this.hasStoreStateChanged = false;
	        this.haveStatePropsBeenPrecalculated = false;
	        this.statePropsPrecalculationError = null;
	
	        if (statePropsPrecalculationError) {
	          throw statePropsPrecalculationError;
	        }
	
	        var shouldUpdateStateProps = true;
	        var shouldUpdateDispatchProps = true;
	        if (pure && renderedElement) {
	          shouldUpdateStateProps = hasStoreStateChanged || haveOwnPropsChanged && this.doStatePropsDependOnOwnProps;
	          shouldUpdateDispatchProps = haveOwnPropsChanged && this.doDispatchPropsDependOnOwnProps;
	        }
	
	        var haveStatePropsChanged = false;
	        var haveDispatchPropsChanged = false;
	        if (haveStatePropsBeenPrecalculated) {
	          haveStatePropsChanged = true;
	        } else if (shouldUpdateStateProps) {
	          haveStatePropsChanged = this.updateStatePropsIfNeeded();
	        }
	        if (shouldUpdateDispatchProps) {
	          haveDispatchPropsChanged = this.updateDispatchPropsIfNeeded();
	        }
	
	        var haveMergedPropsChanged = true;
	        if (haveStatePropsChanged || haveDispatchPropsChanged || haveOwnPropsChanged) {
	          haveMergedPropsChanged = this.updateMergedPropsIfNeeded();
	        } else {
	          haveMergedPropsChanged = false;
	        }
	
	        if (!haveMergedPropsChanged && renderedElement) {
	          return renderedElement;
	        }
	
	        if (withRef) {
	          this.renderedElement = (0, _react.createElement)(WrappedComponent, _extends({}, this.mergedProps, {
	            ref: 'wrappedInstance'
	          }));
	        } else {
	          this.renderedElement = (0, _react.createElement)(WrappedComponent, this.mergedProps);
	        }
	
	        return this.renderedElement;
	      };
	
	      return Connect;
	    }(_react.Component);
	
	    Connect.displayName = connectDisplayName;
	    Connect.WrappedComponent = WrappedComponent;
	    Connect.contextTypes = {
	      store: _storeShape2["default"]
	    };
	    Connect.propTypes = {
	      store: _storeShape2["default"]
	    };
	
	    if (true) {
	      Connect.prototype.componentWillUpdate = function componentWillUpdate() {
	        if (this.version === version) {
	          return;
	        }
	
	        // We are hot reloading!
	        this.version = version;
	        this.trySubscribe();
	        this.clearCache();
	      };
	    }
	
	    return (0, _hoistNonReactStatics2["default"])(Connect, WrappedComponent);
	  };
	}

/***/ },

/***/ 198:
/***/ function(module, exports) {

	"use strict";
	
	exports.__esModule = true;
	exports["default"] = shallowEqual;
	function shallowEqual(objA, objB) {
	  if (objA === objB) {
	    return true;
	  }
	
	  var keysA = Object.keys(objA);
	  var keysB = Object.keys(objB);
	
	  if (keysA.length !== keysB.length) {
	    return false;
	  }
	
	  // Test for A's keys different from B.
	  var hasOwn = Object.prototype.hasOwnProperty;
	  for (var i = 0; i < keysA.length; i++) {
	    if (!hasOwn.call(objB, keysA[i]) || objA[keysA[i]] !== objB[keysA[i]]) {
	      return false;
	    }
	  }
	
	  return true;
	}

/***/ },

/***/ 199:
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	
	exports.__esModule = true;
	exports["default"] = wrapActionCreators;
	
	var _redux = __webpack_require__(42);
	
	function wrapActionCreators(actionCreators) {
	  return function (dispatch) {
	    return (0, _redux.bindActionCreators)(actionCreators, dispatch);
	  };
	}

/***/ },

/***/ 200:
/***/ function(module, exports) {

	/**
	 * Copyright 2015, Yahoo! Inc.
	 * Copyrights licensed under the New BSD License. See the accompanying LICENSE file for terms.
	 */
	'use strict';
	
	var REACT_STATICS = {
	    childContextTypes: true,
	    contextTypes: true,
	    defaultProps: true,
	    displayName: true,
	    getDefaultProps: true,
	    mixins: true,
	    propTypes: true,
	    type: true
	};
	
	var KNOWN_STATICS = {
	    name: true,
	    length: true,
	    prototype: true,
	    caller: true,
	    arguments: true,
	    arity: true
	};
	
	var isGetOwnPropertySymbolsAvailable = typeof Object.getOwnPropertySymbols === 'function';
	
	module.exports = function hoistNonReactStatics(targetComponent, sourceComponent, customStatics) {
	    if (typeof sourceComponent !== 'string') { // don't hoist over string (html) components
	        var keys = Object.getOwnPropertyNames(sourceComponent);
	
	        /* istanbul ignore else */
	        if (isGetOwnPropertySymbolsAvailable) {
	            keys = keys.concat(Object.getOwnPropertySymbols(sourceComponent));
	        }
	
	        for (var i = 0; i < keys.length; ++i) {
	            if (!REACT_STATICS[keys[i]] && !KNOWN_STATICS[keys[i]] && (!customStatics || !customStatics[keys[i]])) {
	                try {
	                    targetComponent[keys[i]] = sourceComponent[keys[i]];
	                } catch (error) {
	
	                }
	            }
	        }
	    }
	
	    return targetComponent;
	};


/***/ },

/***/ 201:
/***/ function(module, exports, __webpack_require__) {

	/**
	 * Copyright 2013-2015, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 */
	
	'use strict';
	
	/**
	 * Use invariant() to assert state which your program assumes to be true.
	 *
	 * Provide sprintf-style format (only %s is supported) and arguments
	 * to provide information about what broke and what you were
	 * expecting.
	 *
	 * The invariant message will be stripped in production, but the invariant
	 * will remain to ensure logic does not differ in production.
	 */
	
	var invariant = function(condition, format, a, b, c, d, e, f) {
	  if (true) {
	    if (format === undefined) {
	      throw new Error('invariant requires an error message argument');
	    }
	  }
	
	  if (!condition) {
	    var error;
	    if (format === undefined) {
	      error = new Error(
	        'Minified exception occurred; use the non-minified dev environment ' +
	        'for the full error message and additional helpful warnings.'
	      );
	    } else {
	      var args = [a, b, c, d, e, f];
	      var argIndex = 0;
	      error = new Error(
	        format.replace(/%s/g, function() { return args[argIndex++]; })
	      );
	      error.name = 'Invariant Violation';
	    }
	
	    error.framesToPop = 1; // we don't care about invariant's own frame
	    throw error;
	  }
	};
	
	module.exports = invariant;


/***/ },

/***/ 202:
/***/ function(module, exports) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();
	
	exports.makeImageRect = makeImageRect;
	exports.sortChildrenByZIndex = sortChildrenByZIndex;
	exports.mapToObj = mapToObj;
	exports.objToMap = objToMap;
	function makeImageRect(rect, layer) {
	    var image = new Image();
	    image.onload = function () {
	        rect.fillPatternImage(image);
	        rect.fillPatternScaleX(rect.width() / image.width);
	        rect.fillPatternScaleY(rect.height() / image.height);
	        layer.draw();
	    };
	    return image;
	}
	
	function sortChildrenByZIndex(container) {
	    container.children.sort(function (a, b) {
	        a.zIndex = a.zIndex || 0;
	        b.zIndex = b.zIndex || 0;
	        return a.zIndex - b.zIndex;
	    });
	}
	
	function mapToObj(map) {
	    var obj = Object.create(null);
	    var _iteratorNormalCompletion = true;
	    var _didIteratorError = false;
	    var _iteratorError = undefined;
	
	    try {
	        for (var _iterator = map[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
	            var _step$value = _slicedToArray(_step.value, 2);
	
	            var k = _step$value[0];
	            var v = _step$value[1];
	
	            // We don’t escape the key '__proto__'
	            // which can cause problems on older engines
	            obj[k] = v;
	        }
	    } catch (err) {
	        _didIteratorError = true;
	        _iteratorError = err;
	    } finally {
	        try {
	            if (!_iteratorNormalCompletion && _iterator.return) {
	                _iterator.return();
	            }
	        } finally {
	            if (_didIteratorError) {
	                throw _iteratorError;
	            }
	        }
	    }
	
	    return obj;
	}
	
	function objToMap(obj) {
	    var map = new Map();
	    var _iteratorNormalCompletion2 = true;
	    var _didIteratorError2 = false;
	    var _iteratorError2 = undefined;
	
	    try {
	        for (var _iterator2 = Object.keys(obj)[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
	            var k = _step2.value;
	
	            map.set(k, obj[k]);
	        }
	    } catch (err) {
	        _didIteratorError2 = true;
	        _iteratorError2 = err;
	    } finally {
	        try {
	            if (!_iteratorNormalCompletion2 && _iterator2.return) {
	                _iterator2.return();
	            }
	        } finally {
	            if (_didIteratorError2) {
	                throw _iteratorError2;
	            }
	        }
	    }
	
	    return map;
	}

/***/ },

/***/ 203:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.App = undefined;
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _Canvas = __webpack_require__(204);
	
	var _Canvas2 = _interopRequireDefault(_Canvas);
	
	var _PinControls = __webpack_require__(208);
	
	var _PinControls2 = _interopRequireDefault(_PinControls);
	
	var _BrushControls = __webpack_require__(209);
	
	var _react = __webpack_require__(1);
	
	var React = _interopRequireWildcard(_react);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	var App = exports.App = function (_React$Component) {
	    _inherits(App, _React$Component);
	
	    function App(props, context) {
	        _classCallCheck(this, App);
	
	        var _this = _possibleConstructorReturn(this, (App.__proto__ || Object.getPrototypeOf(App)).call(this, props, context));
	
	        _this._canvas = null;
	        _this._store = context.store;
	        return _this;
	    }
	
	    _createClass(App, [{
	        key: "componentDidMount",
	        value: function componentDidMount() {
	            this._canvas = new _Canvas2.default(this._store, document.getElementById('canvas'), this.props.gridConfig);
	        }
	    }, {
	        key: "render",
	        value: function render() {
	            var brushes = this._store.getState().brushes.brushes;
	            return React.createElement(
	                "div",
	                { className: "row-fluid" },
	                React.createElement(
	                    "div",
	                    { className: "col-md-10", id: "canvas-holder" },
	                    React.createElement("canvas", { id: "canvas" })
	                ),
	                React.createElement(
	                    "div",
	                    { className: "col-md-2" },
	                    React.createElement(_BrushControls.BrushControls, { brushes: brushes }),
	                    React.createElement(_PinControls2.default, { canvas: this._canvas })
	                )
	            );
	        }
	    }]);
	
	    return App;
	}(React.Component);
	
	App.contextTypes = {
	    store: React.PropTypes.object
	};

/***/ },

/***/ 204:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _Grid = __webpack_require__(205);
	
	var _Grid2 = _interopRequireDefault(_Grid);
	
	var _states = __webpack_require__(41);
	
	var states = _interopRequireWildcard(_states);
	
	var _pixi = __webpack_require__(206);
	
	var PIXI = _interopRequireWildcard(_pixi);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	var stage = null;
	
	var Canvas = function () {
	    /**
	     *
	     * @param store
	     * @param canvasElement
	     * @param config
	     * @param config.backgroundUrl
	     * @param config.beaconMapSaveUrl
	     * @param config.width
	     * @param config.height
	     * @param config.dimensionX
	     * @param config.dimensionY
	     * @param config.dimension
	     */
	    function Canvas(store, canvasElement, config) {
	        _classCallCheck(this, Canvas);
	
	        this._store = store;
	        if (stage !== null) {
	            stage.destroy();
	        }
	        this._width = config.width = config.width || 10;
	        this._height = config.height = config.height || 10;
	        this._dimensionX = config.dimensionX = config.dimensionX || 100;
	        this._dimensionY = config.dimensionY = config.dimensionY || 100;
	        this.beaconMapSaveUrl = config.beaconMapSaveUrl;
	        var renderer = PIXI.autoDetectRenderer(this._width * config.dimensionX, this._height * config.dimensionY, { view: canvasElement });
	        renderer.plugins.interaction.moveWhenInside = true;
	        stage = new PIXI.Container();
	        stage.interactive = true;
	
	        this._grid = new _Grid2.default(stage, store, config);
	        this._grid._promise.then(function () {
	            this._grid.build();
	        }.bind(this));
	
	        var onInteract = function (evt) {
	            if (store.getState().brushes.currentBrush.activated) {
	                var x = Math.floor(evt.data.global.x / this._width);
	                var y = Math.floor(evt.data.global.y / this._height);
	                var color = store.getState().brushes.currentBrush.color;
	                this._grid.drawRect({
	                    color: color,
	                    stroke: 0xAAAAAA,
	                    x: x * this._width,
	                    y: y * this._height,
	                    width: this._width,
	                    height: this._height
	                });
	                if (Array.isArray(this._grid.rects[x]) && typeof this._grid.rects[x][y] !== 'undefined') {
	                    this._grid.rects[x][y] = color === 0x000000 ? states.WALL : states.EMPTY;
	                }
	                // renderer.render(stage);
	                // var node = evt.target;
	                // if (node) {
	                //     // update tooltip
	                //     if (!idBuffer.has(node.id())) {
	                //         if (nodeBuffer.length >= 20) {
	                //             nodeBuffer.pop();
	                //         }
	                //         nodeBuffer.unshift({node: node, color: node.fill()});
	                //         idBuffer.add(node.id());
	                //     }
	                //
	                //     let color = store.getState().currentBrush.color;
	                //     grid.rects[node.x() / WIDTH][node.y() / HEIGHT] = color === '#000' ? states.WALL : states.EMPTY;
	                //     node.fill(color);
	                //     node.getLayer().batchDraw();
	                // }
	            }
	        }.bind(this);
	
	        function update() {
	            renderer.render(stage);
	            requestAnimationFrame(update);
	        }
	
	        update();
	        this._grid._graphics.on('mousedown', onInteract);
	        this._grid._graphics.on('mousemove', onInteract);
	        this._grid._graphics.on('click', onInteract);
	        // function undo(e) {
	        //     if (e.keyCode == 90 && e.ctrlKey) {
	        //         for (let i = 0; i < nodeBuffer.length; i++) {
	        //             let node = nodeBuffer[i].node;
	        //             let color = nodeBuffer[i].color;
	        //             node.fill(color);
	        //             grid.rects[node.x() / WIDTH][node.y() / HEIGHT] = color === '#000' ? states.WALL : states.EMPTY;
	        //             node.getLayer().batchDraw();
	        //         }
	        //         nodeBuffer = [];
	        //         idBuffer.clear();
	        //     }
	        //
	        // }
	        //
	        // document.removeEventListener('keydown', undo);
	        // document.addEventListener('keydown', undo);
	    }
	
	    _createClass(Canvas, [{
	        key: "clear",
	        value: function clear() {
	            this._grid.rects = [];
	            for (var i = 0; i < this._dimensionX; i++) {
	                this._grid.rects[i] = new Array(100);
	                for (var j = 0; j < this._dimensionY; j++) {
	                    this._grid.rects[i][j] = 0;
	                }
	            }
	            this._grid.build();
	            this.save();
	        }
	    }, {
	        key: "save",
	        value: function save() {
	            var jsonRects = JSON.stringify(this._grid.rects);
	            $.ajax({
	                url: this.beaconMapSaveUrl,
	                type: 'POST',
	                data: {
	                    data: jsonRects
	                },
	                success: function success(data) {
	                    console.log(data['success']);
	                },
	                cache: false
	            });
	        }
	    }]);
	
	    return Canvas;
	}();
	
	exports.default = Canvas;

/***/ },

/***/ 205:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _pixi = __webpack_require__(206);
	
	var PIXI = _interopRequireWildcard(_pixi);
	
	var _Pin = __webpack_require__(207);
	
	var _Pin2 = _interopRequireDefault(_Pin);
	
	var _states = __webpack_require__(41);
	
	var states = _interopRequireWildcard(_states);
	
	var _helper = __webpack_require__(202);
	
	var helper = _interopRequireWildcard(_helper);
	
	var _uuid = __webpack_require__(34);
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	var Grid = function () {
	    /**
	     *
	     * @param stage
	     * @param store
	     * @param config
	     * @param config.backgroundUrl
	     * @param config.beaconPinSaveUrl
	     * @param config.beaconPinListUrl
	     * @param config.beaconPinDeleteUrl
	     * @param config.beaconMapGetUrl
	     * @param config.width
	     * @param config.height
	     * @param config.dimensionX
	     * @param config.dimensionY
	     */
	    function Grid(stage, store, config) {
	        _classCallCheck(this, Grid);
	
	        console.log(states.colors);
	        this._colors = states.colors;
	
	        this._stage = stage;
	        this._width = config.width;
	        this._height = config.height;
	        this._dimensionX = config.dimensionX;
	        this._dimensionY = config.dimensionY;
	        this._beaconUrls = {
	            beaconPinSaveUrl: config.beaconPinSaveUrl,
	            beaconPinListUrl: config.beaconPinListUrl,
	            beaconPinDeleteUrl: config.beaconPinDeleteUrl,
	            beaconMapGetUrl: config.beaconMapGetUrl
	        };
	        this._graphics = new PIXI.Graphics();
	        this._stage.addChild(this._graphics);
	        this._graphics.interactive = true;
	        this._backgroundUrl = config.backgroundUrl;
	        this._pins = new Map();
	        this._store = store;
	        this._promise = new Promise(function (resolve, reject) {
	            PIXI.loader.add(config.backgroundUrl).load(this.setupBackground.bind(this, resolve, reject));
	        }.bind(this));
	    }
	
	    _createClass(Grid, [{
	        key: "build",
	        value: function build() {
	            this._graphics.clear();
	            var _iteratorNormalCompletion = true;
	            var _didIteratorError = false;
	            var _iteratorError = undefined;
	
	            try {
	                for (var _iterator = this._pins[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
	                    var _step$value = _slicedToArray(_step.value, 2);
	
	                    var key = _step$value[0];
	                    var pin = _step$value[1];
	
	                    pin.destroy();
	                }
	            } catch (err) {
	                _didIteratorError = true;
	                _iteratorError = err;
	            } finally {
	                try {
	                    if (!_iteratorNormalCompletion && _iterator.return) {
	                        _iterator.return();
	                    }
	                } finally {
	                    if (_didIteratorError) {
	                        throw _iteratorError;
	                    }
	                }
	            }
	
	            for (var i = 0; i < this._dimensionX; i++) {
	                for (var j = 0; j < this._dimensionY; j++) {
	                    this.drawRect({
	                        color: this._colors[this.rects[i][j]],
	                        stroke: 0xAAAAAA,
	                        x: i * this._width,
	                        y: j * this._height,
	                        width: this._width,
	                        height: this._height,
	                        opacity: this.rects[i][j] === states.EMPTY ? 0.5 : 1
	                    });
	                }
	            }
	            var pins = this._store.getState().pins.pins;
	            var _iteratorNormalCompletion2 = true;
	            var _didIteratorError2 = false;
	            var _iteratorError2 = undefined;
	
	            try {
	                for (var _iterator2 = pins[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
	                    var _step2$value = _slicedToArray(_step2.value, 2);
	
	                    var key = _step2$value[0];
	                    var value = _step2$value[1];
	
	                    this.addPin(value.x, value.y, value.id, value.name);
	                }
	            } catch (err) {
	                _didIteratorError2 = true;
	                _iteratorError2 = err;
	            } finally {
	                try {
	                    if (!_iteratorNormalCompletion2 && _iterator2.return) {
	                        _iterator2.return();
	                    }
	                } finally {
	                    if (_didIteratorError2) {
	                        throw _iteratorError2;
	                    }
	                }
	            }
	
	            this._graphics.zIndex = 1;
	            helper.sortChildrenByZIndex(this._stage);
	        }
	    }, {
	        key: "addPin",
	        value: function addPin() {
	            var x = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	            var y = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
	            var id = arguments[2];
	            var name = arguments[3];
	
	            if (typeof name !== 'undefined') {
	                if (!this._pins.has(name)) {
	                    this._store.dispatch({
	                        type: 'ADD_PIN',
	                        id: id,
	                        name: name,
	                        x: x,
	                        y: y
	                    });
	                    this._pins.set(name, new _Pin2.default(x, y, id, name, this));
	                }
	            }
	        }
	    }, {
	        key: "deletePin",
	        value: function deletePin(name) {
	            if (this._pins.has(name)) {
	                this._store.dispatch({
	                    type: 'DELETE_PIN',
	                    name: name
	                });
	                this._pins.get(name).destroy();
	                this._pins.delete(name);
	            }
	        }
	    }, {
	        key: "setupBackground",
	        value: function setupBackground(resolve, reject) {
	            this._sprite = new PIXI.Sprite(PIXI.loader.resources[this._backgroundUrl].texture);
	            this._sprite.width = this._width * this._dimensionX;
	            this._sprite.height = this._height * this._dimensionY;
	            this._sprite.x = 0;
	            this._sprite.y = 0;
	            this._stage.addChild(this._sprite);
	            resolve();
	        }
	    }, {
	        key: "drawRect",
	        value: function drawRect(config) {
	            this._graphics.beginFill(config.color, 0);
	            this._graphics.drawRect(config.x, config.y, config.width, config.height);
	            this._graphics.endFill();
	            this._graphics.beginFill(config.color, config.opacity);
	            this._graphics.lineStyle(1, config.stroke, 1);
	            this._graphics.drawRect(config.x, config.y, config.width, config.height);
	            this._graphics.endFill();
	        }
	    }, {
	        key: "rects",
	        get: function get() {
	            var _this = this;
	
	            if (this._rects === undefined) {
	                (function () {
	                    _this._rects = null;
	                    var self = _this;
	                    $.ajax({
	                        url: self._beaconUrls.beaconMapGetUrl,
	                        type: 'GET',
	                        dataType: "json",
	                        async: false,
	                        success: function success(data) {
	                            self._rects = data;
	                        }
	                    });
	                    if (!Array.isArray(self._rects)) {
	                        self._rects = [];
	                        for (var i = 0; i < self._dimensionX; i++) {
	                            self._rects[i] = new Array(self._dimensionX);
	                            for (var j = 0; j < self._dimensionY; j++) {
	                                self._rects[i][j] = 0; //Math.round(Math.random())
	                            }
	                        }
	                    }
	                })();
	            }
	            return this._rects;
	        },
	        set: function set(rects) {
	            this._rects = rects;
	        }
	    }, {
	        key: "stage",
	        get: function get() {
	            return this._stage;
	        }
	    }]);
	
	    return Grid;
	}();
	
	exports.default = Grid;

/***/ },

/***/ 207:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }(); /**
	                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * Created by Dezmont on 23.10.2016.
	                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      */
	
	
	var _uuid = __webpack_require__(34);
	
	var _states = __webpack_require__(41);
	
	var states = _interopRequireWildcard(_states);
	
	var _pixi = __webpack_require__(206);
	
	var PIXI = _interopRequireWildcard(_pixi);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	var WIDTH_SCALE = 0.2;
	var HEIGHT_SCALE = 0.15;
	var X_POSITION_MULTIPLIER = 0.27;
	var Y_POSITION_MULTIPLIER = 0.84;
	var PIN_IMAGE_SRC = '/img/blue pin.png';
	
	var Pin = function () {
	    function Pin(x, y, id, name, grid) {
	        _classCallCheck(this, Pin);
	
	        this._id = id;
	        this._name = name;
	        this._x = x;
	        this._y = y;
	        this._width = grid._width;
	        this._height = grid._height;
	        this._dimensionX = grid._dimensionX;
	        this._dimensionY = grid._dimensionY;
	        this._beaconPinWidth = this._width * this._dimensionX * WIDTH_SCALE;
	        this._beaconPinHeight = this._height * this._dimensionY * HEIGHT_SCALE;
	
	        this._grid = grid;
	        this._rects = grid.rects;
	        this._stage = grid._stage;
	
	        this.save(x, y);
	
	        this._counter = 0;
	
	        this._group = new PIXI.Container();
	        this._group.zIndex = 2;
	        this._stage.addChild(this._group);
	        x = x * this._width;
	        y = y * this._height;
	        this._group.x = x;
	        this._group.y = y;
	        // this._group.anchor.set(0.3);
	        this._rect = new PIXI.Graphics();
	        Pin.drawRect(this._rect, {
	            x: 0,
	            y: 0,
	            width: this._width,
	            height: this._height,
	            color: states.PIN_COLOR
	        });
	
	        if (!Pin.promise) {
	            Pin.promise = new Promise(function (resolve) {
	                PIXI.loader.add(PIN_IMAGE_SRC).load(function () {
	                    resolve();
	                });
	            });
	        }
	        Pin.promise.then(this.setup.bind(this, x, y));
	
	        this._group.interactive = true;
	        this._group.buttonMode = true;
	        // this._group.pivot.set(this._beaconPinWidth.width * 0.3, this._beaconPinHeight * 0.3);
	        this._group.on('mousedown', this.onDragStart).on('mousedown', this.onPinSelected.bind(this)).on('mouseup', this.onDragEnd()).on('mousedown', this.onPinSelected.bind(this)).on('mouseupoutside', this.onDragEnd())
	        // events for drag move
	        .on('mousemove', this.onDragMove());
	        this._group.addChild(this._rect);
	        // this._group.anchor.set(0.3)
	    }
	
	    _createClass(Pin, [{
	        key: "save",
	        value: function save(x, y) {
	            $.ajax({
	                url: this._grid._beaconUrls.beaconPinSaveUrl,
	                type: 'POST',
	                data: {
	                    'BeaconPins[canvas_height]': this._stage.height,
	                    'BeaconPins[canvas_width]': this._stage.width,
	                    'BeaconPins[id]': this._id,
	                    'BeaconPins[name]': this._name,
	                    'BeaconPins[x]': x,
	                    'BeaconPins[y]': y
	                }
	            });
	        }
	    }, {
	        key: "setup",
	        value: function setup(x, y) {
	            this._sprite = new PIXI.Sprite(PIXI.loader.resources[PIN_IMAGE_SRC].texture);
	            this._beaconPinWidth = this._width * this._dimensionX * WIDTH_SCALE;
	            this._beaconPinHeight = this._height * this._dimensionY * HEIGHT_SCALE;
	            this._sprite.width = this._beaconPinWidth;
	            this._sprite.height = this._beaconPinHeight;
	            this._sprite.x = -(this._beaconPinWidth * X_POSITION_MULTIPLIER);
	            this._sprite.y = -(this._beaconPinHeight * Y_POSITION_MULTIPLIER);
	
	            // events for drag start
	            this._group.addChild(this._sprite);
	        }
	    }, {
	        key: "onDragStart",
	        value: function onDragStart(event) {
	            // store a reference to the data
	            // the reason for this is because of multitouch
	            // we want to track the movement of this particular touch
	            this.data = event.data;
	            this.dragging = true;
	        }
	    }, {
	        key: "onPinSelected",
	        value: function onPinSelected() {
	            this._grid._store.dispatch({
	                type: 'TOGGLE_PIN',
	                name: this._name
	            });
	        }
	    }, {
	        key: "onDragEnd",
	        value: function onDragEnd() {
	            var self = this;
	            return function (event) {
	                // e.stopPropagation();
	
	                this.dragging = false;
	
	                // set the interaction data to null
	                this.data = null;
	                var newPosition = event.data.getLocalPosition(this.parent);
	                var x = Math.round(newPosition.x / self._width);
	                var y = Math.round(newPosition.y / self._height);
	                self._grid._store.dispatch({
	                    type: 'SET_PIN_POSITION',
	                    id: self._id,
	                    name: self._name,
	                    x: x,
	                    y: y
	                });
	                self.save(x, y);
	            };
	        }
	    }, {
	        key: "onDragMove",
	        value: function onDragMove() {
	            var width = this._width;
	            var height = this._height;
	            var self = this;
	            return function (e) {
	                e.stopPropagation();
	                if (this.dragging == true) {
	                    var newPosition = e.data.getLocalPosition(this.parent);
	                    var maxWidth = width * self._dimensionX;
	                    var maxHeight = height * self._dimensionY;
	                    if (newPosition.x > 0 && newPosition.y > 0 && newPosition.x < maxWidth && newPosition.y < maxHeight) {
	                        var x = Math.round(newPosition.x / width);
	                        var y = Math.round(newPosition.y / height);
	                        this.position.x = x * width;
	                        this.position.y = y * height;
	                    } else {
	                        if (newPosition.x >= maxWidth) {
	                            this.position.x = maxWidth - width;
	                        }
	                        if (newPosition.y >= maxHeight) {
	                            this.position.y = maxHeight - height;
	                        }
	                        if (newPosition.x <= 0) {
	                            this.position.x = width;
	                        }
	                        if (newPosition.y <= 0) {
	                            this.position.y = height;
	                        }
	                    }
	                }
	            };
	        }
	    }, {
	        key: "destroy",
	        value: function destroy() {
	            $.ajax({
	                url: this._grid._beaconUrls.beaconPinDeleteUrl,
	                type: 'POST',
	                data: {
	                    'id': this._id
	                }
	            });
	            this._group.destroy();
	        }
	    }, {
	        key: "add",
	        value: function add(x, y) {
	            var self = this;
	            this.counter++;
	
	            // let group = new Konva.Group({
	            //     draggable: true, dragBoundFunc: function (pos) {
	            //         let x = Math.round(pos.x / self._width) * self._width;
	            //         let y = Math.round(pos.y / self._height) * self._height;
	            //
	            //         return {
	            //             x: x,
	            //             y: y
	            //         };
	            //     }
	            // });
	
	            // let beaconRect = new Konva.Rect({
	            //     id: v4(),
	            //     x: x,
	            //     y: y,
	            //     width: this._width,
	            //     height: this._height,
	            //     fill: states.PIN_COLOR,
	            //     stroke: '#aaa',
	            //     opacity: 0.5
	            // });
	            // group.add(beaconRect);
	            // let beaconPinWidth = this._width * this._dimensionX * WIDTH_SCALE;
	            // let beaconPinHeight = this._height * this._dimensionY * HEIGHT_SCALE;
	            // let beaconPin = new Konva.Rect({
	            //     x: x - (beaconPinWidth * X_POSITION_MULTIPLIER),
	            //     y: y - (beaconPinHeight * Y_POSITION_MULTIPLIER),
	            //     width: beaconPinWidth,
	            //     height: beaconPinHeight,
	            // });
	            // group.add(beaconPin);
	            //
	            // groupLayer.add(group);
	            // let beaconPinImage = makeImageRect(beaconPin, groupLayer);
	            // beaconPinImage.src = PIN_IMAGE_SRC;
	            // group.on('dragstart', function (e) {
	            //     e.cancelBubble = true;
	            //     let resultX = beaconRect.x() + group.x();
	            //     let resultY = beaconRect.y() + group.y();
	            //     self._rects[resultX / self._width][resultY / self._height] = states.EMPTY;
	            // });
	            // group.on('dragend', function (e) {
	            //     e.cancelBubble = true;
	            //     let resultX = beaconRect.x() + group.x();
	            //     let resultY = beaconRect.y() + group.y();
	            //     self._rects[resultX / self._width][resultY / self._height] = states.PIN;
	            // });
	            // group.on('mouseover click mousedown', function (e) {
	            //     e.cancelBubble = true;
	            //     return false;
	            // });
	        }
	    }, {
	        key: "counter",
	        set: function set(counter) {
	            this._counter = counter;
	        },
	        get: function get() {
	            return this._counter;
	        }
	    }], [{
	        key: "drawRect",
	        value: function drawRect(graphics, config) {
	            graphics.beginFill(config.color);
	            graphics.lineStyle(1, config.stroke, 1);
	            graphics.drawRect(config.x, config.y, config.width, config.height);
	            graphics.endFill();
	        }
	    }]);
	
	    return Pin;
	}();
	
	exports.default = Pin;

/***/ },

/***/ 208:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _react = __webpack_require__(1);
	
	var React = _interopRequireWildcard(_react);
	
	var _uuid = __webpack_require__(34);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	var PinControls = function (_React$Component) {
	    _inherits(PinControls, _React$Component);
	
	    function PinControls(props, context) {
	        _classCallCheck(this, PinControls);
	
	        return _possibleConstructorReturn(this, (PinControls.__proto__ || Object.getPrototypeOf(PinControls)).call(this, props, context));
	    }
	
	    _createClass(PinControls, [{
	        key: "render",
	        value: function render() {
	            var store = this.context.store;
	
	            var state = store.getState();
	            var canvas = this.props.canvas;
	            var currentPinName = state.pins.currentPin.name;
	            return React.createElement(
	                "div",
	                null,
	                React.createElement(
	                    "div",
	                    { className: "row" },
	                    React.createElement(
	                        "div",
	                        { className: "form-group" },
	                        React.createElement(
	                            "button",
	                            { className: "btn btn-default", onClick: function () {
	                                    store.dispatch({
	                                        type: 'CLEAR_PINS'
	                                    });
	                                    if (typeof canvas !== 'undefined' && canvas !== null) {
	                                        canvas.clear();
	                                    }
	                                }.bind(this) },
	                            "Clear"
	                        ),
	                        React.createElement(
	                            "button",
	                            { className: "btn btn-default", onClick: function () {
	                                    if (typeof canvas !== 'undefined' && canvas !== null) {
	                                        canvas.save();
	                                    }
	                                }.bind(this) },
	                            "Save"
	                        )
	                    ),
	                    React.createElement(
	                        "legend",
	                        null,
	                        "Manage panel"
	                    ),
	                    React.createElement(
	                        "div",
	                        { className: "form-group" },
	                        React.createElement("input", { id: "beacon-pin", type: "text", value: "" })
	                    ),
	                    React.createElement(
	                        "div",
	                        { className: "form-group" },
	                        React.createElement(
	                            "button",
	                            { className: "btn btn-default", id: "add-pin", onClick: function () {
	                                    if (typeof canvas !== 'undefined' && canvas !== null) {
	                                        var beacon = $('#beacon-pin');
	                                        if (beacon.select2('data') !== null) {
	                                            var id = beacon.select2('data').id;
	                                            var name = beacon.select2('data').text;
	                                            beacon.attr('value', '');
	                                            beacon.select2('val', '');
	                                            canvas._grid.addPin(0, 0, id, name);
	                                        }
	                                    }
	                                }.bind(this) },
	                            "Add pin"
	                        )
	                    ),
	                    React.createElement(
	                        "div",
	                        { className: "form-group" },
	                        currentPinName !== null ? React.createElement(
	                            "div",
	                            null,
	                            React.createElement(
	                                "span",
	                                { className: "form-control" },
	                                currentPinName
	                            ),
	                            React.createElement(
	                                "button",
	                                { className: "btn btn-default", onClick: function () {
	
	                                        if (typeof canvas !== 'undefined' || canvas !== null) {
	                                            canvas._grid.deletePin(currentPinName);
	                                        }
	                                    }.bind(this) },
	                                "Delete pin"
	                            )
	                        ) : ''
	                    )
	                )
	            );
	        }
	    }]);
	
	    return PinControls;
	}(React.Component);
	
	exports.default = PinControls;
	;
	
	PinControls.contextTypes = {
	    store: React.PropTypes.object
	};

/***/ },

/***/ 209:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.BrushControls = undefined;
	
	var _react = __webpack_require__(1);
	
	var React = _interopRequireWildcard(_react);
	
	var _BrushControl = __webpack_require__(210);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	var BrushControls = exports.BrushControls = function BrushControls(_ref) {
	    var brushes = _ref.brushes;
	
	    return React.createElement(
	        "div",
	        { className: "row", style: { marginBottom: '10px' } },
	        "Brushes",
	        brushes.map(function (brush, index) {
	            return React.createElement(_BrushControl.BrushControl, { key: index, index: index, brush: brush });
	        })
	    );
	};

/***/ },

/***/ 210:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	
	Object.defineProperty(exports, "__esModule", {
	    value: true
	});
	exports.BrushControl = undefined;
	
	var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
	var _react = __webpack_require__(1);
	
	var React = _interopRequireWildcard(_react);
	
	var _states = __webpack_require__(41);
	
	var states = _interopRequireWildcard(_states);
	
	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
	
	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }
	
	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
	
	var BrushControl = exports.BrushControl = function (_React$Component) {
	    _inherits(BrushControl, _React$Component);
	
	    function BrushControl(props, context) {
	        _classCallCheck(this, BrushControl);
	
	        var _this = _possibleConstructorReturn(this, (BrushControl.__proto__ || Object.getPrototypeOf(BrushControl)).call(this, props, context));
	
	        var store = context.store;
	        var index = props.index;
	
	        _this._store = store;
	        _this._index = index;
	        return _this;
	    }
	
	    _createClass(BrushControl, [{
	        key: "onClick",
	        value: function onClick() {
	            this._store.dispatch({ type: 'TOGGLE_BRUSH', index: this._index });
	        }
	    }, {
	        key: "render",
	        value: function render() {
	            var brush = this.props.brush;
	
	            return React.createElement("div", { className: "cell",
	                style: {
	                    background: states.web_colors[brush.color],
	                    border: brush.toggled ? '3px solid #B92626' : 'none'
	                },
	                onClick: this.onClick.bind(this) });
	        }
	    }]);
	
	    return BrushControl;
	}(React.Component);
	
	BrushControl.contextTypes = {
	    store: React.PropTypes.object
	};

/***/ }

});
//# sourceMappingURL=app.js.map