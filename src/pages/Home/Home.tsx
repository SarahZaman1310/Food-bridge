import { useState, type ReactNode } from "react";
import { Link } from "react-router-dom";
import "./Home.css";

type IconName =
    | "arrow"
    | "box"
    | "building"
    | "check"
    | "community"
    | "heart"
    | "leaf"
    | "menu"
    | "route"
    | "shield"
    | "spark"
    | "truck"
    | "x";

const paths: Record<IconName, ReactNode> = {
    arrow: (
        <>
            <path d="M5 12h14" />
            <path d="m13 6 6 6-6 6" />
        </>
    ),
    box: (
        <>
            <path d="m21 8-9 5-9-5" />
            <path d="m3 8 9-5 9 5v8l-9 5-9-5Z" />
            <path d="M12 13v8" />
        </>
    ),
    building: (
        <>
            <path d="M3 21h18M6 21V7l6-4 6 4v14M9 10h.01M15 10h.01M9 14h.01M15 14h.01M10 21v-3h4v3" />
        </>
    ),
    check: <path d="m5 12 4 4L19 6" />,
    community: (
        <>
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
        </>
    ),
    heart: (
        <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l7.8-7.5a5.5 5.5 0 0 0 1-8.9Z" />
    ),
    leaf: (
        <>
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5.1 18 2 18 2c1 5.5-.2 10.8-4.7 13.4" />
            <path d="M2 21c0-4.5 4-9 14-9" />
        </>
    ),
    menu: <path d="M4 7h16M4 12h16M4 17h16" />,
    route: (
        <>
            <circle cx="6" cy="19" r="2" />
            <circle cx="18" cy="5" r="2" />
            <path d="M8 19h3a4 4 0 0 0 4-4V9a4 4 0 0 1 3-4" />
        </>
    ),
    shield: (
        <>
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
            <path d="m9 12 2 2 4-4" />
        </>
    ),
    spark: (
        <path d="m12 3-1.4 4.1a5 5 0 0 1-3.1 3.1L3 12l4.5 1.8a5 5 0 0 1 3.1 3.1L12 21l1.4-4.1a5 5 0 0 1 3.1-3.1L21 12l-4.5-1.8a5 5 0 0 1-3.1-3.1Z" />
    ),
    truck: (
        <>
            <path d="M10 17h4V5H2v12h3M14 9h4l4 4v4h-3" />
            <circle cx="7.5" cy="17.5" r="2.5" />
            <circle cx="16.5" cy="17.5" r="2.5" />
        </>
    ),
    x: <path d="M6 6l12 12M18 6 6 18" />,
};

function Icon({
    name,
    size = 22,
}: {
    name: IconName;
    size?: number;
}) {
    return (
        <svg
            aria-hidden="true"
            className="icon"
            width={size}
            height={size}
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="1.8"
            strokeLinecap="round"
            strokeLinejoin="round"
        >
            {paths[name]}
        </svg>
    );
}

const values = [
    [
        "leaf",
        "Reduce food waste",
        "Give suitable surplus food another purpose.",
    ],
    [
        "heart",
        "Support communities",
        "Help food reach people who need it.",
    ],
    [
        "shield",
        "Verified organisations",
        "Connect with participating NGOs and charities.",
    ],
    [
        "truck",
        "Volunteer powered",
        "Coordinate collection and delivery support.",
    ],
] as const;

const steps = [
    [
        "box",
        "Donate",
        "Donors list surplus food with its category, quantity and availability.",
    ],
    [
        "building",
        "Request",
        "Verified charities browse suitable donations and request what communities need.",
    ],
    [
        "route",
        "Coordinate",
        "FoodBridge connects an approved request with a donation so collection can be organised.",
    ],
    [
        "truck",
        "Deliver",
        "Available volunteers collect food and deliver it to the appropriate organisation or community.",
    ],
    [
        "check",
        "Complete & feedback",
        "Delivery progress is updated and participants can share feedback after completion.",
    ],
] as const;

