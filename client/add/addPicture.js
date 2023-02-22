const main = document.getElementById('main-picture');
const grid = document.getElementById('grid-picture');
const overlay = document.getElementById('overlay-picture');
const gridButton = document.getElementById('grid-control');
const overlayButton = document.getElementById('overlay-control');
const placeholder = document.getElementById('placeholder');
const placeholderLink = document.getElementById('placeholder-link');
const previewButton = document.getElementById('preview');
const submitButton = document.getElementById('submit');
var showGrid = 1;
var showOverlay = 0;

gridButton.style.color = "white";
overlayButton.style.color = "white";
gridButton.style["background-color"] = "green";
overlayButton.style["background-color"] = "red";
overlay.style.visibility = "hidden";
overlay.style.opacity = 0.75;

var reader = new FileReader();

var ind = 0;

document.getElementById('new-picture').onchange = function (event) {
	reader.readAsDataURL(event.target.files[0]);
}

grid.addEventListener ('click', (event) => {
	event.preventDefault();

	var mainCoords = grid.getBoundingClientRect();
	var posX = Math.round(event.clientX - mainCoords.left);
	var posY = Math.round(event.clientY - mainCoords.top);
	var sectionX = Math.floor(posX / selectRectangleSize.width);
	var sectionY = Math.floor(posY / selectRectangleSize.height);
	
	const x_start = document.getElementById('x-start');
	const x_end = document.getElementById('x-end');
	const y_start = document.getElementById('y-start');
	const y_end = document.getElementById('y-end');
	
	x_start.value = sectionX * selectRectangleSize.width;
	x_end.value = Math.min((sectionX  + 1) * selectRectangleSize.width, 1000);
	y_start.value = sectionY * selectRectangleSize.height;
	y_end.value = Math.min((sectionY  + 1) * selectRectangleSize.height, 1000);
});

gridButton.addEventListener ('click', (event) => {
	event.preventDefault();
	
	if (showGrid) {
		showGrid = 0;
		grid.style.visibility = "hidden";
		gridButton.style["background-color"] = "red";
	} else {
		showGrid = 1;
		grid.style.visibility = "visible";
		gridButton.style["background-color"] = "green";
	}
});

overlayButton.addEventListener ('click', (event) => {
	event.preventDefault();
	
	if (showOverlay) {
		showOverlay = 0;
		overlay.style.visibility = "hidden";
		overlayButton.style["background-color"] = "red";
	} else {
		showOverlay = 1;
		overlay.style.visibility = "visible";
		overlayButton.style["background-color"] = "green";
	}
});

previewButton.addEventListener ('click', (event) => {
	event.preventDefault();
	
	var response = [];
	response["errors"] = [];
	
	const x_start = document.getElementById('x-start').value;
	const x_end = document.getElementById('x-end').value;
	const y_start = document.getElementById('y-start').value;
	const y_end = document.getElementById('y-end').value;
	const href = document.getElementById('link').value;
	const linktext = document.getElementById('text').value;
	const picture = document.getElementById('new-picture');
	
	if (x_start === "" || x_end === "" || y_start === "" || y_end === "" || picture.value == "") {
		response.errors["general"] = "Попълнете всички задължителни полета";
		handleErrors(response);
		return;
	}
	const x_start_val = parseInt(x_start);
	const x_end_val = parseInt(x_end);
	const y_start_val = parseInt(y_start);
	const y_end_val = parseInt(y_end);
	
	if (x_start_val < 0 || x_end_val < 0 || y_start_val < 0 || y_end_val < 0 ||
		x_start_val > 1000 || x_end_val > 1000 || y_start_val > 1000 || y_end_val > 1000 ||
		x_start_val >= x_end_val || y_start_val >= y_end_val) {
		response.errors["general"] = "Невалидни координати";
		handleErrors(response);
		return;
	}
	
	if (href == "") {
		placeholderLink.href="javascript: void(0)";
		placeholderLink.target = "_self";
		placeholder.title = linktext;
	} else {
		placeholderLink.href = href;
		placeholderLink.target = "_blank";
		placeholder.title = linktext;
	}
	
	placeholder.style.left = (x_start_val + parseInt(window.getComputedStyle(main, null).getPropertyValue('padding-left').replace(/px$/, ''))).toString() + "px";
	placeholder.style.top = (y_start_val + parseInt(window.getComputedStyle(main, null).getPropertyValue('padding-top').replace(/px$/, ''))).toString() + "px";
	placeholder.style.width = (x_end_val - x_start_val).toString() + "px";
	placeholder.style.height = (y_end_val - y_start_val).toString() + "px";
	placeholder.style.boxShadow = "0px 0px 25px Green, 0px 0px 10px DarkGreen";	
	
	var dataURL;
	const canvas = document.createElement("CANVAS"); 
	const ctx = canvas.getContext("2d");
	canvas.width = x_end_val - x_start_val;
	canvas.height = y_end_val - y_start_val;
	
	const sourceImage = new Image();
	sourceImage.src = reader.result;
	sourceImage.addEventListener('load', drawPlaceholder);
	
	const addCost = document.getElementById('add-cost');
	addCost.textContent = "За добавянето на тази картина ще са нужни " + ((x_end_val - x_start_val) * (y_end_val - y_start_val)).toString() + " точки";
	
	function drawPlaceholder() {
		ctx.imageSmoothingEnabled = false;
		ctx.drawImage(sourceImage, 0, 0, sourceImage.width, sourceImage.height, 0, 0, x_end_val - x_start_val, y_end_val - y_start_val);
		dataURL = canvas.toDataURL();
		placeholder.src = dataURL;
	}
	
	handleErrors(response);
});

