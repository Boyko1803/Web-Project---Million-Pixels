const logoutReference = document.getElementById('logout');

logoutReference.addEventListener('click', (event) => {
	event.preventDefault();
	
	sendRequest('../../server/utilities/destroySession.php', { method: 'POST' }, loadLogout, handleLogoutError);
});

function loadLogout(response) {
    window.location = logoutReference.href;
}

function handleLogoutError(response) {
	
}