// Object

const employee = {
    name: "Sneha",
    age: 22,
    role: "Developer",
    location: {
        city: "Chennai",
        country: "India"
    }
};

// Object Destructuring
const { name, role, location: { city } } = employee;

console.log("Name:", name);
console.log("Role:", role);
console.log("City:", city);


// Array

const scores = [95, 88, 90, 92];

// Array Destructuring
const [maths, physics, chemistry] = scores;

console.log("Maths:", maths);
console.log("Physics:", physics);
console.log("Chemistry:", chemistry);
