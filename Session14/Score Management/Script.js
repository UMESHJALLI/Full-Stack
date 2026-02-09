// State Management

let teamA = 0;
let teamB = 0;

const updateScore = (team, points) => {

    if (team === "A") {
        teamA += points;
    } 
    else if (team === "B") {
        teamB += points;
    }

    console.log(`Team A: ${teamA} | Team B: ${teamB}`);
};

// Test
updateScore("A", 5);
updateScore("B", 3);
updateScore("A", 7);
