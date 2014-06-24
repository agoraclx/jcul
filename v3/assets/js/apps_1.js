/**
 * @preserve FastClick: polyfill to remove click delays on browsers with touch UIs.
 *
 * @version 0.6.11
 * @codingstandard ftlabs-jsv2
 * @copyright The Financial Times Limited [All Rights Reserved]
 * @license MIT License (see LICENSE.txt)
 */

/*jslint browser:true, node:true*/
/*global define, Event, Node*/


/**
 * Instantiate fast-clicking listeners on the specificed layer.
 *
 * @constructor
 * @param {Element} layer The layer to listen on
 */
function FastClick(layer) {
	'use strict';
	var oldOnClick, self = this;


	/**
	 * Whether a click is currently being tracked.
	 *
	 * @type boolean
	 */
	this.trackingClick = false;


	/**
	 * Timestamp for when when click tracking started.
	 *
	 * @type number
	 */
	this.trackingClickStart = 0;


	/**
	 * The element being tracked for a click.
	 *
	 * @type EventTarget
	 */
	this.targetElement = null;


	/**
	 * X-coordinate of touch start event.
	 *
	 * @type number
	 */
	this.touchStartX = 0;


	/**
	 * Y-coordinate of touch start event.
	 *
	 * @type number
	 */
	this.touchStartY = 0;


	/**
	 * ID of the last touch, retrieved from Touch.identifier.
	 *
	 * @type number
	 */
	this.lastTouchIdentifier = 0;


	/**
	 * Touchmove boundary, beyond which a click will be cancelled.
	 *
	 * @type number
	 */
	this.touchBoundary = 10;


	/**
	 * The FastClick layer.
	 *
	 * @type Element
	 */
	this.layer = layer;

	if (!layer || !layer.nodeType) {
		throw new TypeError('Layer must be a document node');
	}

	/** @type function() */
	this.onClick = function() { return FastClick.prototype.onClick.apply(self, arguments); };

	/** @type function() */
	this.onMouse = function() { return FastClick.prototype.onMouse.apply(self, arguments); };

	/** @type function() */
	this.onTouchStart = function() { return FastClick.prototype.onTouchStart.apply(self, arguments); };

	/** @type function() */
	this.onTouchMove = function() { return FastClick.prototype.onTouchMove.apply(self, arguments); };

	/** @type function() */
	this.onTouchEnd = function() { return FastClick.prototype.onTouchEnd.apply(self, arguments); };

	/** @type function() */
	this.onTouchCancel = function() { return FastClick.prototype.onTouchCancel.apply(self, arguments); };

	if (FastClick.notNeeded(layer)) {
		return;
	}

	// Set up event handlers as required
	if (this.deviceIsAndroid) {
		layer.addEventListener('mouseover', this.onMouse, true);
		layer.addEventListener('mousedown', this.onMouse, true);
		layer.addEventListener('mouseup', this.onMouse, true);
	}

	layer.addEventListener('click', this.onClick, true);
	layer.addEventListener('touchstart', this.onTouchStart, false);
	layer.addEventListener('touchmove', this.onTouchMove, false);
	layer.addEventListener('touchend', this.onTouchEnd, false);
	layer.addEventListener('touchcancel', this.onTouchCancel, false);

	// Hack is required for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
	// which is how FastClick normally stops click events bubbling to callbacks registered on the FastClick
	// layer when they are cancelled.
	if (!Event.prototype.stopImmediatePropagation) {
		layer.removeEventListener = function(type, callback, capture) {
			var rmv = Node.prototype.removeEventListener;
			if (type === 'click') {
				rmv.call(layer, type, callback.hijacked || callback, capture);
			} else {
				rmv.call(layer, type, callback, capture);
			}
		};

		layer.addEventListener = function(type, callback, capture) {
			var adv = Node.prototype.addEventListener;
			if (type === 'click') {
				adv.call(layer, type, callback.hijacked || (callback.hijacked = function(event) {
					if (!event.propagationStopped) {
						callback(event);
					}
				}), capture);
			} else {
				adv.call(layer, type, callback, capture);
			}
		};
	}

	// If a handler is already declared in the element's onclick attribute, it will be fired before
	// FastClick's onClick handler. Fix this by pulling out the user-defined handler function and
	// adding it as listener.
	if (typeof layer.onclick === 'function') {

		// Android browser on at least 3.2 requires a new reference to the function in layer.onclick
		// - the old one won't work if passed to addEventListener directly.
		oldOnClick = layer.onclick;
		layer.addEventListener('click', function(event) {
			oldOnClick(event);
		}, false);
		layer.onclick = null;
	}
}


/**
 * Android requires exceptions.
 *
 * @type boolean
 */
FastClick.prototype.deviceIsAndroid = navigator.userAgent.indexOf('Android') > 0;


/**
 * iOS requires exceptions.
 *
 * @type boolean
 */
FastClick.prototype.deviceIsIOS = /iP(ad|hone|od)/.test(navigator.userAgent);


/**
 * iOS 4 requires an exception for select elements.
 *
 * @type boolean
 */
FastClick.prototype.deviceIsIOS4 = FastClick.prototype.deviceIsIOS && (/OS 4_\d(_\d)?/).test(navigator.userAgent);


/**
 * iOS 6.0(+?) requires the target element to be manually derived
 *
 * @type boolean
 */
FastClick.prototype.deviceIsIOSWithBadTarget = FastClick.prototype.deviceIsIOS && (/OS ([6-9]|\d{2})_\d/).test(navigator.userAgent);


/**
 * Determine whether a given element requires a native click.
 *
 * @param {EventTarget|Element} target Target DOM element
 * @returns {boolean} Returns true if the element needs a native click
 */
FastClick.prototype.needsClick = function(target) {
	'use strict';
	switch (target.nodeName.toLowerCase()) {

	// Don't send a synthetic click to disabled inputs (issue #62)
	case 'button':
	case 'select':
	case 'textarea':
		if (target.disabled) {
			return true;
		}

		break;
	case 'input':

		// File inputs need real clicks on iOS 6 due to a browser bug (issue #68)
		if ((this.deviceIsIOS && target.type === 'file') || target.disabled) {
			return true;
		}

		break;
	case 'label':
	case 'video':
		return true;
	}

	return (/\bneedsclick\b/).test(target.className);
};


/**
 * Determine whether a given element requires a call to focus to simulate click into element.
 *
 * @param {EventTarget|Element} target Target DOM element
 * @returns {boolean} Returns true if the element requires a call to focus to simulate native click.
 */
FastClick.prototype.needsFocus = function(target) {
	'use strict';
	switch (target.nodeName.toLowerCase()) {
	case 'textarea':
		return true;
	case 'select':
		return !this.deviceIsAndroid;
	case 'input':
		switch (target.type) {
		case 'button':
		case 'checkbox':
		case 'file':
		case 'image':
		case 'radio':
		case 'submit':
			return false;
		}

		// No point in attempting to focus disabled inputs
		return !target.disabled && !target.readOnly;
	default:
		return (/\bneedsfocus\b/).test(target.className);
	}
};


/**
 * Send a click event to the specified element.
 *
 * @param {EventTarget|Element} targetElement
 * @param {Event} event
 */
FastClick.prototype.sendClick = function(targetElement, event) {
	'use strict';
	var clickEvent, touch;

	// On some Android devices activeElement needs to be blurred otherwise the synthetic click will have no effect (#24)
	if (document.activeElement && document.activeElement !== targetElement) {
		document.activeElement.blur();
	}

	touch = event.changedTouches[0];

	// Synthesise a click event, with an extra attribute so it can be tracked
	clickEvent = document.createEvent('MouseEvents');
	clickEvent.initMouseEvent(this.determineEventType(targetElement), true, true, window, 1, touch.screenX, touch.screenY, touch.clientX, touch.clientY, false, false, false, false, 0, null);
	clickEvent.forwardedTouchEvent = true;
	targetElement.dispatchEvent(clickEvent);
};

FastClick.prototype.determineEventType = function(targetElement) {
	'use strict';

	//Issue #159: Android Chrome Select Box does not open with a synthetic click event
	if (this.deviceIsAndroid && targetElement.tagName.toLowerCase() === 'select') {
		return 'mousedown';
	}

	return 'click';
};


/**
 * @param {EventTarget|Element} targetElement
 */
FastClick.prototype.focus = function(targetElement) {
	'use strict';
	var length;

	// Issue #160: on iOS 7, some input elements (e.g. date datetime) throw a vague TypeError on setSelectionRange. These elements don't have an integer value for the selectionStart and selectionEnd properties, but unfortunately that can't be used for detection because accessing the properties also throws a TypeError. Just check the type instead. Filed as Apple bug #15122724.
	if (this.deviceIsIOS && targetElement.setSelectionRange && targetElement.type.indexOf('date') !== 0 && targetElement.type !== 'time') {
		length = targetElement.value.length;
		targetElement.setSelectionRange(length, length);
	} else {
		targetElement.focus();
	}
};


/**
 * Check whether the given target element is a child of a scrollable layer and if so, set a flag on it.
 *
 * @param {EventTarget|Element} targetElement
 */
FastClick.prototype.updateScrollParent = function(targetElement) {
	'use strict';
	var scrollParent, parentElement;

	scrollParent = targetElement.fastClickScrollParent;

	// Attempt to discover whether the target element is contained within a scrollable layer. Re-check if the
	// target element was moved to another parent.
	if (!scrollParent || !scrollParent.contains(targetElement)) {
		parentElement = targetElement;
		do {
			if (parentElement.scrollHeight > parentElement.offsetHeight) {
				scrollParent = parentElement;
				targetElement.fastClickScrollParent = parentElement;
				break;
			}

			parentElement = parentElement.parentElement;
		} while (parentElement);
	}

	// Always update the scroll top tracker if possible.
	if (scrollParent) {
		scrollParent.fastClickLastScrollTop = scrollParent.scrollTop;
	}
};


/**
 * @param {EventTarget} targetElement
 * @returns {Element|EventTarget}
 */
FastClick.prototype.getTargetElementFromEventTarget = function(eventTarget) {
	'use strict';

	// On some older browsers (notably Safari on iOS 4.1 - see issue #56) the event target may be a text node.
	if (eventTarget.nodeType === Node.TEXT_NODE) {
		return eventTarget.parentNode;
	}

	return eventTarget;
};


/**
 * On touch start, record the position and scroll offset.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.onTouchStart = function(event) {
	'use strict';
	var targetElement, touch, selection;

	// Ignore multiple touches, otherwise pinch-to-zoom is prevented if both fingers are on the FastClick element (issue #111).
	if (event.targetTouches.length > 1) {
		return true;
	}

	targetElement = this.getTargetElementFromEventTarget(event.target);
	touch = event.targetTouches[0];

	if (this.deviceIsIOS) {

		// Only trusted events will deselect text on iOS (issue #49)
		selection = window.getSelection();
		if (selection.rangeCount && !selection.isCollapsed) {
			return true;
		}

		if (!this.deviceIsIOS4) {

			// Weird things happen on iOS when an alert or confirm dialog is opened from a click event callback (issue #23):
			// when the user next taps anywhere else on the page, new touchstart and touchend events are dispatched
			// with the same identifier as the touch event that previously triggered the click that triggered the alert.
			// Sadly, there is an issue on iOS 4 that causes some normal touch events to have the same identifier as an
			// immediately preceeding touch event (issue #52), so this fix is unavailable on that platform.
			if (touch.identifier === this.lastTouchIdentifier) {
				event.preventDefault();
				return false;
			}

			this.lastTouchIdentifier = touch.identifier;

			// If the target element is a child of a scrollable layer (using -webkit-overflow-scrolling: touch) and:
			// 1) the user does a fling scroll on the scrollable layer
			// 2) the user stops the fling scroll with another tap
			// then the event.target of the last 'touchend' event will be the element that was under the user's finger
			// when the fling scroll was started, causing FastClick to send a click event to that layer - unless a check
			// is made to ensure that a parent layer was not scrolled before sending a synthetic click (issue #42).
			this.updateScrollParent(targetElement);
		}
	}

	this.trackingClick = true;
	this.trackingClickStart = event.timeStamp;
	this.targetElement = targetElement;

	this.touchStartX = touch.pageX;
	this.touchStartY = touch.pageY;

	// Prevent phantom clicks on fast double-tap (issue #36)
	if ((event.timeStamp - this.lastClickTime) < 200) {
		event.preventDefault();
	}

	return true;
};


/**
 * Based on a touchmove event object, check whether the touch has moved past a boundary since it started.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.touchHasMoved = function(event) {
	'use strict';
	var touch = event.changedTouches[0], boundary = this.touchBoundary;

	if (Math.abs(touch.pageX - this.touchStartX) > boundary || Math.abs(touch.pageY - this.touchStartY) > boundary) {
		return true;
	}

	return false;
};


/**
 * Update the last position.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.onTouchMove = function(event) {
	'use strict';
	if (!this.trackingClick) {
		return true;
	}

	// If the touch has moved, cancel the click tracking
	if (this.targetElement !== this.getTargetElementFromEventTarget(event.target) || this.touchHasMoved(event)) {
		this.trackingClick = false;
		this.targetElement = null;
	}

	return true;
};


/**
 * Attempt to find the labelled control for the given label element.
 *
 * @param {EventTarget|HTMLLabelElement} labelElement
 * @returns {Element|null}
 */
FastClick.prototype.findControl = function(labelElement) {
	'use strict';

	// Fast path for newer browsers supporting the HTML5 control attribute
	if (labelElement.control !== undefined) {
		return labelElement.control;
	}

	// All browsers under test that support touch events also support the HTML5 htmlFor attribute
	if (labelElement.htmlFor) {
		return document.getElementById(labelElement.htmlFor);
	}

	// If no for attribute exists, attempt to retrieve the first labellable descendant element
	// the list of which is defined here: http://www.w3.org/TR/html5/forms.html#category-label
	return labelElement.querySelector('button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea');
};


/**
 * On touch end, determine whether to send a click event at once.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.onTouchEnd = function(event) {
	'use strict';
	var forElement, trackingClickStart, targetTagName, scrollParent, touch, targetElement = this.targetElement;

	if (!this.trackingClick) {
		return true;
	}

	// Prevent phantom clicks on fast double-tap (issue #36)
	if ((event.timeStamp - this.lastClickTime) < 200) {
		this.cancelNextClick = true;
		return true;
	}

	// Reset to prevent wrong click cancel on input (issue #156).
	this.cancelNextClick = false;

	this.lastClickTime = event.timeStamp;

	trackingClickStart = this.trackingClickStart;
	this.trackingClick = false;
	this.trackingClickStart = 0;

	// On some iOS devices, the targetElement supplied with the event is invalid if the layer
	// is performing a transition or scroll, and has to be re-detected manually. Note that
	// for this to function correctly, it must be called *after* the event target is checked!
	// See issue #57; also filed as rdar://13048589 .
	if (this.deviceIsIOSWithBadTarget) {
		touch = event.changedTouches[0];

		// In certain cases arguments of elementFromPoint can be negative, so prevent setting targetElement to null
		targetElement = document.elementFromPoint(touch.pageX - window.pageXOffset, touch.pageY - window.pageYOffset) || targetElement;
		targetElement.fastClickScrollParent = this.targetElement.fastClickScrollParent;
	}

	targetTagName = targetElement.tagName.toLowerCase();
	if (targetTagName === 'label') {
		forElement = this.findControl(targetElement);
		if (forElement) {
			this.focus(targetElement);
			if (this.deviceIsAndroid) {
				return false;
			}

			targetElement = forElement;
		}
	} else if (this.needsFocus(targetElement)) {

		// Case 1: If the touch started a while ago (best guess is 100ms based on tests for issue #36) then focus will be triggered anyway. Return early and unset the target element reference so that the subsequent click will be allowed through.
		// Case 2: Without this exception for input elements tapped when the document is contained in an iframe, then any inputted text won't be visible even though the value attribute is updated as the user types (issue #37).
		if ((event.timeStamp - trackingClickStart) > 100 || (this.deviceIsIOS && window.top !== window && targetTagName === 'input')) {
			this.targetElement = null;
			return false;
		}

		this.focus(targetElement);

		// Select elements need the event to go through on iOS 4, otherwise the selector menu won't open.
		if (!this.deviceIsIOS4 || targetTagName !== 'select') {
			this.targetElement = null;
			event.preventDefault();
		}

		return false;
	}

	if (this.deviceIsIOS && !this.deviceIsIOS4) {

		// Don't send a synthetic click event if the target element is contained within a parent layer that was scrolled
		// and this tap is being used to stop the scrolling (usually initiated by a fling - issue #42).
		scrollParent = targetElement.fastClickScrollParent;
		if (scrollParent && scrollParent.fastClickLastScrollTop !== scrollParent.scrollTop) {
			return true;
		}
	}

	// Prevent the actual click from going though - unless the target node is marked as requiring
	// real clicks or if it is in the whitelist in which case only non-programmatic clicks are permitted.
	if (!this.needsClick(targetElement)) {
		event.preventDefault();
		this.sendClick(targetElement, event);
	}

	return false;
};


/**
 * On touch cancel, stop tracking the click.
 *
 * @returns {void}
 */
FastClick.prototype.onTouchCancel = function() {
	'use strict';
	this.trackingClick = false;
	this.targetElement = null;
};


