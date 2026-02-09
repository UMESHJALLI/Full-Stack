const info = document.getElementById("info");

// Listen for mouse movement globally
document.addEventListener("mousemove", (event) => {
    const x = event.clientX;
    const y = event.clientY;

    const width = window.innerWidth;
    const height = window.innerHeight;

    let quadrant = "";

    // Determine quadrant
    if (x < width / 2 && y < height / 2) {
        quadrant = "Top Left";
        document.body.style.backgroundColor = "#e74c3c"; // Red
    } 
    else if (x >= width / 2 && y < height / 2) {
        quadrant = "Top Right";
        document.body.style.backgroundColor = "#3498db"; // Blue
    } 
    else if (x < width / 2 && y >= height / 2) {
        quadrant = "Bottom Left";
        document.body.style.backgroundColor = "#2ecc71"; // Green
    } 
    else {
        quadrant = "Bottom Right";
        document.body.style.backgroundColor = "#9b59b6"; // Purple
    }

    info.textContent = `Mouse Position: (${x}, ${y}) | ${quadrant} Quadrant`;
});
