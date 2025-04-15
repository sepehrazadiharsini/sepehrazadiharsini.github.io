document.addEventListener("DOMContentLoaded", () => {
    const clearButtons = document.querySelectorAll("button[type='reset']");

    clearButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            const confirmation = confirm("Are you sure you want to clear the form?");
            if (!confirmation) {
                event.preventDefault();
            }
        });
    });
});