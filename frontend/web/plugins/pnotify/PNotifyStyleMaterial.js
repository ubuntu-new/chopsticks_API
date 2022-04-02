var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

/* src/PNotifyStyleMaterial.html generated by Svelte v2.15.3 */
var PNotifyStyleMaterial = function (PNotify) {
	"use strict";

	PNotify = PNotify && PNotify.__esModule ? PNotify["default"] : PNotify;

	function setup(Component) {
		Component.key = 'StyleMaterial';

		// Register the module with PNotify.
		PNotify.modules.StyleMaterial = Component;
		// Prepend this module to the container.
		PNotify.modulesPrependContainer.push(Component);

		if (!PNotify.styling.material) {
			PNotify.styling.material = {};
		}
		PNotify.styling.material = _extends(PNotify.styling.material, {
			container: 'pnotify-material',
			notice: 'pnotify-material-notice',
			info: 'pnotify-material-info',
			success: 'pnotify-material-success',
			error: 'pnotify-material-error'
		});

		if (!PNotify.icons.material) {
			PNotify.icons.material = {};
		}
		PNotify.icons.material = _extends(PNotify.icons.material, {
			notice: 'material-icons pnotify-material-icon-notice',
			info: 'material-icons pnotify-material-icon-info',
			success: 'material-icons pnotify-material-icon-success',
			error: 'material-icons pnotify-material-icon-error',
			closer: 'material-icons pnotify-material-icon-closer',
			pinUp: 'material-icons pnotify-material-icon-sticker',
			pinDown: 'material-icons pnotify-material-icon-sticker pnotify-material-icon-stuck'
		});
	};

	function add_css() {
		var style = createElement("style");
		style.id = 'svelte-19og8nx-style';
		style.textContent = "[ui-pnotify] .pnotify-material{-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;font-size:14px}[ui-pnotify] .pnotify-material.ui-pnotify-shadow{-webkit-box-shadow:0px 6px 24px 0px rgba(0,0,0,0.2);-moz-box-shadow:0px 6px 24px 0px rgba(0,0,0,0.2);box-shadow:0px 6px 24px 0px rgba(0,0,0,0.2)}[ui-pnotify] .pnotify-material.ui-pnotify-container{padding:24px}[ui-pnotify] .pnotify-material .ui-pnotify-title{font-size:20px;margin-bottom:20px;line-height:24px}[ui-pnotify] .pnotify-material .ui-pnotify-title:last-child{margin-bottom:0}[ui-pnotify] .pnotify-material .ui-pnotify-text{font-size:16px;line-height:24px}[ui-pnotify].ui-pnotify-with-icon .pnotify-material .ui-pnotify-title,[ui-pnotify].ui-pnotify-with-icon .pnotify-material .ui-pnotify-text,[ui-pnotify].ui-pnotify-with-icon .pnotify-material .ui-pnotify-confirm{margin-left:32px}[dir=rtl] [ui-pnotify].ui-pnotify-with-icon .pnotify-material .ui-pnotify-title,[dir=rtl] [ui-pnotify].ui-pnotify-with-icon .pnotify-material .ui-pnotify-text,[dir=rtl] [ui-pnotify].ui-pnotify-with-icon .pnotify-material .ui-pnotify-confirm{margin-right:32px;margin-left:0}[ui-pnotify] .pnotify-material .ui-pnotify-action-bar{margin-top:20px;margin-right:-16px;margin-bottom:-16px}[dir=rtl] [ui-pnotify] .pnotify-material .ui-pnotify-action-bar{margin-left:-16px;margin-right:0}[ui-pnotify] .pnotify-material-notice{background-color:#FFEE58;border:none;color:#000}[ui-pnotify] .pnotify-material-info{background-color:#26C6DA;border:none;color:#000}[ui-pnotify] .pnotify-material-success{background-color:#66BB6A;border:none;color:#fff}[ui-pnotify] .pnotify-material-error{background-color:#EF5350;border:none;color:#fff}[ui-pnotify] .pnotify-material-icon-notice,[ui-pnotify] .pnotify-material-icon-info,[ui-pnotify] .pnotify-material-icon-success,[ui-pnotify] .pnotify-material-icon-error,[ui-pnotify] .pnotify-material-icon-closer,[ui-pnotify] .pnotify-material-icon-sticker{position:relative}[ui-pnotify] .pnotify-material-icon-closer,[ui-pnotify] .pnotify-material-icon-sticker{height:20px;width:20px;font-size:20px;line-height:20px;position:relative}[ui-pnotify] .pnotify-material-icon-notice:after,[ui-pnotify] .pnotify-material-icon-info:after,[ui-pnotify] .pnotify-material-icon-success:after,[ui-pnotify] .pnotify-material-icon-error:after,[ui-pnotify] .pnotify-material-icon-closer:after,[ui-pnotify] .pnotify-material-icon-sticker:after{font-family:'Material Icons'}[ui-pnotify] .pnotify-material-icon-notice:after{content:\"announcement\"}[ui-pnotify] .pnotify-material-icon-info:after{content:\"info\"}[ui-pnotify] .pnotify-material-icon-success:after{content:\"check_circle\"}[ui-pnotify] .pnotify-material-icon-error:after{content:\"error\"}[ui-pnotify] .pnotify-material-icon-closer,[ui-pnotify] .pnotify-material-icon-sticker{display:inline-block}[ui-pnotify] .pnotify-material-icon-closer:after{top:-4px;content:\"close\"}[ui-pnotify] .pnotify-material-icon-sticker:after{top:-5px;content:\"pause\"}[ui-pnotify] .pnotify-material-icon-sticker.pnotify-material-icon-stuck:after{content:\"play_arrow\"}[ui-pnotify].ui-pnotify .pnotify-material .ui-pnotify-prompt-input{display:block;width:100%;margin-bottom:8px;padding:15px 0 8px;background-color:transparent;color:inherit;border-radius:0;border-top:none;border-left:none;border-right:none;border-bottom-style:solid;border-bottom-color:inherit;border-bottom-width:1px}[ui-pnotify].ui-pnotify .pnotify-material .ui-pnotify-prompt-input:focus{outline:none;border-bottom-color:#3F51B5;border-bottom-width:2px}[ui-pnotify].ui-pnotify .pnotify-material .ui-pnotify-action-button{position:relative;padding:0 16px;overflow:hidden;border-width:0;outline:none;border-radius:2px;background-color:transparent;color:inherit;transition:background-color .3s;text-transform:uppercase;height:36px;margin:6px;min-width:64px;font-weight:bold}[ui-pnotify].ui-pnotify .pnotify-material .ui-pnotify-action-button.ui-pnotify-material-primary{color:#3F51B5}[ui-pnotify].ui-pnotify .pnotify-material .ui-pnotify-action-button:hover,[ui-pnotify].ui-pnotify .pnotify-material .ui-pnotify-action-button:focus{background-color:rgba(0, 0, 0, .12);color:inherit}[ui-pnotify].ui-pnotify .pnotify-material .ui-pnotify-action-button.ui-pnotify-material-primary:hover,[ui-pnotify].ui-pnotify .pnotify-material .ui-pnotify-action-button.ui-pnotify-material-primary:focus{color:#303F9F}[ui-pnotify].ui-pnotify .pnotify-material .ui-pnotify-action-button:before{content:\"\";position:absolute;top:50%;left:50%;display:block;width:0;padding-top:0;border-radius:100%;background-color:rgba(153, 153, 153, .4);-webkit-transform:translate(-50%, -50%);-moz-transform:translate(-50%, -50%);-ms-transform:translate(-50%, -50%);-o-transform:translate(-50%, -50%);transform:translate(-50%, -50%)}[ui-pnotify].ui-pnotify .pnotify-material .ui-pnotify-action-button:active:before{width:120%;padding-top:120%;transition:width .2s ease-out, padding-top .2s ease-out}";
		append(document.head, style);
	}

	function create_main_fragment(component, ctx) {

		return {
			c: noop,

			m: noop,

			p: noop,

			d: noop
		};
	}

	function PNotifyStyleMaterial(options) {
		init(this, options);
		this._state = assign({}, options.data);
		this._intro = true;

		if (!document.getElementById("svelte-19og8nx-style")) add_css();

		this._fragment = create_main_fragment(this, this._state);

		if (options.target) {
			this._fragment.c();
			this._mount(options.target, options.anchor);
		}
	}

	assign(PNotifyStyleMaterial.prototype, {
		destroy: destroy,
		get: get,
		fire: fire,
		on: on,
		set: set,
		_set: _set,
		_stage: _stage,
		_mount: _mount,
		_differs: _differs
	});

	PNotifyStyleMaterial.prototype._recompute = noop;

	setup(PNotifyStyleMaterial);

	function createElement(name) {
		return document.createElement(name);
	}

	function append(target, node) {
		target.appendChild(node);
	}

	function noop() {}

	function init(component, options) {
		component._handlers = blankObject();
		component._slots = blankObject();
		component._bind = options._bind;
		component._staged = {};

		component.options = options;
		component.root = options.root || component;
		component.store = options.store || component.root.store;

		if (!options.root) {
			component._beforecreate = [];
			component._oncreate = [];
			component._aftercreate = [];
		}
	}

	function assign(tar, src) {
		for (var k in src) {
			tar[k] = src[k];
		}return tar;
	}

	function destroy(detach) {
		this.destroy = noop;
		this.fire('destroy');
		this.set = noop;

		this._fragment.d(detach !== false);
		this._fragment = null;
		this._state = {};
	}

	function get() {
		return this._state;
	}

	function fire(eventName, data) {
		var handlers = eventName in this._handlers && this._handlers[eventName].slice();
		if (!handlers) return;

		for (var i = 0; i < handlers.length; i += 1) {
			var handler = handlers[i];

			if (!handler.__calling) {
				try {
					handler.__calling = true;
					handler.call(this, data);
				} finally {
					handler.__calling = false;
				}
			}
		}
	}

	function on(eventName, handler) {
		var handlers = this._handlers[eventName] || (this._handlers[eventName] = []);
		handlers.push(handler);

		return {
			cancel: function cancel() {
				var index = handlers.indexOf(handler);
				if (~index) handlers.splice(index, 1);
			}
		};
	}

	function set(newState) {
		this._set(assign({}, newState));
		if (this.root._lock) return;
		flush(this.root);
	}

	function _set(newState) {
		var oldState = this._state,
		    changed = {},
		    dirty = false;

		newState = assign(this._staged, newState);
		this._staged = {};

		for (var key in newState) {
			if (this._differs(newState[key], oldState[key])) changed[key] = dirty = true;
		}
		if (!dirty) return;

		this._state = assign(assign({}, oldState), newState);
		this._recompute(changed, this._state);
		if (this._bind) this._bind(changed, this._state);

		if (this._fragment) {
			this.fire("state", { changed: changed, current: this._state, previous: oldState });
			this._fragment.p(changed, this._state);
			this.fire("update", { changed: changed, current: this._state, previous: oldState });
		}
	}

	function _stage(newState) {
		assign(this._staged, newState);
	}

	function _mount(target, anchor) {
		this._fragment[this._fragment.i ? 'i' : 'm'](target, anchor || null);
	}

	function _differs(a, b) {
		return a != a ? b == b : a !== b || a && (typeof a === "undefined" ? "undefined" : _typeof(a)) === 'object' || typeof a === 'function';
	}

	function blankObject() {
		return Object.create(null);
	}

	function flush(component) {
		component._lock = true;
		callAll(component._beforecreate);
		callAll(component._oncreate);
		callAll(component._aftercreate);
		component._lock = false;
	}

	function callAll(fns) {
		while (fns && fns.length) {
			fns.shift()();
		}
	}
	return PNotifyStyleMaterial;
}(PNotify);