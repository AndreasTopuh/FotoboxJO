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
          setStatus('expired'); // Set status to expired if timer runs out
          return 0;
        }
        return prev - 1;
      });
    }, 1000);
    return () => clearInterval(interval);
  }, []);

  useEffect(() => {
    let checkInterval;
    if (orderId) {
      checkInterval = setInterval(async () => {
        try {
          const res = await fetch(`https://gofotobox.online/payment/status/${orderId}`);
          const data = await res.json();
          setStatus(data.status);

          if (data.status === 'settlement') {
            setShowModal(true);
            clearInterval(checkInterval); // Stop checking once settled
          } else if (data.status === 'expired') {
            clearInterval(checkInterval); // Stop checking if expired
          }
        } catch (err) {
          console.error('Error checking status:', err);
        }
      }, 5000); // Check every 5 seconds
    }
    return () => checkInterval && clearInterval(checkInterval);
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
      setStatus('pending'); // Set initial status
    } catch (err) {
      console.error('Error fetching QR:', err);
      setStatus('error');
    } finally {
      setLoading(false);
    }
  };

  const checkPaymentStatus = async () => {
    if (!orderId) return;
    try {
      const res = await fetch(`https://gofotobox.online/payment/status/${orderId}`);
      const data = await res.json();
      setStatus(data.status);

      if (data.status === 'settlement') {
        setShowModal(true);
      } else if (data.status === 'expired') {
        alert('Pembayaran telah kadaluarsa. Silakan coba lagi.');
      }
    } catch (err) {
      console.error('Error checking status:', err);
      alert('Gagal memeriksa status pembayaran.');
    }
  };

  const handleContinue = () => {
    setShowModal(false);
    window.location.href = '/frame';
  };

  return (
    <div className="h-screen flex items-center justify-center bg-gradient-to-r from-[#FF8679] via-[#F2AAAE] to-[#F6D3AD]">
      <div className="bg-white p-6 rounded-xl shadow-lg border border-gray-200 max-w-md mx-auto">
        <h1 className="text-2xl font-bold mb-4 text-gray-800">Selesaikan Pembayaran</h1>
        <p className="mb-2 text-lg text-gray-600">Jumlah: <strong>Rp15.000</strong></p>
        <p className="mb-4 text-sm text-red-600">
          Batas waktu pembayaran: {Math.floor(timer / 60)}:{String(timer % 60).padStart(2, '0')}
        </p>

        {qrUrl ? (
          <div className="text-center">
            <div className="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-4">
              <img
                src={qrUrl}
                alt="QR Code Pembayaran"
                className="w-64 h-64 mx-auto rounded-md border-2 border-white"
              />
              <p className="mt-2 text-sm text-gray-500">
                Scan kode QR menggunakan aplikasi e-wallet kamu
              </p>
            </div>
            <p className="mb-4 text-sm text-blue-600">Status: {status || 'Menunggu Pembayaran'}</p>
            <button
              onClick={checkPaymentStatus}
              className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg mr-2"
            >
              Periksa Status
            </button>
            {status === 'expired' && (
              <button
                onClick={createQRISPayment}
                disabled={loading}
                className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg"
              >
                {loading ? 'Memuat...' : 'Buat Ulang QR'}
              </button>
            )}
          </div>
        ) : (
          <button
            onClick={createQRISPayment}
            disabled={loading || timer <= 0}
            className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg w-full transition disabled:opacity-50"
          >
            {loading ? 'Memuat QR...' : 'Buat Kode QR Pembayaran'}
          </button>
        )}
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