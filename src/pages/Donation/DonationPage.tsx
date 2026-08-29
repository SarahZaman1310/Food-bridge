import { useEffect, useState, type ChangeEvent, type FormEvent } from "react";
import { Link } from "react-router-dom";
import "./DonationPage.css";

type DonationForm = {
  donorName: string;
  donorType: string;
  email: string;
  phone: string;
  address: string;
  foodName: string;
  foodCategory: string;
  quantity: string;
  unit: string;
  expiryAt: string;
  confirmation: boolean;
};

type Donation = DonationForm & {
  id: string;
};

const donorTypes = [
  "Individual",
  "Restaurant",
  "Grocery Store",
  "Bakery",
  "Hotel",
  "Organization",
  "Other",
];

const foodCategories = [
  "Cooked Meals",
  "Fruits & Vegetables",
  "Bakery",
  "Packaged Food",
  "Dairy",
  "Dry Food",
  "Beverages",
  "Other",
];

const quantityUnits = ["portions", "kg", "boxes", "packs", "items"];

const formatExpiry = (value: string) => {
  if (!value) return "Not provided";

  const date = new Date(value);

  if (Number.isNaN(date.getTime())) {
    return value;
  }

  return new Intl.DateTimeFormat("en-US", {
    dateStyle: "medium",
    timeStyle: "short",
  }).format(date);
};

const initialState: DonationForm = {
  donorName: "",
  donorType: "Individual",
  email: "",
  phone: "",
  address: "",
  foodName: "",
  foodCategory: "Cooked Meals",
  quantity: "",
  unit: "portions",
  expiryAt: "",
  confirmation: false,
};

