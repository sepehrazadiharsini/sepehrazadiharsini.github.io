document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("postForm");
    const titleInput = document.getElementById("title");
    const contentInput = document.getElementById("content");

    form.addEventListener("submit", (event) => {
        let isValid = true;

        // Reset previous error styles
        titleInput.classList.remove("error");
        contentInput.classList.remove("error");

        // Check if title is empty
        if (titleInput.value.trim() === "") {
            isValid = false;
            titleInput.classList.add("error");
        }

        // Check if content is empty
        if (contentInput.value.trim() === "") {
            isValid = false;
            contentInput.classList.add("error");
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
});