/**
 * Determine mouse events which should be permitted.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.onMouse = function(event) {
	'use strict';

	// If a target element was never set (because a touch event was never fired) allow the event
	if (!this.targetElement) {
		return true;
	}

	if (event.forwardedTouchEvent) {
		return true;
	}

	// Programmatically generated events targeting a specific element should be permitted
	if (!event.cancelable) {
		return true;
	}

	// Derive and check the target element to see whether the mouse event needs to be permitted;
	// unless explicitly enabled, prevent non-touch click events from triggering actions,
	// to prevent ghost/doubleclicks.
	if (!this.needsClick(this.targetElement) || this.cancelNextClick) {

		// Prevent any user-added listeners declared on FastClick element from being fired.
		if (event.stopImmediatePropagation) {
			event.stopImmediatePropagation();
		} else {

			// Part of the hack for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
			event.propagationStopped = true;
		}

		// Cancel the event
		event.stopPropagation();
		event.preventDefault();

		return false;
	}

	// If the mouse event is permitted, return true for the action to go through.
	return true;
};


/**
 * On actual clicks, determine whether this is a touch-generated click, a click action occurring
 * naturally after a delay after a touch (which needs to be cancelled to avoid duplication), or
 * an actual click which should be permitted.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.onClick = function(event) {
	'use strict';
	var permitted;

	// It's possible for another FastClick-like library delivered with third-party code to fire a click event before FastClick does (issue #44). In that case, set the click-tracking flag back to false and return early. This will cause onTouchEnd to return early.
	if (this.trackingClick) {
		this.targetElement = null;
		this.trackingClick = false;
		return true;
	}

	// Very odd behaviour on iOS (issue #18): if a submit element is present inside a form and the user hits enter in the iOS simulator or clicks the Go button on the pop-up OS keyboard the a kind of 'fake' click event will be triggered with the submit-type input element as the target.
	if (event.target.type === 'submit' && event.detail === 0) {
		return true;
	}

	permitted = this.onMouse(event);

	// Only unset targetElement if the click is not permitted. This will ensure that the check for !targetElement in onMouse fails and the browser's click doesn't go through.
	if (!permitted) {
		this.targetElement = null;
	}

	// If clicks are permitted, return true for the action to go through.
	return permitted;
};


/**
 * Remove all FastClick's event listeners.
 *
 * @returns {void}
 */
FastClick.prototype.destroy = function() {
	'use strict';
	var layer = this.layer;

	if (this.deviceIsAndroid) {
		layer.removeEventListener('mouseover', this.onMouse, true);
		layer.removeEventListener('mousedown', this.onMouse, true);
		layer.removeEventListener('mouseup', this.onMouse, true);
	}

	layer.removeEventListener('click', this.onClick, true);
	layer.removeEventListener('touchstart', this.onTouchStart, false);
	layer.removeEventListener('touchmove', this.onTouchMove, false);
	layer.removeEventListener('touchend', this.onTouchEnd, false);
	layer.removeEventListener('touchcancel', this.onTouchCancel, false);
};


/**
 * Check whether FastClick is needed.
 *
 * @param {Element} layer The layer to listen on
 */
FastClick.notNeeded = function(layer) {
	'use strict';
	var metaViewport;
	var chromeVersion;

	// Devices that don't support touch don't need FastClick
	if (typeof window.ontouchstart === 'undefined') {
		return true;
	}

	// Chrome version - zero for other browsers
	chromeVersion = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

	if (chromeVersion) {

		if (FastClick.prototype.deviceIsAndroid) {
			metaViewport = document.querySelector('meta[name=viewport]');

			if (metaViewport) {
				// Chrome on Android with user-scalable="no" doesn't need FastClick (issue #89)
				if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
					return true;
				}
				// Chrome 32 and above with width=device-width or less don't need FastClick
				if (chromeVersion > 31 && window.innerWidth <= window.screen.width) {
					return true;
				}
			}

		// Chrome desktop doesn't need FastClick (issue #15)
		} else {
			return true;
		}
	}

	// IE10 with -ms-touch-action: none, which disables double-tap-to-zoom (issue #97)
	if (layer.style.msTouchAction === 'none') {
		return true;
	}

	return false;
};


/**
 * Factory method for creating a FastClick object
 *
 * @param {Element} layer The layer to listen on
 */
FastClick.attach = function(layer) {
	'use strict';
	return new FastClick(layer);
};


if (typeof define !== 'undefined' && define.amd) {

	// AMD. Register as an anonymous module.
	define(function() {
		'use strict';
		return FastClick;
	});
} else if (typeof module !== 'undefined' && module.exports) {
	module.exports = FastClick.attach;
	module.exports.FastClick = FastClick;
} else {
	window.FastClick = FastClick;
}

/*
 * jQuery Superfish Menu Plugin - v1.7.4
 * Copyright (c) 2013 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 *	http://www.opensource.org/licenses/mit-license.php
 *	http://www.gnu.org/licenses/gpl.html
 */

;(function(e){"use strict";var s=function(){var s={bcClass:"sf-breadcrumb",menuClass:"sf-js-enabled",anchorClass:"sf-with-ul",menuArrowClass:"sf-arrows"},o=function(){var s=/iPhone|iPad|iPod/i.test(navigator.userAgent);return s&&e(window).load(function(){e("body").children().on("click",e.noop)}),s}(),n=function(){var e=document.documentElement.style;return"behavior"in e&&"fill"in e&&/iemobile/i.test(navigator.userAgent)}(),t=function(e,o){var n=s.menuClass;o.cssArrows&&(n+=" "+s.menuArrowClass),e.toggleClass(n)},i=function(o,n){return o.find("li."+n.pathClass).slice(0,n.pathLevels).addClass(n.hoverClass+" "+s.bcClass).filter(function(){return e(this).children(n.popUpSelector).hide().show().length}).removeClass(n.pathClass)},r=function(e){e.children("a").toggleClass(s.anchorClass)},a=function(e){var s=e.css("ms-touch-action");s="pan-y"===s?"auto":"pan-y",e.css("ms-touch-action",s)},l=function(s,t){var i="li:has("+t.popUpSelector+")";e.fn.hoverIntent&&!t.disableHI?s.hoverIntent(u,p,i):s.on("mouseenter.superfish",i,u).on("mouseleave.superfish",i,p);var r="MSPointerDown.superfish";o||(r+=" touchend.superfish"),n&&(r+=" mousedown.superfish"),s.on("focusin.superfish","li",u).on("focusout.superfish","li",p).on(r,"a",t,h)},h=function(s){var o=e(this),n=o.siblings(s.data.popUpSelector);n.length>0&&n.is(":hidden")&&(o.one("click.superfish",!1),"MSPointerDown"===s.type?o.trigger("focus"):e.proxy(u,o.parent("li"))())},u=function(){var s=e(this),o=d(s);clearTimeout(o.sfTimer),s.siblings().superfish("hide").end().superfish("show")},p=function(){var s=e(this),n=d(s);o?e.proxy(f,s,n)():(clearTimeout(n.sfTimer),n.sfTimer=setTimeout(e.proxy(f,s,n),n.delay))},f=function(s){s.retainPath=e.inArray(this[0],s.$path)>-1,this.superfish("hide"),this.parents("."+s.hoverClass).length||(s.onIdle.call(c(this)),s.$path.length&&e.proxy(u,s.$path)())},c=function(e){return e.closest("."+s.menuClass)},d=function(e){return c(e).data("sf-options")};return{hide:function(s){if(this.length){var o=this,n=d(o);if(!n)return this;var t=n.retainPath===!0?n.$path:"",i=o.find("li."+n.hoverClass).add(this).not(t).removeClass(n.hoverClass).children(n.popUpSelector),r=n.speedOut;s&&(i.show(),r=0),n.retainPath=!1,n.onBeforeHide.call(i),i.stop(!0,!0).animate(n.animationOut,r,function(){var s=e(this);n.onHide.call(s)})}return this},show:function(){var e=d(this);if(!e)return this;var s=this.addClass(e.hoverClass),o=s.children(e.popUpSelector);return e.onBeforeShow.call(o),o.stop(!0,!0).animate(e.animation,e.speed,function(){e.onShow.call(o)}),this},destroy:function(){return this.each(function(){var o,n=e(this),i=n.data("sf-options");return i?(o=n.find(i.popUpSelector).parent("li"),clearTimeout(i.sfTimer),t(n,i),r(o),a(n),n.off(".superfish").off(".hoverIntent"),o.children(i.popUpSelector).attr("style",function(e,s){return s.replace(/display[^;]+;?/g,"")}),i.$path.removeClass(i.hoverClass+" "+s.bcClass).addClass(i.pathClass),n.find("."+i.hoverClass).removeClass(i.hoverClass),i.onDestroy.call(n),n.removeData("sf-options"),void 0):!1})},init:function(o){return this.each(function(){var n=e(this);if(n.data("sf-options"))return!1;var h=e.extend({},e.fn.superfish.defaults,o),u=n.find(h.popUpSelector).parent("li");h.$path=i(n,h),n.data("sf-options",h),t(n,h),r(u),a(n),l(n,h),u.not("."+s.bcClass).superfish("hide",!0),h.onInit.call(this)})}}}();e.fn.superfish=function(o){return s[o]?s[o].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof o&&o?e.error("Method "+o+" does not exist on jQuery.fn.superfish"):s.init.apply(this,arguments)},e.fn.superfish.defaults={popUpSelector:"ul,.sf-mega",hoverClass:"sfHover",pathClass:"overrideThisToUse",pathLevels:1,delay:800,animation:{opacity:"show"},animationOut:{opacity:"hide"},speed:"normal",speedOut:"fast",cssArrows:!0,disableHI:!1,onInit:e.noop,onBeforeShow:e.noop,onShow:e.noop,onBeforeHide:e.noop,onHide:e.noop,onIdle:e.noop,onDestroy:e.noop},e.fn.extend({hideSuperfishUl:s.hide,showSuperfishUl:s.show})})(jQuery);


/*
 * Supersubs v0.3b - jQuery plugin
 * Copyright (c) 2013 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 *
 * This plugin automatically adjusts submenu widths of suckerfish-style menus to that of
 * their longest list item children. If you use this, please expect bugs and report them
 * to the jQuery Google Group with the word 'Superfish' in the subject line.
 *
 */

;(function($){ // $ will refer to jQuery within this closure

	$.fn.supersubs = function(options){
		var opts = $.extend({}, $.fn.supersubs.defaults, options);
		// return original object to support chaining
		return this.each(function() {
			// cache selections
			var $$ = $(this);
			// support metadata
			var o = $.meta ? $.extend({}, opts, $$.data()) : opts;
			// cache all ul elements and show them in preparation for measurements
			$ULs = $$.find('ul').show();
			// get the font size of menu.
			// .css('fontSize') returns various results cross-browser, so measure an em dash instead
			var fontsize = $('<li id="menu-fontsize">&#8212;</li>').css({
				'padding' : 0,
				'position' : 'absolute',
				'top' : '-999em',
				'width' : 'auto'
			}).appendTo($$)[0].clientWidth; //clientWidth is faster than .width()
			// remove em dash
			$('#menu-fontsize').remove();
			// loop through each ul in menu
			$ULs.each(function(i) {
				// cache this ul
				var $ul = $(this);
				// get all (li) children of this ul
				var $LIs = $ul.children();
				// get all anchor grand-children
				var $As = $LIs.children('a');
				// force content to one line and save current float property
				var liFloat = $LIs.css('white-space','nowrap').css('float');
				// remove width restrictions and floats so elements remain vertically stacked
				$ul.add($LIs).add($As).css({
					'float' : 'none',
					'width'	: 'auto'
				});
				// this ul will now be shrink-wrapped to longest li due to position:absolute
				// so save its width as ems.
				var emWidth = $ul[0].clientWidth / fontsize;
				// add more width to ensure lines don't turn over at certain sizes in various browsers
				emWidth += o.extraWidth;
				// restrict to at least minWidth and at most maxWidth
				if (emWidth > o.maxWidth)		{ emWidth = o.maxWidth; }
				else if (emWidth < o.minWidth)	{ emWidth = o.minWidth; }
				emWidth += 'em';
				// set ul to width in ems
				$ul.css('width',emWidth);
				// restore li floats to avoid IE bugs
				// set li width to full width of this ul
				// revert white-space to normal
				$LIs.css({
					'float' : liFloat,
					'width' : '100%',
					'white-space' : 'normal'
				})
				// update offset position of descendant ul to reflect new width of parent.
				// set it to 100% in case it isn't already set to this in the CSS
				.each(function(){
					var $childUl = $(this).children('ul');
					var offsetDirection = $childUl.css('left') !== undefined ? 'left' : 'right';
					$childUl.css(offsetDirection,'100%');
				});
			}).hide();

		});
	};
	// expose defaults
	$.fn.supersubs.defaults = {
		minWidth		: 9,		// requires em unit.
		maxWidth		: 25,		// requires em unit.
		extraWidth		: 0			// extra width can ensure lines don't sometimes turn over due to slight browser differences in how they round-off values
	};

})(jQuery); // plugin code ends


