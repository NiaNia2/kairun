const burger = document.querySelector(".burger");
const burgerContent = document.querySelector(".burger-content");

burger.addEventListener("mouseover", () => {
    burgerContent.style.display = "flex";
});

burger.addEventListener("mouseout", () => {
    burgerContent.style.display = "none";
});

burgerContent.addEventListener("mouseover", () => {
    burgerContent.style.display = "flex";
});

burgerContent.addEventListener("mouseout", () => {
    burgerContent.style.display = "none";
});