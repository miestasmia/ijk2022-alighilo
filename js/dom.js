window.$ = function $ (query, where = document) { return where.querySelectorAll(query); }
window.$$ = function $$ (query, where = document) { return where.querySelector(query); }
