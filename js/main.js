// Add required stars
for (const el of $('main>form>div>[required]')) {
	const label = $$(`label[for=${el.id}]`);
	label.classList.add('label-required');
}
