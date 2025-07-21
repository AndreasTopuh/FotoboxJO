// import { useEffect, useState } from 'react';
// import { useNavigate } from 'react-router-dom';

// export default function PaymentScreen() {
//   const navigate = useNavigate();
//   const [qrUrl, setQrUrl] = useState(null);
//   const [vaNumber, setVaNumber] = useState(null);
//   const [loading, setLoading] = useState(false);
//   const [orderId, setOrderId] = useState(null);
//   const [timer, setTimer] = useState(300); // 5 minutes in seconds
//   const [status, setStatus] = useState(null);
//   const [showPopup, setShowPopup] = useState(false);
//   const paymentMethod = localStorage.getItem('payment_method') || 'qris';

//   // Initialize session from localStorage
//   useEffect(() => {
//     const savedOrderId = localStorage.getItem('order_id');
//     const savedQrUrl = localStorage.getItem('qr_url');
//     const savedVaNumber = localStorage.getItem('va_number');
//     const sessionStart = localStorage.getItem('session_start');

//     if (sessionStart) {
//       const elapsed = Math.floor((Date.now() - parseInt(sessionStart)) / 1000);
//       const remainingTime = 300 - elapsed;
//       if (remainingTime <= 0) {
//         localStorage.clear();
//         navigate('/');
//         return;
//       }
//       setTimer(remainingTime);
//     } else {
//       // No session exists, redirect to landing
//       navigate('/');
//       return;
//     }

//     if (savedOrderId) {
//       setOrderId(savedOrderId);
//       if (savedQrUrl) setQrUrl(savedQrUrl);
//       if (savedVaNumber) setVaNumber(savedVaNumber);
//     }
//   }, [navigate]);

//   // Timer countdown
//   useEffect(() => {
//     const interval = setInterval(() => {
//       setTimer((prev) => {
//         if (prev <= 1) {
//           clearInterval(interval);
//           localStorage.clear();
//           navigate('/');
//         }
//         return prev - 1;
//       });
//     }, 1000);

//     return () => clearInterval(interval);
//   }, [navigate]);

//   // Auto-polling payment status
//   useEffect(() => {
//     if (!orderId) return;
//     const checkInterval = setInterval(async () => {
//       try {
//         const res = await fetch(`https://gofotobox.online/payment/status/${orderId}`);
//         const data = await res.json();
//         setStatus(data.status);
//       } catch (err) {
//         console.error('Gagal cek status:', err);
//       }
//     }, 5000);
//     return () => clearInterval(checkInterval);
//   }, [orderId]);

//   // Create new payment
//   const createPayment = async () => {
//     setLoading(true);
//     try {
//       const res = await fetch('https://gofotobox.online/payment/create', {
//         method: 'POST',
//         headers: { 'Content-Type': 'application/json' },
//         body: JSON.stringify({
//           amount: 15000,
//           method: paymentMethod,
//         }),
//       });
//       const data = await res.json();
//       if (paymentMethod === 'qris') {
//         setQrUrl(data.qr_url);
//         localStorage.setItem('qr_url', data.qr_url);
//       } else if (paymentMethod === 'bank_transfer') {
//         setVaNumber(data.va_number);
//         localStorage.setItem('va_number', data.va_number);
//       }
//       setOrderId(data.order_id);
//       localStorage.setItem('order_id', data.order_id);
//     } catch (err) {
//       console.error('Error creating payment:', err);
//     } finally {
//       setLoading(false);
//     }
//   };

//   // Show payment status popup
//   const checkPaymentStatus = () => {
//     setShowPopup(true);
//   };

//   // Close popup
//   const closePopup = () => {
//     setShowPopup(false);
//   };

//   // Navigate to frame select on success
//   const proceedToFrameSelect = () => {
//     localStorage.clear();
//     navigate('/frame');
//   };

//   return (
//     <div className="h-screen flex flex-col items-center justify-center bg-gray-50">
//       <div className="text-center max-w-md mx-auto px-4">
//         <h1 className="text-3xl font-bold mb-4 text-gray-800">Selesaikan Pembayaran</h1>
//         <p className="mb-2 text-lg text-gray-600">Jumlah: <strong>Rp15.000</strong></p>
//         <p className="mb-4 text-sm text-red-600">
//           Batas waktu pembayaran: {Math.floor(timer / 60)}:{String(timer % 60).padStart(2, '0')}
//         </p>

