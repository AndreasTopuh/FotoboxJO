import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Landing from './pages/Landing';
import FrameSelect from './pages/FrameSelect';
import CameraSession from './pages/CameraSession';
import PaymentScreen from './pages/PaymentScreen';
import EditPage from './pages/EditPage';

export default function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Landing />} />
        <Route path="/payment" element={<PaymentScreen />} />
        <Route path="/frame" element={<FrameSelect />} />
        <Route path="/camera" element={<CameraSession />} />
        <Route path="/edit" element={<EditPage />} />
      </Routes>
    </Router>
  );
}