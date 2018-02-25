function hintClick() {
	var hintImg = document.getElementById('hintImg');
	hintImg.addEventListener('click', function() {
		var hintBlock = document.getElementById('hintBlock');
		hintBlock.style.visibility = 'visible';
		hintBlock.addEventListener('click', function() {
			hintBlock.style.visibility = 'hidden';
		}
		);
	}
	);
}
function regClick() {
	var h2reg = document.getElementById('h2reg');
	h2reg.addEventListener('click', function() {
		var regForm = document.getElementById('regForm');
		regForm.style.visibility = 'visible';
		h2reg.style.border = 'none';
		h2reg.style.background = 'none';
	});
}

function editClick() {
	var postView = document.getEementById('postContent');
	editPost.addEventListener('click', function() {
		var postArea = document.getEementById('editedPost');
		postView.style.visibility = 'hidden';
		postView.style.zIndex = '0';
		postArea.style.display = 'block';
		postArea.style.zIndex = '50';
	});
}

function editClickOn() {
	var postView = document.getEementById('postContent');
	var postArea = document.getEementById('editedPost');
	postView.style.visibility = 'hidden';
	postView.style.zIndex = '0';
	postArea.style.display = 'block';
	postArea.style.zIndex = '50';
}

function alT() {
	alert(2233);
}

// ----------------------------

hintClick();
regClick();