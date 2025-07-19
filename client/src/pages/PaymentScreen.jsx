// client/src/pages/Payment.jsx
import { useEffect, useState } from 'react';

export default function PaymentScreen() {
  const [snapToken, setSnapToken] = useState(null);
  const [loading, setLoading] = useState(false);
  const [timer, setTimer] = useState(300); // 5 menit

useEffect(() => {
  const script = document.createElement('script');
  script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
  script.setAttribute('data-client-key', 'Mid-client-goJFIu1ThRtMKfAe');
  script.async = true;
  document.body.appendChild(script);

  return () => {
    document.body.removeChild(script);
  };
}, []);

  useEffect(() => {
    // Countdown timer
    const interval = setInterval(() => {
      setTimer((prev) => {
        if (prev <= 1) clearInterval(interval);
        return prev - 1;
      });
    }, 1000);

    return () => clearInterval(interval);
  }, []);

  const fetchSnapToken = async () => {
    setLoading(true);
    try {
      const res = await fetch('http://fotoboxjo.online/payment/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ amount: 15000 }),
      });
      const data = await res.json();
      setSnapToken(data.token);

      // Inject Snap
      window.snap.pay(data.token, {
        onSuccess: async function () {
          const check = await fetch('http://fotoboxjo.online/payment/status/' + data.order_id);
          const result = await check.json();
          if (result.status === 'settlement') {
            window.location.href = '/frame';
          } else {
            alert('Pembayaran belum berhasil. Silakan cek kembali.');
          }
        },
        onPending: function () {
          alert('Pembayaran masih pending!');
        },
        onError: function () {
          alert('Terjadi error saat pembayaran.');
        },
        onClose: function () {
          alert('Kamu menutup popup pembayaran.');
        },
      });
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="h-screen flex flex-col items-center justify-center bg-gray-100">
      <h1 className="text-3xl font-bold mb-4 text-gray-800">Selesaikan Pembayaran</h1>
      <p className="mb-2 text-lg text-gray-600">Jumlah: <strong>Rp15.000</strong></p>
      <p className="mb-4 text-sm text-red-600">Batas waktu pembayaran: {Math.floor(timer / 60)}:{String(timer % 60).padStart(2, '0')}</p>
      <button
        onClick={fetchSnapToken}
        disabled={loading || timer <= 0}
        className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition disabled:opacity-50"
      >
        {loading ? 'Memuat...' : 'Lanjutkan ke Pembayaran'}
      </button>
      <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-goJFIu1ThRtMKfAe"></script>
    </div>
  );
}