const roles = [
    [
        "Donors",
        "List safe surplus food and make it available to organisations supporting local communities.",
        "Donate food",
        "/donate",
        "https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=1000&q=85",
        "Volunteers organising donated food in boxes",
    ],
    [
        "NGOs & charities",
        "Find suitable donations, submit food requests and coordinate support for recipients.",
        "Request food",
        "/login",
        "https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=1000&q=85",
        "Community volunteers working together",
    ],
    [
        "Volunteers",
        "Help collect and deliver donated food wherever it is needed.",
        "Join as volunteer",
        "/login",
        "https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=1000&q=85",
        "Volunteer supporting a community food programme",
    ],
    [
        "Communities",
        "Food reaches households through participating charities and community organisations.",
        "How it works",
        "#how-it-works",
        "https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?auto=format&fit=crop&w=1000&q=85",
        "People sharing support in their community",
    ],
] as const;

const foods = [
    [
        "Fresh fruit & vegetables",
        "Produce",
        "https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=900&q=85",
        "Colourful fresh fruit and vegetables",
    ],
    [
        "Prepared meals",
        "Ready-to-eat",
        "https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&w=900&q=85",
        "Healthy prepared meals ready for sharing",
    ],
    [
        "Bread & bakery items",
        "Bakery",
        "https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=85",
        "Fresh loaves of bread and bakery items",
    ],
] as const;

const benefits = [
    [
        "leaf",
        "Less food waste",
        "Help usable surplus food avoid unnecessary disposal.",
    ],
    [
        "community",
        "Better connections",
        "Bring donors and community organisations together in one place.",
    ],
    [
        "truck",
        "Coordinated delivery",
        "Connect approved requests with available volunteers.",
    ],
    [
        "heart",
        "Community support",
        "Help organisations provide food support more efficiently.",
    ],
    [
        "route",
        "Traceable progress",
        "Delivery updates help participants follow each collection.",
    ],
    [
        "spark",
        "Helpful feedback",
        "Share feedback once a delivery has been completed.",
    ],
] as const;

