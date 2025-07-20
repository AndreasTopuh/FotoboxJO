import { useEffect, useState } from 'react';

export default function PaymentScreen() {
  const [qrUrl, setQrUrl] = useState(null);
  const [loading, setLoading] = useState(false);
  const [orderId, setOrderId] = useState(null);
  const [timer, setTimer] = useState(300); // 5 minutes
  const [status, setStatus] = useState(null);
  const [showModal, setShowModal] = useState(false);

  useEffect(() => {
    const interval = setInterval(() => {
      setTimer((prev) => {
        if (prev <= 1) {
          clearInterval(interval);
          return 0;
        }
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
          setShowModal(true);
          clearInterval(checkInterval); // Stop checking once settled
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

  const handleContinue = () => {
    setShowModal(false);
    window.location.href = '/frame';
  };

  return (
    <div className="h-screen flex flex-col items-center justify-center bg-gradient-to-r from-[#FF8679] via-[#F2AAAE] to-[#F6D3AD]">
      <div className="text-center max-w-lg mx-auto px-4 py-6 bg-white rounded-xl shadow-2xl border border-gray-200">
        <h1 className="text-3xl font-bold mb-4 text-gray-800">Selesaikan Pembayaran</h1>
        <p className="mb-2 text-lg text-gray-600">Jumlah: <strong>Rp15.000</strong></p>
        <p className="mb-6 text-sm text-red-600">Batas waktu pembayaran: {Math.floor(timer / 60)}:{String(timer % 60).padStart(2, '0')}</p>

        {qrUrl ? (
          <div className="mb-6 p-4 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg shadow-inner">
            <img
              src={qrUrl}
              alt="QR Code Pembayaran"
              className="w-72 h-72 mx-auto rounded-md border-2 border-white"
            />
            <p className="mt-2 text-sm text-gray-500">Scan kode QR menggunakan aplikasi e-wallet kamu</p>
            <p className="mt-2 text-sm text-blue-600">Status: {status || 'Menunggu Pembayaran'}</p>
          </div>
        ) : (
          <button
            onClick={createQRISPayment}
            disabled={loading || timer <= 0}
            className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition disabled:opacity-50 w-full mb-4"
          >
            {loading ? 'Memuat QR...' : 'Buat Kode QR Pembayaran'}
          </button>
        )}

        {/* Payment Options (Placeholder - Style like the image) */}
        <div className="grid grid-cols-3 gap-4 mb-6">
          <button className="bg-white p-2 rounded-lg shadow-md hover:bg-gray-100">
            <img src="/path/to/qris-icon.png" alt="QRIS" className="w-12 h-12 mx-auto" />
            <p className="text-xs text-gray-700 mt-1">QR Code</p>
          </button>
          <button className="bg-white p-2 rounded-lg shadow-md hover:bg-gray-100">
            <img src="/path/to/bni-icon.png" alt="BNI" className="w-12 h-12 mx-auto" />
            <p className="text-xs text-gray-700 mt-1">BNI</p>
          </button>
          <button className="bg-white p-2 rounded-lg shadow-md hover:bg-gray-100">
            <img src="/path/to/gopay-icon.png" alt="Gopay" className="w-12 h-12 mx-auto" />
            <p className="text-xs text-gray-700 mt-1">Gopay</p>
          </button>
        </div>
      </div>

      {/* Success Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-white p-6 rounded-lg shadow-lg text-center max-w-sm">
            <h2 className="text-2xl font-bold text-green-600 mb-4">Pembayaran Berhasil!</h2>
            <p className="text-gray-600 mb-4">Terima kasih atas pembayaran Anda. Silakan lanjutkan.</p>
            <button
              onClick={handleContinue}
              className="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg"
            >
              Lanjut
            </button>
          </div>
        </div>
      )}
    </div>
  );
}