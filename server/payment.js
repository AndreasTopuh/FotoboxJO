const express = require('express');
const router = express.Router();
const midtransClient = require('midtrans-client');

const core = new midtransClient.CoreApi({
  isProduction: false,
  serverKey: process.env.MIDTRANS_SERVER_KEY,
  clientKey: process.env.MIDTRANS_CLIENT_KEY,
});

const orders = {};

router.post('/notification', async (req, res) => {
  try {
    const notification = await core.transaction.notification(req.body);
    const transactionStatus = notification.transaction_status;
    const orderId = notification.order_id;

    console.log(`📥 Notifikasi dari Midtrans untuk ${orderId}:`, transactionStatus);

    // Update order status and add timestamp
    if (orders[orderId]) {
      orders[orderId].status = transactionStatus;
      orders[orderId].updatedAt = Date.now();
    }

    res.status(200).json({ message: 'Notifikasi diterima' });
  } catch (err) {
    console.error('❌ Gagal proses notifikasi:', err);
    res.status(500).json({ error: 'Gagal proses notifikasi' });
  }
});

router.post('/create', async (req, res) => {
  const orderId = 'ORDER-' + Date.now();
  const amount = req.body.amount || 15000;
  const method = req.body.method || 'qris';

  let parameter = {
    transaction_details: {
      order_id: orderId,
      gross_amount: amount,
    },
  };

  if (method === 'qris') {
    parameter.payment_type = 'qris';
  } else if (method === 'bank_transfer') {
    parameter.payment_type = 'bank_transfer';
    parameter.bank_transfer = {
      bank: 'bca',
    };
  } else {
    return res.status(400).json({ error: 'Metode pembayaran tidak valid' });
  }

  try {
    const chargeResponse = await core.charge(parameter);
    console.log('🧾 Midtrans response:', JSON.stringify(chargeResponse, null, 2));

    const responsePayload = {
      order_id: orderId,
    };

    if (method === 'qris') {
      responsePayload.qr_url =
        chargeResponse.actions?.find((a) => a.name === 'generate-qr-code')?.url ||
        chargeResponse.qr_url;
    } else if (method === 'bank_transfer') {
      responsePayload.va_number = chargeResponse.va_numbers?.[0]?.va_number || null;
    }

    // Store order details with creation timestamp
    orders[orderId] = {
      status: chargeResponse.transaction_status || 'pending',
      createdAt: Date.now(),
      method,
    };

    res.json(responsePayload);
  } catch (err) {
    console.error('🔥 Gagal membuat transaksi:', err);
    res.status(500).json({ error: 'Gagal membuat transaksi' });
  }
});

router.get('/status/:orderId', async (req, res) => {
  const { orderId } = req.params;

  if (!orders[orderId]) {
    return res.status(404).json({ error: 'Order tidak ditemukan' });
  }

  // Check if order has expired (5 minutes = 300,000 ms)
  const elapsed = Date.now() - orders[orderId].createdAt;
  if (elapsed > 300000) {
    orders[orderId].status = 'expire';
  }

  try {
    // Optionally, fetch latest status from Midtrans
    const statusResponse = await core.transaction.status(orderId);
    orders[orderId].status = statusResponse.transaction_status;
    orders[orderId].updatedAt = Date.now();
    res.json({ status: statusResponse.transaction_status });
  } catch (err) {
    console.error('🔥 Gagal cek status:', err);
    // Return cached status if Midtrans call fails
    res.json({ status: orders[orderId].status });
  }
});

module.exports = router;