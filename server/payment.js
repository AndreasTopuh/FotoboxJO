const express = require('express');
const router = express.Router();
const midtransClient = require('midtrans-client');

// Initialize Midtrans Core API
const core = new midtransClient.CoreApi({
  isProduction: false, // Set to true for production
  serverKey: process.env.MIDTRANS_SERVER_KEY,
  clientKey: process.env.MIDTRANS_CLIENT_KEY,
});

// In-memory storage for orders (replace with a database in production)
const orders = {};

router.post('/create', async (req, res) => {
  const orderId = 'ORDER-' + Date.now();
  const amount = req.body.amount || 15000;

  if (amount <= 0) {
    return res.status(400).json({ error: 'Jumlah pembayaran harus lebih dari 0' });
  }

  const parameter = {
    payment_type: 'qris',
    transaction_details: {
      order_id: orderId,
      gross_amount: amount,
    },
  };

  try {
    const chargeResponse = await core.charge(parameter);
    console.log('🧾 Midtrans QRIS response:', JSON.stringify(chargeResponse, null, 2));

    const qrUrl = chargeResponse.actions?.find(a => a.name === 'generate-qr-code')?.url || chargeResponse.qr_url;

    if (!qrUrl) {
      throw new Error('QR URL not found in response');
    }

    orders[orderId] = { status: 'pending', amount, created_at: new Date() };
    res.json({
      qr_url: qrUrl,
      order_id: orderId,
    });
  } catch (err) {
    console.error('🔥 Gagal membuat transaksi QRIS:', err.message);
    res.status(500).json({ error: 'Gagal membuat transaksi QRIS', details: err.message });
  }
});

router.get('/status/:orderId', async (req, res) => {
  const orderId = req.params.orderId;
  const order = orders[orderId];

  if (!order) {
    return res.status(404).json({ error: 'Order not found' });
  }

  try {
    const statusResponse = await core.transaction.status(orderId);
    const transactionStatus = statusResponse.transaction_status;

    orders[orderId].status = transactionStatus;
    res.json({
      status: transactionStatus,
      order_id: orderId,
      amount: order.amount,
      updated_at: new Date(),
    });
  } catch (err) {
    console.error('🔥 Error checking status:', err.message);
    res.status(500).json({ error: 'Gagal memeriksa status pembayaran', details: err.message });
  }
});

// Webhook handler for Midtrans notification
router.post('/payment/notification', (req, res) => {
  const payload = req.body;

  if (payload && payload.order_id && payload.transaction_status) {
    const orderId = payload.order_id;
    orders[orderId] = orders[orderId] || { status: 'pending' };
    orders[orderId].status = payload.transaction_status;

    console.log(`🔔 Webhook received for order ${orderId}: ${payload.transaction_status}`);
    res.status(200).json({ message: 'Webhook received' }); // Respond with 200 OK
  } else {
    res.status(400).json({ error: 'Invalid webhook payload' });
  }
});

module.exports = router;