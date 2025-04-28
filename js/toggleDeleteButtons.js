function toggleDeleteButtons() {
    const buttons = document.querySelectorAll('.delete-btn');
    buttons.forEach(btn => {
        btn.style.display = btn.style.display === 'none' ? 'block' : 'none';
    });
}