function Home() {
    const [menuOpen, setMenuOpen] = useState(false);

    const closeMenu = () => setMenuOpen(false);

    return (
        <div className="home-page">
            <header className="site-header">
                <div className="nav-shell">
                    <a
                        className="brand"
                        href="#top"
                        aria-label="FoodBridge home"
                        onClick={closeMenu}
                    >
                        <span className="brand-mark">
                            <Icon name="leaf" size={24} />
                        </span>

                        <span>
                            <strong>FoodBridge</strong>
                            <small>Food · People · Impact</small>
                        </span>
                    </a>

                    <button
                        className="menu-toggle"
                        type="button"
                        aria-expanded={menuOpen}
                        aria-controls="primary-navigation"
                        aria-label={
                            menuOpen
                                ? "Close navigation menu"
                                : "Open navigation menu"
                        }
                        onClick={() => setMenuOpen((v) => !v)}
                    >
                        <Icon name={menuOpen ? "x" : "menu"} />
                    </button>

                    <nav
                        id="primary-navigation"
                        className={`primary-nav ${menuOpen ? "is-open" : ""}`}
                        aria-label="Primary navigation"
                    >
                        <a href="#top" onClick={closeMenu}>
                            Home
                        </a>

                        <a href="#how-it-works" onClick={closeMenu}>
                            How it works
                        </a>

                        <Link to="/donate" onClick={closeMenu}>
    Donate food
</Link>

                        <Link to="/login" onClick={closeMenu}>
                            Request food
                        </Link>

                        <a href="#volunteer" onClick={closeMenu}>
                            Volunteer
                        </a>

                        <a href="#about" onClick={closeMenu}>
                            About
                        </a>
                    </nav>

                    <div className="nav-actions">
                      

                        <Link className="button button-small" to="/login">
                            Get started
                            <Icon name="arrow" size={17} />
                        </Link>
                    </div>
                </div>
            </header>

            <main id="top">
                <section
                    className="hero section-shell"
                    aria-labelledby="hero-title"
                >
                    <div className="hero-copy">
                        <p className="eyebrow">
                            <Icon name="leaf" size={15} />
                            Reducing food waste. Supporting communities.
                        </p>

                        <h1 id="hero-title">
                            Good food deserves a <em>second chance.</em>
                        </h1>

                        <p className="hero-lead">
                            FoodBridge connects donors with verified charities
                            and volunteers, helping surplus food reach people
                            who need it instead of going to waste.
                        </p>

                        <div className="button-row">
                            <Link className="button" to="/donate">
    Donor
                                <Icon name="arrow" size={18} />
                            </Link>

                            <Link
                                className="button button-outline"
                                to="/login"
                            >
                                Recipient
                            </Link>
                        </div>

                        <a className="volunteer-link" href="#volunteer">
                            Become a volunteer
                            <Icon name="arrow" size={16} />
                        </a>
                    </div>

                    <div className="hero-media">
                        <img
                            src="https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=1400&q=88"
                            alt="Volunteers preparing boxes of fresh food for community distribution"
                        />

                        <div className="hero-note hero-note-top">
                            <span className="note-icon">
                                <Icon name="shield" size={20} />
                            </span>

                            <span>
                                <strong>Verified charity network</strong>
                                <small>Trusted community connections</small>
                            </span>
                        </div>

                        <div className="hero-note hero-note-bottom">
                            <span className="note-icon warm">
                                <Icon name="box" size={20} />
                            </span>

                            <span>
                                <strong>Fresh food, shared</strong>
                                <small>Surplus becomes support</small>
                            </span>
                        </div>
                    </div>
                </section>

                <section
                    className="value-strip"
                    aria-label="FoodBridge benefits"
                >
                    <div className="section-shell value-grid">
                        {values.map((v) => (
                            <article key={v[1]}>
                                <span className="value-icon">
                                    <Icon name={v[0]} />
                                </span>

                                <div>
                                    <h2>{v[1]}</h2>
                                    <p>{v[2]}</p>
                                </div>
                            </article>
                        ))}
                    </div>
                </section>

                <section
                    className="section-shell section-block how"
                    id="how-it-works"
                >
                    <div className="section-heading centered">
                        <p className="kicker">
                            A simple, coordinated journey
                        </p>

                        <h2>How FoodBridge works</h2>

                        <p>From surplus food to someone’s table.</p>
                    </div>

                    <div className="steps">
                        {steps.map((s, i) => (
                            <article className="step" key={s[1]}>
                                <span className="step-number">
                                    {String(i + 1).padStart(2, "0")}
                                </span>

                                <span className="step-icon">
                                    <Icon name={s[0]} />
                                </span>

                                <h3>{s[1]}</h3>
                                <p>{s[2]}</p>
                            </article>
                        ))}
                    </div>

                    <div
                        className="workflow"
                        aria-label="FoodBridge workflow"
                    >
                        <span>Donor</span>
                        <Icon name="arrow" />
                        <span>Food donation</span>
                        <Icon name="arrow" />
                        <span>Charity request</span>
                        <Icon name="arrow" />
                        <span>Volunteer delivery</span>
                        <Icon name="arrow" />
                        <span>Community</span>
                    </div>
                </section>

                <section className="photo-story section-shell section-block">
                    <div className="photo-collage">
                        <img
                            className="photo-main"
                            src="https://images.unsplash.com/photo-1599059813005-11265ba4b4ce?auto=format&fit=crop&w=1100&q=85"
                            alt="A box filled with fresh vegetables for donation"
                        />

                        <img
                            className="photo-small"
                            src="https://images.unsplash.com/photo-1593113616828-6f22bca04804?auto=format&fit=crop&w=800&q=85"
                            alt="Volunteers sorting supplies for community support"
                        />

                        <div className="collage-caption">
                            <Icon name="heart" size={20} />
                            Food · People · Impact
                        </div>
                    </div>

                    <div className="story-copy">
                        <p className="kicker">Rescue good food</p>

                        <h2>
                            Food that can help shouldn’t become waste.
                        </h2>

                        <p>
                            From fresh produce to prepared meals, FoodBridge
                            helps connect suitable surplus food with
                            organisations supporting people in the community.
                        </p>

                        <Link className="button" to="/donate">
    Share surplus food
                            <Icon name="arrow" size={18} />
                        </Link>
                    </div>
                </section>

                <section
                    className="roles-section section-block"
                    id="about"
                >
                    <div className="section-shell">
                        <div className="section-heading">
                            <p className="kicker">
                                Built for everyone involved
                            </p>

                            <h2>Who uses FoodBridge?</h2>

                            <p>
                                One shared platform, designed around each part
                                of the food support journey.
                            </p>
                        </div>

                        <div className="role-grid">
                            {roles.map((r) => (
                                <article className="role-card" key={r[0]}>
                                    <div className="role-image">
                                        <img src={r[4]} alt={r[5]} />
                                    </div>

                                    <div className="role-body">
                                        <h3>{r[0]}</h3>
                                        <p>{r[1]}</p>

                                        {r[3].startsWith("/") ? (
                                            <Link to={r[3]}>
                                                {r[2]}
                                                <Icon
                                                    name="arrow"
                                                    size={16}
                                                />
                                            </Link>
                                        ) : (
                                            <a href={r[3]}>
                                                {r[2]}
                                                <Icon
                                                    name="arrow"
                                                    size={16}
                                                />
                                            </a>
                                        )}
                                    </div>
                                </article>
                            ))}
                        </div>
                    </div>
                </section>

                <section className="section-shell section-block food-showcase">
                    <div className="section-heading heading-row">
                        <div>
                            <p className="kicker">
                                Example donation categories
                            </p>

                            <h2>Food ready to make a difference</h2>
                        </div>

                        <p>
                            These examples show the types of suitable surplus
                            food that donors may list on FoodBridge.
                        </p>
                    </div>

                    <div className="food-grid">
                        {foods.map((f) => (
                            <article className="food-card" key={f[0]}>
                                <div className="food-image">
                                    <img src={f[2]} alt={f[3]} />

                                    <span className="status">
                                        <i />
                                        Available
                                    </span>
                                </div>

                                <div className="food-card-body">
                                    <span>{f[1]}</span>
                                    <h3>{f[0]}</h3>
                                    <p>Example listing</p>
                                </div>
                            </article>
                        ))}
                    </div>
                </section>

                <section className="benefits-section section-block">
                    <div className="section-shell benefits-layout">
                        <div className="section-heading">
                            <p className="kicker">Why FoodBridge?</p>

                            <h2>
                                Small actions.
                                <br />
                                Meaningful impact.
                            </h2>

                            <p>
                                A clearer way to coordinate surplus food from
                                first listing to completed delivery.
                            </p>
                        </div>

                        <div className="benefit-grid">
                            {benefits.map((b) => (
                                <article key={b[1]}>
                                    <span>
                                        <Icon name={b[0]} />
                                    </span>

                                    <h3>{b[1]}</h3>
                                    <p>{b[2]}</p>
                                </article>
                            ))}
                        </div>
                    </div>
                </section>

                <section className="feature section-shell section-block">
                    <div className="feature-media">
                        <img
                            src="https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=1200&q=85"
                            alt="Charity volunteers joining hands as a team"
                        />

                        <span className="image-label">
                            <Icon name="building" size={18} />
                            For NGOs & charities
                        </span>
                    </div>

                    <FeatureCopy ngo />
                </section>

                <section
                    className="feature feature-reverse section-shell section-block"
                    id="volunteer"
                >
                    <div className="feature-media">
                        <img
                            src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=1200&q=85"
                            alt="A group of community volunteers working together"
                        />

                        <span className="image-label">
                            <Icon name="truck" size={18} />
                            Volunteer powered
                        </span>
                    </div>

                    <FeatureCopy />
                </section>

                <section className="final-cta section-shell">
                    <div>
                        <p className="kicker">
                            Turn surplus into support
                        </p>

                        <h2>
                            Have food to share?
                            <br />
                            Someone can use it.
                        </h2>

                        <p>
                            Join FoodBridge and help turn surplus food into
                            community support.
                        </p>
                    </div>

                    <div className="cta-actions">
                        <Link className="button button-light" to="/donate">
    Donate food
                            <Icon name="arrow" size={18} />
                        </Link>

                        <Link
                            className="button button-ghost"
                            to="/login"
                        >
                            Request food
                        </Link>

                        <a href="#volunteer">Volunteer with us</a>
                    </div>
                </section>
            </main>

            <footer className="site-footer">
                <div className="section-shell footer-grid">
                    <div className="footer-intro">
                        <a
                            className="brand brand-footer"
                            href="#top"
                        >
                            <span className="brand-mark">
                                <Icon name="leaf" size={24} />
                            </span>

                            <span>
                                <strong>FoodBridge</strong>
                                <small>Food · People · Impact</small>
                            </span>
                        </a>

                        <p>
                            Connecting surplus food with communities through
                            donors, charities and volunteers.
                        </p>
                    </div>

                    <FooterLinks
                        title="Platform"
                        links={[
                            ["Donate food", "/donate"],
                            ["Request food", "/login"],
                            ["Volunteer", "#volunteer"],
                        ]}
                    />

                    <FooterLinks
                        title="Learn"
                        links={[
                            ["How it works", "#how-it-works"],
                            ["About FoodBridge", "#about"],
                            ["Our impact", "#about"],
                        ]}
                    />

                    <FooterLinks
                        title="Account"
                        links={[
                            ["Log in", "/login"],
                            ["Get started", "/login"],
                        ]}
                    />
                </div>

                <div className="section-shell footer-bottom">
                    <span>© FoodBridge. University project.</span>
                    <a href="#top">Back to top ↑</a>
                </div>
            </footer>
        </div>
    );
}

