function requestFullScreen() {
	var elem = document.documentElement;
	document.addEventListener('fullscreenerror', (event) => {
		fullScreenReduced();
	});
	if (elem.requestFullscreen) {
		elem.requestFullscreen();
		screenStretch();
	} else if (elem.msRequestFullscreen) {
		elem.msRequestFullscreen();
		screenStretch();
	} else if (elem.mozRequestFullScreen) {
		elem.mozRequestFullScreen();
		screenStretch();

	} else if (elem.webkitRequestFullscreen) {
		elem.webkitRequestFullscreen();
		screenStretch();
	}

}

function exitFullscreen() {
	var elem = document.documentElement;
	if (elem.exitFullscreen) {
		elem.exitFullscreen();
	} else if (elem.exitFullscreen) {
		elem.exitFullscreen();
	} else if (elem.exitFullscreen) {
		elem.exitFullscreen();
	} else if (elem.exitFullscreen) {
		elem.exitFullscreen();
	}
}

function screenStretch() {
	var windowHeight = window.screen.height;
	if (parseInt(windowHeight) > 768) {
		var myHeight = windowHeight - 768;
	} else {
		var myHeight = (windowHeight - 768) + 65;
	}
	if (parseInt(windowHeight) > 825) {
		var examQuestionCss = 450 + myHeight;
		var examQuestionBox = 385 + myHeight;
		var examQuestionPalette = 300 + myHeight;
		var examQuestionPassage = 475 + myHeight;
	} else {
		var examQuestionCss = 325 + myHeight;
		var examQuestionBox = 290 + myHeight;
		if (parseInt(windowHeight) > 768) {
			var examQuestionPalette = 200 + myHeight;
		} else {
			var examQuestionPalette = 150 + myHeight;
		}
		var examQuestionPassage = 325 + myHeight;
	}


	$('.fullscreen').hide();
	$('.normalscreen').show();
	$("div.exam-Question").css({ "min-height": examQuestionCss + "px", "height": examQuestionCss + "px" });
	$("div.exam-questionBox").css({ "min-height": examQuestionBox + "px", "height": examQuestionBox + "px" });
	$("#exam-divQuestionPallete").css({
		"min-height": examQuestionPalette + "px",
		"height": examQuestionPalette + "px"
	});
	$("div.exam-divPassage").css({
		"min-height": examQuestionPassage + "px",
		"height": examQuestionPassage + "px"
	});
}

if (document.addEventListener) {
	document.addEventListener('webkitfullscreenchange', exitHandler, false);
	document.addEventListener('mozfullscreenchange', exitHandler, false);
	document.addEventListener('fullscreenchange', exitHandler, false);
	document.addEventListener('MSFullscreenChange', exitHandler, false);
	window.addEventListener('resize', resizeDiv, false);
	window.addEventListener('load', resizeDiv, false);

}

function exitHandler() {
	if (document.webkitIsFullScreen === false) {
		fullScreenReduced();
	} else if (document.mozFullScreen === false) {
		fullScreenReduced();
	} else if (document.msFullscreenElement === false) {
		fullScreenReduced();
	}
}

function resizeDiv() {
	var windowWidth = window.innerWidth;
	var windowHeight = window.innerHeight;
	var element = document.getElementById("_menu_header");
	var width = element.clientWidth;
	if (parseInt(windowWidth) < 549) {
		$(".exam_question_palette").css({
			"width": width + "px",
		});
		$("#_navbar_exam").css({
			'min-height': windowHeight - 100 + "px",
			'height': windowHeight - 100 + "px",
			"margin-top": '2px'
		})

	}

	if (549 < parseInt(windowWidth) < 768) {
		$(".exam_question_palette").css({
			"width": width + "px",
		});
		$("#_navbar_exam").css({
			'min-height': windowHeight - 150 + "px",
			'height': windowHeight - 150 + "px",
			"margin-top": '2px'
		})

	}

	if (windowWidth > 980) {
		$(".exam_question_palette").css({
			"width": "auto",
		});
	}



}

function fullScreenReduced() {

	var windowHeight = window.screen.height;
	var windowWidth = window.screen.width;
	if (parseInt(windowHeight) > 768) {
		var myHeight = windowHeight - 768;
	} else {
		var myHeight = (windowHeight - 768) + 65;
	}
	if (parseInt(windowHeight) > 768) {
		var examQuestionCss = 320 + myHeight;
		var examQuestionBox = 150 + myHeight;
		var examQuestionPalette = 180 + myHeight;
	} else {
		var examQuestionCss = 210 + myHeight;
		var examQuestionBox = 260 + myHeight;
		var examQuestionPalette = 130 + myHeight;
	}

	if (768 < parseInt(windowWidth) < 900) {
		var examQuestionPalette = 100 + myHeight;

	}


	var examQuestionPassage = 300 + myHeight;
	$('.normalscreen').hide();
	$('.fullscreen').show();
	$("div.exam-Question").css({ "min-height": examQuestionCss + "px", "height": examQuestionCss + "px" });
	$("div.exam-questionBox").css({ "min-height": examQuestionBox + "px", "height": examQuestionBox + "px" });
	$("#exam-divQuestionPallete").css({
		"min-height": examQuestionPalette + "px",
		"height": examQuestionPalette + "px"
	});
	$("div.exam-divPassage").css({
		"min-height": examQuestionPassage + "px",
		"height": examQuestionPassage + "px"
	});
}

$(document).ready(function () {
	$('.normalscreen').hide();
	fullScreenReduced();
	//requestFullScreen();
});
