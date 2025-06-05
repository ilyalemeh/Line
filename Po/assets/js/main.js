window.addEventListener('DOMContentLoaded', () => {
	const toggleBtn = document.getElementById('header-toggle')
	const menu = document.getElementById('header-menu')

	if (!toggleBtn || !menu) {
		return
	}

	toggleBtn.addEventListener('click', () => {
		const expanded = toggleBtn.getAttribute('aria-expanded') === 'true'
		toggleBtn.setAttribute('aria-expanded', String(!expanded))
		menu.classList.toggle('is-open')
	})

	document
		.querySelectorAll('.bids__table select[name="status"]')
		.forEach(select => {
			select.addEventListener('change', function () {
				const reasonInput = this.closest('form').querySelector(
					'input[name="cancellation_reason"]'
				)
				if (this.value === 'rejected') {
					reasonInput.style.display = 'inline'
				} else {
					reasonInput.style.display = 'none'
					reasonInput.value = ''
				}
			})
		})
})
