
function doDelete(o) {
	
	let server = new XMLHttpRequest();
	let id = this.dataset.id;
	let page = this.dataset.page;
	let data = new FormData();

	data.set("id", id);
	
	server.open("POST", `/${page}/delete`, true);
	
	server.onload = function() {
		
		let response = JSON.parse(server.response);
		
		if(response.success) {
			let el = document.querySelector("#line-" + response.id);
			el.style.backgroundColor = "#000"; //just so he/she know that something happened
			setTimeout(() => el.remove(), 50);
		} else {
			//send some message 
		}
		
	};
	
	server.send(data);
}



document.querySelectorAll(".action.delete button").forEach((a) => { a.addEventListener("click", doDelete) });