/*
 * jQuery FlexSlider v2.2.0
 * Copyright 2012 WooThemes
 * Contributing Author: Tyler Smith
 */(function(e){e.flexslider=function(t,n){var r=e(t);r.vars=e.extend({},e.flexslider.defaults,n);var i=r.vars.namespace,s=window.navigator&&window.navigator.msPointerEnabled&&window.MSGesture,o=("ontouchstart"in window||s||window.DocumentTouch&&document instanceof DocumentTouch)&&r.vars.touch,u="click touchend MSPointerUp",a="",f,l=r.vars.direction==="vertical",c=r.vars.reverse,h=r.vars.itemWidth>0,p=r.vars.animation==="fade",d=r.vars.asNavFor!=="",v={},m=!0;e.data(t,"flexslider",r);v={init:function(){r.animating=!1;r.currentSlide=parseInt(r.vars.startAt?r.vars.startAt:0);isNaN(r.currentSlide)&&(r.currentSlide=0);r.animatingTo=r.currentSlide;r.atEnd=r.currentSlide===0||r.currentSlide===r.last;r.containerSelector=r.vars.selector.substr(0,r.vars.selector.search(" "));r.slides=e(r.vars.selector,r);r.container=e(r.containerSelector,r);r.count=r.slides.length;r.syncExists=e(r.vars.sync).length>0;r.vars.animation==="slide"&&(r.vars.animation="swing");r.prop=l?"top":"marginLeft";r.args={};r.manualPause=!1;r.stopped=!1;r.started=!1;r.startTimeout=null;r.transitions=!r.vars.video&&!p&&r.vars.useCSS&&function(){var e=document.createElement("div"),t=["perspectiveProperty","WebkitPerspective","MozPerspective","OPerspective","msPerspective"];for(var n in t)if(e.style[t[n]]!==undefined){r.pfx=t[n].replace("Perspective","").toLowerCase();r.prop="-"+r.pfx+"-transform";return!0}return!1}();r.vars.controlsContainer!==""&&(r.controlsContainer=e(r.vars.controlsContainer).length>0&&e(r.vars.controlsContainer));r.vars.manualControls!==""&&(r.manualControls=e(r.vars.manualControls).length>0&&e(r.vars.manualControls));if(r.vars.randomize){r.slides.sort(function(){return Math.round(Math.random())-.5});r.container.empty().append(r.slides)}r.doMath();r.setup("init");r.vars.controlNav&&v.controlNav.setup();r.vars.directionNav&&v.directionNav.setup();r.vars.keyboard&&(e(r.containerSelector).length===1||r.vars.multipleKeyboard)&&e(document).bind("keyup",function(e){var t=e.keyCode;if(!r.animating&&(t===39||t===37)){var n=t===39?r.getTarget("next"):t===37?r.getTarget("prev"):!1;r.flexAnimate(n,r.vars.pauseOnAction)}});r.vars.mousewheel&&r.bind("mousewheel",function(e,t,n,i){e.preventDefault();var s=t<0?r.getTarget("next"):r.getTarget("prev");r.flexAnimate(s,r.vars.pauseOnAction)});r.vars.pausePlay&&v.pausePlay.setup();r.vars.slideshow&&r.vars.pauseInvisible&&v.pauseInvisible.init();if(r.vars.slideshow){r.vars.pauseOnHover&&r.hover(function(){!r.manualPlay&&!r.manualPause&&r.pause()},function(){!r.manualPause&&!r.manualPlay&&!r.stopped&&r.play()});if(!r.vars.pauseInvisible||!v.pauseInvisible.isHidden())r.vars.initDelay>0?r.startTimeout=setTimeout(r.play,r.vars.initDelay):r.play()}d&&v.asNav.setup();o&&r.vars.touch&&v.touch();(!p||p&&r.vars.smoothHeight)&&e(window).bind("resize orientationchange focus",v.resize);r.find("img").attr("draggable","false");setTimeout(function(){r.vars.start(r)},200)},asNav:{setup:function(){r.asNav=!0;r.animatingTo=Math.floor(r.currentSlide/r.move);r.currentItem=r.currentSlide;r.slides.removeClass(i+"active-slide").eq(r.currentItem).addClass(i+"active-slide");if(!s)r.slides.click(function(t){t.preventDefault();var n=e(this),s=n.index(),o=n.offset().left-e(r).scrollLeft();if(o<=0&&n.hasClass(i+"active-slide"))r.flexAnimate(r.getTarget("prev"),!0);else if(!e(r.vars.asNavFor).data("flexslider").animating&&!n.hasClass(i+"active-slide")){r.direction=r.currentItem<s?"next":"prev";r.flexAnimate(s,r.vars.pauseOnAction,!1,!0,!0)}});else{t._slider=r;r.slides.each(function(){var t=this;t._gesture=new MSGesture;t._gesture.target=t;t.addEventListener("MSPointerDown",function(e){e.preventDefault();e.currentTarget._gesture&&e.currentTarget._gesture.addPointer(e.pointerId)},!1);t.addEventListener("MSGestureTap",function(t){t.preventDefault();var n=e(this),i=n.index();if(!e(r.vars.asNavFor).data("flexslider").animating&&!n.hasClass("active")){r.direction=r.currentItem<i?"next":"prev";r.flexAnimate(i,r.vars.pauseOnAction,!1,!0,!0)}})})}}},controlNav:{setup:function(){r.manualControls?v.controlNav.setupManual():v.controlNav.setupPaging()},setupPaging:function(){var t=r.vars.controlNav==="thumbnails"?"control-thumbs":"control-paging",n=1,s,o;r.controlNavScaffold=e('<ol class="'+i+"control-nav "+i+t+'"></ol>');if(r.pagingCount>1)for(var f=0;f<r.pagingCount;f++){o=r.slides.eq(f);s=r.vars.controlNav==="thumbnails"?'<img src="'+o.attr("data-thumb")+'"/>':"<a>"+n+"</a>";if("thumbnails"===r.vars.controlNav&&!0===r.vars.thumbCaptions){var l=o.attr("data-thumbcaption");""!=l&&undefined!=l&&(s+='<span class="'+i+'caption">'+l+"</span>")}r.controlNavScaffold.append("<li>"+s+"</li>");n++}r.controlsContainer?e(r.controlsContainer).append(r.controlNavScaffold):r.append(r.controlNavScaffold);v.controlNav.set();v.controlNav.active();r.controlNavScaffold.delegate("a, img",u,function(t){t.preventDefault();if(a===""||a===t.type){var n=e(this),s=r.controlNav.index(n);if(!n.hasClass(i+"active")){r.direction=s>r.currentSlide?"next":"prev";r.flexAnimate(s,r.vars.pauseOnAction)}}a===""&&(a=t.type);v.setToClearWatchedEvent()})},setupManual:function(){r.controlNav=r.manualControls;v.controlNav.active();r.controlNav.bind(u,function(t){t.preventDefault();if(a===""||a===t.type){var n=e(this),s=r.controlNav.index(n);if(!n.hasClass(i+"active")){s>r.currentSlide?r.direction="next":r.direction="prev";r.flexAnimate(s,r.vars.pauseOnAction)}}a===""&&(a=t.type);v.setToClearWatchedEvent()})},set:function(){var t=r.vars.controlNav==="thumbnails"?"img":"a";r.controlNav=e("."+i+"control-nav li "+t,r.controlsContainer?r.controlsContainer:r)},active:function(){r.controlNav.removeClass(i+"active").eq(r.animatingTo).addClass(i+"active")},update:function(t,n){r.pagingCount>1&&t==="add"?r.controlNavScaffold.append(e("<li><a>"+r.count+"</a></li>")):r.pagingCount===1?r.controlNavScaffold.find("li").remove():r.controlNav.eq(n).closest("li").remove();v.controlNav.set();r.pagingCount>1&&r.pagingCount!==r.controlNav.length?r.update(n,t):v.controlNav.active()}},directionNav:{setup:function(){var t=e('<ul class="'+i+'direction-nav"><li><a class="'+i+'prev" href="#">'+r.vars.prevText+'</a></li><li><a class="'+i+'next" href="#">'+r.vars.nextText+"</a></li></ul>");if(r.controlsContainer){e(r.controlsContainer).append(t);r.directionNav=e("."+i+"direction-nav li a",r.controlsContainer)}else{r.append(t);r.directionNav=e("."+i+"direction-nav li a",r)}v.directionNav.update();r.directionNav.bind(u,function(t){t.preventDefault();var n;if(a===""||a===t.type){n=e(this).hasClass(i+"next")?r.getTarget("next"):r.getTarget("prev");r.flexAnimate(n,r.vars.pauseOnAction)}a===""&&(a=t.type);v.setToClearWatchedEvent()})},update:function(){var e=i+"disabled";r.pagingCount===1?r.directionNav.addClass(e).attr("tabindex","-1"):r.vars.animationLoop?r.directionNav.removeClass(e).removeAttr("tabindex"):r.animatingTo===0?r.directionNav.removeClass(e).filter("."+i+"prev").addClass(e).attr("tabindex","-1"):r.animatingTo===r.last?r.directionNav.removeClass(e).filter("."+i+"next").addClass(e).attr("tabindex","-1"):r.directionNav.removeClass(e).removeAttr("tabindex")}},pausePlay:{setup:function(){var t=e('<div class="'+i+'pauseplay"><a></a></div>');if(r.controlsContainer){r.controlsContainer.append(t);r.pausePlay=e("."+i+"pauseplay a",r.controlsContainer)}else{r.append(t);r.pausePlay=e("."+i+"pauseplay a",r)}v.pausePlay.update(r.vars.slideshow?i+"pause":i+"play");r.pausePlay.bind(u,function(t){t.preventDefault();if(a===""||a===t.type)if(e(this).hasClass(i+"pause")){r.manualPause=!0;r.manualPlay=!1;r.pause()}else{r.manualPause=!1;r.manualPlay=!0;r.play()}a===""&&(a=t.type);v.setToClearWatchedEvent()})},update:function(e){e==="play"?r.pausePlay.removeClass(i+"pause").addClass(i+"play").html(r.vars.playText):r.pausePlay.removeClass(i+"play").addClass(i+"pause").html(r.vars.pauseText)}},touch:function(){var e,n,i,o,u,a,f=!1,d=0,v=0,m=0;if(!s){t.addEventListener("touchstart",g,!1);function g(s){if(r.animating)s.preventDefault();else if(window.navigator.msPointerEnabled||s.touches.length===1){r.pause();o=l?r.h:r.w;a=Number(new Date);d=s.touches[0].pageX;v=s.touches[0].pageY;i=h&&c&&r.animatingTo===r.last?0:h&&c?r.limit-(r.itemW+r.vars.itemMargin)*r.move*r.animatingTo:h&&r.currentSlide===r.last?r.limit:h?(r.itemW+r.vars.itemMargin)*r.move*r.currentSlide:c?(r.last-r.currentSlide+r.cloneOffset)*o:(r.currentSlide+r.cloneOffset)*o;e=l?v:d;n=l?d:v;t.addEventListener("touchmove",y,!1);t.addEventListener("touchend",b,!1)}}function y(t){d=t.touches[0].pageX;v=t.touches[0].pageY;u=l?e-v:e-d;f=l?Math.abs(u)<Math.abs(d-n):Math.abs(u)<Math.abs(v-n);var s=500;if(!f||Number(new Date)-a>s){t.preventDefault();if(!p&&r.transitions){r.vars.animationLoop||(u/=r.currentSlide===0&&u<0||r.currentSlide===r.last&&u>0?Math.abs(u)/o+2:1);r.setProps(i+u,"setTouch")}}}function b(s){t.removeEventListener("touchmove",y,!1);if(r.animatingTo===r.currentSlide&&!f&&u!==null){var l=c?-u:u,h=l>0?r.getTarget("next"):r.getTarget("prev");r.canAdvance(h)&&(Number(new Date)-a<550&&Math.abs(l)>50||Math.abs(l)>o/2)?r.flexAnimate(h,r.vars.pauseOnAction):p||r.flexAnimate(r.currentSlide,r.vars.pauseOnAction,!0)}t.removeEventListener("touchend",b,!1);e=null;n=null;u=null;i=null}}else{t.style.msTouchAction="none";t._gesture=new MSGesture;t._gesture.target=t;t.addEventListener("MSPointerDown",w,!1);t._slider=r;t.addEventListener("MSGestureChange",E,!1);t.addEventListener("MSGestureEnd",S,!1);function w(e){e.stopPropagation();if(r.animating)e.preventDefault();else{r.pause();t._gesture.addPointer(e.pointerId);m=0;o=l?r.h:r.w;a=Number(new Date);i=h&&c&&r.animatingTo===r.last?0:h&&c?r.limit-(r.itemW+r.vars.itemMargin)*r.move*r.animatingTo:h&&r.currentSlide===r.last?r.limit:h?(r.itemW+r.vars.itemMargin)*r.move*r.currentSlide:c?(r.last-r.currentSlide+r.cloneOffset)*o:(r.currentSlide+r.cloneOffset)*o}}function E(e){e.stopPropagation();var n=e.target._slider;if(!n)return;var r=-e.translationX,s=-e.translationY;m+=l?s:r;u=m;f=l?Math.abs(m)<Math.abs(-r):Math.abs(m)<Math.abs(-s);if(e.detail===e.MSGESTURE_FLAG_INERTIA){setImmediate(function(){t._gesture.stop()});return}if(!f||Number(new Date)-a>500){e.preventDefault();if(!p&&n.transitions){n.vars.animationLoop||(u=m/(n.currentSlide===0&&m<0||n.currentSlide===n.last&&m>0?Math.abs(m)/o+2:1));n.setProps(i+u,"setTouch")}}}function S(t){t.stopPropagation();var r=t.target._slider;if(!r)return;if(r.animatingTo===r.currentSlide&&!f&&u!==null){var s=c?-u:u,l=s>0?r.getTarget("next"):r.getTarget("prev");r.canAdvance(l)&&(Number(new Date)-a<550&&Math.abs(s)>50||Math.abs(s)>o/2)?r.flexAnimate(l,r.vars.pauseOnAction):p||r.flexAnimate(r.currentSlide,r.vars.pauseOnAction,!0)}e=null;n=null;u=null;i=null;m=0}}},resize:function(){if(!r.animating&&r.is(":visible")){h||r.doMath();if(p)v.smoothHeight();else if(h){r.slides.width(r.computedW);r.update(r.pagingCount);r.setProps()}else if(l){r.viewport.height(r.h);r.setProps(r.h,"setTotal")}else{r.vars.smoothHeight&&v.smoothHeight();r.newSlides.width(r.computedW);r.setProps(r.computedW,"setTotal")}}},smoothHeight:function(e){if(!l||p){var t=p?r:r.viewport;e?t.animate({height:r.slides.eq(r.animatingTo).height()},e):t.height(r.slides.eq(r.animatingTo).height())}},sync:function(t){var n=e(r.vars.sync).data("flexslider"),i=r.animatingTo;switch(t){case"animate":n.flexAnimate(i,r.vars.pauseOnAction,!1,!0);break;case"play":!n.playing&&!n.asNav&&n.play();break;case"pause":n.pause()}},pauseInvisible:{visProp:null,init:function(){var e=["webkit","moz","ms","o"];if("hidden"in document)return"hidden";for(var t=0;t<e.length;t++)e[t]+"Hidden"in document&&(v.pauseInvisible.visProp=e[t]+"Hidden");if(v.pauseInvisible.visProp){var n=v.pauseInvisible.visProp.replace(/[H|h]idden/,"")+"visibilitychange";document.addEventListener(n,function(){v.pauseInvisible.isHidden()?r.startTimeout?clearTimeout(r.startTimeout):r.pause():r.started?r.play():r.vars.initDelay>0?setTimeout(r.play,r.vars.initDelay):r.play()})}},isHidden:function(){return document[v.pauseInvisible.visProp]||!1}},setToClearWatchedEvent:function(){clearTimeout(f);f=setTimeout(function(){a=""},3e3)}};r.flexAnimate=function(t,n,s,u,a){!r.vars.animationLoop&&t!==r.currentSlide&&(r.direction=t>r.currentSlide?"next":"prev");d&&r.pagingCount===1&&(r.direction=r.currentItem<t?"next":"prev");if(!r.animating&&(r.canAdvance(t,a)||s)&&r.is(":visible")){if(d&&u){var f=e(r.vars.asNavFor).data("flexslider");r.atEnd=t===0||t===r.count-1;f.flexAnimate(t,!0,!1,!0,a);r.direction=r.currentItem<t?"next":"prev";f.direction=r.direction;if(Math.ceil((t+1)/r.visible)-1===r.currentSlide||t===0){r.currentItem=t;r.slides.removeClass(i+"active-slide").eq(t).addClass(i+"active-slide");return!1}r.currentItem=t;r.slides.removeClass(i+"active-slide").eq(t).addClass(i+"active-slide");t=Math.floor(t/r.visible)}r.animating=!0;r.animatingTo=t;n&&r.pause();r.vars.before(r);r.syncExists&&!a&&v.sync("animate");r.vars.controlNav&&v.controlNav.active();h||r.slides.removeClass(i+"active-slide").eq(t).addClass(i+"active-slide");r.atEnd=t===0||t===r.last;r.vars.directionNav&&v.directionNav.update();if(t===r.last){r.vars.end(r);r.vars.animationLoop||r.pause()}if(!p){var m=l?r.slides.filter(":first").height():r.computedW,g,y,b;if(h){g=r.vars.itemMargin;b=(r.itemW+g)*r.move*r.animatingTo;y=b>r.limit&&r.visible!==1?r.limit:b}else r.currentSlide===0&&t===r.count-1&&r.vars.animationLoop&&r.direction!=="next"?y=c?(r.count+r.cloneOffset)*m:0:r.currentSlide===r.last&&t===0&&r.vars.animationLoop&&r.direction!=="prev"?y=c?0:(r.count+1)*m:y=c?(r.count-1-t+r.cloneOffset)*m:(t+r.cloneOffset)*m;r.setProps(y,"",r.vars.animationSpeed);if(r.transitions){if(!r.vars.animationLoop||!r.atEnd){r.animating=!1;r.currentSlide=r.animatingTo}r.container.unbind("webkitTransitionEnd transitionend");r.container.bind("webkitTransitionEnd transitionend",function(){r.wrapup(m)})}else r.container.animate(r.args,r.vars.animationSpeed,r.vars.easing,function(){r.wrapup(m)})}else if(!o){r.slides.eq(r.currentSlide).css({zIndex:1}).animate({opacity:0},r.vars.animationSpeed,r.vars.easing);r.slides.eq(t).css({zIndex:2}).animate({opacity:1},r.vars.animationSpeed,r.vars.easing,r.wrapup)}else{r.slides.eq(r.currentSlide).css({opacity:0,zIndex:1});r.slides.eq(t).css({opacity:1,zIndex:2});r.wrapup(m)}r.vars.smoothHeight&&v.smoothHeight(r.vars.animationSpeed)}};r.wrapup=function(e){!p&&!h&&(r.currentSlide===0&&r.animatingTo===r.last&&r.vars.animationLoop?r.setProps(e,"jumpEnd"):r.currentSlide===r.last&&r.animatingTo===0&&r.vars.animationLoop&&r.setProps(e,"jumpStart"));r.animating=!1;r.currentSlide=r.animatingTo;r.vars.after(r)};r.animateSlides=function(){!r.animating&&m&&r.flexAnimate(r.getTarget("next"))};r.pause=function(){clearInterval(r.animatedSlides);r.animatedSlides=null;r.playing=!1;r.vars.pausePlay&&v.pausePlay.update("play");r.syncExists&&v.sync("pause")};r.play=function(){r.playing&&clearInterval(r.animatedSlides);r.animatedSlides=r.animatedSlides||setInterval(r.animateSlides,r.vars.slideshowSpeed);r.started=r.playing=!0;r.vars.pausePlay&&v.pausePlay.update("pause");r.syncExists&&v.sync("play")};r.stop=function(){r.pause();r.stopped=!0};r.canAdvance=function(e,t){var n=d?r.pagingCount-1:r.last;return t?!0:d&&r.currentItem===r.count-1&&e===0&&r.direction==="prev"?!0:d&&r.currentItem===0&&e===r.pagingCount-1&&r.direction!=="next"?!1:e===r.currentSlide&&!d?!1:r.vars.animationLoop?!0:r.atEnd&&r.currentSlide===0&&e===n&&r.direction!=="next"?!1:r.atEnd&&r.currentSlide===n&&e===0&&r.direction==="next"?!1:!0};r.getTarget=function(e){r.direction=e;return e==="next"?r.currentSlide===r.last?0:r.currentSlide+1:r.currentSlide===0?r.last:r.currentSlide-1};r.setProps=function(e,t,n){var i=function(){var n=e?e:(r.itemW+r.vars.itemMargin)*r.move*r.animatingTo,i=function(){if(h)return t==="setTouch"?e:c&&r.animatingTo===r.last?0:c?r.limit-(r.itemW+r.vars.itemMargin)*r.move*r.animatingTo:r.animatingTo===r.last?r.limit:n;switch(t){case"setTotal":return c?(r.count-1-r.currentSlide+r.cloneOffset)*e:(r.currentSlide+r.cloneOffset)*e;case"setTouch":return c?e:e;case"jumpEnd":return c?e:r.count*e;case"jumpStart":return c?r.count*e:e;default:return e}}();return i*-1+"px"}();if(r.transitions){i=l?"translate3d(0,"+i+",0)":"translate3d("+i+",0,0)";n=n!==undefined?n/1e3+"s":"0s";r.container.css("-"+r.pfx+"-transition-duration",n)}r.args[r.prop]=i;(r.transitions||n===undefined)&&r.container.css(r.args)};r.setup=function(t){if(!p){var n,s;if(t==="init"){r.viewport=e('<div class="'+i+'viewport"></div>').css({overflow:"hidden",position:"relative"}).appendTo(r).append(r.container);r.cloneCount=0;r.cloneOffset=0;if(c){s=e.makeArray(r.slides).reverse();r.slides=e(s);r.container.empty().append(r.slides)}}if(r.vars.animationLoop&&!h){r.cloneCount=2;r.cloneOffset=1;t!=="init"&&r.container.find(".clone").remove();r.container.append(r.slides.first().clone().addClass("clone").attr("aria-hidden","true")).prepend(r.slides.last().clone().addClass("clone").attr("aria-hidden","true"))}r.newSlides=e(r.vars.selector,r);n=c?r.count-1-r.currentSlide+r.cloneOffset:r.currentSlide+r.cloneOffset;if(l&&!h){r.container.height((r.count+r.cloneCount)*200+"%").css("position","absolute").width("100%");setTimeout(function(){r.newSlides.css({display:"block"});r.doMath();r.viewport.height(r.h);r.setProps(n*r.h,"init")},t==="init"?100:0)}else{r.container.width((r.count+r.cloneCount)*200+"%");r.setProps(n*r.computedW,"init");setTimeout(function(){r.doMath();r.newSlides.css({width:r.computedW,"float":"left",display:"block"});r.vars.smoothHeight&&v.smoothHeight()},t==="init"?100:0)}}else{r.slides.css({width:"100%","float":"left",marginRight:"-100%",position:"relative"});t==="init"&&(o?r.slides.css({opacity:0,display:"block",webkitTransition:"opacity "+r.vars.animationSpeed/1e3+"s ease",zIndex:1}).eq(r.currentSlide).css({opacity:1,zIndex:2}):r.slides.css({opacity:0,display:"block",zIndex:1}).eq(r.currentSlide).css({zIndex:2}).animate({opacity:1},r.vars.animationSpeed,r.vars.easing));r.vars.smoothHeight&&v.smoothHeight()}h||r.slides.removeClass(i+"active-slide").eq(r.currentSlide).addClass(i+"active-slide")};r.doMath=function(){var e=r.slides.first(),t=r.vars.itemMargin,n=r.vars.minItems,i=r.vars.maxItems;r.w=r.viewport===undefined?r.width():r.viewport.width();r.h=e.height();r.boxPadding=e.outerWidth()-e.width();if(h){r.itemT=r.vars.itemWidth+t;r.minW=n?n*r.itemT:r.w;r.maxW=i?i*r.itemT-t:r.w;r.itemW=r.minW>r.w?(r.w-t*(n-1))/n:r.maxW<r.w?(r.w-t*(i-1))/i:r.vars.itemWidth>r.w?r.w:r.vars.itemWidth;r.visible=Math.floor(r.w/r.itemW);r.move=r.vars.move>0&&r.vars.move<r.visible?r.vars.move:r.visible;r.pagingCount=Math.ceil((r.count-r.visible)/r.move+1);r.last=r.pagingCount-1;r.limit=r.pagingCount===1?0:r.vars.itemWidth>r.w?r.itemW*(r.count-1)+t*(r.count-1):(r.itemW+t)*r.count-r.w-t}else{r.itemW=r.w;r.pagingCount=r.count;r.last=r.count-1}r.computedW=r.itemW-r.boxPadding};r.update=function(e,t){r.doMath();if(!h){e<r.currentSlide?r.currentSlide+=1:e<=r.currentSlide&&e!==0&&(r.currentSlide-=1);r.animatingTo=r.currentSlide}if(r.vars.controlNav&&!r.manualControls)if(t==="add"&&!h||r.pagingCount>r.controlNav.length)v.controlNav.update("add");else if(t==="remove"&&!h||r.pagingCount<r.controlNav.length){if(h&&r.currentSlide>r.last){r.currentSlide-=1;r.animatingTo-=1}v.controlNav.update("remove",r.last)}r.vars.directionNav&&v.directionNav.update()};r.addSlide=function(t,n){var i=e(t);r.count+=1;r.last=r.count-1;l&&c?n!==undefined?r.slides.eq(r.count-n).after(i):r.container.prepend(i):n!==undefined?r.slides.eq(n).before(i):r.container.append(i);r.update(n,"add");r.slides=e(r.vars.selector+":not(.clone)",r);r.setup();r.vars.added(r)};r.removeSlide=function(t){var n=isNaN(t)?r.slides.index(e(t)):t;r.count-=1;r.last=r.count-1;isNaN(t)?e(t,r.slides).remove():l&&c?r.slides.eq(r.last).remove():r.slides.eq(t).remove();r.doMath();r.update(n,"remove");r.slides=e(r.vars.selector+":not(.clone)",r);r.setup();r.vars.removed(r)};v.init()};e(window).blur(function(e){focused=!1}).focus(function(e){focused=!0});e.flexslider.defaults={namespace:"flex-",selector:".slides > li",animation:"fade",easing:"swing",direction:"horizontal",reverse:!1,animationLoop:!0,smoothHeight:!1,startAt:0,slideshow:!0,slideshowSpeed:7e3,animationSpeed:600,initDelay:0,randomize:!1,thumbCaptions:!1,pauseOnAction:!0,pauseOnHover:!1,pauseInvisible:!0,useCSS:!0,touch:!0,video:!1,controlNav:!0,directionNav:!0,prevText:"Previous",nextText:"Next",keyboard:!0,multipleKeyboard:!1,mousewheel:!1,pausePlay:!1,pauseText:"Pause",playText:"Play",controlsContainer:"",manualControls:"",sync:"",asNavFor:"",itemWidth:0,itemMargin:0,minItems:1,maxItems:0,move:0,allowOneSlide:!0,start:function(){},before:function(){},after:function(){},end:function(){},added:function(){},removed:function(){}};e.fn.flexslider=function(t){t===undefined&&(t={});if(typeof t=="object")return this.each(function(){var n=e(this),r=t.selector?t.selector:".slides > li",i=n.find(r);if(i.length===1&&t.allowOneSlide===!0||i.length===0){i.fadeIn(400);t.start&&t.start(n)}else n.data("flexslider")===undefined&&new e.flexslider(this,t)});var n=e(this).data("flexslider");switch(t){case"play":n.play();break;case"pause":n.pause();break;case"stop":n.stop();break;case"next":n.flexAnimate(n.getTarget("next"),!0);break;case"prev":case"previous":n.flexAnimate(n.getTarget("prev"),!0);break;default:typeof t=="number"&&n.flexAnimate(t,!0)}}})(jQuery);


