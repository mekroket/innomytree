// Script for Innomytree

document.addEventListener('DOMContentLoaded', () => {
    console.log('Innomytree loaded');

    // Help Button Logic
    const helpBtn = document.querySelector('.help-btn');
    if (helpBtn) {
        helpBtn.addEventListener('click', () => {
            alert('Yardım özelliği yakında eklenecek!');
        });
    }
});