function DonationPage() {
  const [form, setForm] = useState<DonationForm>(initialState);
  const [donations, setDonations] = useState<Donation[]>([]);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);
  const [editingDonationId, setEditingDonationId] = useState<string | null>(
    null
  );
  const [isSubmitting, setIsSubmitting] = useState(false);

  /*
   * Load current donor's donations
   */
  useEffect(() => {
    const loadDonations = async () => {
      try {
        const token = localStorage.getItem("auth_token");

        const response = await fetch(
          "http://127.0.0.1:8000/api/food-donations",
          {
            headers: {
              Authorization: `Bearer ${token}`,
              Accept: "application/json",
            },
          }
        );

        const result = await response.json();

        if (response.ok) {
          const loadedDonations = result.data.map((donation: any) => ({
            id: donation.id.toString(),

            foodName: donation.food_name,
            foodCategory: donation.food_category,
            quantity: donation.quantity.toString(),
            unit: donation.unit,
            expiryAt: donation.expiry_at,

            donorName: donation.donor?.donor_name || "Unknown",
            donorType: donation.donor?.donor_type || "Unknown",
            email: donation.donor?.email || "",
            phone: donation.donor?.phone || "",
            address: donation.donor?.address || "",

            confirmation: true,
          }));

          setDonations(loadedDonations);
        } else {
          setSuccessMessage(
            result.message || "Failed to load donations."
          );
        }
      } catch {
        setSuccessMessage("Cannot load donations from backend.");
      }
    };

    loadDonations();
  }, []);

  /*
   * Logout
   */
  const handleLogout = async () => {
    try {
      const token = localStorage.getItem("auth_token");

      if (token) {
        await fetch("http://127.0.0.1:8000/api/logout", {
          method: "POST",
          headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
        });
      }
    } catch {
      // Even if backend logout fails,
      // clear the local session anyway.
    } finally {
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user");
      localStorage.removeItem("auth_user");

      window.location.href = "/login";
    }
  };

  /*
   * Check whether the form is ready
   */
  const isReady =
    form.donorName.trim() !== "" &&
    form.donorType.trim() !== "" &&
    form.email.trim() !== "" &&
    form.phone.trim() !== "" &&
    form.address.trim() !== "" &&
    form.foodName.trim() !== "" &&
    form.foodCategory.trim() !== "" &&
    form.quantity.trim() !== "" &&
    form.expiryAt.trim() !== "" &&
    form.confirmation;

  /*
   * Handle form changes
   */
  const handleChange = (
    event: ChangeEvent<
      HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement
    >
  ) => {
    const { name, value, type } = event.target;

    const checked =
      type === "checkbox"
        ? (event.target as HTMLInputElement).checked
        : undefined;

    setForm((previous) => ({
      ...previous,
      [name]: type === "checkbox" ? checked : value,
    }));

    if (successMessage) {
      setSuccessMessage(null);
    }
  };

  /*
   * Start editing a donation
   */
  const handleEdit = (donation: Donation) => {
    setEditingDonationId(donation.id);

    setForm({
      donorName: donation.donorName,
      donorType: donation.donorType,
      email: donation.email,
      phone: donation.phone,
      address: donation.address,
      foodName: donation.foodName,
      foodCategory: donation.foodCategory,
      quantity: donation.quantity,
      unit: donation.unit,
      expiryAt: donation.expiryAt
        ? donation.expiryAt.slice(0, 16)
        : "",
      confirmation: true,
    });

    setSuccessMessage(null);

    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  };

  /*
   * Cancel editing
   */
  const handleCancelEdit = () => {
    setEditingDonationId(null);
    setForm(initialState);
    setSuccessMessage(null);
  };

  /*
   * Create or update donation
   */
  const handleSubmit = async (event: FormEvent) => {
    event.preventDefault();

    if (!isReady || isSubmitting) {
      return;
    }

    setIsSubmitting(true);

    try {
      const token = localStorage.getItem("auth_token");

      const isEditing = editingDonationId !== null;

      const url = isEditing
        ? `http://127.0.0.1:8000/api/food-donations/${editingDonationId}`
        : "http://127.0.0.1:8000/api/food-donations";

      const response = await fetch(url, {
        method: isEditing ? "PUT" : "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
          food_name: form.foodName,
          food_category: form.foodCategory,
          quantity: Number(form.quantity),
          unit: form.unit,
          expiry_at: form.expiryAt,
        }),
      });

      const result = await response.json();

      if (!response.ok) {
        setSuccessMessage(
          result.message ||
            (isEditing
              ? "Failed to update donation."
              : "Failed to add donation.")
        );

        return;
      }

      /*
       * UPDATE
       */
      if (isEditing) {
        const updatedDonation: Donation = {
          ...form,
          id: editingDonationId,
        };

        setDonations((previous) =>
          previous.map((donation) =>
            donation.id === editingDonationId
              ? updatedDonation
              : donation
          )
        );

        setSuccessMessage("Donation updated successfully.");

        setEditingDonationId(null);
        setForm(initialState);
      }

      /*
       * CREATE
       */
      else {
        const donation = result.data;

        const newDonation: Donation = {
          id: donation.id.toString(),

          foodName: donation.food_name,
          foodCategory: donation.food_category,
          quantity: donation.quantity.toString(),
          unit: donation.unit,
          expiryAt: donation.expiry_at,

          donorName: donation.donor?.donor_name || form.donorName,
          donorType: donation.donor?.donor_type || form.donorType,
          email: donation.donor?.email || form.email,
          phone: donation.donor?.phone || form.phone,
          address: donation.donor?.address || form.address,

          confirmation: true,
        };

        setDonations((previous) => [
          newDonation,
          ...previous,
        ]);

        setSuccessMessage("Donation added successfully.");

        setForm(initialState);
      }
    } catch {
      setSuccessMessage("Cannot connect to backend.");
    } finally {
      setIsSubmitting(false);
    }
  };

  /*
   * Delete donation
   */
  const handleDelete = async (id: string) => {
    const shouldDelete = window.confirm(
      "Are you sure you want to delete this donation?"
    );

    if (!shouldDelete) {
      return;
    }

    try {
      const token = localStorage.getItem("auth_token");

      const response = await fetch(
        `http://127.0.0.1:8000/api/food-donations/${id}`,
        {
          method: "DELETE",
          headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
        }
      );

      const result = await response.json();

      if (response.ok) {
        setDonations((previous) =>
          previous.filter((donation) => donation.id !== id)
        );

        setSuccessMessage("Donation deleted successfully.");
      } else {
        setSuccessMessage(
          result.message || "Failed to delete donation."
        );
      }
    } catch {
      setSuccessMessage("Cannot connect to backend.");
    }
  };

  return (
    <div className="donation-page">
      <header className="donation-header">
        <Link
          className="brand"
          to="/"
          aria-label="FoodBridge home"
        >
          <span className="brand-mark">
            <svg
              viewBox="0 0 24 24"
              aria-hidden="true"
              className="icon"
              width="23"
              height="23"
              fill="none"
              stroke="currentColor"
              strokeWidth="1.8"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5.1 18 2 18 2c1 5.5-.2 10.8-4.7 13.4" />
              <path d="M2 21c0-4.5 4-9 14-9" />
            </svg>
          </span>

          <span>
            <strong>FoodBridge</strong>
            <small>Food · People · Impact</small>
          </span>
        </Link>

        <button
          type="button"
          className="logout-button"
          onClick={handleLogout}
        >
          Logout
        </button>
      </header>

      <main className="donation-main">
        <section
          className="section-shell donation-hero"
          aria-labelledby="donation-title"
        >
          <p className="kicker">
            SHARE FOOD. SUPPORT COMMUNITIES.
          </p>

          <h1 id="donation-title">
            {editingDonationId
              ? "Edit your donation"
              : "Donate surplus food"}
          </h1>

          <p>
            Donors can share usable surplus food with verified
            charities and communities instead of letting it go to
            waste.
          </p>
        </section>

        <section className="section-shell">
          <form
            className="donation-form"
            onSubmit={handleSubmit}
            noValidate
          >
            <div className="form-card">
              <div className="form-grid">
                <div className="form-section">
                  <h2>Your information</h2>

                  <div className="field-group">
                    <label htmlFor="donorName">
                      Donor Name
                    </label>

                    <input
                      id="donorName"
                      name="donorName"
                      type="text"
                      value={form.donorName}
                      onChange={handleChange}
                      placeholder="Enter your full name or organization name"
                      required
                    />
                  </div>

                  <div className="field-group">
                    <label htmlFor="donorType">
                      Donor Type
                    </label>

                    <select
                      id="donorType"
                      name="donorType"
                      value={form.donorType}
                      onChange={handleChange}
                      required
                    >
                      {donorTypes.map((option) => (
                        <option
                          key={option}
                          value={option}
                        >
                          {option}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div className="field-group">
                    <label htmlFor="email">
                      Email Address
                    </label>

                    <input
                      id="email"
                      name="email"
                      type="email"
                      value={form.email}
                      onChange={handleChange}
                      placeholder="name@example.com"
                      required
                    />
                  </div>

                  <div className="field-group">
                    <label htmlFor="phone">
                      Phone Number
                    </label>

                    <input
                      id="phone"
                      name="phone"
                      type="tel"
                      value={form.phone}
                      onChange={handleChange}
                      placeholder="e.g. +1 555 234 5678"
                      required
                    />
                  </div>

                  <div className="field-group">
                    <label htmlFor="address">
                      Address
                    </label>

                    <textarea
                      id="address"
                      name="address"
                      value={form.address}
                      onChange={handleChange}
                      placeholder="Enter the pickup or collection address"
                      required
                    />
                  </div>
                </div>

                <div className="form-section">
                  <h2>Food details</h2>

                  <div className="field-group">
                    <label htmlFor="foodName">
                      Food Name
                    </label>

                    <input
                      id="foodName"
                      name="foodName"
                      type="text"
                      value={form.foodName}
                      onChange={handleChange}
                      placeholder="e.g. Vegetable meals, bread, rice"
                      required
                    />
                  </div>

                  <div className="field-group">
                    <label htmlFor="foodCategory">
                      Food Category
                    </label>

                    <select
                      id="foodCategory"
                      name="foodCategory"
                      value={form.foodCategory}
                      onChange={handleChange}
                      required
                    >
                      {foodCategories.map((option) => (
                        <option
                          key={option}
                          value={option}
                        >
                          {option}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div className="field-row">
                    <div className="field-group field-half">
                      <label htmlFor="quantity">
                        Quantity
                      </label>

                      <input
                        id="quantity"
                        name="quantity"
                        type="number"
                        min="1"
                        step="1"
                        value={form.quantity}
                        onChange={handleChange}
                        placeholder="Number"
                        required
                      />
                    </div>

                    <div className="field-group field-half">
                      <label htmlFor="unit">
                        Unit
                      </label>

                      <select
                        id="unit"
                        name="unit"
                        value={form.unit}
                        onChange={handleChange}
                      >
                        {quantityUnits.map((option) => (
                          <option
                            key={option}
                            value={option}
                          >
                            {option}
                          </option>
                        ))}
                      </select>
                    </div>
                  </div>

                  <div className="field-group">
                    <label htmlFor="expiryAt">
                      Expiry / Best before
                    </label>

                    <input
                      id="expiryAt"
                      name="expiryAt"
                      type="datetime-local"
                      value={form.expiryAt}
                      onChange={handleChange}
                      required
                    />

                    <small className="helper-text">
                      Please provide the estimated time until
                      which the food remains safe to collect and
                      consume.
                    </small>
                  </div>
                </div>
              </div>

              <div
                className="safety-box"
                aria-label="Donation safety information"
              >
                <h3>Donation checklist</h3>

                <ul>
                  <li>
                    Food should be safe and suitable for
                    consumption.
                  </li>

                  <li>
                    Please provide accurate quantity and expiry
                    information.
                  </li>

                  <li>
                    A verified organisation may contact the donor
                    regarding collection.
                  </li>
                </ul>
              </div>

              <div className="confirmation-row">
                <input
                  id="confirmation"
                  name="confirmation"
                  type="checkbox"
                  checked={form.confirmation}
                  onChange={handleChange}
                />

                <label htmlFor="confirmation">
                  I confirm that the food information provided is
                  accurate and the food is suitable for donation.
                </label>
              </div>

              <div className="submit-row">
                <button
                  className="button"
                  type="submit"
                  disabled={!isReady || isSubmitting}
                >
                  {isSubmitting
                    ? editingDonationId
                      ? "Updating..."
                      : "Submitting..."
                    : editingDonationId
                      ? "Update donation"
                      : "Submit donation"}
                </button>

                {editingDonationId && (
                  <button
                    type="button"
                    className="delete-button"
                    onClick={handleCancelEdit}
                  >
                    Cancel edit
                  </button>
                )}

                {successMessage ? (
                  <p
                    className="success-message"
                    role="status"
                    aria-live="polite"
                  >
                    {successMessage}
                  </p>
                ) : null}
              </div>
            </div>
          </form>
        </section>

        <section
          className="section-shell donations-section"
          aria-labelledby="donations-title"
        >
          <div className="donations-header">
            <div>
              <p className="kicker">Your donations</p>

              <h2 id="donations-title">
                Your Donations
              </h2>
            </div>

            <p>
              Manage the food donations you have added.
            </p>
          </div>

          {donations.length === 0 ? (
            <div
              className="empty-state"
              aria-live="polite"
            >
              <h3>No donations added yet</h3>

              <p>
                Your submitted food donations will appear here.
              </p>
            </div>
          ) : (
            <div className="donation-grid">
              {donations.map((donation) => (
                <article
                  className="donation-card"
                  key={donation.id}
                >
                  <div className="card-header">
                    <h3>{donation.foodName}</h3>
                  </div>

                  <dl className="card-details">
                    <div>
                      <dt>Category</dt>
                      <dd>{donation.foodCategory}</dd>
                    </div>

                    <div>
                      <dt>Quantity</dt>
                      <dd>
                        {donation.quantity}{" "}
                        {donation.unit}
                      </dd>
                    </div>

                    <div>
                      <dt>Expires</dt>
                      <dd>
                        {formatExpiry(
                          donation.expiryAt
                        )}
                      </dd>
                    </div>

                    <div>
                      <dt>Donor</dt>
                      <dd>{donation.donorName}</dd>
                    </div>

                    <div>
                      <dt>Donor Type</dt>
                      <dd>{donation.donorType}</dd>
                    </div>

                    <div>
                      <dt>Phone</dt>
                      <dd>{donation.phone}</dd>
                    </div>

                    <div className="full-width">
                      <dt>Pickup</dt>
                      <dd>{donation.address}</dd>
                    </div>

                    <div className="full-width">
                      <dt>Email</dt>
                      <dd>{donation.email}</dd>
                    </div>
                  </dl>

                  <div className="card-footer">
                    <button
                      type="button"
                      className="button"
                      onClick={() =>
                        handleEdit(donation)
                      }
                    >
                      Edit
                    </button>

                    <button
                      type="button"
                      className="delete-button"
                      onClick={() =>
                        handleDelete(donation.id)
                      }
                    >
                      Delete
                    </button>
                  </div>
                </article>
              ))}
            </div>
          )}
        </section>
      </main>
    </div>
  );
}

export default DonationPage;