// Accordion

;(function ($, window, undefined){
  'use strict';

  $.fn.foundationAccordion = function (options) {

    $('.accordion li', this).on('click.fndtn', function () {
    var p = $(this).parent(); //changed this
      var flyout = $(this).children('.content').first();
      $('.content', p).not(flyout).slideUp(400, function() {
      	$(this).parent('li').removeClass('active'); //changed this
      });
      flyout.slideDown(400).parent('li').addClass('active');
    });

  };

})( jQuery, this );

// Alerts

;(function ($, window, undefined) {
  'use strict';

  $.fn.foundationAlerts = function (options) {
    var settings = $.extend({
      callback: $.noop
    }, options);

    $(document).on("click", ".notification-box a.close", function (e) {
      e.preventDefault();
      $(this).closest(".notification-box").fadeOut(function () {
        $(this).remove();
        // Do something else after the alert closes
        settings.callback();
      });
    });

  };

})(jQuery, this);


// Tabs

;(function ($, window, undefined) {
  'use strict';

  $.fn.foundationTabs = function (options) {

    var settings = $.extend({
      callback: $.noop
    }, options);

    var activateTab = function ($tab) {
      var $activeTab = $tab.closest('dl').find('dd.active'),
          target = $tab.children('a').attr("href"),
          hasHash = /^#/.test(target),
          contentLocation = '';

      if (hasHash) {
        contentLocation = target + 'Tab';

        // Strip off the current url that IE adds
        contentLocation = contentLocation.replace(/^.+#/, '#');

        //Show Tab Content
        $(contentLocation).closest('.tabs-content').children('li').removeClass('active').hide();
        $(contentLocation).css('display', 'block').addClass('active');
      }

      //Make Tab Active
      $activeTab.removeClass('active');
      $tab.addClass('active');
    };

    $(document).on('click.fndtn', 'dl.tabs dd a', function (event){
      activateTab($(this).parent('dd'));
    });

    if (window.location.hash) {
      activateTab($('a[href="' + window.location.hash + '"]').parent('dd'));
      settings.callback();
    }

  };

})(jQuery, this);


// Images Loaded
(function(c,q){var m="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";c.fn.imagesLoaded=function(f){function n(){var b=c(j),a=c(h);d&&(h.length?d.reject(e,b,a):d.resolve(e));c.isFunction(f)&&f.call(g,e,b,a)}function p(b){k(b.target,"error"===b.type)}function k(b,a){b.src===m||-1!==c.inArray(b,l)||(l.push(b),a?h.push(b):j.push(b),c.data(b,"imagesLoaded",{isBroken:a,src:b.src}),r&&d.notifyWith(c(b),[a,e,c(j),c(h)]),e.length===l.length&&(setTimeout(n),e.unbind(".imagesLoaded",
p)))}var g=this,d=c.isFunction(c.Deferred)?c.Deferred():0,r=c.isFunction(d.notify),e=g.find("img").add(g.filter("img")),l=[],j=[],h=[];c.isPlainObject(f)&&c.each(f,function(b,a){if("callback"===b)f=a;else if(d)d[b](a)});e.length?e.bind("load.imagesLoaded error.imagesLoaded",p).each(function(b,a){var d=a.src,e=c.data(a,"imagesLoaded");if(e&&e.src===d)k(a,e.isBroken);else if(a.complete&&a.naturalWidth!==q)k(a,0===a.naturalWidth||0===a.naturalHeight);else if(a.readyState||a.complete)a.src=m,a.src=d}):
n();return d?d.promise(g):g}})(jQuery);



/*
 *	jQuery OwlCarousel v1.29
 *
 *	Copyright (c) 2013 Bartosz Wojciechowski
 *	http://www.owlgraphic.com/owlcarousel
 *
 *	Licensed under MIT
 *
 */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('7(F 3t.3s!=="9"){3t.3s=9(e){9 t(){}t.4X=e;q 5u t}}(9(e,t,n,r){b i={1B:9(t,n){b r=c;r.$k=e(n);r.6=e.3R({},e.3n.2t.6,r.$k.w(),t);r.2w=t;r.4l()},4l:9(){b t=c;7(F t.6.3u==="9"){t.6.3u.S(c,[t.$k])}7(F t.6.2Q==="3r"){b n=t.6.2Q;9 r(e){7(F t.6.3a==="9"){t.6.3a.S(c,[e])}p{b n="";1E(b r 3l e["h"]){n+=e["h"][r]["1W"]}t.$k.2s(n)}t.2J()}e.5s(n,r)}p{t.2J()}},2J:9(e){b t=c;t.$k.A({29:0});t.2a=t.6.v;t.4I();t.5e=0;t.1L;t.1T()},1T:9(){b e=c;7(e.$k.1N().14===0){q d}e.1I();e.4G();e.$V=e.$k.1N();e.K=e.$V.14;e.4F();e.$M=e.$k.11(".h-1W");e.$J=e.$k.11(".h-1n");e.3k="W";e.1i=0;e.m=0;e.4E();e.4C()},4C:9(){b e=c;e.2K();e.2N();e.4A();e.2W();e.4x();e.4w();e.2p();e.4u();7(e.6.2l!==d){e.4r(e.6.2l)}7(e.6.N===j){e.6.N=5t}e.1a();e.$k.11(".h-1n").A("4q","4p");7(!e.$k.27(":2H")){e.2I()}p{e.$k.A("29",1)}e.5v=d;e.28();7(F e.6.2L==="9"){e.6.2L.S(c,[e.$k])}},28:9(){b e=c;7(e.6.1Y===j){e.1Y()}7(e.6.1s===j){e.1s()}e.4j();7(F e.6.2Y==="9"){e.6.2Y.S(c,[e.$k])}},30:9(){b e=c;7(F e.6.31==="9"){e.6.31.S(c,[e.$k])}e.2I();e.2K();e.2N();e.4i();e.2W();e.28();7(F e.6.33==="9"){e.6.33.S(c,[e.$k])}},4g:9(e){b t=c;15(9(){t.30()},0)},2I:9(){b e=c;7(e.$k.27(":2H")===d){e.$k.A({29:0});16(e.1z);16(e.1L)}p{q d}e.1L=4f(9(){7(e.$k.27(":2H")){e.4g();e.$k.4c({29:1},3j);16(e.1L)}},5n)},4F:9(){b e=c;e.$V.5o(\'<L I="h-1n">\').4a(\'<L I="h-1W"></L>\');e.$k.11(".h-1n").4a(\'<L I="h-1n-49">\');e.1M=e.$k.11(".h-1n-49");e.$k.A("4q","4p")},1I:9(){b e=c;b t=e.$k.1J(e.6.1I);b n=e.$k.1J(e.6.2h);e.$k.w("h-47",e.$k.2k("2n")).w("h-46",e.$k.2k("I"));7(!t){e.$k.H(e.6.1I)}7(!n){e.$k.H(e.6.2h)}},2K:9(){b t=c;7(t.6.2F===d){q d}7(t.6.45===j){t.6.v=t.2a=1;t.6.1w=d;t.6.1K=d;t.6.1X=d;t.6.1Q=d;t.6.1Z=d;q d}b n=e(t.6.43).1q();7(n>(t.6.1w[0]||t.2a)){t.6.v=t.2a}7(n<=t.6.1w[0]&&t.6.1w!==d){t.6.v=t.6.1w[1]}7(n<=t.6.1K[0]&&t.6.1K!==d){t.6.v=t.6.1K[1]}7(n<=t.6.1X[0]&&t.6.1X!==d){t.6.v=t.6.1X[1]}7(n<=t.6.1Q[0]&&t.6.1Q!==d){t.6.v=t.6.1Q[1]}7(n<=t.6.1Z[0]&&t.6.1Z!==d){t.6.v=t.6.1Z[1]}7(t.6.v>t.K&&t.6.42===j){t.6.v=t.K}},4x:9(){b n=c,r;7(n.6.2F!==j){q d}b i=e(t).1q();n.2S=9(){7(e(t).1q()!==i){7(n.6.N!==d){16(n.1z)}58(r);r=15(9(){i=e(t).1q();n.30()},n.6.41)}};e(t).40(n.2S)},4i:9(){b e=c;7(e.D.1p===j){7(e.G[e.m]>e.1O){e.1o(e.G[e.m])}p{e.1o(0);e.m=0}}p{7(e.G[e.m]>e.1O){e.1d(e.G[e.m])}p{e.1d(0);e.m=0}}7(e.6.N!==d){e.35()}},3Z:9(){b t=c;b n=0;b r=t.K-t.6.v;t.$M.2v(9(i){b s=e(c);s.A({1q:t.Q}).w("h-1W",3c(i));7(i%t.6.v===0||i===r){7(!(i>r)){n+=1}}s.w("h-2r",n)})},3Y:9(){b e=c;b t=0;b t=e.$M.14*e.Q;e.$J.A({1q:t*2,Y:0});e.3Z()},2N:9(){b e=c;e.3X();e.3Y();e.3W();e.3m()},3X:9(){b e=c;e.Q=24.5k(e.$k.1q()/e.6.v)},3m:9(){b e=c;b t=(e.K*e.Q-e.6.v*e.Q)*-1;7(e.6.v>e.K){e.E=0;t=0;e.1O=0}p{e.E=e.K-e.6.v;e.1O=t}q t},3V:9(){q 0},3W:9(){b e=c;e.G=[0];b t=0;1E(b n=0;n<e.K;n++){t+=e.Q;e.G.3U(-t)}},4A:9(){b t=c;7(t.6.2f===j||t.6.1r===j){t.C=e(\'<L I="h-5A"/>\').5C("5F",!t.D.12).56(t.$k)}7(t.6.1r===j){t.3T()}7(t.6.2f===j){t.3S()}},3S:9(){b t=c;b n=e(\'<L I="h-5f"/>\');t.C.1h(n);t.1x=e("<L/>",{"I":"h-1f",2s:t.6.2A[0]||""});t.1y=e("<L/>",{"I":"h-W",2s:t.6.2A[1]||""});n.1h(t.1x).1h(t.1y);n.z("2D.C 1S.C",\'L[I^="h"]\',9(e){e.1e()});n.z("2d.C 2c.C",\'L[I^="h"]\',9(n){n.1e();7(e(c).1J("h-W")){t.W()}p{t.1f()}})},3T:9(){b t=c;t.1g=e(\'<L I="h-1r"/>\');t.C.1h(t.1g);t.1g.z("2d.C 2c.C",".h-1k",9(n){n.1e();7(3c(e(c).w("h-1k"))!==t.m){t.1j(3c(e(c).w("h-1k")),j)}})},3P:9(){b t=c;7(t.6.1r===d){q d}t.1g.2s("");b n=0;b r=t.K-t.K%t.6.v;1E(b i=0;i<t.K;i++){7(i%t.6.v===0){n+=1;7(r===i){b s=t.K-t.6.v}b o=e("<L/>",{"I":"h-1k"});b u=e("<3O></3O>",{5m:t.6.2P===j?n:"","I":t.6.2P===j?"h-5X":""});o.1h(u);o.w("h-1k",r===i?s:i);o.w("h-2r",n);t.1g.1h(o)}}t.2R()},2R:9(){b t=c;7(t.6.1r===d){q d}t.1g.11(".h-1k").2v(9(n,r){7(e(c).w("h-2r")===e(t.$M[t.m]).w("h-2r")){t.1g.11(".h-1k").R("25");e(c).H("25")}})},2U:9(){b e=c;7(e.6.2f===d){q d}7(e.6.2j===d){7(e.m===0&&e.E===0){e.1x.H("18");e.1y.H("18")}p 7(e.m===0&&e.E!==0){e.1x.H("18");e.1y.R("18")}p 7(e.m===e.E){e.1x.R("18");e.1y.H("18")}p 7(e.m!==0&&e.m!==e.E){e.1x.R("18");e.1y.R("18")}}},2W:9(){b e=c;e.3P();e.2U();7(e.C){7(e.6.v>=e.K){e.C.3N()}p{e.C.3M()}}},4Z:9(){b e=c;7(e.C){e.C.2Z()}},W:9(e){b t=c;7(t.23){q d}t.22=t.m;t.m+=t.6.21===j?t.6.v:1;7(t.m>t.E+(t.6.21==j?t.6.v-1:0)){7(t.6.2j===j){t.m=0;e="2b"}p{t.m=t.E;q d}}t.1j(t.m,e)},1f:9(e){b t=c;7(t.23){q d}t.22=t.m;7(t.6.21===j&&t.m>0&&t.m<t.6.v){t.m=0}p{t.m-=t.6.21===j?t.6.v:1}7(t.m<0){7(t.6.2j===j){t.m=t.E;e="2b"}p{t.m=0;q d}}t.1j(t.m,e)},1j:9(e,t,n){b r=c;7(r.23){q d}r.34();7(F r.6.1V==="9"){r.6.1V.S(c,[r.$k])}7(e>=r.E){e=r.E}p 7(e<=0){e=0}r.m=r.h.m=e;7(r.6.2l!==d&&n!=="3K"&&r.6.v===1&&r.D.1p===j){r.1A(0);7(r.D.1p===j){r.1o(r.G[e])}p{r.1d(r.G[e],1)}r.3J();r.2e();q d}b i=r.G[e];7(r.D.1p===j){r.1P=d;7(t===j){r.1A("1v");15(9(){r.1P=j},r.6.1v)}p 7(t==="2b"){r.1A(r.6.2g);15(9(){r.1P=j},r.6.2g)}p{r.1A("1l");15(9(){r.1P=j},r.6.1l)}r.1o(i)}p{7(t===j){r.1d(i,r.6.1v)}p 7(t==="2b"){r.1d(i,r.6.2g)}p{r.1d(i,r.6.1l)}}r.2e()},34:9(){b e=c;e.1i=e.h.1i=e.22===r?e.m:e.22;e.22=r},3e:9(e){b t=c;t.34();7(F t.6.1V==="9"){t.6.1V.S(c,[t.$k])}7(e>=t.E||e===-1){e=t.E}p 7(e<=0){e=0}t.1A(0);7(t.D.1p===j){t.1o(t.G[e])}p{t.1d(t.G[e],1)}t.m=t.h.m=e;t.2e()},2e:9(){b e=c;e.2R();e.2U();e.28();7(F e.6.3f==="9"){e.6.3f.S(c,[e.$k])}7(e.6.N!==d){e.35()}},U:9(){b e=c;e.3h="U";16(e.1z)},35:9(){b e=c;7(e.3h!=="U"){e.1a()}},1a:9(){b e=c;e.3h="1a";7(e.6.N===d){q d}16(e.1z);e.1z=4f(9(){e.W(j)},e.6.N)},1A:9(e){b t=c;7(e==="1l"){t.$J.A(t.2y(t.6.1l))}p 7(e==="1v"){t.$J.A(t.2y(t.6.1v))}p 7(F e!=="3r"){t.$J.A(t.2y(e))}},2y:9(e){b t=c;q{"-1H-1b":"2m "+e+"1u 2o","-1F-1b":"2m "+e+"1u 2o","-o-1b":"2m "+e+"1u 2o",1b:"2m "+e+"1u 2o"}},3F:9(){q{"-1H-1b":"","-1F-1b":"","-o-1b":"",1b:""}},3E:9(e){q{"-1H-O":"1m("+e+"T, B, B)","-1F-O":"1m("+e+"T, B, B)","-o-O":"1m("+e+"T, B, B)","-1u-O":"1m("+e+"T, B, B)",O:"1m("+e+"T, B,B)"}},1o:9(e){b t=c;t.$J.A(t.3E(e))},3D:9(e){b t=c;t.$J.A({Y:e})},1d:9(e,t){b n=c;n.2u=d;n.$J.U(j,j).4c({Y:e},{4W:t||n.6.1l,48:9(){n.2u=j}})},4I:9(){b e=c;b r="1m(B, B, B)",i=n.4Y("L");i.2n.3A="  -1F-O:"+r+"; -1u-O:"+r+"; -o-O:"+r+"; -1H-O:"+r+"; O:"+r;b s=/1m\\(B, B, B\\)/g,o=i.2n.3A.55(s),u=o!==1c&&o.14===1;b a="59"3l t||5b.5c;e.D={1p:u,12:a}},4w:9(){b e=c;7(e.6.1G!==d||e.6.1U!==d){e.3B();e.3C()}},4G:9(){b e=c;b t=["s","e","x"];e.13={};7(e.6.1G===j&&e.6.1U===j){t=["2D.h 1S.h","3o.h 3G.h","2d.h 3H.h 2c.h"]}p 7(e.6.1G===d&&e.6.1U===j){t=["2D.h","3o.h","2d.h 3H.h"]}p 7(e.6.1G===j&&e.6.1U===d){t=["1S.h","3G.h","2c.h"]}e.13["3I"]=t[0];e.13["38"]=t[1];e.13["36"]=t[2]},3C:9(){b t=c;t.$k.z("5B.h",9(e){e.1e()});t.$k.z("1S.3L",9(t){q e(t.19).27("5G, 4J, 4K, 4L")})},3B:9(){9 o(e){7(e.2X){q{x:e.2X[0].2O,y:e.2X[0].3Q}}p{7(e.2O!==r){q{x:e.2O,y:e.3Q}}p{q{x:e.50,y:e.53}}}}9 u(t){7(t==="z"){e(n).z(i.13["38"],f);e(n).z(i.13["36"],l)}p 7(t==="P"){e(n).P(i.13["38"]);e(n).P(i.13["36"])}}9 a(n){b n=n.3w||n||t.3v;7(n.5a===3){q d}7(i.2u===d&&!i.6.3q){q d}7(i.1P===d&&!i.6.3q){q d}7(i.6.N!==d){16(i.1z)}7(i.D.12!==j&&!i.$J.1J("3p")){i.$J.H("3p")}i.Z=0;i.X=0;e(c).A(i.3F());b r=e(c).2q();s.37=r.Y;s.2V=o(n).x-r.Y;s.2T=o(n).y-r.5q;u("z");s.2x=d;s.2M=n.19||n.44}9 f(r){b r=r.3w||r||t.3v;i.Z=o(r).x-s.2V;i.2G=o(r).y-s.2T;i.X=i.Z-s.37;7(F i.6.2C==="9"&&s.3y!==j&&i.X!==0){s.3y=j;i.6.2C.S(c)}7(i.X>8||i.X<-8&&i.D.12===j){r.1e?r.1e():r.5E=d;s.2x=j}7((i.2G>10||i.2G<-10)&&s.2x===d){e(n).P("3o.h")}b u=9(){q i.X/5};b a=9(){q i.1O+i.X/5};i.Z=24.3m(24.3V(i.Z,u()),a());7(i.D.1p===j){i.1o(i.Z)}p{i.3D(i.Z)}}9 l(n){b n=n.3w||n||t.3v;n.19=n.19||n.44;s.3y=d;7(i.D.12!==j){i.$J.R("3p")}7(i.X!==0){b r=i.4b();i.1j(r,d,"3K");7(s.2M===n.19&&i.D.12!==j){e(n.19).z("3i.4d",9(t){t.4M();t.4N();t.1e();e(n.19).P("3i.4d")});b o=e.4O(n.19,"4P")["3i"];b a=o.4Q();o.4R(0,0,a)}}u("P")}b i=c;b s={2V:0,2T:0,4S:0,37:0,2q:1c,4T:1c,4U:1c,2x:1c,4V:1c,2M:1c};i.2u=j;i.$k.z(i.13["3I"],".h-1n",a)},4b:9(){b e=c,t;b t=e.4e();7(t>e.E){e.m=e.E;t=e.E}p 7(e.Z>=0){t=0;e.m=0}q t},4e:9(){b t=c;b n=t.G;b r=t.Z;b i=1c;e.2v(n,9(e,s){7(r-t.Q/20>n[e+1]&&r-t.Q/20<s&&t.39()==="Y"){i=s;t.m=e}p 7(r+t.Q/20<s&&r+t.Q/20>n[e+1]&&t.39()==="4h"){i=n[e+1];t.m=e+1}});q t.m},39:9(){b e=c,t;7(e.X<0){t="4h";e.3k="W"}p{t="Y";e.3k="1f"}q t},4E:9(){b e=c;e.$k.z("h.W",9(){e.W()});e.$k.z("h.1f",9(){e.1f()});e.$k.z("h.1a",9(t,n){e.6.N=n;e.1a();e.32="1a"});e.$k.z("h.U",9(){e.U();e.32="U"});e.$k.z("h.1j",9(t,n){e.1j(n)});e.$k.z("h.3e",9(t,n){e.3e(n)})},2p:9(){b e=c;7(e.6.2p===j&&e.D.12!==j&&e.6.N!==d){e.$k.z("51",9(){e.U()});e.$k.z("52",9(){7(e.32!=="U"){e.1a()}})}},1Y:9(){b t=c;7(t.6.1Y===d){q d}1E(b n=0;n<t.K;n++){b i=e(t.$M[n]);7(i.w("h-17")==="17"){4k}b s=i.w("h-1W"),o=i.11(".54"),u;7(F o.w("26")!=="3r"){i.w("h-17","17");4k}7(i.w("h-17")===r){o.3N();i.H("4m").w("h-17","57")}7(t.6.4n===j){u=s>=t.m}p{u=j}7(u&&s<t.m+t.6.v&&o.14){t.4o(i,o)}}},4o:9(e,t){9 i(){r+=1;7(n.2B(t.2z(0))){s()}p 7(r<=2i){15(i,2i)}p{s()}}9 s(){e.w("h-17","17").R("4m");t.5d("w-26");n.6.4s==="4t"?t.5g(5h):t.3M()}b n=c,r=0;t[0].26=t.w("26");i()},1s:9(){9 s(){i+=1;7(t.2B(n.2z(0))){o()}p 7(i<=2i){15(s,2i)}p{t.1M.A("3g","")}}9 o(){b n=e(t.$M[t.m]).3g();t.1M.A("3g",n+"T");7(!t.1M.1J("1s")){15(9(){t.1M.H("1s")},0)}}b t=c;b n=e(t.$M[t.m]).11("5j");7(n.2z(0)!==r){b i=0;s()}p{o()}},2B:9(e){7(!e.48){q d}7(F e.4v!=="5l"&&e.4v==0){q d}q j},4j:9(){b t=c;7(t.6.3b===j){t.$M.R("25")}t.1t=[];1E(b n=t.m;n<t.m+t.6.v;n++){t.1t.3U(n);7(t.6.3b===j){e(t.$M[n]).H("25")}}t.h.1t=t.1t},4r:9(e){b t=c;t.4y="h-"+e+"-5p";t.4z="h-"+e+"-3l"},3J:9(){9 u(e,t){q{2q:"5r",Y:e+"T"}}b e=c;e.23=j;b t=e.4y,n=e.4z,r=e.$M.1C(e.m),i=e.$M.1C(e.1i),s=24.4B(e.G[e.m])+e.G[e.1i],o=24.4B(e.G[e.m])+e.Q/2;e.$J.H("h-1D").A({"-1H-O-1D":o+"T","-1F-4D-1D":o+"T","4D-1D":o+"T"});b a="5w 5x 5y 5z";i.A(u(s,10)).H(t).z(a,9(){e.2E=j;i.P(a);e.3x(i,t)});r.H(n).z(a,9(){e.3d=j;r.P(a);e.3x(r,n)})},3x:9(e,t){b n=c;e.A({2q:"",Y:""}).R(t);7(n.2E&&n.3d){n.$J.R("h-1D");n.2E=d;n.3d=d;n.23=d}},4u:9(){b e=c;e.h={2w:e.2w,5D:e.$k,V:e.$V,M:e.$M,m:e.m,1i:e.1i,1t:e.1t,12:e.D.12,D:e.D}},4H:9(){b r=c;r.$k.P(".h h 1S.3L");e(n).P(".h h");e(t).P("40",r.2S)},1R:9(){b e=c;7(e.$k.1N().14!==0){e.$J.3z();e.$V.3z().3z();7(e.C){e.C.2Z()}}e.4H();e.$k.2k("2n",e.$k.w("h-47")||"").2k("I",e.$k.w("h-46"))},5H:9(){b e=c;e.U();16(e.1L);e.1R();e.$k.5I()},5J:9(t){b n=c;b r=e.3R({},n.2w,t);n.1R();n.1B(r,n.$k)},5K:9(e,t){b n=c,i;7(!e){q d}7(n.$k.1N().14===0){n.$k.1h(e);n.1T();q d}n.1R();7(t===r||t===-1){i=-1}p{i=t}7(i>=n.$V.14||i===-1){n.$V.1C(-1).5L(e)}p{n.$V.1C(i).5M(e)}n.1T()},5N:9(e){b t=c,n;7(t.$k.1N().14===0){q d}7(e===r||e===-1){n=-1}p{n=e}t.1R();t.$V.1C(n).2Z();t.1T()}};e.3n.2t=9(t){q c.2v(9(){7(e(c).w("h-1B")===j){q d}e(c).w("h-1B",j);b n=3t.3s(i);n.1B(t,c);e.w(c,"2t",n)})};e.3n.2t.6={v:5,1w:[5O,4],1K:[5P,3],1X:[5Q,2],1Q:d,1Z:[5R,1],45:d,42:d,1l:3j,1v:5S,2g:5T,N:d,2p:d,2f:d,2A:["1f","W"],2j:j,21:d,1r:j,2P:d,2F:j,41:3j,43:t,1I:"h-5U",2h:"h-2h",1Y:d,4n:j,4s:"4t",1s:d,2Q:d,3a:d,3q:j,1G:j,1U:j,3b:d,2l:d,31:d,33:d,3u:d,2L:d,1V:d,3f:d,2Y:d,2C:d}})(5V,5W,5i)',62,370,'||||||options|if||function||var|this|false||||owl||true|elem||currentItem|||else|return|||||items|data|||on|css|0px|owlControls|browser|maximumItem|typeof|positionsInArray|addClass|class|owlWrapper|itemsAmount|div|owlItems|autoPlay|transform|off|itemWidth|removeClass|apply|px|stop|userItems|next|newRelativeX|left|newPosX||find|isTouch|ev_types|length|setTimeout|clearInterval|loaded|disabled|target|play|transition|null|css2slide|preventDefault|prev|paginationWrapper|append|prevItem|goTo|page|slideSpeed|translate3d|wrapper|transition3d|support3d|width|pagination|autoHeight|visibleItems|ms|paginationSpeed|itemsDesktop|buttonPrev|buttonNext|autoPlayInterval|swapSpeed|init|eq|origin|for|moz|mouseDrag|webkit|baseClass|hasClass|itemsDesktopSmall|checkVisible|wrapperOuter|children|maximumPixels|isCss3Finish|itemsTabletSmall|unWrap|mousedown|setVars|touchDrag|beforeMove|item|itemsTablet|lazyLoad|itemsMobile||scrollPerPage|storePrevItem|isTransition|Math|active|src|is|eachMoveUpdate|opacity|orignalItems|rewind|mouseup|touchend|afterGo|navigation|rewindSpeed|theme|100|rewindNav|attr|transitionStyle|all|style|ease|stopOnHover|position|roundPages|html|owlCarousel|isCssFinish|each|userOptions|sliding|addCssSpeed|get|navigationText|completeImg|startDragging|touchstart|endPrev|responsive|newPosY|visible|watchVisibility|logIn|updateItems|afterInit|targetElement|calculateAll|pageX|paginationNumbers|jsonPath|checkPagination|resizer|offsetY|checkNavigation|offsetX|updateControls|touches|afterAction|remove|updateVars|beforeUpdate|hoverStatus|afterUpdate|getPrevItem|checkAp|end|relativePos|move|moveDirection|jsonSuccess|addClassActive|Number|endCurrent|jumpTo|afterMove|height|apStatus|click|200|playDirection|in|max|fn|touchmove|grabbing|dragBeforeAnimFinish|string|create|Object|beforeInit|event|originalEvent|clearTransStyle|dragging|unwrap|cssText|gestures|disabledEvents|css2move|doTranslate|removeTransition|mousemove|touchcancel|start|singleItemTransition|drag|disableTextSelect|show|hide|span|updatePagination|pageY|extend|buildButtons|buildPagination|push|min|loops|calculateWidth|appendWrapperSizes|appendItemsSizes|resize|responsiveRefreshRate|itemsScaleUp|responsiveBaseWidth|srcElement|singleItem|originalClasses|originalStyles|complete|outer|wrap|getNewPosition|animate|disable|improveClosest|setInterval|reload|right|updatePosition|onVisibleItems|continue|loadContent|loading|lazyFollow|lazyPreload|block|display|transitionTypes|lazyEffect|fade|owlStatus|naturalWidth|moveEvents|response|outClass|inClass|buildControls|abs|onStartup|perspective|customEvents|wrapItems|eventTypes|clearEvents|checkBrowser|textarea|select|option|stopImmediatePropagation|stopPropagation|_data|events|pop|splice|baseElWidth|minSwipe|maxSwipe|dargging|duration|prototype|createElement|destroyControls|clientX|mouseover|mouseout|clientY|lazyOwl|match|appendTo|checked|clearTimeout|ontouchstart|which|navigator|msMaxTouchPoints|removeAttr|wrapperWidth|buttons|fadeIn|400|document|img|round|undefined|text|500|wrapAll|out|top|relative|getJSON|5e3|new|onstartup|webkitAnimationEnd|oAnimationEnd|MSAnimationEnd|animationend|controls|dragstart|toggleClass|baseElement|returnValue|clickable|input|destroy|removeData|reinit|addItem|after|before|removeItem|1199|979|768|479|800|1e3|carousel|jQuery|window|numbers'.split('|'),0,{}))


