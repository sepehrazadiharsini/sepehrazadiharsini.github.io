document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("postForm");
    const titleInput = document.getElementById("title");
    const contentInput = document.getElementById("content");

    form.addEventListener("submit", (event) => {
        let isValid = true;

        
        titleInput.classList.remove("error");
        contentInput.classList.remove("error");

        if (titleInput.value.trim() === "") {
            isValid = false;
            titleInput.classList.add("error");
        }

        if (contentInput.value.trim() === "") {
            isValid = false;
            contentInput.classList.add("error");
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});