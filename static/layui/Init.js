window.rootPath = (function (src) {
	src = document.currentScript
		? document.currentScript.src
		: document.scripts[document.scripts.length - 1].src;
	return src.substring(0, src.lastIndexOf("/") + 1);
})();
if (typeof $ == "undefined") {
	window.jQuery = layui.jquery;
	window.$ = layui.jquery;
}
if (typeof moduleInit == "undefined") {
	window.moduleInit = [];
}
var module = {};
if (moduleInit.length > 0) {
	for (var i = 0; i < moduleInit.length; i++) {
		module[moduleInit[i]] = moduleInit[i];
	}
}
layui.config({
	base: rootPath + "module/",
	version: "2.9.16"
}).extend(module).use(moduleInit, function () {
	if (typeof gouguInit === 'function') {
		gouguInit();
	}
});



/**
 * 统一API接口调用方法
 * @param {string} url - API接口地址
 * @param {string} method - 请求方法（GET, POST等）
 * @param {object} [data] - 发送到服务器的数据
 * @param {function} [success] - 请求成功时的回调函数
 * @param {function} [error] - 请求失败时的回调函数
 */
function apiCall(url, method, data, success, error) {
	method = method || 'GET';
	data = data || {};

	if (!error) {
		error = function (jqXHR, textStatus, errorThrown) {
			layer.msg('请求失败: ' + textStatus, {icon: 5});
		};
	}
	$.ajax({
		url: url,
		type: method,
		data: data,
		success: function (response) {
			if (response && response.code === 1) {
				if (typeof success === 'function') {
					success(response);
				}
			} else {
				layer.msg((response.msg || '未知错误'));
			}
		},
		error: error
	});
}


