import { useEffect, useState } from 'react';

export default function PaymentScreen() {
  const [qrUrl, setQrUrl] = useState(null);
  const [loading, setLoading] = useState(false);
  const [orderId, setOrderId] = useState(null);
  const [timer, setTimer] = useState(300); // 5 menit
  const [status, setStatus] = useState(null);

  useEffect(() => {
    const interval = setInterval(() => {
      setTimer((prev) => {
        if (prev <= 1) clearInterval(interval);
        return prev - 1;
      });
    }, 1000);
    return () => clearInterval(interval);
  }, []);

  useEffect(() => {
    const checkInterval = setInterval(async () => {
      if (!orderId) return;
      try {
        const res = await fetch(`https://gofotobox.online/payment/status/${orderId}`);
        const data = await res.json();
        setStatus(data.status);

        if (data.status === 'settlement') {
          window.location.href = '/frame';
        }
      } catch (err) {
        console.error(err);
      }
    }, 5000);
    return () => clearInterval(checkInterval);
  }, [orderId]);

  const createQRISPayment = async () => {
    setLoading(true);
    try {
      const res = await fetch('https://gofotobox.online/payment/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ amount: 15000 }),
      });
      const data = await res.json();
      setQrUrl(data.qr_url);
      setOrderId(data.order_id);
    } catch (err) {
      console.error('Error fetching QR:', err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="h-screen flex flex-col items-center justify-center bg-gray-50">
      <div className="text-center max-w-md mx-auto px-4">
        <h1 className="text-3xl font-bold mb-4 text-gray-800">Selesaikan Pembayaran</h1>
        <p className="mb-2 text-lg text-gray-600">Jumlah: <strong>Rp15.000</strong></p>
        <p className="mb-4 text-sm text-red-600">Batas waktu pembayaran: {Math.floor(timer / 60)}:{String(timer % 60).padStart(2, '0')}</p>

        {qrUrl ? (
          <>
            <img src={qrUrl} alt="QR Code Pembayaran" className="w-64 h-64 mx-auto my-4 border p-2 bg-white" />
            <p className="text-sm text-gray-500">Scan kode QR menggunakan aplikasi e-wallet kamu</p>
            <p className="mt-2 text-sm text-blue-600">Status: {status}</p>
          </>
        ) : (
          <button
            onClick={createQRISPayment}
            disabled={loading || timer <= 0}
            className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition disabled:opacity-50 w-full"
          >
            {loading ? 'Memuat QR...' : 'Buat Kode QR Pembayaran'}
          </button>
        )}
      </div>
    </div>
  );
}
