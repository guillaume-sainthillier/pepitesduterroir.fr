function _construct(Parent, args, Class) { if (_isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

!function (t) {
  var e = {};

  function i(n) {
    if (e[n]) return e[n].exports;
    var r = e[n] = {
      i: n,
      l: !1,
      exports: {}
    };
    return t[n].call(r.exports, r, r.exports, i), r.l = !0, r.exports;
  }

  i.m = t, i.c = e, i.d = function (t, e, n) {
    i.o(t, e) || Object.defineProperty(t, e, {
      enumerable: !0,
      get: n
    });
  }, i.r = function (t) {
    "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {
      value: "Module"
    }), Object.defineProperty(t, "__esModule", {
      value: !0
    });
  }, i.t = function (t, e) {
    if (1 & e && (t = i(t)), 8 & e) return t;
    if (4 & e && "object" == _typeof(t) && t && t.__esModule) return t;
    var n = Object.create(null);
    if (i.r(n), Object.defineProperty(n, "default", {
      enumerable: !0,
      value: t
    }), 2 & e && "string" != typeof t) for (var r in t) {
      i.d(n, r, function (e) {
        return t[e];
      }.bind(null, r));
    }
    return n;
  }, i.n = function (t) {
    var e = t && t.__esModule ? function () {
      return t.default;
    } : function () {
      return t;
    };
    return i.d(e, "a", e), e;
  }, i.o = function (t, e) {
    return Object.prototype.hasOwnProperty.call(t, e);
  }, i.p = "", i(i.s = 12);
}([function (t, e, i) {
  var n,
      r,
      o = {},
      s = (n = function n() {
    return window && document && document.all && !window.atob;
  }, function () {
    return void 0 === r && (r = n.apply(this, arguments)), r;
  }),
      a = function (t) {
    var e = {};
    return function (t, i) {
      if ("function" == typeof t) return t();

      if (void 0 === e[t]) {
        var n = function (t, e) {
          return e ? e.querySelector(t) : document.querySelector(t);
        }.call(this, t, i);

        if (window.HTMLIFrameElement && n instanceof window.HTMLIFrameElement) try {
          n = n.contentDocument.head;
        } catch (t) {
          n = null;
        }
        e[t] = n;
      }

      return e[t];
    };
  }(),
      c = null,
      u = 0,
      l = [],
      p = i(1);

  function d(t, e) {
    for (var i = 0; i < t.length; i++) {
      var n = t[i],
          r = o[n.id];

      if (r) {
        r.refs++;

        for (var s = 0; s < r.parts.length; s++) {
          r.parts[s](n.parts[s]);
        }

        for (; s < n.parts.length; s++) {
          r.parts.push(y(n.parts[s], e));
        }
      } else {
        var a = [];

        for (s = 0; s < n.parts.length; s++) {
          a.push(y(n.parts[s], e));
        }

        o[n.id] = {
          id: n.id,
          refs: 1,
          parts: a
        };
      }
    }
  }

  function f(t, e) {
    for (var i = [], n = {}, r = 0; r < t.length; r++) {
      var o = t[r],
          s = e.base ? o[0] + e.base : o[0],
          a = {
        css: o[1],
        media: o[2],
        sourceMap: o[3]
      };
      n[s] ? n[s].parts.push(a) : i.push(n[s] = {
        id: s,
        parts: [a]
      });
    }

    return i;
  }

  function h(t, e) {
    var i = a(t.insertInto);
    if (!i) throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
    var n = l[l.length - 1];
    if ("top" === t.insertAt) n ? n.nextSibling ? i.insertBefore(e, n.nextSibling) : i.appendChild(e) : i.insertBefore(e, i.firstChild), l.push(e);else if ("bottom" === t.insertAt) i.appendChild(e);else {
      if ("object" != _typeof(t.insertAt) || !t.insertAt.before) throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");
      var r = a(t.insertAt.before, i);
      i.insertBefore(e, r);
    }
  }

  function g(t) {
    if (null === t.parentNode) return !1;
    t.parentNode.removeChild(t);
    var e = l.indexOf(t);
    e >= 0 && l.splice(e, 1);
  }

  function m(t) {
    var e = document.createElement("style");

    if (void 0 === t.attrs.type && (t.attrs.type = "text/css"), void 0 === t.attrs.nonce) {
      var n = function () {
        0;
        return i.nc;
      }();

      n && (t.attrs.nonce = n);
    }

    return v(e, t.attrs), h(t, e), e;
  }

  function v(t, e) {
    Object.keys(e).forEach(function (i) {
      t.setAttribute(i, e[i]);
    });
  }

  function y(t, e) {
    var i, n, r, o;

    if (e.transform && t.css) {
      if (!(o = "function" == typeof e.transform ? e.transform(t.css) : e.transform.default(t.css))) return function () {};
      t.css = o;
    }

    if (e.singleton) {
      var s = u++;
      i = c || (c = m(e)), n = w.bind(null, i, s, !1), r = w.bind(null, i, s, !0);
    } else t.sourceMap && "function" == typeof URL && "function" == typeof URL.createObjectURL && "function" == typeof URL.revokeObjectURL && "function" == typeof Blob && "function" == typeof btoa ? (i = function (t) {
      var e = document.createElement("link");
      return void 0 === t.attrs.type && (t.attrs.type = "text/css"), t.attrs.rel = "stylesheet", v(e, t.attrs), h(t, e), e;
    }(e), n = function (t, e, i) {
      var n = i.css,
          r = i.sourceMap,
          o = void 0 === e.convertToAbsoluteUrls && r;
      (e.convertToAbsoluteUrls || o) && (n = p(n));
      r && (n += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(r)))) + " */");
      var s = new Blob([n], {
        type: "text/css"
      }),
          a = t.href;
      t.href = URL.createObjectURL(s), a && URL.revokeObjectURL(a);
    }.bind(null, i, e), r = function r() {
      g(i), i.href && URL.revokeObjectURL(i.href);
    }) : (i = m(e), n = function (t, e) {
      var i = e.css,
          n = e.media;
      n && t.setAttribute("media", n);
      if (t.styleSheet) t.styleSheet.cssText = i;else {
        for (; t.firstChild;) {
          t.removeChild(t.firstChild);
        }

        t.appendChild(document.createTextNode(i));
      }
    }.bind(null, i), r = function r() {
      g(i);
    });

    return n(t), function (e) {
      if (e) {
        if (e.css === t.css && e.media === t.media && e.sourceMap === t.sourceMap) return;
        n(t = e);
      } else r();
    };
  }

  t.exports = function (t, e) {
    if ("undefined" != typeof DEBUG && DEBUG && "object" != (typeof document === "undefined" ? "undefined" : _typeof(document))) throw new Error("The style-loader cannot be used in a non-browser environment");
    (e = e || {}).attrs = "object" == _typeof(e.attrs) ? e.attrs : {}, e.singleton || "boolean" == typeof e.singleton || (e.singleton = s()), e.insertInto || (e.insertInto = "head"), e.insertAt || (e.insertAt = "bottom");
    var i = f(t, e);
    return d(i, e), function (t) {
      for (var n = [], r = 0; r < i.length; r++) {
        var s = i[r];
        (a = o[s.id]).refs--, n.push(a);
      }

      t && d(f(t, e), e);

      for (r = 0; r < n.length; r++) {
        var a;

        if (0 === (a = n[r]).refs) {
          for (var c = 0; c < a.parts.length; c++) {
            a.parts[c]();
          }

          delete o[a.id];
        }
      }
    };
  };

  var b,
      S = (b = [], function (t, e) {
    return b[t] = e, b.filter(Boolean).join("\n");
  });

  function w(t, e, i, n) {
    var r = i ? "" : n.css;
    if (t.styleSheet) t.styleSheet.cssText = S(e, r);else {
      var o = document.createTextNode(r),
          s = t.childNodes;
      s[e] && t.removeChild(s[e]), s.length ? t.insertBefore(o, s[e]) : t.appendChild(o);
    }
  }
}, function (t, e) {
  t.exports = function (t) {
    var e = "undefined" != typeof window && window.location;
    if (!e) throw new Error("fixUrls requires window.location");
    if (!t || "string" != typeof t) return t;
    var i = e.protocol + "//" + e.host,
        n = i + e.pathname.replace(/\/[^\/]*$/, "/");
    return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, function (t, e) {
      var r,
          o = e.trim().replace(/^"(.*)"$/, function (t, e) {
        return e;
      }).replace(/^'(.*)'$/, function (t, e) {
        return e;
      });
      return /^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(o) ? t : (r = 0 === o.indexOf("//") ? o : 0 === o.indexOf("/") ? i + o : n + o.replace(/^\.\//, ""), "url(" + JSON.stringify(r) + ")");
    });
  };
}, function (t, e) {
  !function (t) {
    if (t.support.touch = "ontouchend" in document, t.support.touch) {
      var e,
          i = t.ui.mouse.prototype,
          n = i._mouseInit,
          r = i._mouseDestroy;
      i._touchStart = function (t) {
        !e && this._mouseCapture(t.originalEvent.changedTouches[0]) && (e = !0, this._touchMoved = !1, o(t, "mouseover"), o(t, "mousemove"), o(t, "mousedown"));
      }, i._touchMove = function (t) {
        e && (this._touchMoved = !0, o(t, "mousemove"));
      }, i._touchEnd = function (t) {
        e && (o(t, "mouseup"), o(t, "mouseout"), this._touchMoved || o(t, "click"), e = !1);
      }, i._mouseInit = function () {
        this.element.bind({
          touchstart: t.proxy(this, "_touchStart"),
          touchmove: t.proxy(this, "_touchMove"),
          touchend: t.proxy(this, "_touchEnd")
        }), n.call(this);
      }, i._mouseDestroy = function () {
        this.element.unbind({
          touchstart: t.proxy(this, "_touchStart"),
          touchmove: t.proxy(this, "_touchMove"),
          touchend: t.proxy(this, "_touchEnd")
        }), r.call(this);
      };
    }

    function o(t, e) {
      if (!(t.originalEvent.touches.length > 1)) {
        t.preventDefault();
        var i = t.originalEvent.changedTouches[0],
            n = document.createEvent("MouseEvents");
        n.initMouseEvent(e, !0, !0, window, 1, i.screenX, i.screenY, i.clientX, i.clientY, !1, !1, !1, !1, 0, null), t.target.dispatchEvent(n);
      }
    }
  }(jQuery);
}, function (t, e, i) {
  var n = i(4);
  "string" == typeof n && (n = [[t.i, n, ""]]);
  var r = {
    hmr: !0,
    transform: void 0,
    insertInto: void 0
  };
  i(0)(n, r);
  n.locals && (t.exports = n.locals);
}, function (t, e, i) {}, function (t, e, i) {
  var n = i(6);
  "string" == typeof n && (n = [[t.i, n, ""]]);
  var r = {
    hmr: !0,
    transform: void 0,
    insertInto: void 0
  };
  i(0)(n, r);
  n.locals && (t.exports = n.locals);
}, function (t, e, i) {}, function (t, e, i) {
  var n = i(8);
  "string" == typeof n && (n = [[t.i, n, ""]]);
  var r = {
    hmr: !0,
    transform: void 0,
    insertInto: void 0
  };
  i(0)(n, r);
  n.locals && (t.exports = n.locals);
}, function (t, e, i) {},,,, function (t, e, i) {
  "use strict";

  i.r(e);
  i(2);

  var n = function n(t) {
    return t.split("&").map(function (t) {
      var _t$split = t.split("="),
          _t$split2 = _slicedToArray(_t$split, 2),
          e = _t$split2[0],
          i = _t$split2[1];

      return {
        name: e,
        value: decodeURIComponent(i).replace(/\+/g, " ")
      };
    });
  };

  var r = function r(t) {
    _classCallCheck(this, r);

    this.message = t, this.name = "LocalizationException";
  };

  var o = /*#__PURE__*/function () {
    function o(t, e, i, n, r, _o, s, a, c, u, l) {
      _classCallCheck(this, o);

      this.decimal = t, this.group = e, this.list = i, this.percentSign = n, this.minusSign = r, this.plusSign = _o, this.exponential = s, this.superscriptingExponent = a, this.perMille = c, this.infinity = u, this.nan = l, this.validateData();
    }

    _createClass(o, [{
      key: "getDecimal",
      value: function getDecimal() {
        return this.decimal;
      }
    }, {
      key: "getGroup",
      value: function getGroup() {
        return this.group;
      }
    }, {
      key: "getList",
      value: function getList() {
        return this.list;
      }
    }, {
      key: "getPercentSign",
      value: function getPercentSign() {
        return this.percentSign;
      }
    }, {
      key: "getMinusSign",
      value: function getMinusSign() {
        return this.minusSign;
      }
    }, {
      key: "getPlusSign",
      value: function getPlusSign() {
        return this.plusSign;
      }
    }, {
      key: "getExponential",
      value: function getExponential() {
        return this.exponential;
      }
    }, {
      key: "getSuperscriptingExponent",
      value: function getSuperscriptingExponent() {
        return this.superscriptingExponent;
      }
    }, {
      key: "getPerMille",
      value: function getPerMille() {
        return this.perMille;
      }
    }, {
      key: "getInfinity",
      value: function getInfinity() {
        return this.infinity;
      }
    }, {
      key: "getNan",
      value: function getNan() {
        return this.nan;
      }
    }, {
      key: "validateData",
      value: function validateData() {
        if (!this.decimal || "string" != typeof this.decimal) throw new r("Invalid decimal");
        if (!this.group || "string" != typeof this.group) throw new r("Invalid group");
        if (!this.list || "string" != typeof this.list) throw new r("Invalid symbol list");
        if (!this.percentSign || "string" != typeof this.percentSign) throw new r("Invalid percentSign");
        if (!this.minusSign || "string" != typeof this.minusSign) throw new r("Invalid minusSign");
        if (!this.plusSign || "string" != typeof this.plusSign) throw new r("Invalid plusSign");
        if (!this.exponential || "string" != typeof this.exponential) throw new r("Invalid exponential");
        if (!this.superscriptingExponent || "string" != typeof this.superscriptingExponent) throw new r("Invalid superscriptingExponent");
        if (!this.perMille || "string" != typeof this.perMille) throw new r("Invalid perMille");
        if (!this.infinity || "string" != typeof this.infinity) throw new r("Invalid infinity");
        if (!this.nan || "string" != typeof this.nan) throw new r("Invalid nan");
      }
    }]);

    return o;
  }();

  var s = /*#__PURE__*/function () {
    function s(t, e, i, n, _s2, a, c, u) {
      _classCallCheck(this, s);

      if (this.positivePattern = t, this.negativePattern = e, this.symbol = i, this.maxFractionDigits = n, this.minFractionDigits = n < _s2 ? n : _s2, this.groupingUsed = a, this.primaryGroupSize = c, this.secondaryGroupSize = u, !this.positivePattern || "string" != typeof this.positivePattern) throw new r("Invalid positivePattern");
      if (!this.negativePattern || "string" != typeof this.negativePattern) throw new r("Invalid negativePattern");
      if (!(this.symbol && this.symbol instanceof o)) throw new r("Invalid symbol");
      if ("number" != typeof this.maxFractionDigits) throw new r("Invalid maxFractionDigits");
      if ("number" != typeof this.minFractionDigits) throw new r("Invalid minFractionDigits");
      if ("boolean" != typeof this.groupingUsed) throw new r("Invalid groupingUsed");
      if ("number" != typeof this.primaryGroupSize) throw new r("Invalid primaryGroupSize");
      if ("number" != typeof this.secondaryGroupSize) throw new r("Invalid secondaryGroupSize");
    }

    _createClass(s, [{
      key: "getSymbol",
      value: function getSymbol() {
        return this.symbol;
      }
    }, {
      key: "getPositivePattern",
      value: function getPositivePattern() {
        return this.positivePattern;
      }
    }, {
      key: "getNegativePattern",
      value: function getNegativePattern() {
        return this.negativePattern;
      }
    }, {
      key: "getMaxFractionDigits",
      value: function getMaxFractionDigits() {
        return this.maxFractionDigits;
      }
    }, {
      key: "getMinFractionDigits",
      value: function getMinFractionDigits() {
        return this.minFractionDigits;
      }
    }, {
      key: "isGroupingUsed",
      value: function isGroupingUsed() {
        return this.groupingUsed;
      }
    }, {
      key: "getPrimaryGroupSize",
      value: function getPrimaryGroupSize() {
        return this.primaryGroupSize;
      }
    }, {
      key: "getSecondaryGroupSize",
      value: function getSecondaryGroupSize() {
        return this.secondaryGroupSize;
      }
    }]);

    return s;
  }();

  var a = "symbol";

  var c = /*#__PURE__*/function (_s3) {
    _inherits(c, _s3);

    var _super = _createSuper(c);

    function c(t, e, i, n, o, s, a, _c, u, l) {
      var _this;

      _classCallCheck(this, c);

      if (_this = _super.call(this, t, e, i, n, o, s, a, _c), _this.currencySymbol = u, _this.currencyCode = l, !_this.currencySymbol || "string" != typeof _this.currencySymbol) throw new r("Invalid currencySymbol");
      if (!_this.currencyCode || "string" != typeof _this.currencyCode) throw new r("Invalid currencyCode");
      return _possibleConstructorReturn(_this);
    }

    _createClass(c, [{
      key: "getCurrencySymbol",
      value: function getCurrencySymbol() {
        return this.currencySymbol;
      }
    }, {
      key: "getCurrencyCode",
      value: function getCurrencyCode() {
        return this.currencyCode;
      }
    }], [{
      key: "getCurrencyDisplay",
      value: function getCurrencyDisplay() {
        return a;
      }
    }]);

    return c;
  }(s);

  var u = "¤",
      l = ".",
      p = ",",
      d = "-",
      f = "%",
      h = "+";

  var g = /*#__PURE__*/function () {
    function g(t) {
      _classCallCheck(this, g);

      this.numberSpecification = t;
    }

    _createClass(g, [{
      key: "format",
      value: function format(t, e) {
        void 0 !== e && (this.numberSpecification = e);
        var i = Math.abs(t).toFixed(this.numberSpecification.getMaxFractionDigits());

        var _this$extractMajorMin = this.extractMajorMinorDigits(i),
            _this$extractMajorMin2 = _slicedToArray(_this$extractMajorMin, 2),
            n = _this$extractMajorMin2[0],
            r = _this$extractMajorMin2[1],
            o = n = this.splitMajorGroups(n);

        (r = this.adjustMinorDigitsZeroes(r)) && (o += l + r);
        var s = this.getCldrPattern(n < 0);
        return o = this.addPlaceholders(o, s), o = this.replaceSymbols(o), o = this.performSpecificReplacements(o);
      }
    }, {
      key: "extractMajorMinorDigits",
      value: function extractMajorMinorDigits(t) {
        var e = t.toString().split(".");
        return [e[0], void 0 === e[1] ? "" : e[1]];
      }
    }, {
      key: "splitMajorGroups",
      value: function splitMajorGroups(t) {
        if (!this.numberSpecification.isGroupingUsed()) return t;
        var e = t.split("").reverse();
        var i = [];

        for (i.push(e.splice(0, this.numberSpecification.getPrimaryGroupSize())); e.length;) {
          i.push(e.splice(0, this.numberSpecification.getSecondaryGroupSize()));
        }

        i = i.reverse();
        var n = [];
        return i.forEach(function (t) {
          n.push(t.reverse().join(""));
        }), n.join(p);
      }
    }, {
      key: "adjustMinorDigitsZeroes",
      value: function adjustMinorDigitsZeroes(t) {
        var e = t;
        return e.length > this.numberSpecification.getMaxFractionDigits() && (e = e.replace(/0+$/, "")), e.length < this.numberSpecification.getMinFractionDigits() && (e = e.padEnd(this.numberSpecification.getMinFractionDigits(), "0")), e;
      }
    }, {
      key: "getCldrPattern",
      value: function getCldrPattern(t) {
        return t ? this.numberSpecification.getNegativePattern() : this.numberSpecification.getPositivePattern();
      }
    }, {
      key: "replaceSymbols",
      value: function replaceSymbols(t) {
        var e = this.numberSpecification.getSymbol();
        var i = t;
        return i = (i = (i = (i = (i = i.split(l).join(e.getDecimal())).split(p).join(e.getGroup())).split(d).join(e.getMinusSign())).split(f).join(e.getPercentSign())).split(h).join(e.getPlusSign());
      }
    }, {
      key: "addPlaceholders",
      value: function addPlaceholders(t, e) {
        return e.replace(/#?(,#+)*0(\.[0#]+)*/, t);
      }
    }, {
      key: "performSpecificReplacements",
      value: function performSpecificReplacements(t) {
        return this.numberSpecification instanceof c ? t.split(u).join(this.numberSpecification.getCurrencySymbol()) : t;
      }
    }], [{
      key: "build",
      value: function build(t) {
        var e = _construct(o, _toConsumableArray(t.symbol));

        var i;
        return i = t.currencySymbol ? new c(t.positivePattern, t.negativePattern, e, parseInt(t.maxFractionDigits, 10), parseInt(t.minFractionDigits, 10), t.groupingUsed, t.primaryGroupSize, t.secondaryGroupSize, t.currencySymbol, t.currencyCode) : new s(t.positivePattern, t.negativePattern, e, parseInt(t.maxFractionDigits, 10), parseInt(t.minFractionDigits, 10), t.groupingUsed, t.primaryGroupSize, t.secondaryGroupSize), new g(i);
      }
    }]);

    return g;
  }();

  var m = g;

  var v = {},
      y = function y(t, e, i, n) {
    void 0 === v[t] ? e.text(e.text().replace(/([^\d]*)(?:[\d .,]+)([^\d]+)(?:[\d .,]+)(.*)/, "$1".concat(i, "$2").concat(n, "$3"))) : e.text("".concat(v[t].format(i), " - ").concat(v[t].format(n)));
  };

  var b = function b() {
    $(".faceted-slider").each(function () {
      var t = $(this),
          e = t.data("slider-values"),
          i = t.data("slider-specifications");
      null != i && (v[t.data("slider-id")] = m.build(i)), y(t.data("slider-id"), $("#facet_label_".concat(t.data("slider-id"))), null === e ? t.data("slider-min") : e[0], null === e ? t.data("slider-max") : e[1]), $("#slider-range_".concat(t.data("slider-id"))).slider({
        range: !0,
        min: t.data("slider-min"),
        max: t.data("slider-max"),
        values: [null === e ? t.data("slider-min") : e[0], null === e ? t.data("slider-max") : e[1]],
        stop: function stop(e, i) {
          var r = t.data("slider-encoded-url").split("?");
          var o = [];
          r.length > 1 && (o = n(r[1]));
          var s = !1;
          o.forEach(function (t) {
            "q" === t.name && (s = !0);
          }), s || o.push({
            name: "q",
            value: ""
          }), o.forEach(function (e) {
            "q" === e.name && (e.value += [e.value.length > 0 ? "/" : "", t.data("slider-label"), "-", t.data("slider-unit"), "-", i.values[0], "-", i.values[1]].join(""));
          });
          var a = [r[0], "?", $.param(o)].join("");
          prestashop.emit("updateFacets", a);
        },
        slide: function slide(e, i) {
          y(t.data("slider-id"), $("#facet_label_".concat(t.data("slider-id"))), i.values[0], i.values[1]);
        }
      });
    });
  };

  i(3);
  var S = '<div class="faceted-overlay">\n<div class="overlay__inner">\n<div class="overlay__content"><span class="spinner"></span></div>\n</div>\n</div>';
  $(document).ready(function () {
    prestashop.on("updateProductList", function () {
      setTimeout(function () {
        b();
      }, 200);
    }), b();
  });
  i(5), i(7);
}]);