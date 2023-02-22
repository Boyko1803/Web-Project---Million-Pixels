const loginButton = document.getElementById('login');

loginButton.addEventListener('click', (event) => {
	event.preventDefault();
	
	const data = {};
    data["fn"] = document.getElementById('fn').value;
	data["password"] = document.getElementById('password').value;
	
	sendRequest('../../server/login/login.php', { method: 'POST', data: `data=${JSON.stringify(data)}` }, load, handleErrors);
});

function load(response) {
    window.location = '../view/view.php';
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