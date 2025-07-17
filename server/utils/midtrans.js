const midtransClient = require('midtrans-client');
let snap = new midtransClient.Snap({
  isProduction: false,
  serverKey: process.env.MIDTRANS_SERVER_KEY
});

exports.createTransaction = async (req, res) => {
  const { order_id, amount } = req.body;
  try {
    let transaction = await snap.createTransaction({
      transaction_details: {
        order_id,
        gross_amount: amount
      },
      payment_type: "qris",
      qris: { acquirer: "gopay" }
    });
    res.json(transaction);
  } catch (e) {
    res.status(500).json({ error: e.message });
  }
};
