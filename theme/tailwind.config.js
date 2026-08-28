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
          400: "#38BDB2", 500: "#1AA79C", 600: "#12857D", 700: "#0E7D6E", 800: "#0B534E",
        },
        // embraceyoursmile imza CTA (coral daire-ok)
        coral: { 400: "#F4685A", 500: "#F04A3C", 600: "#D93A2E" },
        ink: { 900: "#16302A", 700: "#3B4A46", 500: "#6B7A76" },
        cream: { 50: "#F7F5F1", 100: "#EFEBE3" },
        gold: { 500: "#C39B4A", 400: "#D4B368" },
        line: "#E3E8E7",
      },
      fontFamily: {
        display: ['"Playfair Display"', 'Georgia', 'serif'],
        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
      },
      fontSize: {
        // embraceyoursmile: çok büyük dramatik editorial serif
        "hero": ["clamp(3.2rem, 8vw, 6.5rem)", { lineHeight: "0.98", letterSpacing: "-0.02em" }],
        "h2": ["clamp(2.2rem, 4.5vw, 3.6rem)", { lineHeight: "1.06", letterSpacing: "-0.015em" }],
        "h3": ["1.45rem", { lineHeight: "1.2" }],
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
