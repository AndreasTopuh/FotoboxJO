// import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
// import Landing from './pages/Landing';
// import FrameSelect from './pages/FrameSelect';
// import FrameTemplate from './pages/FrameTemplate';
// import CameraSession from './pages/CameraSession';
// import PaymentScreen from './pages/PaymentScreen';
// import EditPage from './pages/EditPage';
// import ResultPage from './pages/ResultPage';
// import SelectPayment from './pages/SelectPayment';

// export default function App() {
//   return (
//     <Router>
//       <Routes>
//         <Route path="/" element={<Landing />} />
//         <Route path="/selectpayment" element={<SelectPayment />} />
//         <Route path="/payment" element={<PaymentScreen />} />
//         <Route path="/frame" element={<FrameSelect />} />
//         <Route path="/frametemplate" element={<FrameTemplate />} />
//         <Route path="/camera" element={<CameraSession />} />
//         <Route path="/edit" element={<EditPage />} />
//         <Route path="/result" element={<ResultPage />} />
//       </Routes>
//     </Router>
//   );
// }

import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Landing from './pages/Landing';
import SelectPayment from './pages/SelectPayment';
import PaymentScreen from './pages/PaymentScreen';
import FrameSelect from './pages/FrameSelect';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Landing />} />
        <Route path="/select-payment" element={<SelectPayment />} />
        <Route path="/payment" element={<PaymentScreen />} />
        <Route path="/frame" element={<FrameSelect />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;