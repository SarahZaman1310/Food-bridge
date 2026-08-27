import { BrowserRouter, Routes, Route } from "react-router-dom";

import Home from "./pages/Home/Home";
import Login from "./pages/Auth/Login";
import Signup from "./pages/Auth/Signup";
import DonationPage from "./pages/Donation/DonationPage";
import VolunteerPage from "./pages/Volunteer/VolunteerPage";
import ProtectedRoute from "./pages/Auth/ProtectedRoute";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Public pages */}
        <Route path="/" element={<Home />} />
        <Route path="/login" element={<Login />} />
        <Route path="/signup" element={<Signup />} />

        {/* Protected donor page */}
        <Route
          path="/donate"
          element={
            <ProtectedRoute>
              <DonationPage />
            </ProtectedRoute>
          }
        />

        <Route
          path="/volunteer"
          element={
            <ProtectedRoute requiredRole="volunteer">
              <VolunteerPage />
            </ProtectedRoute>
          }
        />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
