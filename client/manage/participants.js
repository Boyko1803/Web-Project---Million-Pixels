const deleteButtons = document.getElementsByClassName('delete');
const allowButtons = document.getElementsByClassName('allow-to-participate');
const forbidButtons = document.getElementsByClassName('forbid-to-participate');
const addButton = document.getElementById('add-button');
const updateButton = document.getElementById('update-button');

for (let i = 0; i < deleteButtons.length; i += 1) {
	deleteButtons[i].addEventListener('click', (event) => {
		const data = {};
		data["fn"] = event.target.parentNode.parentNode.childNodes[1].textContent;
		
		if (confirm("Сигурни ли сте?")) sendRequest('../../server/manage/deleteParticipant.php', { method: 'POST', data: `data=${JSON.stringify(data)}` }, load, handleErrors);
	});
}

for (let i = 0; i < allowButtons.length; i += 1) {
	allowButtons[i].addEventListener('click', (event) => {
		const data = {};
		data["fn"] = event.target.parentNode.parentNode.childNodes[1].textContent;
		data["forbidden_to_participate"] = 0;
		
		sendRequest('../../server/manage/changeAllowanceOfParticipant.php', { method: 'POST', data: `data=${JSON.stringify(data)}` }, load, handleErrors);
	});
}

for (let i = 0; i < forbidButtons.length; i += 1) {
	forbidButtons[i].addEventListener('click', (event) => {
		const data = {};
		data["fn"] = event.target.parentNode.parentNode.childNodes[1].textContent;
		data["forbidden_to_participate"] = 1;
		
		sendRequest('../../server/manage/changeAllowanceOfParticipant.php', { method: 'POST', data: `data=${JSON.stringify(data)}` }, load, handleErrors);
	});
}

addButton.addEventListener('click', (event) => {
	event.preventDefault();
	
	const data = {};
    data["fn"] = document.getElementById('fn-add').value;
	data["points"] = document.getElementById('points-add').value;
	data["grade"] = document.getElementById('grade-add').value;
	
	sendRequest('../../server/manage/addParticipant.php', { method: 'POST', data: `data=${JSON.stringify(data)}` }, load, handleErrors);
});

updateButton.addEventListener('click', (event) => {
	event.preventDefault();
	
	const data = {};
    data["fn"] = document.getElementById('fn-update').value;
	data["points"] = document.getElementById('points-update').value;
	
	sendRequest('../../server/manage/updateParticipant.php', { method: 'POST', data: `data=${JSON.stringify(data)}` }, load, handleErrors);
});

function load(response) {
    location.reload();
}

function handleErrors(response) {
	const errors = response["errors"];
	console.log(errors);
	const errorMessages = document.getElementsByClassName("error");
	for (let key in errorMessages) {
		errorMessages[key].textContent="";
	}
	
	for (let error in errors) {
		console.log(error);
		let error_id = error + "_error";
		document.getElementById(error_id).textContent = errors[error];
	}
}