submitButton.addEventListener ('click', (event) => {
	event.preventDefault();
	
	var response = [];
	response["errors"] = [];
	
	const x_start = document.getElementById('x-start').value;
	const x_end = document.getElementById('x-end').value;
	const y_start = document.getElementById('y-start').value;
	const y_end = document.getElementById('y-end').value;
	const href = document.getElementById('link').value;
	const linktext = document.getElementById('text').value;
	const picture = document.getElementById('new-picture');
	
	if (x_start === "" || x_end === "" || y_start === "" || y_end === "" || picture.value == "") {
		response.errors["general"] = "Попълнете всички задължителни полета";
		handleErrors(response);
		return;
	}
	const x_start_val = parseInt(x_start);
	const x_end_val = parseInt(x_end);
	const y_start_val = parseInt(y_start);
	const y_end_val = parseInt(y_end);
	
	if (x_start_val < 0 || x_end_val < 0 || y_start_val < 0 || y_end_val < 0 ||
		x_start_val > 1000 || x_end_val > 1000 || y_start_val > 1000 || y_end_val > 1000 ||
		x_start_val >= x_end_val || y_start_val >= y_end_val) {
		response.errors["general"] = "Невалидни координати";
		handleErrors(response);
		return;
	}
	
	var dataURL;
	const canvas = document.createElement("CANVAS"); 
	const ctx = canvas.getContext("2d");
	canvas.width = x_end_val - x_start_val;
	canvas.height = y_end_val - y_start_val;
	
	const sourceImage = new Image();
	sourceImage.src = reader.result;
	sourceImage.addEventListener('load', sendPicture);
	
	function sendPicture() {
		ctx.imageSmoothingEnabled = false;
		ctx.drawImage(sourceImage, 0, 0, sourceImage.width, sourceImage.height, 0, 0, x_end_val - x_start_val, y_end_val - y_start_val);
		dataURL = canvas.toDataURL("image/png");
		
		const data = {};
		data["x_start"] = x_start_val;
		data["x_end"] = x_end_val;
		data["y_start"] = y_start_val;
		data["y_end"] = y_end_val;
		data["image"] = dataURL;
		data["link"] = href;
		data["text"] = linktext;
		
		sendRequest('../../server/add/addPicture.php', { method: 'POST', data: `data=${JSON.stringify(data)}` }, load, handleErrors);
	}
});

function load(response) {
    location.reload();
}

function handleErrors(response) {
	const errors = response["errors"];
	const errorMessages = document.getElementsByClassName("error");
	for (let key in errorMessages) {
		errorMessages[key].textContent="";
	}
	
	for (let error in errors) {
		let error_id = error + "_error";
		document.getElementById(error_id).textContent = errors[error];
	}
}