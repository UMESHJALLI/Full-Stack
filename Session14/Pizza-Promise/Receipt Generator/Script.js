// Arrow Function

const generateReceipt = (price, tip) => {

    const total = price + tip;

    console.log(`
----- RECEIPT -----
Price : ₹${price}
Tip   : ₹${tip}
Total : ₹${total}
-------------------
    `);
};

// Test
generateReceipt(400, 50);
