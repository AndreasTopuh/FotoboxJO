const express = require('express');
const router = express.Router();
const midtransClient = require('midtrans-client');

const snap = new midtransClient.Snap({
  isProduction: false,
  serverKey: process.env.MIDTRANS_SERVER_KEY,
});

const orders = {};

router.post('/create', async (req, res) => {
  const orderId = 'ORDER-' + Date.now();
  const amount = req.body.amount || 15000;

  const parameter = {
    transaction_details: {
      order_id: orderId,
      gross_amount: amount,
    },
    credit_card: {
      secure: true,
    },
  };

  try {
    const transaction = await snap.createTransaction(parameter);
    orders[orderId] = { status: 'pending' };
    res.json({ token: transaction.token, order_id: orderId });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Gagal membuat transaksi' });
  }
});

router.post('/notification', async (req, res) => {
  const notif = req.body;
  const orderId = notif.order_id;
  const transactionStatus = notif.transaction_status;

  console.log('Notifikasi masuk:', notif);

  if (orders[orderId]) {
    orders[orderId].status = transactionStatus;
  }

  res.sendStatus(200);
});

router.get('/status/:orderId', (req, res) => {
  const status = orders[req.params.orderId]?.status || 'not_found';
  res.json({ status });
});

module.exports = router;