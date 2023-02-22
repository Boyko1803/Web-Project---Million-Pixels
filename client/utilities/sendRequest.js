function sendRequest(url, options, successCallback, errorCallback) { 
	var request = new XMLHttpRequest();

	request.onload = function () {
		var response = [];
		if (request.responseText != "") response = JSON.parse(request.responseText);
		else request["errors"] = [];

		if (request.status === 200) {
			successCallback(response);
		} else {
			errorCallback(response);
		}
	}

	request.open(options.method, url, true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send(options.data);
}