/*! Magnific Popup - v0.9.9 - 2013-11-15
* http://dimsemenov.com/plugins/magnific-popup/
* Copyright (c) 2013 Dmitry Semenov; */
;(function(e) {
    var t, n, i, o, r, a, s, l = "Close",
        c = "BeforeClose",
        d = "AfterClose",
        u = "BeforeAppend",
        p = "MarkupParse",
        f = "Open",
        m = "Change",
        g = "mfp",
        v = "." + g,
        h = "mfp-ready",
        C = "mfp-removing",
        y = "mfp-prevent-close",
        w = function() {},
        b = !! window.jQuery,
        I = e(window),
        x = function(e, n) {
            t.ev.on(g + e + v, n);
        },
        k = function(t, n, i, o) {
            var r = document.createElement("div");
            return r.className = "mfp-" + t, i && (r.innerHTML = i), o ? n && n.appendChild(r) : (r = e(r), n && r.appendTo(n)), r;
        },
        T = function(n, i) {
            t.ev.triggerHandler(g + n, i), t.st.callbacks && (n = n.charAt(0).toLowerCase() + n.slice(1), t.st.callbacks[n] && t.st.callbacks[n].apply(t, e.isArray(i) ? i : [i]));
        },
        E = function(n) {
            return n === s && t.currTemplate.closeBtn || (t.currTemplate.closeBtn = e(t.st.closeMarkup.replace("%title%", t.st.tClose)), s = n), t.currTemplate.closeBtn;
        },
        _ = function() {
            e.magnificPopup.instance || (t = new w, t.init(), e.magnificPopup.instance = t);
        },
        S = function() {
            var e = document.createElement("p").style,
                t = ["ms", "O", "Moz", "Webkit"];
            if (void 0 !== e.transition) return !0;
            for (; t.length;) if (t.pop() + "Transition" in e) return !0;
            return !1;
        };
    w.prototype = {
        constructor: w,
        init: function() {
            var n = navigator.appVersion;
            t.isIE7 = -1 !== n.indexOf("MSIE 7."), t.isIE8 = -1 !== n.indexOf("MSIE 8."), t.isLowIE = t.isIE7 || t.isIE8, t.isAndroid = /android/gi.test(n), t.isIOS = /iphone|ipad|ipod/gi.test(n), t.supportsTransition = S(), t.probablyMobile = t.isAndroid || t.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent), i = e(document.body), o = e(document), t.popupsCache = {}
        },
        open: function(n) {
            var i;
            if (n.isObj === !1) {
                t.items = n.items.toArray(), t.index = 0;
                var r, s = n.items;
                for (i = 0; s.length > i; i++) if (r = s[i], r.parsed && (r = r.el[0]), r === n.el[0]) {
                    t.index = i;
                    break
                }
            } else t.items = e.isArray(n.items) ? n.items : [n.items], t.index = n.index || 0;
            if (t.isOpen) return t.updateItemHTML(), void 0;
            t.types = [], a = "", t.ev = n.mainEl && n.mainEl.length ? n.mainEl.eq(0) : o, n.key ? (t.popupsCache[n.key] || (t.popupsCache[n.key] = {}), t.currTemplate = t.popupsCache[n.key]) : t.currTemplate = {}, t.st = e.extend(!0, {}, e.magnificPopup.defaults, n), t.fixedContentPos = "auto" === t.st.fixedContentPos ? !t.probablyMobile : t.st.fixedContentPos, t.st.modal && (t.st.closeOnContentClick = !1, t.st.closeOnBgClick = !1, t.st.showCloseBtn = !1, t.st.enableEscapeKey = !1), t.bgOverlay || (t.bgOverlay = k("bg").on("click" + v, function() {
                t.close();
            }), t.wrap = k("wrap").attr("tabindex", -1).on("click" + v, function(e) {
                t._checkIfClose(e.target) && t.close();
            }), t.container = k("container", t.wrap)), t.contentContainer = k("content"), t.st.preloader && (t.preloader = k("preloader", t.container, t.st.tLoading));
            var l = e.magnificPopup.modules;
            for (i = 0; l.length > i; i++) {
                var c = l[i];
                c = c.charAt(0).toUpperCase() + c.slice(1), t["init" + c].call(t);
            }
            T("BeforeOpen"), t.st.showCloseBtn && (t.st.closeBtnInside ? (x(p, function(e, t, n, i) {
                n.close_replaceWith = E(i.type);
            }), a += " mfp-close-btn-in") : t.wrap.append(E())), t.st.alignTop && (a += " mfp-align-top"), t.fixedContentPos ? t.wrap.css({
                overflow: t.st.overflowY,
                overflowX: "hidden",
                overflowY: t.st.overflowY
            }) : t.wrap.css({
                top: I.scrollTop(),
                position: "absolute"
            }), (t.st.fixedBgPos === !1 || "auto" === t.st.fixedBgPos && !t.fixedContentPos) && t.bgOverlay.css({
                height: o.height(),
                position: "absolute"
            }), t.st.enableEscapeKey && o.on("keyup" + v, function(e) {
                27 === e.keyCode && t.close();
            }), I.on("resize" + v, function() {
                t.updateSize();
            }), t.st.closeOnContentClick || (a += " mfp-auto-cursor"), a && t.wrap.addClass(a);
            var d = t.wH = I.height(),
                u = {};
            if (t.fixedContentPos && t._hasScrollBar(d)) {
                var m = t._getScrollbarSize();
                m && (u.marginRight = m);
            }
            t.fixedContentPos && (t.isIE7 ? e("body, html").css("overflow", "hidden") : u.overflow = "hidden");
            var g = t.st.mainClass;
            return t.isIE7 && (g += " mfp-ie7"), g && t._addClassToMFP(g), t.updateItemHTML(), T("BuildControls"), e("html").css(u), t.bgOverlay.add(t.wrap).prependTo(document.body), t._lastFocusedEl = document.activeElement, setTimeout(function() {
                t.content ? (t._addClassToMFP(h), t._setFocus()) : t.bgOverlay.addClass(h), o.on("focusin" + v, t._onFocusIn);
            }, 16), t.isOpen = !0, t.updateSize(d), T(f), n;
        },
        close: function() {
            t.isOpen && (T(c), t.isOpen = !1, t.st.removalDelay && !t.isLowIE && t.supportsTransition ? (t._addClassToMFP(C), setTimeout(function() {
                t._close();
            }, t.st.removalDelay)) : t._close());
        },
        _close: function() {
            T(l);
            var n = C + " " + h + " ";
            if (t.bgOverlay.detach(), t.wrap.detach(), t.container.empty(), t.st.mainClass && (n += t.st.mainClass + " "), t._removeClassFromMFP(n), t.fixedContentPos) {
                var i = {
                    marginRight: ""
                };
                t.isIE7 ? e("body, html").css("overflow", "") : i.overflow = "", e("html").css(i);
            }
            o.off("keyup" + v + " focusin" + v), t.ev.off(v), t.wrap.attr("class", "mfp-wrap").removeAttr("style"), t.bgOverlay.attr("class", "mfp-bg"), t.container.attr("class", "mfp-container"), !t.st.showCloseBtn || t.st.closeBtnInside && t.currTemplate[t.currItem.type] !== !0 || t.currTemplate.closeBtn && t.currTemplate.closeBtn.detach(), t._lastFocusedEl && e(t._lastFocusedEl).focus(), t.currItem = null, t.content = null, t.currTemplate = null, t.prevHeight = 0, T(d);
        },
        updateSize: function(e) {
            if (t.isIOS) {
                var n = document.documentElement.clientWidth / window.innerWidth,
                    i = window.innerHeight * n;
                t.wrap.css("height", i), t.wH = i;
            } else t.wH = e || I.height();
            t.fixedContentPos || t.wrap.css("height", t.wH), T("Resize");
        },
        updateItemHTML: function() {
            var n = t.items[t.index];
            t.contentContainer.detach(), t.content && t.content.detach(), n.parsed || (n = t.parseEl(t.index));
            var i = n.type;
            if (T("BeforeChange", [t.currItem ? t.currItem.type : "", i]), t.currItem = n, !t.currTemplate[i]) {
                var o = t.st[i] ? t.st[i].markup : !1;
                T("FirstMarkupParse", o), t.currTemplate[i] = o ? e(o) : !0
            }
            r && r !== n.type && t.container.removeClass("mfp-" + r + "-holder");
            var a = t["get" + i.charAt(0).toUpperCase() + i.slice(1)](n, t.currTemplate[i]);
            t.appendContent(a, i), n.preloaded = !0, T(m, n), r = n.type, t.container.prepend(t.contentContainer), T("AfterChange");
        },
        appendContent: function(e, n) {
            t.content = e, e ? t.st.showCloseBtn && t.st.closeBtnInside && t.currTemplate[n] === !0 ? t.content.find(".mfp-close").length || t.content.append(E()) : t.content = e : t.content = "", T(u), t.container.addClass("mfp-" + n + "-holder"), t.contentContainer.append(t.content);
        },
        parseEl: function(n) {
            var i = t.items[n],
                o = i.type;
            if (i = i.tagName ? {
                el: e(i)
            } : {
                data: i,
                src: i.src
            }, i.el) {
                for (var r = t.types, a = 0; r.length > a; a++) if (i.el.hasClass("mfp-" + r[a])) {
                    o = r[a];
                    break
                }
                i.src = i.el.attr("data-mfp-src"), i.src || (i.src = i.el.attr("href"));
            }
            return i.type = o || t.st.type || "inline", i.index = n, i.parsed = !0, t.items[n] = i, T("ElementParse", i), t.items[n];
        },
        addGroup: function(e, n) {
            var i = function(i) {
                i.mfpEl = this, t._openClick(i, e, n);
            };
            n || (n = {});
            var o = "click.magnificPopup";
            n.mainEl = e, n.items ? (n.isObj = !0, e.off(o).on(o, i)) : (n.isObj = !1, n.delegate ? e.off(o).on(o, n.delegate, i) : (n.items = e, e.off(o).on(o, i)));
        },
        _openClick: function(n, i, o) {
            var r = void 0 !== o.midClick ? o.midClick : e.magnificPopup.defaults.midClick;
            if (r || 2 !== n.which && !n.ctrlKey && !n.metaKey) {
                var a = void 0 !== o.disableOn ? o.disableOn : e.magnificPopup.defaults.disableOn;
                if (a) if (e.isFunction(a)) {
                    if (!a.call(t)) return !0;
                } else if (a > I.width()) return !0;
                n.type && (n.preventDefault(), t.isOpen && n.stopPropagation()), o.el = e(n.mfpEl), o.delegate && (o.items = i.find(o.delegate)), t.open(o);
            }
        },
        updateStatus: function(e, i) {
            if (t.preloader) {
                n !== e && t.container.removeClass("mfp-s-" + n), i || "loading" !== e || (i = t.st.tLoading);
                var o = {
                    status: e,
                    text: i
                };
                T("UpdateStatus", o), e = o.status, i = o.text, t.preloader.html(i), t.preloader.find("a").on("click", function(e) {
                    e.stopImmediatePropagation();
                }), t.container.addClass("mfp-s-" + e), n = e;
            }
        },
        _checkIfClose: function(n) {
            if (!e(n).hasClass(y)) {
                var i = t.st.closeOnContentClick,
                    o = t.st.closeOnBgClick;
                if (i && o) return !0;
                if (!t.content || e(n).hasClass("mfp-close") || t.preloader && n === t.preloader[0]) return !0;
                if (n === t.content[0] || e.contains(t.content[0], n)) {
                    if (i) return !0
                } else if (o && e.contains(document, n)) return !0;
                return !1;
            }
        },
        _addClassToMFP: function(e) {
            t.bgOverlay.addClass(e), t.wrap.addClass(e);
        },
        _removeClassFromMFP: function(e) {
            this.bgOverlay.removeClass(e), t.wrap.removeClass(e);
        },
        _hasScrollBar: function(e) {
            return (t.isIE7 ? o.height() : document.body.scrollHeight) > (e || I.height());
        },
        _setFocus: function() {
            (t.st.focus ? t.content.find(t.st.focus).eq(0) : t.wrap).focus();
        },
        _onFocusIn: function(n) {
            return n.target === t.wrap[0] || e.contains(t.wrap[0], n.target) ? void 0 : (t._setFocus(), !1);
        },
        _parseMarkup: function(t, n, i) {
            var o;
            i.data && (n = e.extend(i.data, n)), T(p, [t, n, i]), e.each(n, function(e, n) {
                if (void 0 === n || n === !1) return !0;
                if (o = e.split("_"), o.length > 1) {
                    var i = t.find(v + "-" + o[0]);
                    if (i.length > 0) {
                        var r = o[1];
                        "replaceWith" === r ? i[0] !== n[0] && i.replaceWith(n) : "img" === r ? i.is("img") ? i.attr("src", n) : i.replaceWith('<img src="' + n + '" class="' + i.attr("class") + '" />') : i.attr(o[1], n);
                    }
                } else t.find(v + "-" + e).html(n);
            })
        },
        _getScrollbarSize: function() {
            if (void 0 === t.scrollbarSize) {
                var e = document.createElement("div");
                e.id = "mfp-sbm", e.style.cssText = "width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;", document.body.appendChild(e), t.scrollbarSize = e.offsetWidth - e.clientWidth, document.body.removeChild(e);
            }
            return t.scrollbarSize;
        }
    }, e.magnificPopup = {
        instance: null,
        proto: w.prototype,
        modules: [],
        open: function(t, n) {
            return _(), t = t ? e.extend(!0, {}, t) : {}, t.isObj = !0, t.index = n || 0, this.instance.open(t);
        },
        close: function() {
            return e.magnificPopup.instance && e.magnificPopup.instance.close();
        },
        registerModule: function(t, n) {
            n.options && (e.magnificPopup.defaults[t] = n.options), e.extend(this.proto, n.proto), this.modules.push(t);
        },
        defaults: {
            disableOn: 0,
            key: null,
            midClick: !1,
            mainClass: "",
            preloader: !0,
            focus: "",
            closeOnContentClick: !1,
            closeOnBgClick: !0,
            closeBtnInside: !0,
            showCloseBtn: !0,
            enableEscapeKey: !0,
            modal: !1,
            alignTop: !1,
            removalDelay: 0,
            fixedContentPos: "auto",
            fixedBgPos: "auto",
            overflowY: "auto",
            closeMarkup: '<button title="%title%" type="button" class="mfp-close">×</button>',
            tClose: "Close (Esc)",
            tLoading: "Loading..."
        }
    }, e.fn.magnificPopup = function(n) {
        _();
        var i = e(this);
        if ("string" == typeof n) if ("open" === n) {
            var o, r = b ? i.data("magnificPopup") : i[0].magnificPopup,
                a = parseInt(arguments[1], 10) || 0;
            r.items ? o = r.items[a] : (o = i, r.delegate && (o = o.find(r.delegate)), o = o.eq(a)), t._openClick({
                mfpEl: o
            }, i, r)
        } else t.isOpen && t[n].apply(t, Array.prototype.slice.call(arguments, 1));
        else n = e.extend(!0, {}, n), b ? i.data("magnificPopup", n) : i[0].magnificPopup = n, t.addGroup(i, n);
        return i;
    };
    var P, O, z, M = "inline",
        B = function() {
            z && (O.after(z.addClass(P)).detach(), z = null);
        };
    e.magnificPopup.registerModule(M, {
        options: {
            hiddenClass: "hide",
            markup: "",
            tNotFound: "Content not found"
        },
        proto: {
            initInline: function() {
                t.types.push(M), x(l + "." + M, function() {
                    B();
                });
            },
            getInline: function(n, i) {
                if (B(), n.src) {
                    var o = t.st.inline,
                        r = e(n.src);
                    if (r.length) {
                        var a = r[0].parentNode;
                        a && a.tagName && (O || (P = o.hiddenClass, O = k(P), P = "mfp-" + P), z = r.after(O).detach().removeClass(P)), t.updateStatus("ready");
                    } else t.updateStatus("error", o.tNotFound), r = e("<div>");
                    return n.inlineElement = r, r;
                }
                return t.updateStatus("ready"), t._parseMarkup(i, {}, n), i;
            }
        }
    });
    var F, H = "ajax",
        L = function() {
            F && i.removeClass(F);
        },
        A = function() {
            L(), t.req && t.req.abort();
        };
    e.magnificPopup.registerModule(H, {
        options: {
            settings: null,
            cursor: "mfp-ajax-cur",
            tError: '<a href="%url%">The content</a> could not be loaded.'
        },
        proto: {
            initAjax: function() {
                t.types.push(H), F = t.st.ajax.cursor, x(l + "." + H, A), x("BeforeChange." + H, A);
            },
            getAjax: function(n) {
                F && i.addClass(F), t.updateStatus("loading");
                var o = e.extend({
                    url: n.src,
                    success: function(i, o, r) {
                        var a = {
                            data: i,
                            xhr: r
                        };
                        T("ParseAjax", a), t.appendContent(e(a.data), H), n.finished = !0, L(), t._setFocus(), setTimeout(function() {
                            t.wrap.addClass(h);
                        }, 16), t.updateStatus("ready"), T("AjaxContentAdded");
                    },
                    error: function() {
                        L(), n.finished = n.loadError = !0, t.updateStatus("error", t.st.ajax.tError.replace("%url%", n.src));
                    }
                }, t.st.ajax.settings);
                return t.req = e.ajax(o), "";
            }
        }
    });
    var j, N = function(n) {
        if (n.data && void 0 !== n.data.title) return n.data.title;
        var i = t.st.image.titleSrc;
        if (i) {
            if (e.isFunction(i)) return i.call(t, n);
            if (n.el) return n.el.attr(i) || "";
        }
        return "";
    };
    e.magnificPopup.registerModule("image", {
        options: {
            markup: '<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',
            cursor: "mfp-zoom-out-cur",
            titleSrc: "title",
            verticalFit: !0,
            tError: '<a href="%url%">The image</a> could not be loaded.'
        },
        proto: {
            initImage: function() {
                var e = t.st.image,
                    n = ".image";
                t.types.push("image"), x(f + n, function() {
                    "image" === t.currItem.type && e.cursor && i.addClass(e.cursor);
                }), x(l + n, function() {
                    e.cursor && i.removeClass(e.cursor), I.off("resize" + v);
                }), x("Resize" + n, t.resizeImage), t.isLowIE && x("AfterChange", t.resizeImage);
            },
            resizeImage: function() {
                var e = t.currItem;
                if (e && e.img && t.st.image.verticalFit) {
                    var n = 0;
                    t.isLowIE && (n = parseInt(e.img.css("padding-top"), 10) + parseInt(e.img.css("padding-bottom"), 10)), e.img.css("max-height", t.wH - n)
                }
            },
            _onImageHasSize: function(e) {
                e.img && (e.hasSize = !0, j && clearInterval(j), e.isCheckingImgSize = !1, T("ImageHasSize", e), e.imgHidden && (t.content && t.content.removeClass("mfp-loading"), e.imgHidden = !1))
            },
            findImageSize: function(e) {
                var n = 0,
                    i = e.img[0],
                    o = function(r) {
                        j && clearInterval(j), j = setInterval(function() {
                            return i.naturalWidth > 0 ? (t._onImageHasSize(e), void 0) : (n > 200 && clearInterval(j), n++, 3 === n ? o(10) : 40 === n ? o(50) : 100 === n && o(500), void 0)
                        }, r);
                    };
                o(1);
            },
            getImage: function(n, i) {
                var o = 0,
                    r = function() {
                        n && (n.img[0].complete ? (n.img.off(".mfploader"), n === t.currItem && (t._onImageHasSize(n), t.updateStatus("ready")), n.hasSize = !0, n.loaded = !0, T("ImageLoadComplete")) : (o++, 200 > o ? setTimeout(r, 100) : a()));
                    },
                    a = function() {
                        n && (n.img.off(".mfploader"), n === t.currItem && (t._onImageHasSize(n), t.updateStatus("error", s.tError.replace("%url%", n.src))), n.hasSize = !0, n.loaded = !0, n.loadError = !0);
                    },
                    s = t.st.image,
                    l = i.find(".mfp-img");
                if (l.length) {
                    var c = document.createElement("img");
                    c.className = "mfp-img", n.img = e(c).on("load.mfploader", r).on("error.mfploader", a), c.src = n.src, l.is("img") && (n.img = n.img.clone()), n.img[0].naturalWidth > 0 && (n.hasSize = !0);
                }
                return t._parseMarkup(i, {
                    title: N(n),
                    img_replaceWith: n.img
                }, n), t.resizeImage(), n.hasSize ? (j && clearInterval(j), n.loadError ? (i.addClass("mfp-loading"), t.updateStatus("error", s.tError.replace("%url%", n.src))) : (i.removeClass("mfp-loading"), t.updateStatus("ready")), i) : (t.updateStatus("loading"), n.loading = !0, n.hasSize || (n.imgHidden = !0, i.addClass("mfp-loading"), t.findImageSize(n)), i);
            }
        }
    });
    var W, R = function() {
        return void 0 === W && (W = void 0 !== document.createElement("p").style.MozTransform), W;
    };
    e.magnificPopup.registerModule("zoom", {
        options: {
            enabled: !1,
            easing: "ease-in-out",
            duration: 300,
            opener: function(e) {
                return e.is("img") ? e : e.find("img");
            }
        },
        proto: {
            initZoom: function() {
                var e, n = t.st.zoom,
                    i = ".zoom";
                if (n.enabled && t.supportsTransition) {
                    var o, r, a = n.duration,
                        s = function(e) {
                            var t = e.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),
                                i = "all " + n.duration / 1e3 + "s " + n.easing,
                                o = {
                                    position: "fixed",
                                    zIndex: 9999,
                                    left: 0,
                                    top: 0,
                                    "-webkit-backface-visibility": "hidden"
                                },
                                r = "transition";
                            return o["-webkit-" + r] = o["-moz-" + r] = o["-o-" + r] = o[r] = i, t.css(o), t;
                        },
                        d = function() {
                            t.content.css("visibility", "visible");
                        };
                    x("BuildControls" + i, function() {
                        if (t._allowZoom()) {
                            if (clearTimeout(o), t.content.css("visibility", "hidden"), e = t._getItemToZoom(), !e) return d(), void 0;
                            r = s(e), r.css(t._getOffset()), t.wrap.append(r), o = setTimeout(function() {
                                r.css(t._getOffset(!0)), o = setTimeout(function() {
                                    d(), setTimeout(function() {
                                        r.remove(), e = r = null, T("ZoomAnimationEnded");
                                    }, 16);
                                }, a);
                            }, 16);
                        }
                    }), x(c + i, function() {
                        if (t._allowZoom()) {
                            if (clearTimeout(o), t.st.removalDelay = a, !e) {
                                if (e = t._getItemToZoom(), !e) return;
                                r = s(e);
                            }
                            r.css(t._getOffset(!0)), t.wrap.append(r), t.content.css("visibility", "hidden"), setTimeout(function() {
                                r.css(t._getOffset());
                            }, 16);
                        }
                    }), x(l + i, function() {
                        t._allowZoom() && (d(), r && r.remove(), e = null);
                    });
                }
            },
            _allowZoom: function() {
                return "image" === t.currItem.type;
            },
            _getItemToZoom: function() {
                return t.currItem.hasSize ? t.currItem.img : !1;
            },
            _getOffset: function(n) {
                var i;
                i = n ? t.currItem.img : t.st.zoom.opener(t.currItem.el || t.currItem);
                var o = i.offset(),
                    r = parseInt(i.css("padding-top"), 10),
                    a = parseInt(i.css("padding-bottom"), 10);
                o.top -= e(window).scrollTop() - r;
                var s = {
                    width: i.width(),
                    height: (b ? i.innerHeight() : i[0].offsetHeight) - a - r
                };
                return R() ? s["-moz-transform"] = s.transform = "translate(" + o.left + "px," + o.top + "px)" : (s.left = o.left, s.top = o.top), s;
            }
        }
    });
    var Z = "iframe",
        q = "//about:blank",
        D = function(e) {
            if (t.currTemplate[Z]) {
                var n = t.currTemplate[Z].find("iframe");
                n.length && (e || (n[0].src = q), t.isIE8 && n.css("display", e ? "block" : "none"));
            }
        };
    e.magnificPopup.registerModule(Z, {
        options: {
            markup: '<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',
            srcAction: "iframe_src",
            patterns: {
                youtube: {
                    index: "youtube.com",
                    id: "v=",
                    src: "//www.youtube.com/embed/%id%?autoplay=1"
                },
                vimeo: {
                    index: "vimeo.com/",
                    id: "/",
                    src: "//player.vimeo.com/video/%id%?autoplay=1"
                },
                gmaps: {
                    index: "//maps.google.",
                    src: "%id%&output=embed"
                }
            }
        },
        proto: {
            initIframe: function() {
                t.types.push(Z), x("BeforeChange", function(e, t, n) {
                    t !== n && (t === Z ? D() : n === Z && D(!0));
                }), x(l + "." + Z, function() {
                    D();
                });
            },
            getIframe: function(n, i) {
                var o = n.src,
                    r = t.st.iframe;
                e.each(r.patterns, function() {
                    return o.indexOf(this.index) > -1 ? (this.id && (o = "string" == typeof this.id ? o.substr(o.lastIndexOf(this.id) + this.id.length, o.length) : this.id.call(this, o)), o = this.src.replace("%id%", o), !1) : void 0;
                });
                var a = {};
                return r.srcAction && (a[r.srcAction] = o), t._parseMarkup(i, a, n), t.updateStatus("ready"), i;
            }
        }
    });
    var K = function(e) {
        var n = t.items.length;
        return e > n - 1 ? e - n : 0 > e ? n + e : e;
    },
        Y = function(e, t, n) {
            return e.replace(/%curr%/gi, t + 1).replace(/%total%/gi, n);
        };
    e.magnificPopup.registerModule("gallery", {
        options: {
            enabled: !1,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            preload: [0, 2],
            navigateByImgClick: !0,
            arrows: !0,
            tPrev: "Previous (Left arrow key)",
            tNext: "Next (Right arrow key)",
            tCounter: "%curr% of %total%"
        },
        proto: {
            initGallery: function() {
                var n = t.st.gallery,
                    i = ".mfp-gallery",
                    r = Boolean(e.fn.mfpFastClick);
                return t.direction = !0, n && n.enabled ? (a += " mfp-gallery", x(f + i, function() {
                    n.navigateByImgClick && t.wrap.on("click" + i, ".mfp-img", function() {
                        return t.items.length > 1 ? (t.next(), !1) : void 0;
                    }), o.on("keydown" + i, function(e) {
                        37 === e.keyCode ? t.prev() : 39 === e.keyCode && t.next();
                    });
                }), x("UpdateStatus" + i, function(e, n) {
                    n.text && (n.text = Y(n.text, t.currItem.index, t.items.length));
                }), x(p + i, function(e, i, o, r) {
                    var a = t.items.length;
                    o.counter = a > 1 ? Y(n.tCounter, r.index, a) : "";
                }), x("BuildControls" + i, function() {
                    if (t.items.length > 1 && n.arrows && !t.arrowLeft) {
                        var i = n.arrowMarkup,
                            o = t.arrowLeft = e(i.replace(/%title%/gi, n.tPrev).replace(/%dir%/gi, "left")).addClass(y),
                            a = t.arrowRight = e(i.replace(/%title%/gi, n.tNext).replace(/%dir%/gi, "right")).addClass(y),
                            s = r ? "mfpFastClick" : "click";
                        o[s](function() {
                            t.prev();
                        }), a[s](function() {
                            t.next();
                        }), t.isIE7 && (k("b", o[0], !1, !0), k("a", o[0], !1, !0), k("b", a[0], !1, !0), k("a", a[0], !1, !0)), t.container.append(o.add(a))
                    }
                }), x(m + i, function() {
                    t._preloadTimeout && clearTimeout(t._preloadTimeout), t._preloadTimeout = setTimeout(function() {
                        t.preloadNearbyImages(), t._preloadTimeout = null;
                    }, 16)
                }), x(l + i, function() {
                    o.off(i), t.wrap.off("click" + i), t.arrowLeft && r && t.arrowLeft.add(t.arrowRight).destroyMfpFastClick(), t.arrowRight = t.arrowLeft = null
                }), void 0) : !1;
            },
            next: function() {
                t.direction = !0, t.index = K(t.index + 1), t.updateItemHTML();
            },
            prev: function() {
                t.direction = !1, t.index = K(t.index - 1), t.updateItemHTML();
            },
            goTo: function(e) {
                t.direction = e >= t.index, t.index = e, t.updateItemHTML();
            },
            preloadNearbyImages: function() {
                var e, n = t.st.gallery.preload,
                    i = Math.min(n[0], t.items.length),
                    o = Math.min(n[1], t.items.length);
                for (e = 1;
                (t.direction ? o : i) >= e; e++) t._preloadItem(t.index + e);
                for (e = 1;
                (t.direction ? i : o) >= e; e++) t._preloadItem(t.index - e)
            },
            _preloadItem: function(n) {
                if (n = K(n), !t.items[n].preloaded) {
                    var i = t.items[n];
                    i.parsed || (i = t.parseEl(n)), T("LazyLoad", i), "image" === i.type && (i.img = e('<img class="mfp-img" />').on("load.mfploader", function() {
                        i.hasSize = !0
                    }).on("error.mfploader", function() {
                        i.hasSize = !0, i.loadError = !0, T("LazyLoadError", i);
                    }).attr("src", i.src)), i.preloaded = !0;
                }
            }
        }
    });
    var U = "retina";
    e.magnificPopup.registerModule(U, {
        options: {
            replaceSrc: function(e) {
                return e.src.replace(/\.\w+$/, function(e) {
                    return "@2x" + e;
                });
            },
            ratio: 1
        },
        proto: {
            initRetina: function() {
                if (window.devicePixelRatio > 1) {
                    var e = t.st.retina,
                        n = e.ratio;
                    n = isNaN(n) ? n() : n, n > 1 && (x("ImageHasSize." + U, function(e, t) {
                        t.img.css({
                            "max-width": t.img[0].naturalWidth / n,
                            width: "100%"
                        })
                    }), x("ElementParse." + U, function(t, i) {
                        i.src = e.replaceSrc(i, n);
                    }));
                }
            }
        }
    }), function() {
        var t = 1e3,
            n = "ontouchstart" in window,
            i = function() {
                I.off("touchmove" + r + " touchend" + r)
            },
            o = "mfpFastClick",
            r = "." + o;
        e.fn.mfpFastClick = function(o) {
            return e(this).each(function() {
                var a, s = e(this);
                if (n) {
                    var l, c, d, u, p, f;
                    s.on("touchstart" + r, function(e) {
                        u = !1, f = 1, p = e.originalEvent ? e.originalEvent.touches[0] : e.touches[0], c = p.clientX, d = p.clientY, I.on("touchmove" + r, function(e) {
                            p = e.originalEvent ? e.originalEvent.touches : e.touches, f = p.length, p = p[0], (Math.abs(p.clientX - c) > 10 || Math.abs(p.clientY - d) > 10) && (u = !0, i())
                        }).on("touchend" + r, function(e) {
                            i(), u || f > 1 || (a = !0, e.preventDefault(), clearTimeout(l), l = setTimeout(function() {
                                a = !1
                            }, t), o())
                        })
                    })
                }
                s.on("click" + r, function() {
                    a || o();
                });
            });
        }, e.fn.destroyMfpFastClick = function() {
            e(this).off("touchstart" + r + " click" + r), n && I.off("touchmove" + r + " touchend" + r)
        };
    }(), _();
})
(window.jQuery || window.Zepto);



