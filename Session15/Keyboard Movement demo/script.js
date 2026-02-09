
const box = document.getElementById("box");

let x = 200;
let y = 200;
const speed = 10;

// Global keyboard listener
document.addEventListener("keydown", function (event) {
    switch (event.key) {
        case "ArrowUp":
            y -= speed;
            break;
        case "ArrowDown":
            y += speed;
            break;
        case "ArrowLeft":
            x -= speed;
            break;
        case "ArrowRight":
            x += speed;
            break;
    }

    box.style.left = x + "px";
    box.style.top = y + "px";
});
