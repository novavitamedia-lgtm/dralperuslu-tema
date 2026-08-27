/** @type {import('tailwindcss').Config} */
module.exports = {
  // WP şablonları + statik önizleme üreticisi (bileşen HTML'i .py string'lerinde) + üretilen HTML
  content: [
    "./**/*.php",
    "../scripts/build_preview.py",
    "../preview/**/*.html",
  ],
  theme: {
    container: {
      center: true,
      padding: { DEFAULT: "1.25rem", lg: "2rem" },
      screens: { "2xl": "1200px" },
    },
    extend: {
      colors: {
        brand: {
          50: "#ECF7F5", 100: "#E6F4F2", 300: "#7FCFC7",
          400: "#38BDB2", 500: "#1AA79C", 600: "#12857D", 700: "#0F6B65", 800: "#0B534E",
        },
        ink: { 900: "#12201F", 700: "#324241", 500: "#5C6E6C" },
        cream: { 50: "#F8F6F1", 100: "#F1ECE3" },
        gold: { 500: "#C39B4A", 400: "#D4B368" },
        line: "#E3E8E7",
      },
      fontFamily: {
        display: ['Fraunces', 'Georgia', 'serif'],
        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
      },
      fontSize: {
        "hero": ["clamp(2.5rem, 5vw, 4.25rem)", { lineHeight: "1.05", letterSpacing: "-0.02em" }],
        "h2": ["clamp(1.9rem, 3.2vw, 2.75rem)", { lineHeight: "1.12", letterSpacing: "-0.015em" }],
        "h3": ["1.35rem", { lineHeight: "1.2" }],
        "lead": ["1.15rem", { lineHeight: "1.7" }],
      },
      maxWidth: { prose: "68ch" },
      borderRadius: { xl2: "1.25rem" },
      boxShadow: {
        card: "0 10px 30px -12px rgba(18,133,125,0.18)",
        cardHover: "0 18px 40px -12px rgba(18,133,125,0.28)",
        soft: "0 2px 10px rgba(18,32,31,0.06)",
      },
      transitionTimingFunction: {
        out: "cubic-bezier(0.16, 1, 0.3, 1)",
        spring: "cubic-bezier(0.34, 1.56, 0.64, 1)",
      },
      keyframes: {
        fadeInUp: { "0%": { opacity: "0", transform: "translateY(24px)" }, "100%": { opacity: "1", transform: "translateY(0)" } },
        kenBurns: { "0%": { transform: "scale(1)" }, "100%": { transform: "scale(1.08)" } },
        marquee: { "0%": { transform: "translateX(0)" }, "100%": { transform: "translateX(-50%)" } },
        floaty: { "0%,100%": { transform: "translateY(0)" }, "50%": { transform: "translateY(-10px)" } },
      },
      animation: {
        kenBurns: "kenBurns 16s ease-out both",
        marquee: "marquee 30s linear infinite",
        floaty: "floaty 6s ease-in-out infinite",
      },
    },
  },
  safelist: [
    "animate-marquee", "animate-kenBurns", "animate-floaty",
  ],
  plugins: [],
};
