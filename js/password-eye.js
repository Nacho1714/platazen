document.addEventListener('DOMContentLoaded', () => {

    const toggleButtons = document.querySelectorAll('[data-toggle-button]');
    const passwordFields = document.querySelectorAll('[data-toggle-password]');

    toggleButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const passwordField = passwordFields[index];
            const icon = button.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                passwordField.type = 'password';
                icon.textContent = 'remove_red_eye';
            }
        });
    });

})