/*
 * Pause jQuery plugin v0.1
 *
 * Copyright 2010 by Tobia Conforto <tobia.conforto@gmail.com>
 *
 * Based on Pause-resume-animation jQuery plugin by Joe Weitzel
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or(at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */
(function(){var e=jQuery,f="jQuery.pause",d=1,b=e.fn.animate,a={};function c(){return new Date().getTime()}e.fn.animate=function(k,h,j,i){var g=e.speed(h,j,i);g.complete=g.old;return this.each(function(){if(!this[f]){this[f]=d++}var l=e.extend({},g);b.apply(e(this),[k,e.extend({},l)]);a[this[f]]={run:true,prop:k,opt:l,start:c(),done:0}})};e.fn.pause=function(){return this.each(function(){if(!this[f]){this[f]=d++}var g=a[this[f]];if(g&&g.run){g.done+=c()-g.start;if(g.done>g.opt.duration){delete a[this[f]]}else{e(this).stop();g.run=false}}})};e.fn.resume=function(){return this.each(function(){if(!this[f]){this[f]=d++}var g=a[this[f]];if(g&&!g.run){g.opt.duration-=g.done;g.done=0;g.run=true;g.start=c();b.apply(e(this),[g.prop,e.extend({},g.opt)])}})}})();

jQuery(document).ready(function(){
  var marquee = jQuery("#marquee");
	var time_multiplier = 20;
	var current;

	marquee.hover(function(){
		current.pause();
	}, function(){
		current.resume();
	});

    var reset = function() {
		current = jQuery(this);
		var item_width = jQuery(this).outerWidth();

		var time = time_multiplier * jQuery(this).outerWidth();
        jQuery(this).animate({ 'margin-left': -item_width }, time, 'linear', function(){
			var clone_item = jQuery(this).clone();
			clone_item.css({ 'margin-left': '0' });
			marquee.append(clone_item);

			jQuery(this).remove();
			reset.call(marquee.children().filter(':first'));
		});
    };

    reset.call(marquee.children().filter(':first'));
});