//         {qrUrl && paymentMethod === 'qris' ? (
//           <>
//             <img src={qrUrl} alt="QR Code Pembayaran" className="w-64 h-64 mx-auto my-4 border p-2 bg-white" />
//             <p className="text-sm text-gray-500">Scan kode QR menggunakan aplikasi e-wallet kamu</p>
//           </>
//         ) : vaNumber && paymentMethod === 'bank_transfer' ? (
//           <>
//             <p className="text-lg font-semibold text-gray-800">
//               Nomor Virtual Account: <strong>{vaNumber}</strong>
//             </p>
//             <p className="text-sm text-gray-500">Lakukan pembayaran melalui bank BCA</p>
//           </>
//         ) : (
//           <button
//             onClick={createPayment}
//             disabled={loading || timer <= 0}
//             className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition disabled:opacity-50 w-full"
//           >
//             {loading ? 'Memuat...' : paymentMethod === 'qris' ? 'Buat Kode QR Pembayaran' : 'Buat Virtual Account'}
//           </button>
//         )}

//         {(qrUrl || vaNumber) && (
//           <>
//             {status && (
//               <p
//                 className={`mt-2 text-sm font-medium ${
//                   status === 'settlement'
//                     ? 'text-green-600'
//                     : status === 'pending'
//                     ? 'text-yellow-600'
//                     : ['expire', 'cancel', 'failure'].includes(status)
//                     ? 'text-red-600'
//                     : 'text-gray-600'
//                 }`}
//               >
//                 Status: {status}
//               </p>
//             )}
//             <button
//               onClick={checkPaymentStatus}
//               className="mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded transition w-full"
//             >
//               Cek Pembayaran
//             </button>
//           </>
//         )}

//         {showPopup && (
//           <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
//             <div className="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full text-center">
//               {status === 'settlement' ? (
//                 <>
//                   <h2 className="text-xl font-bold mb-4 text-green-600">Terima Kasih!</h2>
//                   <p className="text-gray-600 mb-4">Pembayaran Anda telah berhasil.</p>
//                   <button
//                     onClick={proceedToFrameSelect}
//                     className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition w-full"
//                   >
//                     Lanjutkan
//                   </button>
//                 </>
//               ) : (
//                 <>
//                   <h2 className="text-xl font-bold mb-4 text-red-600">Pembayaran Belum Selesai</h2>
//                   <p className="text-gray-600 mb-4">
//                     Tolong lakukan pembayaran sesuai metode yang dipilih.
//                   </p>
//                   <button
//                     onClick={closePopup}
//                     className="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded transition w-full"
//                   >
//                     OK
//                   </button>
//                 </>
//               )}
//             </div>
//           </div>
//         )}
//       </div>
//     </div>
//   );
// }

import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

const PaymentScreen = () => {
  const navigate = useNavigate();
  const [session, setSession] = useState(null);
  const [status, setStatus] = useState('pending');
  const [remainingTime, setRemainingTime] = useState(300); // in seconds

  useEffect(() => {
    const data = JSON.parse(sessionStorage.getItem('paymentSession'));
    if (!data) return navigate('/');

    setSession(data);
    updateRemainingTime(data.startTime);

    const interval = setInterval(() => {
      updateRemainingTime(data.startTime);
    }, 1000);

    return () => clearInterval(interval);
  }, []);

  useEffect(() => {
    const fetchLogs = async () => {
      try {
        const res = await fetch('https://gofotobox.online/payment/log');
        const data = await res.json();
        data.logs.forEach(msg => alert(msg)); // ⛳ munculkan log backend sebagai alert
      } catch (err) {
        console.error('Gagal ambil log backend:', err);
      }
    };

    fetchLogs(); // panggil langsung saat load
  }, []);

  const updateRemainingTime = (startTime) => {
    const now = Date.now();
    const elapsed = Math.floor((now - startTime) / 1000);
    const remaining = 300 - elapsed;

    if (remaining <= 0) {
      sessionStorage.removeItem('paymentSession');
      navigate('/');
    } else {
      setRemainingTime(remaining);
    }
  };

  const cekStatus = async () => {
    try {
      const res = await axios.get(`https://gofotobox.online/payment/status/${session.order_id}`);
      setStatus(res.data.status);
      if (res.data.status === 'settlement') {
        navigate('/frame');
      } else {
        alert('Pembayaran belum selesai, ayo lanjutkan dulu!');
      }
    } catch (err) {
      console.error('Gagal cek status:', err);
    }
  };

  if (!session) return null;

  return (
    <div>
      <h2>Silakan Selesaikan Pembayaran</h2>
      <p>Sisa waktu: {Math.floor(remainingTime / 60)}:{String(remainingTime % 60).padStart(2, '0')}</p>
      {session.method === 'qris' && (
        <>
          <h3>QRIS</h3>
          <img src={session.qr_url} alt="Kode QR" style={{ width: '300px' }} />
        </>
      )}
      {session.method === 'bank_transfer' && (
        <>
          <h3>Virtual Account BCA</h3>
          <p>No. Virtual Account: <strong>{session.va_number}</strong></p>
        </>
      )}
      <button onClick={cekStatus}>Cek Status</button>
    </div>
  );
};

export default PaymentScreen;
