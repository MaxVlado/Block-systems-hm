function adjustTextareaHeight(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

document.addEventListener('DOMContentLoaded', function() {
    var textareas = document.querySelectorAll('textarea[name="css"], textarea[name="code"]');
    textareas.forEach(function(textarea) {
        adjustTextareaHeight(textarea);
        textarea.addEventListener('input', function() {
            adjustTextareaHeight(textarea);
        });
    });
});



document.getElementById('shortcode').addEventListener('input', function(e) {
    var validPattern = /^[a-z0-9_-]+$/;
    var inputValue = e.target.value;

    if (!validPattern.test(inputValue)) {
        e.target.setCustomValidity('Only lowercase letters, numbers, hyphens and underscores are allowed');
    } else {
        e.target.setCustomValidity('');
    }
});