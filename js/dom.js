window.$ = function $ (query, where = document) { return where.querySelectorAll(query); }
window.$$ = function $$ (query, where = document) { return where.querySelector(query); }

function setBoolAttribute (el, attr, val) {
	if (val) {
		el.setAttribute(attr, '');
	} else {
		el.removeAttribute(attr);
	}
}