;
(function($, window, undefined) {
  'use strict';

  var $doc = $(document),
          win = $(window),
          Modernizr = window.Modernizr,
          scrollTime = null,
          scrollTimer = null;

  var SITE = SITE || {};

  SITE = {
    init: function() {
      var self = this,
              obj;

      for (obj in self) {
        if (self.hasOwnProperty(obj)) {
          var _method = self[obj];
          if (_method.selector !== undefined && _method.init !== undefined) {
            if ($(_method.selector).length > 0) {
              if (_method.dependencies !== undefined) {
                (function(_async) {
                  Modernizr.load([
                    {
                      load: _async.dependencies,
                      complete: function() {
                        _async.init();
                      }
                    }]);
                })(_method);
              } else {
                _method.init();
              }
            }
          }
        }
      }
    },
    hoverEvents: {
      selector: 'body',
      init: function() {
        var base = this,
                container = $(base.selector);
        win.on('scroll', function() {
          clearTimeout(scrollTime);
          if (!container.hasClass('disable-hover')) {
            container.addClass('disable-hover');
          }

          scrollTime = setTimeout(function() {
            container.removeClass('disable-hover');
          }, 400);
        });
      }
    },
    categoryColors: {
      selector: '#nav a, .extendmenu a',
      init: function() {
        var base = this,
                container = $(base.selector);

        container.live({
          mouseenter: function() {
            var color = $(this).data('color');
            $(this).css('background-color', color).css('border-color', color);
          },
          mouseleave: function() {
            $(this).removeAttr("style");
          }
        });
      }
    },
    responsiveNav: {
      selector: '#mobile-toggle',
      target: '#mobile-menu',
      init: function() {
        var base = this,
                container = $(base.selector),
                target = $(base.target);

        container.click(function() {
          target.stop(true, true).slideToggle(500);
          return false;
        });

        target.find('ul li').each(function() {
          if ($(this).find('> ul').length > 0) {
            $(this).find('> a').append('<span><i class="fa fa-plus"></i></span>');
            $(this).find('li a').prepend('<span><i class="fa fa-angle-right"></i></span>');
          }
        });

        target.find('ul li:has(">ul") > a').click(function() {
          $(this).find('i').toggleClass('fa fa-plus').toggleClass('fa fa-minus');
          $(this).parent().find('> ul').stop(true, true).slideToggle();
          return false;
        });
      },
      toggle: function() {
        if (win.width() > 767) {
          var base = this,
                  target = $(base.target);

          target.hide();
        }
      }
    },
    superfish: {
      selector: '.sf-menu',
      init: function() {
        var base = this,
                container = $(base.selector);
        container.supersubs({
          minWidth: 12, // minimum width of submenus in em units
          maxWidth: 27, // maximum width of submenus in em units
          extraWidth: 1	// extra width can ensure lines don't sometimes turn over
        }).superfish({
          delay: 100, // one second delay on mouseout
          animation: {height: 'show'}, // fade-in and slide-down animation
          speed: 'fast', // faster animation speed
          cssArrows: false             // disable generation of arrow mark-up
        });
      }
    },
    megamenu: {
      selector: '.mega-menu',
      init: function() {
        var base = this,
                container = $(base.selector),
                item = container.find('li').has('.category-holder');


        item.each(function() {
          var that = $(this),
                  holder = that.find('.category-holder'),
                  list = that.find('.category-holder > ul'),
                  subitems = list.find('li'),
                  subposts = that.find('.category-children>div'),
                  offset = that.offset(),
                  w = holder.width();

          that.live({
            mouseenter: function() {
              if (offset.left + w > $(window).width()) {
                holder.addClass('menu-left');
              }
              holder.stop(true).animate({height: 'show'});
            },
            mouseleave: function() {
              holder.stop(true).hide(0, function() {
                holder.removeClass('menu-left');
              }).removeAttr('style');
            }
          });
          subitems.each(function() {
            var that = $(this);
            that.on('hover', function() {
              var i = that.index(),
                      h = subposts.eq(i).outerHeight() + 40;

              subitems.find('a').removeClass('active');
              that.find('a').addClass('active');
              subposts.removeClass('active').eq(i).addClass('active');
              holder.height(Math.max(h, list.outerHeight()));
            });
          });
        });

        SITE.megamenu.control();
      },
      control: function() {
        var base = this,
                container = $(base.selector);

        $('<li class="smallmenu"><a href="#" data-color="#222"><i class="fa fa-plus"></i></a><div class="extendmenu"></div></li>').appendTo(container).hide()
                .mouseenter(function() {
                  container.find('.extendmenu').stop(true).animate({height: 'show'});
                })
                .mouseleave(function() {
                  container.find('.extendmenu').stop(true).hide().removeAttr('style');
                });

        function organizeMenuItems() {
          var containerWidth = $(base.selector).width(),
                  smallMenuWidth = $(".smallmenu").width(),
                  widthSum = 0;

          container.find('>li:not(.smallmenu)').each(function() {
            widthSum += $(this).outerWidth(true);
            if (widthSum + smallMenuWidth > containerWidth)
              $(this).hide();
            else
              $(this).show();
          });

          var hiddenItems = container.find('>li:not(.smallmenu):not(:visible)');

          if (hiddenItems.length > 0)
            $(".smallmenu").show();
          else
            $(".smallmenu").hide();

          container.find('.extendmenu').html(hiddenItems.find('>a').clone());
        }
        organizeMenuItems();

        win.on('resize', function() {
          organizeMenuItems();
        });
      }
    },

    shareThisArticle: {
      selector: '#sharethispost',
      init: function() {
        var base = this;
        var container = $(base.selector);
//        var done;
        container.toggle(function() {
          container.add(container.find('.placeholder')).animate({height: 40}, function() {
//            if (!done) {
              container.find('.placeholder').sharrre({
                share: {
                  googlePlus: true,
                  facebook: true,
                  linkedin: false,
                  twitter: true,
                  pinterest: true
                },
                buttons: {
                  googlePlus: {
                    annotation: 'bubble'
                  },
                  facebook: {
                    width: '85'
                  }
                },
                enableHover: false,
                enableCounter: true,
                enableTracking: true
              });
//              done = true;
//            }
            container.find('i').removeClass('fa-plus').addClass('fa-minus');
          });
        }, function() {
          container.add(container.find('.placeholder')).animate({height: 0}, 600, function() {
            container.find('i').removeClass('fa-minus').addClass('fa-plus');
          });
        });
      }
    },

    endPage: {
      selector: '#endpage-box',
      init: function() {
        var base = this,
                container = $(base.selector),
                close = container.find('.close');

        if (win.width() > 767 && ($.cookie('end_page_box') !== 'hide')) {
          container.endpage_box({
            animation: "slide", // There are several animations available: fade, slide, flyInLeft, flyInRight, flyInUp, flyInDown, or false if you don't want it to animate. The default value is fade.
            from: "50%",
            to: "110%"
          });

          close.on('click', function() {
            container.hide().remove();
            $.cookie('end_page_box', 'hide', {expires: 1});
            return false;
          });
        }
      }
    },
    scrollBubble: {
      selector: '#scrollbubble',
      init: function() {
        var base = this,
                container = $(base.selector);

        if (win.width() > 940) {
          var viewportHeight = win.height(),
                  scrollbarHeight = viewportHeight / $doc.height() * viewportHeight,
                  progress = win.scrollTop() / ($doc.height() - viewportHeight),
                  distance = progress * (viewportHeight - scrollbarHeight) + scrollbarHeight / 2 - container.height() / 2;


          container.css('top', distance).text(Math.round(progress * 100) + '%').fadeIn(100);

          if (scrollTimer !== null) {
            clearTimeout(scrollTimer);
          }
          scrollTimer = setTimeout(function() {
            container.fadeOut();
          }, 1000);
        }
      }
    },
    flex: {
      selector: '.flex',
      init: function() {
        var base = this,
                container = $(base.selector);
        container.each(function() {
          var that = $(this),
                  controls = (that.data('controls') === false ? false : true),
                  bullets = (that.data('bullets') === false ? false : true);
          that.imagesLoaded(function() {
            that.removeClass('flex-start');
            that.flexslider({
              animation: "slide",
              directionNav: controls,
              controlNav: bullets,
              animationSpeed: 800,
              useCSS: false,
              prevText: '<i class="fa fa-angle-left"></i>',
              nextText: '<i class="fa fa-angle-right"></i>'
            });
          });

        });
      }
    },
    carousel: {
      selector: '.owl',
      init: function() {
        var base = this,
                container = $(base.selector);

        container.each(function() {
          var that = $(this),
                  columns = that.data('columns'),
                  navigation = (that.data('navigation') === true ? true : false),
                  autoplay = (that.data('autoplay') === false ? false : true),
                  pagination = (that.data('pagination') === true ? true : false);

          that.owlCarousel({
            //Basic Speeds
            slideSpeed: 1200,
            //Autoplay
            autoPlay: autoplay,
            goToFirst: true,
            stopOnHover: true,
            // Navigation
            navigation: navigation,
            navigationText: ['<i class="fa fa-long-arrow-left"></i>', '<i class="fa fa-long-arrow-right"></i>'],
            pagination: pagination,
            // Responsive
            responsive: true,
            items: columns,
            itemsDesktop: false,
            itemsDesktopSmall: [980, (columns < 3 ? columns : 3)],
            itemsTablet: [768, (columns < 2 ? columns : 2)],
            itemsMobile: [479, 1]
          });
        });
      }
    },
    toggle: {
      selector: '.toggle .title',
      init: function() {
        var base = this,
                container = $(base.selector);
        container.each(function() {
          $(this).toggle(function() {
            $(this).addClass("toggled").find('i').removeClass('fa fa-plus').addClass('fa fa-minus').end().closest('.toggle').find('.inner').slideDown(400);
          }, function() {
            $(this).removeClass("toggled").find('i').removeClass('fa fa-minus').addClass('fa fa-plus').end().closest('.toggle').find('.inner').slideUp(400);
          });
        });
      }
    },
    homeAjax: {
      selector: '#recentnews',
      loadmore: '#loadmore',
      init: function() {
        var base = this,
                loadmore = $(base.loadmore),
                page = 1;

        loadmore.live('click', function() {
          var text = loadmore.text(),
                  loading = loadmore.data('loading'),
                  nomore = loadmore.data('nomore'),
                  count = loadmore.data('count'),
                  action = loadmore.data('action');

          loadmore.text(loading).addClass('active');

          $.post(themeajax.url, {
            action: action,
            count: count,
            page: page++


          }, function(data) {

            var d = $.parseHTML(data),
                    l = ($(d).length - 1) / 2;

            if (data === '' || data === 'undefined' || data === 'No More Posts' || data === 'No $args array created') {
              data = '';
              loadmore.text(nomore).removeClass('active').die("click").on('click', function() {
                return false;
              });

            } else if (l < count) {
              loadmore.text(nomore).removeClass('active').die("click").on('click', function() {
                return false;
              });

              $(d).insertBefore(loadmore).hide();
              var i = 0;
              $(d).each(function() {
                $(this).delay(i * 300).slideDown('300');

                i++;
              });
              return false;
            } else {

              loadmore.text(text).removeClass('active');

              $(d).insertBefore(loadmore).hide();
              var f = 0;
              $(d).each(function() {
                $(this).delay(f * 300).slideDown('300');

                f++;
              });
            }
          });
          return false;
        });
      }
    },
    likethis: {
      selector: '.likeThis',
      init: function() {
        var base = this,
                container = $(base.selector);

        container.live('click', function() {

          var that = $(this),
                  id = that.data('id'),
                  blogurl = $('body').data('url');

          if (that.hasClass('active')) {
            return false;
          } else {
            $.ajax({
              type: "POST",
              url: blogurl + "/index.php",
              data: "likepost=" + id,
              beforeSend: function() {
                $('.likeThis[data-id=' + id + ']').find('i').removeClass('fa fa-heart-o').addClass('fa fa-refresh fa-spin');
              },
              success: function() {
                var text = $('.likeThis[data-id=' + id + ']').html(),
                        patt = /(\d)+/,
                        num = patt.exec(text);

                num[0]++;
                text = text.replace(patt, num[0]);
                $('.likeThis[data-id=' + id + ']').html(text);
                $('.likeThis[data-id=' + id + ']').find('i').removeClass('fa fa-refresh fa-spin').removeClass('fa fa-heart-o').addClass('fa fa-heart');
                that.addClass("active");
              }
            });
          }
          return false;
        });
      }
    },
    blogMasonry: {
      selector: '.masonry',
      init: function() {
        var base = this,
                container = $(base.selector);

        $(window).load(function() {
          container.isotope({
            itemSelector: '.item',
            layoutMode: 'fitRows',
            resizable: false,
            animationOptions: {
              duration: 1000,
              easing: 'linear',
              queue: false
            }
          });
        });
        base.resize(container);
      },
      resize: function(container) {
        $(window).smartresize(function() {
          container.isotope({
            masonry: {columnWidth: container.width() / 3}
          });
        });
      }
    },
    magnific: {
      selector: '[rel=magnific], .wp-caption a',
      init: function() {
        var base = this,
                container = $(base.selector);


        container.each(function() {
          $(this).magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: false,
            fixedContentPos: true,
            removalDelay: 300,
            mainClass: 'my-mfp-slide-bottom',
            image: {
              verticalFit: true
            }
          });
        });

      }
    },
    magnificGallery: {
      selector: '[rel=gallery]',
      init: function() {
        var base = this,
                container = $(base.selector);

        container.each(function() {
          $(this).magnificPopup({
            delegate: 'a',
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: false,
            fixedContentPos: true,
            removalDelay: 300,
            mainClass: 'my-mfp-slide-bottom',
            gallery: {
              enabled: true,
              navigateByImgClick: true,
              preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
            },
            image: {
              verticalFit: true,
              titleSrc: function(item) {
                return item.el.attr('title');
              }
            }
          });
        });

      }
    },
    parsley: {
      selector: '.comment-form, .wpcf7-form',
      init: function() {
        var base = this,
                container = $(base.selector);

        if ($.fn.parsley) {
          container.parsley();
        }
      }
    },
    contact: {
      selector: '#contact-map',
      init: function() {
        var base = this,
                container = $(base.selector),
                mapzoom = container.data('map-zoom'),
                maplat = container.data('map-center-lat'),
                maplong = container.data('map-center-long'),
                mapinfo = container.data('pin-info'),
                pinimage = container.data('pin-image');



        var latLng = new google.maps.LatLng(maplat, maplong);

        var mapOptions = {
          center: latLng,
          zoom: mapzoom,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          scrollwheel: false,
          panControl: true,
          zoomControl: 1,
          mapTypeControl: false,
          scaleControl: false,
          streetViewControl: false
        };

        var map = new google.maps.Map(document.getElementById("contact-map"), mapOptions);

        google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
          var venuemarker = new google.maps.Marker({
            position: latLng,
            map: map,
            icon: pinimage,
            animation: google.maps.Animation.DROP
          });
          map.setCenter(latLng);

          if (mapinfo) {
            var infowindow = new google.maps.InfoWindow({
              content: mapinfo
            });

            infowindow.open(map, venuemarker);

            map.setCenter(latLng);

          }
        });

      }
    },
    styleSwitcher: {
      selector: '#style-switcher',
      init: function() {
        var base = this,
                container = $(base.selector),
                toggle = container.find('.style-toggle'),
                onoffswitch = container.find('.switch');

        toggle.on('click', function() {
          container.add($(this)).toggleClass('active');
          return false;
        });

        onoffswitch.each(function() {
          var that = $(this);
          $(this).find('a').on('click', function() {
            that.find('a').removeClass('active');
            $(this).addClass('active');

            if ($(this).parents('ul').data('name') === 'header') {
              $(document.body).removeClass('notfixed');
              $(document.body).addClass($(this).data('class'));

              $('#header, #header .logo a, #header .desktop-menu ul, #header .desktop-menu .searchlink, .headersearch').attr("style", "");
              $('#header').removeClass('fixed').removeClass('small');
              $('#header').addClass($(this).data('class2'));
            }
            return false;
          });
        });

        var style = $('<style type="text/css" id="theme_color" />').appendTo('head');
        container.find('.first').minicolors({
          defaultValue: $('.first').data('default'),
          change: function(hex) {
            style.html('.sf-menu li.current-menu-item, .sf-menu li ul li:hover, .owl-buttons>div:hover, .jp-interface, .filters li a.active, .filters li a:hover, .iconbox.left > span, .iconbox.right > span, ul.accordion > li.active div.title, .toggle .title.toggled, .btn, input[type=submit], .comment-reply-link, .label.red, .dropcap.boxed, .bargraph > span span, .pagenavi ul li.disabled a, .mobile-menu ul li a.active, .taglink:hover, .widget.widget_tag_cloud li > a:hover { background:' + hex + '; } #breadcrumb .name > div { border-color: ' + hex + '; } a:hover, .iconbox.top > span { color: ' + hex + '; } ::-webkit-selection{ background-color: ' + hex + '; } ::-moz-selection{ background-color: ' + hex + '; } ::selection{ background-color: ' + hex + '; } ');
          }
        });
        container.find('.second').minicolors({
          defaultValue: $('.second').data('default'),
          change: function(hex) {
            style.html('.flex .bulletrow .flex-control-nav.flex-control-paging a.flex-active, .pricing .item.featured .header, .flex .bulletrow .flex-control-nav.flex-control-paging a:hover, .btn.red, input[type=submit].red, .comment-reply-link.red { background:' + hex + '; } blockquote.styled, .post .post-gallery.quote, .widget.widget_calendar table caption { border-color: ' + hex + '; } .iconbox.top:hover > span, .testimonials.flex blockquote p cite, .widget.widget_calendar table caption, .fresco .overlay .details, .fresco .overlay .zoom, .fresco .overlay .static { color: ' + hex + '; }');
          }
        });
      }
    }
  };

  // on Resize & Scroll
  $(window).resize(function() {
    SITE.responsiveNav.toggle();
  });
  $(window).scroll(function() {
    SITE.scrollBubble.init();
  });

  $doc.ready(function() {
    FastClick.attach(document.body);

    $.fn.foundationAlerts ? $doc.foundationAlerts() : null;
    $.fn.foundationAccordion ? $doc.foundationAccordion() : null;
    $.fn.foundationTabs ? $doc.foundationTabs() : null;

    SITE.init();


//    $('.placeholder').sharrre({
//      share: {
//        googlePlus: true,
//        facebook: true,
//        twitter: true,
//        pinterest: true
//      },
//      buttons: {
//        googlePlus: {
//          annotation: 'bubble'
//        },
//        facebook: {
//          width: '85'
//        }
//      },
//      enableHover: false,
//      enableCounter: true,
//      enableTracking: true
//    });



  });

})(jQuery, this);


