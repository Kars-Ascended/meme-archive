function deleteMeme(filename) {
    if (!confirm('Are you sure you want to delete this meme?')) {
        return;
    }

    const formData = new FormData();
    formData.append('filename', filename);

    fetch('/backend/scripts/delete.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
        } else {
            const element = document.querySelector(`.meme-card[data-filename="${filename}"]`);
            element.remove();
        }
    })
    .catch(error => {
        alert('Error deleting meme: ' + error);
    });
}