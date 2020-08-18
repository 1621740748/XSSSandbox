// Removed because of XSS vuln that those nasty hackers found

window.onhashchange = function() {
	// Load maths from hash
	var hash = decodeURIComponent(document.location.hash.substr(1))
	var res = eval(hash);
	console.log(res);
}


