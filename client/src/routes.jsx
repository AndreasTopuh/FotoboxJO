import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Landing from './pages/Landing';
import ChoosePackage from './pages/ChoosePackage';
import Payment from './pages/Payment';
import FrameSelect from './pages/FrameSelect';
// import CameraSession from './pages/CameraSession';
// import PhotoEditor from './pages/PhotoEditor';
// import Preview from './pages/Preview';
// import Output from './pages/Output';

export default function AppRoutes() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Landing />} />
        <Route path="/paket" element={<ChoosePackage />} />
        <Route path="/payment" element={<Payment />} />
        <Route path="/frame" element={<FrameSelect />} />
        {/* <Route path="/camera" element={<CameraSession />} /> */}
        {/* <Route path="/edit" element={<PhotoEditor />} /> */}
        {/* <Route path="/preview" element={<Preview />} /> */}
        {/* <Route path="/output" element={<Output />} /> */}
      </Routes>
    </Router>
  );
}