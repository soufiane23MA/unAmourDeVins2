document.addEventListener('DOMContentLoaded', function () {
	const burger = document.querySelector('.burger-btn');
	const navRight = document.querySelector('.nav-right');

	burger.addEventListener('click', () => {
		navRight.classList.toggle('open');
	});
});