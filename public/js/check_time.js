
function checkTime(element) {

	if (element.value == 0 || element.value == null)
		element.className = 'invalid';
	else
		element.className = 'input';
}


