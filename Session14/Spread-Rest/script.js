// Spread Operator

const collegeFriends = ["Rahul", "Anita", "Kiran"];
const workFriends = ["Meena", "Suresh", "Vikram"];

const partyList = ["Me", ...collegeFriends, ...workFriends];

console.log("Party List:", partyList);


// Rest Operator

const invite = (host, ...guests) => {

    console.log("Host:", host);
    console.log("Guests:");

    guests.forEach((guest, i) => {
        console.log(`${i + 1}. ${guest}`);
    });
};

// Test
invite("Sneha", "Rahul", "Anita", "Meena", "Vikram");
