// routes/payment.js
import express from 'express';
import midtransClient from 'midtrans-client';

const router = express.Router();

const snap = new midtransClient.Snap({
  isProduction: false,
  serverKey: process.env.MIDTRANS_SERVER_KEY,
});

router.post('/token', async (req, res) => {
  const { packageId } = req.body;

  const price = packageId === 'premium' ? 20000 : 10000;

  const parameter = {
    transaction_details: {
      order_id: 'ORDER-' + Math.floor(Math.random() * 1000000),
      gross_amount: price,
    },
    credit_card: { secure: true },
  };

  try {
    const transaction = await snap.createTransaction(parameter);
    res.json({ token: transaction.token });
  } catch (err) {
    res.status(500).json({ error: 'Gagal membuat transaksi' });
  }
});

export default router;
