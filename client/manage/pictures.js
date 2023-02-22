const main = document.getElementById('main-picture');
const deleteButtons = document.getElementsByClassName('delete');
const previewButtons = document.getElementsByClassName('preview');
const placeholder = document.getElementById('placeholder');

for (let i = 0; i < deleteButtons.length; i += 1) {
	deleteButtons[i].addEventListener('click', (event) => {
		const data = {};
		data["id"] = event.target.parentNode.parentNode.childNodes[1].textContent;
		
		if (confirm("Сигурни ли сте?")) sendRequest('../../server/manage/deletePicture.php', { method: 'POST', data: `data=${JSON.stringify(data)}` }, load, handleErrors);
	});
}

for (let i = 0; i < previewButtons.length; i += 1) {
	previewButtons[i].addEventListener('click', (event) => {
		let x_start = event.target.parentNode.parentNode.childNodes[7].textContent;
		let x_end = event.target.parentNode.parentNode.childNodes[9].textContent;
		let y_start = event.target.parentNode.parentNode.childNodes[11].textContent;
		let y_end = event.target.parentNode.parentNode.childNodes[13].textContent;
		
		let x_start_val = parseInt(x_start);
		let x_end_val = parseInt(x_end);
		let y_start_val = parseInt(y_start);
		let y_end_val = parseInt(y_end);
		
		placeholder.style.left = (x_start_val + parseInt(window.getComputedStyle(main, null).getPropertyValue('padding-left').replace(/px$/, ''))).toString() + "px";
		placeholder.style.top = (y_start_val + parseInt(window.getComputedStyle(main, null).getPropertyValue('padding-top').replace(/px$/, ''))).toString() + "px";
		placeholder.style.width = (x_end_val - x_start_val).toString() + "px";
		placeholder.style.height = (y_end_val - y_start_val).toString() + "px";
		placeholder.style.boxShadow = "0px 0px 25px Red, 0px 0px 10px DarkRed";
		placeholder.style["z-index"] = 1;
	});
}

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