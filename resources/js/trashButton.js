document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".deletebtn img").forEach(img => {
        img.addEventListener("mouseenter", function () {
            this.src = this.getAttribute("data-hover");
        });

        img.addEventListener("mouseleave", function () {
            this.src = this.getAttribute("data-default");
        });
    });



    document.querySelectorAll(".deletebtn img").forEach(img => {
        img.addEventListener("mouseenter", function () {
            this.src = this.getAttribute("data-hover");
        });

        img.addEventListener("mouseleave", function () {
            this.src = this.getAttribute("data-default");
        });
    });




    
});