import { Link, useNavigate } from "react-router-dom";
import "./Auth.css";

function Signup() {
  const navigate = useNavigate();

  const handleSignup = (e: React.FormEvent) => {
    e.preventDefault();

    // Dummy signup for now
    // Later this will connect to Laravel
    navigate("/donor");
  };

  return (
    <div className="auth-page">
      <div className="auth-card signup-card">
        <div className="auth-brand">
          <div className="auth-logo">🌿</div>
          <div>
            <h1>FoodBridge</h1>
            <span>Food · People · Impact</span>
          </div>
        </div>

        <div className="auth-heading">
          <p>JOIN FOODBRIDGE</p>
          <h2>Create your account</h2>
          <span>
            Become part of a community working to reduce food waste.
          </span>
        </div>

        <form onSubmit={handleSignup}>
          <div className="form-group">
            <label>Full name</label>
            <input
              type="text"
              placeholder="Enter your full name"
              required
            />
          </div>

          <div className="form-group">
            <label>Email address</label>
            <input
              type="email"
              placeholder="Enter your email"
              required
            />
          </div>

          <div className="form-group">
            <label>Phone number</label>
            <input
              type="tel"
              placeholder="Enter your phone number"
              required
            />
          </div>

          <div className="form-group">
            <label>I want to join as</label>

            <select required>
              <option value="">Select a role</option>
              <option value="donor">Donor</option>
              <option value="ngo">NGO / Charity</option>
              <option value="volunteer">Volunteer</option>
              <option value="recipient">Recipient</option>
            </select>
          </div>

          <div className="form-group">
            <label>Password</label>
            <input
              type="password"
              placeholder="Create a password"
              required
            />
          </div>

          <button type="submit" className="auth-button">
            Create account
          </button>
        </form>

        <p className="auth-switch">
          Already have an account?{" "}
          <Link to="/login">Log in</Link>
        </p>

        <Link to="/" className="back-home">
          ← Back to FoodBridge
        </Link>
      </div>

      <div className="auth-image signup-image">
        <div className="auth-overlay">
          <span>MAKE AN IMPACT</span>
          <h2>One connection can make a difference.</h2>
          <p>
            Join donors, charities and volunteers working together to give
            surplus food a second chance.
          </p>
        </div>
      </div>
    </div>
  );
}

export default Signup;