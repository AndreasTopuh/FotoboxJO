const express = require('express');
const router = express.Router();
const midtransClient = require('midtrans-client');

const core = new midtransClient.CoreApi({
  isProduction: false,
  serverKey: process.env.MIDTRANS_SERVER_KEY,
  clientKey: process.env.MIDTRANS_CLIENT_KEY,
});

const orders = {};

router.post('/create', async (req, res) => {
  const orderId = 'ORDER-' + Date.now();
  const amount = req.body.amount || 15000;

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

    orders[orderId] = { status: 'pending' };
    res.json({
      qr_url: qrUrl,
      order_id: orderId,
    });
  } catch (err) {
    console.error('🔥 Gagal membuat transaksi QRIS:', err);
    res.status(500).json({ error: 'Gagal membuat transaksi QRIS' });
  }
});

module.exports = router;