function FeatureCopy({ ngo = false }: { ngo?: boolean }) {
    const list = ngo
        ? [
              "Browse suitable donations",
              "Request required quantities",
              "Coordinate collection and delivery",
              "Support registered recipients",
          ]
        : [];

    return (
        <div className="feature-copy">
            <p className="kicker">
                {ngo
                    ? "Food support, easier to coordinate"
                    : "Make the connection"}
            </p>

            <h2>
                {ngo
                    ? "Helping charities find food when communities need it."
                    : "A few hours can move food where it matters."}
            </h2>

            <p>
                {ngo
                    ? "Registered NGOs and charities can explore suitable donations, submit requests and coordinate food support through FoodBridge."
                    : "FoodBridge volunteers help connect approved food requests with communities by assisting with pickup and delivery."}
            </p>

            {ngo ? (
                <ul>
                    {list.map((x) => (
                        <li key={x}>
                            <Icon name="check" size={17} />
                            {x}
                        </li>
                    ))}
                </ul>
            ) : (
                <div className="chips">
                    <span>Flexible availability</span>
                    <span>Pickup coordination</span>
                    <span>Community delivery</span>
                    <span>Delivery updates</span>
                </div>
            )}

            <Link className="button" to="/login">
                {ngo ? "Explore food support" : "Become a volunteer"}
                <Icon name="arrow" size={18} />
            </Link>
        </div>
    );
}

function FooterLinks({
    title,
    links,
}: {
    title: string;
    links: readonly (readonly [string, string])[];
}) {
    return (
        <div>
            <h2>{title}</h2>

            {links.map(([label, href]) =>
                href.startsWith("/") ? (
                    <Link key={label} to={href}>
                        {label}
                    </Link>
                ) : (
                    <a key={label} href={href}>
                        {label}
                    </a>
                ),
            )}
        </div>
    );
}

export default Home;