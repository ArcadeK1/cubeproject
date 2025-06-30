import './bootstrap';



document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".floating-label input, .floating-label select").forEach(function(element) {
        element.addEventListener("input", function() {
            let field = this;
            let floatingLabel = field.closest(".floating-label");
            if (field.classList.contains("is-invalid")) {
                floatingLabel.classList.add("has-error");
            } else {
                floatingLabel.classList.remove("has-error");
            }
        });
    });
});

