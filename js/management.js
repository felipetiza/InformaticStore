function loadToast(){
    var x = document.getElementById("toast")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

function loadModalWindow(showWithoutClick){
	var modal = document.getElementById('myModal');
	var btn   = document.getElementsByClassName("myBtn");
	var span  = document.getElementsByClassName("close")[0];

	if(showWithoutClick)
		btn[0].click();

	btn[0].onclick = function() { modal.style.display = "block"; }
	btn[1].onclick = function() { modal.style.display = "block"; }
	span.onclick = function() {
	    modal.style.display = "none";
	}
	window.onclick = function(event) {
	    if (event.target == modal)
	        modal.style.display = "none";
	}
}

function loadAccordion(){
	var acc = document.getElementsByClassName("accordion");
	var i;
	for (i = 0; i < acc.length; i++) {
		acc[i].onclick = function() {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.maxHeight)
	  			panel.style.maxHeight = null;
			else
	  			panel.style.maxHeight = panel.scrollHeight + "px";
		}
	}
}