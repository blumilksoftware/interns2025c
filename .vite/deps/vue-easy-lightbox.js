import {
  Fragment,
  Teleport,
  Transition,
  computed,
  createVNode,
  defineComponent,
  isRef,
  isVNode,
  nextTick,
  onBeforeUnmount,
  onMounted,
  reactive,
  ref,
  watch,
  withModifiers
} from "./chunk-Y7TKRIWE.js";
import "./chunk-G3PMV62Z.js";

// node_modules/vue-easy-lightbox/dist/vue-easy-lightbox.esm.min.js
function m(e, t) {
  void 0 === t && (t = {});
  var o = t.insertAt;
  if (e && "undefined" != typeof document) {
    var l = document.head || document.getElementsByTagName("head")[0], n = document.createElement("style");
    n.type = "text/css", "top" === o && l.firstChild ? l.insertBefore(n, l.firstChild) : l.appendChild(n), n.styleSheet ? n.styleSheet.cssText = e : n.appendChild(document.createTextNode(e));
  }
}
m(".vel-fade-enter-active,.vel-fade-leave-active{-webkit-transition:all .3s ease;transition:all .3s ease}.vel-fade-enter-from,.vel-fade-leave-to{opacity:0}.vel-img-swiper{display:block;position:relative}.vel-modal{background:rgba(0,0,0,.5);bottom:0;left:0;margin:0;position:fixed;right:0;top:0;z-index:9998}.vel-img-wrapper{left:50%;margin:0;position:absolute;top:50%;-webkit-transform:translate(-50% -50%);transform:translate(-50% -50%);-webkit-transition:.3s linear;transition:.3s linear;will-change:transform opacity}.vel-img,.vel-img-wrapper{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.vel-img{background-color:rgba(0,0,0,.7);-webkit-box-shadow:0 5px 20px 2px rgba(0,0,0,.7);box-shadow:0 5px 20px 2px rgba(0,0,0,.7);display:block;max-height:80vh;max-width:80vw;position:relative;-webkit-transition:-webkit-transform .3s ease-in-out;transition:-webkit-transform .3s ease-in-out;transition:transform .3s ease-in-out;transition:transform .3s ease-in-out,-webkit-transform .3s ease-in-out}@media (max-width:750px){.vel-img{max-height:95vh;max-width:85vw}}.vel-btns-wrapper{position:static}.vel-btns-wrapper .btn__close,.vel-btns-wrapper .btn__next,.vel-btns-wrapper .btn__prev{-webkit-tap-highlight-color:transparent;color:#fff;cursor:pointer;font-size:32px;opacity:.6;outline:none;position:absolute;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);-webkit-transition:.15s linear;transition:.15s linear;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.vel-btns-wrapper .btn__close:hover,.vel-btns-wrapper .btn__next:hover,.vel-btns-wrapper .btn__prev:hover{opacity:1}.vel-btns-wrapper .btn__close.disable,.vel-btns-wrapper .btn__close.disable:hover,.vel-btns-wrapper .btn__next.disable,.vel-btns-wrapper .btn__next.disable:hover,.vel-btns-wrapper .btn__prev.disable,.vel-btns-wrapper .btn__prev.disable:hover{cursor:default;opacity:.2}.vel-btns-wrapper .btn__next{right:12px}.vel-btns-wrapper .btn__prev{left:12px}.vel-btns-wrapper .btn__close{right:10px;top:24px}@media (max-width:750px){.vel-btns-wrapper .btn__next,.vel-btns-wrapper .btn__prev{font-size:20px}.vel-btns-wrapper .btn__close{font-size:24px}.vel-btns-wrapper .btn__next{right:4px}.vel-btns-wrapper .btn__prev{left:4px}}.vel-modal.is-rtl .vel-btns-wrapper .btn__next{left:12px;right:auto}.vel-modal.is-rtl .vel-btns-wrapper .btn__prev{left:auto;right:12px}@media (max-width:750px){.vel-modal.is-rtl .vel-btns-wrapper .btn__next{left:4px;right:auto}.vel-modal.is-rtl .vel-btns-wrapper .btn__prev{left:auto;right:4px}}.vel-modal.is-rtl .vel-img-title{direction:rtl}");
m('.vel-loading{left:50%;position:absolute;top:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}.vel-loading .ring{display:inline-block;height:64px;width:64px}.vel-loading .ring:after{-webkit-animation:ring 1.2s linear infinite;animation:ring 1.2s linear infinite;border-color:hsla(0,0%,100%,.7) transparent;border-radius:50%;border-style:solid;border-width:5px;content:" ";display:block;height:46px;margin:1px;width:46px}@-webkit-keyframes ring{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@keyframes ring{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}');
m(".vel-on-error{left:50%;position:absolute;top:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}.vel-on-error .icon{color:#aaa;font-size:80px}");
m(".vel-img-title{bottom:60px;color:#ccc;cursor:default;font-size:12px;left:50%;line-height:1;max-width:80%;opacity:.8;overflow:hidden;position:absolute;text-align:center;text-overflow:ellipsis;-webkit-transform:translate(-50%);transform:translate(-50%);-webkit-transition:opacity .15s;transition:opacity .15s;white-space:nowrap}.vel-img-title:hover{opacity:1}");
m(".vel-icon{fill:currentColor;height:1em;overflow:hidden;vertical-align:-.15em;width:1em}");
m(".vel-toolbar{border-radius:4px;bottom:8px;display:-webkit-box;display:-ms-flexbox;display:flex;left:50%;opacity:.9;overflow:hidden;padding:0;position:absolute;-webkit-transform:translate(-50%);transform:translate(-50%)}.vel-toolbar,.vel-toolbar .toolbar-btn{background-color:#2d2d2d;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.vel-toolbar .toolbar-btn{-ms-flex-negative:0;-webkit-tap-highlight-color:transparent;color:#fff;cursor:pointer;flex-shrink:0;font-size:20px;outline:none;padding:6px 10px}.vel-toolbar .toolbar-btn:active,.vel-toolbar .toolbar-btn:hover{background-color:#3d3d3d}");
var g = "vel";
var f = defineComponent({ name: "SvgIcon", props: { type: { type: String, default: "" } }, setup: (e) => () => createVNode("svg", { class: `${g}-icon icon`, "aria-hidden": "true" }, [createVNode("use", { "xlink:href": `#icon-${e.type}` }, null)]) });
var h = "undefined" != typeof window;
var w = () => {
};
var x = false;
if (h) try {
  const e = {};
  Object.defineProperty(e, "passive", { get() {
    x = true;
  } }), window.addEventListener("test-passive", w, e);
} catch (e) {
}
var y = function(e, t, o) {
  let l = arguments.length > 3 && void 0 !== arguments[3] && arguments[3];
  h && e.addEventListener(t, o, !!x && { capture: false, passive: l });
};
var z = (e, t, o) => {
  h && e.removeEventListener(t, o);
};
var k = (e) => {
  e.preventDefault();
};
var _ = Object.prototype.toString;
var D = (e) => (t) => _.call(t).slice(8, -1) === e;
var M = (e) => !!e && D("Object")(e);
var S = (e) => !!e && D("String")(e);
function L(e) {
  return null != e;
}
var C = defineComponent({ name: "Toolbar", props: { zoomIn: { type: Function, default: w }, zoomOut: { type: Function, default: w }, rotateLeft: { type: Function, default: w }, rotateRight: { type: Function, default: w }, resize: { type: Function, default: w }, rotateDisabled: { type: Boolean, default: false }, zoomDisabled: { type: Boolean, default: false } }, setup: (e) => () => createVNode("div", { class: `${g}-toolbar` }, [!e.zoomDisabled && createVNode(Fragment, null, [createVNode("div", { role: "button", "aria-label": "zoom in button", class: "toolbar-btn toolbar-btn__zoomin", onClick: e.zoomIn }, [createVNode(f, { type: "zoomin" }, null)]), createVNode("div", { role: "button", "aria-label": "zoom out button", class: "toolbar-btn toolbar-btn__zoomout", onClick: e.zoomOut }, [createVNode(f, { type: "zoomout" }, null)])]), createVNode("div", { role: "button", "aria-label": "resize image button", class: "toolbar-btn toolbar-btn__resize", onClick: e.resize }, [createVNode(f, { type: "resize" }, null)]), !e.rotateDisabled && createVNode(Fragment, null, [createVNode("div", { role: "button", "aria-label": "image rotate left button", class: "toolbar-btn toolbar-btn__rotate", onClick: e.rotateLeft }, [createVNode(f, { type: "rotate-left" }, null)]), createVNode("div", { role: "button", "aria-label": "image rotate right button", class: "toolbar-btn toolbar-btn__rotate", onClick: e.rotateRight }, [createVNode(f, { type: "rotate-right" }, null)])])]) });
var Y = () => createVNode("div", { class: `${g}-loading` }, [createVNode("div", { class: "ring" }, null)]);
var B = () => createVNode("div", { class: `${g}-on-error` }, [createVNode("div", { class: "ring" }, null), createVNode(f, { type: "img-broken" }, null)]);
var X = (e, o) => {
  let { slots: l } = o;
  return createVNode("div", { class: `${g}-img-title` }, [l.default ? l.default() : ""]);
};
var E = defineComponent({ name: "DefaultIcons", setup: () => () => createVNode("svg", { "aria-hidden": true, style: "position: absolute; width: 0; height: 0; overflow: hidden; visibility: hidden;" }, [createVNode("symbol", { id: "icon-rotate-right", viewBox: "0 0 1024 1024" }, [createVNode("path", { d: "M275.199914 450.496179v20.031994c0.384-38.079988 12.543996-67.423979 36.479989-87.967973 22.431993-20.351994 49.215985-30.55999 80.319975-30.55999 32.06399 0 59.295981 10.175997 81.759974 30.55999 22.815993 20.543994 34.591989 49.887984 35.359989 87.967973v123.935961c-0.768 37.887988-12.543996 67.135979-35.359989 87.679973-22.431993 20.351994-49.695984 30.75199-81.759974 31.10399a120.255962 120.255962 0 0 1-72.991978-24.895992c-21.503993-15.839995-35.359989-38.751988-41.567987-68.735979h60.831981c9.247997 23.007993 27.167992 34.495989 53.759983 34.49599 37.535988-0.384 56.863982-21.407993 57.983982-63.071981v-38.751988c-28.095991 8.863997-54.303983 13.119996-78.623975 12.735996a91.263971 91.263971 0 0 1-68.447979-27.711991c-18.847994-18.303994-28.095991-47.231985-27.711991-86.847973z m62.55998 24.863992c7.103998 24.799992 25.215992 37.343988 54.271983 37.663989 27.103992-0.288 44.703986-11.327996 52.831984-33.11999 3.135999-8.383997 2.655999-29.599991-1.28-38.559988-8.607997-19.615994-25.791992-29.695991-51.551984-30.20799-28.383991 0.576-46.303986 12.639996-53.759983 36.159988a58.719982 58.719982 0 0 0-0.512 28.063991z m390.335878 115.711964v-116.895963c-1.12-41.311987-20.447994-62.335981-57.983981-63.07198-37.727988 0.768-56.959982 21.791993-57.695982 63.07198v116.895963c0.768 41.663987 19.999994 62.68798 57.695982 63.071981 37.535988-0.384 56.863982-21.407993 57.983981-63.071981z m-174.815945 3.391999v-123.935961c0.384-38.079988 12.543996-67.423979 36.479989-87.967973 22.431993-20.351994 49.215985-30.55999 80.319975-30.55999 32.06399 0 59.295981 10.175997 81.759974 30.55999 22.815993 20.543994 34.591989 49.887984 35.359989 87.967973v123.935961c-0.768 37.887988-12.543996 67.135979-35.359989 87.679973-22.431993 20.351994-49.695984 30.75199-81.759974 31.10399-31.10399-0.384-57.887982-10.751997-80.319975-31.10399-23.935993-20.543994-36.127989-49.791984-36.479989-87.679973z m282.559912-479.07185A509.887841 509.887841 0 0 0 511.99984 0.00032C229.215928 0.00032 0 229.216248 0 512.00016s229.215928 511.99984 511.99984 511.99984 511.99984-229.215928 511.99984-511.99984c0-3.743999-0.032-7.455998-0.128-11.167997-1.631999-11.295996-8.159997-27.103992-31.87199-27.103991-27.487991 0-31.67999 21.247993-32.03199 32.06399l0.032 4.127999a30.62399 30.62399 0 0 0 0.16 2.079999H959.9997c0 247.423923-200.575937 447.99986-447.99986 447.99986S63.99998 759.424083 63.99998 512.00016 264.575917 64.0003 511.99984 64.0003a446.079861 446.079861 0 0 1 277.439913 96.22397l-94.91197 91.679971c-25.439992 24.607992-17.439995 44.991986 17.887994 45.599986l188.031942 3.295999a64.31998 64.31998 0 0 0 65.055979-62.84798l3.295999-188.127942C969.407697 15.040315 949.311703 5.792318 923.871711 30.368311l-87.999972 85.023973z", fill: "" }, null)]), createVNode("symbol", { id: "icon-rotate-left", viewBox: "0 0 1024 1024" }, [createVNode("path", { d: "M275.199914 450.496179v20.031994c0.384-38.079988 12.543996-67.423979 36.479989-87.967973 22.431993-20.351994 49.215985-30.55999 80.319975-30.55999 32.06399 0 59.295981 10.175997 81.759974 30.55999 22.815993 20.543994 34.591989 49.887984 35.359989 87.967973v123.935961c-0.768 37.887988-12.543996 67.135979-35.359989 87.679973-22.431993 20.351994-49.695984 30.75199-81.759974 31.10399a120.255962 120.255962 0 0 1-72.991978-24.895992c-21.503993-15.839995-35.359989-38.751988-41.567987-68.735979h60.831981c9.247997 23.007993 27.167992 34.495989 53.759983 34.49599 37.535988-0.384 56.863982-21.407993 57.983982-63.071981v-38.751988c-28.095991 8.863997-54.303983 13.119996-78.623975 12.735996a91.263971 91.263971 0 0 1-68.447979-27.711991c-18.847994-18.303994-28.095991-47.231985-27.711991-86.847973z m62.55998 24.863992c7.103998 24.799992 25.215992 37.343988 54.271983 37.663989 27.103992-0.288 44.703986-11.327996 52.831984-33.11999 3.135999-8.383997 2.655999-29.599991-1.28-38.559988-8.607997-19.615994-25.791992-29.695991-51.551984-30.20799-28.383991 0.576-46.303986 12.639996-53.759983 36.159988a58.719982 58.719982 0 0 0-0.512 28.063991z m390.335878 115.711964v-116.895963c-1.12-41.311987-20.447994-62.335981-57.983981-63.07198-37.727988 0.768-56.959982 21.791993-57.695982 63.07198v116.895963c0.768 41.663987 19.999994 62.68798 57.695982 63.071981 37.535988-0.384 56.863982-21.407993 57.983981-63.071981z m-174.815945 3.391999v-123.935961c0.384-38.079988 12.543996-67.423979 36.479989-87.967973 22.431993-20.351994 49.215985-30.55999 80.319975-30.55999 32.06399 0 59.295981 10.175997 81.759974 30.55999 22.815993 20.543994 34.591989 49.887984 35.359989 87.967973v123.935961c-0.768 37.887988-12.543996 67.135979-35.359989 87.679973-22.431993 20.351994-49.695984 30.75199-81.759974 31.10399-31.10399-0.384-57.887982-10.751997-80.319975-31.10399-23.935993-20.543994-36.127989-49.791984-36.479989-87.679973zM188.159941 115.392284A509.887841 509.887841 0 0 1 511.99984 0.00032c282.783912 0 511.99984 229.215928 511.99984 511.99984s-229.215928 511.99984-511.99984 511.99984S0 794.784072 0 512.00016c0-3.743999 0.032-7.455998 0.128-11.167997 1.631999-11.295996 8.159997-27.103992 31.87199-27.103991 27.487991 0 31.67999 21.247993 32.03199 32.06399L63.99998 509.920161a30.62399 30.62399 0 0 1-0.16 2.079999H63.99998c0 247.423923 200.575937 447.99986 447.99986 447.99986s447.99986-200.575937 447.99986-447.99986S759.423763 64.0003 511.99984 64.0003a446.079861 446.079861 0 0 0-277.439913 96.22397l94.91197 91.679971c25.439992 24.607992 17.439995 44.991986-17.887994 45.599986L123.551961 300.800226a64.31998 64.31998 0 0 1-65.055979-62.84798l-3.295999-188.127942C54.591983 15.040315 74.687977 5.792318 100.127969 30.368311l87.999972 85.023973z", fill: "" }, null)]), createVNode("symbol", { id: "icon-resize", viewBox: "0 0 1024 1024" }, [createVNode("path", { d: "M456.036919 791.8108 270.553461 791.8108 460.818829 601.572038l-39.593763-39.567157L231.314785 751.915162l0.873903-183.953615c0-15.465227-12.515035-27.981285-27.981285-27.981285s-27.981285 12.515035-27.981285 27.981285l0 251.829516c0 8.3072 3.415796 14.975063 8.826016 19.564591 5.082762 5.192256 12.132318 8.416693 19.947308 8.416693l251.036453 0c15.46625 0 27.981285-12.514012 27.981285-27.981285C484.018204 804.325835 471.504192 791.8108 456.036919 791.8108zM838.945819 184.644347c-5.082762-5.191232-12.132318-8.416693-19.947308-8.416693L567.961034 176.227654c-15.46625 0-27.981285 12.515035-27.981285 27.981285 0 15.46625 12.514012 27.981285 27.981285 27.981285l185.483458 0L563.206754 422.427962l39.567157 39.567157 189.910281-189.910281-0.873903 183.953615c0 15.46625 12.514012 27.981285 27.981285 27.981285s27.981285-12.514012 27.981285-27.981285L847.772858 204.208938C847.771835 195.902762 844.356039 189.234899 838.945819 184.644347zM847.771835 64.303538 176.227142 64.303538c-61.809741 0-111.924115 50.115398-111.924115 111.924115l0 671.544693c0 61.809741 50.114374 111.924115 111.924115 111.924115l671.544693 0c61.809741 0 111.924115-50.114374 111.924115-111.924115l0-671.544693C959.69595 114.418936 909.581576 64.303538 847.771835 64.303538zM903.733381 847.772346c0 30.878265-25.056676 55.962569-55.962569 55.962569L176.227142 903.734916c-30.90487 0-55.962569-25.084305-55.962569-55.962569l0-671.544693c0-30.9325 25.056676-55.962569 55.962569-55.962569l671.544693 0c30.90487 0 55.962569 25.03007 55.962569 55.962569L903.734404 847.772346z" }, null)]), createVNode("symbol", { id: "icon-img-broken", viewBox: "0 0 1024 1024" }, [createVNode("path", { d: "M810.666667 128H213.333333c-46.933333 0-85.333333 38.4-85.333333 85.333333v597.333334c0 46.933333 38.4 85.333333 85.333333 85.333333h597.333334c46.933333 0 85.333333-38.4 85.333333-85.333333V213.333333c0-46.933333-38.4-85.333333-85.333333-85.333333z m0 682.666667H213.333333v-195.413334l42.24 42.24 170.666667-170.666666 170.666667 170.666666 170.666666-170.24L810.666667 530.346667V810.666667z m0-401.493334l-43.093334-43.093333-170.666666 171.093333-170.666667-170.666666-170.666667 170.666666-42.24-42.666666V213.333333h597.333334v195.84z" }, null)]), createVNode("symbol", { id: "icon-prev", viewBox: "0 0 1024 1024" }, [createVNode("path", { d: "M784.652701 955.6957 346.601985 517.644983c-2.822492-2.822492-2.822492-7.902977 0-11.289967l439.179713-439.179713c6.77398-6.77398 10.725469-16.370452 10.725469-25.966924L796.507166 36.692393c0-20.32194-16.370452-36.692393-36.692393-36.692393l-4.515987 0c-9.596472 0-19.192944 3.951488-25.966924 10.725469L250.072767 489.420066c-12.418964 12.418964-12.418964 32.740904 0 45.159868l477.565601 477.565601c7.338479 7.338479 17.499449 11.854465 28.224917 11.854465l0 0c22.015436 0 40.079383-18.063947 40.079383-40.079383l0 0C796.507166 973.759647 791.99118 963.598677 784.652701 955.6957z" }, null)]), createVNode("symbol", { id: "icon-next", viewBox: "0 0 1024 1024" }, [createVNode("path", { d: "M246.121279 955.6957l438.050717-438.050717c2.822492-2.822492 2.822492-7.902977 0-11.289967L244.992282 67.175303c-6.77398-6.77398-10.725469-16.370452-10.725469-25.966924L234.266814 36.692393C234.266814 16.370452 250.637266 0 270.959206 0l4.515987 0c9.596472 0 19.192944 3.951488 25.966924 10.725469l478.694598 478.694598c12.418964 12.418964 12.418964 32.740904 0 45.159868l-477.565601 477.565601c-7.338479 7.338479-17.499449 11.854465-28.224917 11.854465l0 0c-22.015436 0-40.079383-18.063947-40.079383-40.079383l0 0C234.266814 973.759647 238.7828 963.598677 246.121279 955.6957z" }, null)]), createVNode("symbol", { id: "icon-zoomin", viewBox: "0 0 1024 1024" }, [createVNode("path", { d: "M725.504 652.864c46.4-61.44 71.744-136.448 71.744-218.752C797.248 230.464 632.768 64 430.656 64S64 230.464 64 434.112C64 639.36 228.48 805.76 430.656 805.76c86.656 0 164.48-30.144 227.52-81.088L889.984 960 960 891.264l-234.496-238.4z m-294.848 67.456c-155.776 0-282.624-128.896-282.624-286.208s126.848-286.208 282.624-286.208 282.624 128.896 282.624 286.208-126.912 286.208-282.624 286.208z" }, null), createVNode("path", { d: "M235.712 369.92h390.72v127.104H235.712z" }, null), createVNode("path", { d: "M367.488 238.144h127.104v390.72H367.488z" }, null)]), createVNode("symbol", { id: "icon-close", viewBox: "0 0 1024 1024" }, [createVNode("path", { d: "M570.24 512l259.2 259.2-58.88 58.24L512 570.24l-261.12 261.12-58.24-58.24L453.76 512 194.56 252.8l58.24-58.24L512 453.76l261.12-261.12 58.24 58.24z" }, null)]), createVNode("symbol", { id: "icon-zoomout", viewBox: "0 0 1024 1024" }, [createVNode("path", { d: "M725.504 652.864c46.4-61.44 71.744-136.448 71.744-218.752C797.248 230.464 632.768 64 430.656 64S64 230.464 64 434.112C64 639.36 228.48 805.76 430.656 805.76c86.656 0 164.48-30.144 227.52-81.088L889.984 960 960 891.264l-234.496-238.4z m-294.848 67.456c-155.776 0-282.624-128.896-282.624-286.208s126.848-286.208 282.624-286.208 282.624 128.896 282.624 286.208-126.912 286.208-282.624 286.208z" }, null), createVNode("path", { d: "M235.712 369.92h390.72v127.104H235.712z" }, null)])]) });
var T = h ? window : global;
var $ = Date.now();
function I(e) {
  const t = Date.now(), o = Math.max(0, 16 - (t - $)), l = setTimeout(e, o);
  return $ = t + o, l;
}
function O(e) {
  return (T.requestAnimationFrame || I).call(T, e);
}
function R(e) {
  (T.cancelAnimationFrame || T.clearTimeout).call(T, e);
}
function A(e, t) {
  const o = e.clientX - t.clientX, l = e.clientY - t.clientY;
  return Math.sqrt(o * o + l * l);
}
function j(e) {
  return "function" == typeof e || "[object Object]" === Object.prototype.toString.call(e) && !isVNode(e);
}
var H = defineComponent({ name: "VueEasyLightbox", props: { imgs: { type: [Array, String], default: () => "" }, visible: { type: Boolean, default: false }, index: { type: Number, default: 0 }, scrollDisabled: { type: Boolean, default: true }, escDisabled: { type: Boolean, default: false }, moveDisabled: { type: Boolean, default: false }, titleDisabled: { type: Boolean, default: false }, maskClosable: { type: Boolean, default: true }, teleport: { type: [String, Object], default: null }, swipeTolerance: { type: Number, default: 50 }, loop: { type: Boolean, default: false }, rtl: { type: Boolean, default: false }, zoomScale: { type: Number, default: 0.12 }, maxZoom: { type: Number, default: 3 }, minZoom: { type: Number, default: 0.1 }, rotateDisabled: { type: Boolean, default: false }, zoomDisabled: { type: Boolean, default: false }, pinchDisabled: { type: Boolean, default: false }, dblclickDisabled: { type: Boolean, default: false } }, emits: { hide: () => true, "on-error": (e) => true, "on-prev": (e, t) => true, "on-next": (e, t) => true, "on-prev-click": (e, t) => true, "on-next-click": (e, t) => true, "on-index-change": (e, t) => true, "on-rotate": (e) => true }, setup(e, o) {
  let { emit: p, slots: v } = o;
  const { imgRef: m2, imgState: h2, setImgSize: w2 } = (() => {
    const e2 = ref(), t = reactive({ width: 0, height: 0, maxScale: 1 });
    return { imgRef: e2, imgState: t, setImgSize: () => {
      if (e2.value) {
        const { width: o2, height: l, naturalWidth: n } = e2.value;
        t.maxScale = n / o2, t.width = o2, t.height = l;
      }
    } };
  })(), x2 = ref(e.index), _2 = ref(""), T2 = reactive({ scale: 1, lastScale: 1, rotateDeg: 0, top: 0, left: 0, initX: 0, initY: 0, lastX: 0, lastY: 0, touches: [] }), $2 = reactive({ loadError: false, loading: false, dragging: false, gesturing: false, wheeling: false }), I2 = computed((() => {
    return t = e.imgs, D("Array")(t) ? e.imgs.map(((e2) => "string" == typeof e2 ? { src: e2 } : (function(e3) {
      return M(e3) && S(e3.src);
    })(e2) ? e2 : void 0)).filter(L) : S(e.imgs) ? [{ src: e.imgs }] : [];
    var t;
  })), H2 = computed((() => I2.value[x2.value])), F2 = computed((() => I2.value[x2.value]?.src)), N2 = computed((() => I2.value[x2.value]?.title)), P = computed((() => I2.value[x2.value]?.alt)), V = computed((() => ({ cursor: $2.loadError ? "default" : e.moveDisabled ? $2.dragging ? "grabbing" : "grab" : "move", top: `calc(50% + ${T2.top}px)`, left: `calc(50% + ${T2.left}px)`, transition: $2.dragging || $2.gesturing ? "none" : "", transform: `translate(-50%, -50%) scale(${T2.scale}) rotate(${T2.rotateDeg}deg)` }))), Z = () => {
    p("hide");
  }, q = () => {
    T2.scale = 1, T2.lastScale = 1, T2.rotateDeg = 0, T2.top = 0, T2.left = 0, $2.loadError = false, $2.dragging = false, $2.loading = true;
  }, U = (t, o2) => {
    const l = x2.value;
    q(), x2.value = t, I2.value[x2.value] === I2.value[t] && nextTick((() => {
      $2.loading = false;
    })), e.visible && l !== t && (o2 && o2(l, t), p("on-index-change", l, t));
  }, W = () => {
    const t = x2.value, o2 = e.loop ? (t + 1) % I2.value.length : t + 1;
    !e.loop && o2 > I2.value.length - 1 || U(o2, ((e2, t2) => {
      p("on-next", e2, t2), p("on-next-click", e2, t2);
    }));
  }, G = () => {
    const t = x2.value;
    let o2 = t - 1;
    if (0 === t) {
      if (!e.loop) return;
      o2 = I2.value.length - 1;
    }
    U(o2, ((e2, t2) => {
      p("on-prev", e2, t2), p("on-prev-click", e2, t2);
    }));
  }, J = (e2) => {
    Math.abs(1 - e2) < 0.05 ? e2 = 1 : Math.abs(h2.maxScale - e2) < 0.05 && (e2 = h2.maxScale), T2.lastScale = T2.scale, T2.scale = e2;
  }, K = () => {
    const t = T2.scale + e.zoomScale;
    t < h2.maxScale * e.maxZoom && J(t);
  }, Q = () => {
    const t = T2.scale - e.zoomScale;
    t > e.minZoom && J(t);
  }, ee = () => {
    const e2 = T2.rotateDeg % 360;
    p("on-rotate", Math.abs(e2 < 0 ? e2 + 360 : e2));
  }, te = () => {
    T2.rotateDeg -= 90, ee();
  }, oe = () => {
    T2.rotateDeg += 90, ee();
  }, le = () => {
    T2.scale = 1, T2.top = 0, T2.left = 0;
  }, ne = function() {
    let t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0;
    return !e.moveDisabled && 0 === t;
  }, { onMouseDown: ae, onMouseMove: re, onMouseUp: ie } = /* @__PURE__ */ ((e2, t, o2) => {
    let l, n = false;
    return { onMouseDown: (o3) => {
      e2.initX = e2.lastX = o3.clientX, e2.initY = e2.lastY = o3.clientY, t.dragging = true, n = false, o3.stopPropagation();
    }, onMouseUp: (e3) => {
      o2(e3.button) && R(l), t.dragging = false, n = false;
    }, onMouseMove: (a) => {
      if (t.dragging) if (o2(a.button)) {
        if (n) return;
        n = true, l = O((() => {
          const { top: t2, left: o3, lastY: l2, lastX: r } = e2;
          e2.top = t2 - l2 + a.clientY, e2.left = o3 - r + a.clientX, e2.lastX = a.clientX, e2.lastY = a.clientY, n = false;
        }));
      } else e2.lastX = a.clientX, e2.lastY = a.clientY;
      a.stopPropagation();
    } };
  })(T2, $2, ne), { onTouchStart: se, onTouchMove: ce, onTouchEnd: ue } = /* @__PURE__ */ ((e2, t, o2, l, n) => {
    let a, r = false;
    return { onTouchStart: (e3) => {
      const { touches: l2 } = e3;
      l2.length > 1 && n() ? (o2.gesturing = true, t.touches = l2) : (t.initX = t.lastX = l2[0].clientX, t.initY = t.lastY = l2[0].clientY, o2.dragging = true), e3.stopPropagation();
    }, onTouchMove: (i) => {
      if (r) return;
      const { touches: s } = i, { lastX: c, lastY: u, left: d, top: p2, scale: b } = t;
      if (!o2.gesturing && o2.dragging) {
        if (!s[0]) return;
        const { clientX: e3, clientY: o3 } = s[0];
        l() ? a = O((() => {
          t.lastX = e3, t.lastY = o3, t.top = p2 - u + o3, t.left = d - c + e3, r = false;
        })) : (t.lastX = e3, t.lastY = o3);
      } else o2.gesturing && t.touches.length > 1 && s.length > 1 && n() && (a = O((() => {
        const o3 = (A(t.touches[0], t.touches[1]) - A(s[0], s[1])) / e2.width;
        t.touches = s;
        const l2 = b - 1.3 * o3;
        l2 > 0.5 && l2 < 1.5 * e2.maxScale && (t.scale = l2), r = false;
      })));
    }, onTouchEnd: () => {
      R(a), o2.dragging = false, o2.gesturing = false, r = false;
    } };
  })(h2, T2, $2, ne, (() => !e.pinchDisabled)), de = () => {
    e.dblclickDisabled || (T2.scale !== h2.maxScale ? (T2.lastScale = T2.scale, T2.scale = h2.maxScale) : T2.scale = T2.lastScale);
  }, pe = (t) => {
    $2.loadError || $2.gesturing || $2.loading || $2.dragging || $2.wheeling || !e.scrollDisabled || e.zoomDisabled || ($2.wheeling = true, setTimeout((() => {
      $2.wheeling = false;
    }), 80), t.deltaY < 0 ? K() : Q());
  }, be = (t) => {
    const o2 = t;
    e.visible && (!e.escDisabled && "Escape" === o2.key && e.visible && Z(), "ArrowLeft" === o2.key && (e.rtl ? W() : G()), "ArrowRight" === o2.key && (e.rtl ? G() : W()));
  }, ve = () => {
    e.maskClosable && Z();
  }, me = () => {
    w2();
  }, ge = () => {
    $2.loading = false;
  }, fe = (e2) => {
    $2.loading = false, $2.loadError = true, p("on-error", e2);
  }, he = () => {
    e.visible && w2();
  };
  watch((() => e.index), ((e2) => {
    e2 < 0 || e2 >= I2.value.length || U(e2);
  })), watch((() => $2.dragging), ((t, o2) => {
    const l = !t && o2;
    if (!ne() && l) {
      const t2 = T2.lastX - T2.initX, o3 = T2.lastY - T2.initY, l2 = e.swipeTolerance;
      Math.abs(t2) > Math.abs(o3) && (t2 < -1 * l2 ? W() : t2 > l2 && G());
    }
  })), watch((() => e.visible), ((t) => {
    if (t) {
      q();
      const t2 = I2.value.length;
      if (0 === t2) return x2.value = 0, $2.loading = false, void nextTick((() => $2.loadError = true));
      x2.value = e.index >= t2 ? t2 - 1 : e.index < 0 ? 0 : e.index, e.scrollDisabled && we();
    } else e.scrollDisabled && xe();
  }));
  const we = () => {
    document && (_2.value = document.body.style.overflowY, document.body.style.overflowY = "hidden");
  }, xe = () => {
    document && (document.body.style.overflowY = _2.value);
  };
  onMounted((() => {
    y(document, "keydown", be), y(window, "resize", he);
  })), onBeforeUnmount((() => {
    z(document, "keydown", be), z(window, "resize", he), e.scrollDisabled && xe();
  }));
  const ye = () => $2.loading ? v.loading ? v.loading({ key: "loading" }) : createVNode(Y, { key: "img-loading" }, null) : $2.loadError ? v.onerror ? v.onerror({ key: "onerror" }) : createVNode(B, { key: "img-on-error" }, null) : createVNode("div", { class: `${g}-img-wrapper`, style: V.value, key: "img-wrapper" }, [createVNode("img", { alt: P.value, ref: m2, draggable: "false", class: `${g}-img`, src: F2.value, onMousedown: ae, onMouseup: ie, onMousemove: re, onTouchstart: se, onTouchmove: ce, onTouchend: ue, onLoad: me, onDblclick: de, onDragstart: (e2) => {
    e2.preventDefault();
  } }, null)]), ze = () => {
    if (v["prev-btn"]) return v["prev-btn"]({ prev: G });
    if (I2.value.length <= 1) return;
    const o2 = !e.loop && x2.value <= 0;
    return createVNode("div", { role: "button", "aria-label": "previous image button", class: "btn__prev " + (o2 ? "disable" : ""), onClick: G }, [e.rtl ? createVNode(f, { type: "next" }, null) : createVNode(f, { type: "prev" }, null)]);
  }, ke = () => {
    if (v["next-btn"]) return v["next-btn"]({ next: W });
    if (I2.value.length <= 1) return;
    const o2 = !e.loop && x2.value >= I2.value.length - 1;
    return createVNode("div", { role: "button", "aria-label": "next image button", class: "btn__next " + (o2 ? "disable" : ""), onClick: W }, [e.rtl ? createVNode(f, { type: "prev" }, null) : createVNode(f, { type: "next" }, null)]);
  }, _e = () => {
    if (!(e.titleDisabled || $2.loading || $2.loadError)) return v.title ? v.title({ currentImg: H2.value }) : N2.value ? createVNode(X, null, { default: () => [N2.value] }) : void 0;
  }, De = () => {
    let o2;
    if (e.visible) return createVNode("div", { onTouchmove: k, class: [`${g}-modal`, e.rtl ? "is-rtl" : ""], onClick: withModifiers(ve, ["self"]), onWheel: pe }, [createVNode(E, null, null), createVNode(Transition, { name: `${g}-fade`, mode: "out-in" }, j(o2 = ye()) ? o2 : { default: () => [o2] }), createVNode("img", { style: "display:none;", src: F2.value, onError: fe, onLoad: ge }, null), createVNode("div", { class: `${g}-btns-wrapper` }, [ze(), ke(), _e(), v["close-btn"] ? v["close-btn"]({ close: Z }) : createVNode("div", { role: "button", "aria-label": "close image preview button", class: "btn__close", onClick: Z }, [createVNode(f, { type: "close" }, null)]), v.toolbar ? v.toolbar({ toolbarMethods: { zoomIn: K, zoomOut: Q, rotate: te, rotateLeft: te, rotateRight: oe, resize: le }, zoomIn: K, zoomOut: Q, rotate: te, rotateLeft: te, rotateRight: oe, resize: le }) : createVNode(C, { zoomIn: K, zoomOut: Q, resize: le, rotateLeft: te, rotateRight: oe, rotateDisabled: e.rotateDisabled, zoomDisabled: e.zoomDisabled }, null)])]);
  };
  return () => {
    let o2;
    if (e.teleport) {
      let o3;
      return createVNode(Teleport, { to: e.teleport }, { default: () => [createVNode(Transition, { name: `${g}-fade` }, j(o3 = De()) ? o3 : { default: () => [o3] })] });
    }
    return createVNode(Transition, { name: `${g}-fade` }, j(o2 = De()) ? o2 : { default: () => [o2] });
  };
} });
var F = function() {
  let e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : { imgs: "" };
  const { imgs: t = "", initIndex: o = 0 } = e, n = ref(t), a = ref(o), r = ref(false), i = function() {
    let e2 = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : a.value;
    a.value = e2;
  };
  return { imgsRef: n, indexRef: a, visibleRef: r, show: function() {
    let e2 = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : a.value;
    "number" == typeof e2 ? i(e2) : isRef(e2) && "number" == typeof e2.value ? i(e2.value) : e2 instanceof Event && i(a.value), r.value = true;
  }, onHide: () => {
    r.value = false;
  }, changeIndex: i };
};
var N = Object.assign(H, { install: (e) => {
  e.component(H.name, H);
} });
export {
  N as default,
  F as useEasyLightbox
};
//# sourceMappingURL=vue-easy-lightbox.js.map
