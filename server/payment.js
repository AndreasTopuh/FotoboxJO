const express = require('express');
const router = express.Router();
const midtransClient = require('midtrans-client');

const core = new midtransClient.CoreApi({
  isProduction: false,
  serverKey: process.env.MIDTRANS_SERVER_KEY,
  clientKey: process.env.MIDTRANS_CLIENT_KEY,
});

const orders = {};
let logs = []; // 🧠 Untuk menyimpan log sementara

router.post('/notification', (req, res) => {
  console.log('[NOTIF] Dapet notifikasi Midtrans:', req.body);
  res.status(200).send('Notification received');
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
    parameter.bank_transfer = { bank: 'bca' };
  } else {
    return res.status(400).json({ error: 'Metode pembayaran tidak valid' });
  }

  try {
    const chargeResponse = await core.charge(parameter);
    const log = '🧾 Midtrans response: ' + JSON.stringify(chargeResponse, null, 2);
    logs.push(log);
    console.log(log);

    const responsePayload = { order_id: orderId };
    if (method === 'qris') {
      responsePayload.qr_url = chargeResponse.actions?.find((a) => a.name === 'generate-qr-code')?.url || chargeResponse.qr_url;
    } else if (method === 'bank_transfer') {
      responsePayload.va_number = chargeResponse.va_numbers?.[0]?.va_number || null;
    }

    orders[orderId] = {
      status: chargeResponse.transaction_status || 'pending',
      createdAt: Date.now(),
      method,
    };

    res.json(responsePayload);
  } catch (err) {
    const errMsg = '🔥 Gagal membuat transaksi: ' + err.message;
    logs.push(errMsg);
    console.error(errMsg);
    res.status(500).json({ error: 'Gagal membuat transaksi' });
  }
});

router.get('/status/:orderId', async (req, res) => {
  const { orderId } = req.params;

  if (!orders[orderId]) {
    return res.status(404).json({ error: 'Order tidak ditemukan' });
  }

  const elapsed = Date.now() - orders[orderId].createdAt;
  if (elapsed > 300000) {
    orders[orderId].status = 'expire';
  }

  try {
    const statusResponse = await core.transaction.status(orderId);
    orders[orderId].status = statusResponse.transaction_status;
    orders[orderId].updatedAt = Date.now();

    const log = `📦 Status update dari Midtrans untuk ${orderId}: ${statusResponse.transaction_status}`;
    logs.push(log);
    console.log(log);

    res.json({ status: statusResponse.transaction_status });
  } catch (err) {
    const fallbackLog = `🔥 Gagal cek status dari Midtrans, fallback ke cached status: ${orders[orderId].status}`;
    logs.push(fallbackLog);
    console.error(fallbackLog);
    res.json({ status: orders[orderId].status });
  }
});

// 🔍 Endpoint baru untuk ambil log
router.get('/log', (req, res) => {
  res.json({ logs });
  logs = []; // Kosongkan log setelah diambil agar nggak numpuk
});

module.exports = router;
