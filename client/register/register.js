const registerButton = document.getElementById('register');

registerButton.addEventListener('click', (event) => {
	event.preventDefault();
	
	const data = {};
    data["fn"] = document.getElementById('fn').value;
	data["name"] = document.getElementById('name').value;
	data["password"] = document.getElementById('password').value;
	data["confirm_password"] = document.getElementById('confirm_password').value;
	data["email"] = document.getElementById('email').value;

    sendRequest('../../server/register/register.php', { method: 'POST', data: `data=${JSON.stringify(data)}` }, load, handleErrors);
});

function load(response) {
    window.location = '../login/login.html';
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