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

    // Update status order kalau kamu simpan di memory/database
    if (orders[orderId]) {
      orders[orderId].status = transactionStatus;
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
      bank: 'bca'
    };
  } else {
    return res.status(400).json({ error: 'Metode pembayaran tidak valid' });
  }

  try {
    const chargeResponse = await core.charge(parameter);
    console.log('🧾 Midtrans response:', JSON.stringify(chargeResponse, null, 2));

    const responsePayload = {
      order_id: orderId
    };

    if (method === 'qris') {
      responsePayload.qr_url = chargeResponse.actions?.find(a => a.name === 'generate-qr-code')?.url || chargeResponse.qr_url;
    } else if (method === 'bank_transfer') {
      responsePayload.va_number = chargeResponse.va_numbers?.[0]?.va_number || null;
    }

    res.json(responsePayload);
  } catch (err) {
    console.error('🔥 Gagal membuat transaksi:', err);
    res.status(500).json({ error: 'Gagal membuat transaksi' });
  }
});

module.exports = router;