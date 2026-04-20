# 🌱 Eco-Web Auditor

A sustainability-focused web performance tool that evaluates URLs against 20 digital ecological metrics to measure and reduce their carbon footprint.

## 🚀 Overview
The **Eco-Web Auditor** is designed to bridge the gap between standard performance audits (like Lighthouse) and digital sustainability. While performance is key, efficiency is what saves the planet. This tool analyzes "under-the-radar" factors like zombie code, green hosting, and energy-efficient design patterns to provide a weighted score and actionable technical tips.

Built as an entry for the **DEV Weekend Challenge: Earth Day Edition**.

## ✨ Features
- **20-Point Audit:** Evaluates 10 primary metrics (80% weight) and 10 secondary metrics (20% weight).
- **AI-Powered Analysis:** Leverages the **Google Gemini API** to parse HTML and provide intelligent sustainability insights.
- **Accessibility First:** High-contrast UI, screen-reader optimized, and dark-mode compatible.
- **Privacy Minded:** Server-side PHP implementation ensures API keys remain hidden from the client.
- **Actionable Results:** Provides clear 'Excellent', 'Pass', or 'Fail' statuses with specific reasons for each.

## 🛠️ Tech Stack
- **Backend:** PHP (cURL for data fetching and API communication)
- **Frontend:** HTML5, CSS3 (including BEM-style status badges and SVG progress animations)
- **AI Engine:** Google Gemini API (`gemini-flash-latest`)

## 📊 Audited Metrics

### Primary Metrics (80% Weight)
1. **Image Optimization:** Formats (WebP/AVIF) and Lazy Loading.
2. **Dark Mode Support:** Energy reduction for OLED screens.
3. **Code Minification:** Reducing payload size.
4. **Green Web Hosting:** Infrastructure powered by renewables.
5. **CDN Usage:** Reducing network distance.
6. **Browser Caching:** Minimizing redundant data transfers.
7. **Font Efficiency:** Use of system fonts or subsetting.
8. **Static vs. Dynamic:** Minimizing server-side CPU cycles.
9. **UX Simplicity:** Streamlining the user path to save energy.
10. **Zombie Data:** Removing unused 3rd-party tracking scripts.

### Secondary Metrics (Surfaced on Failure)
*DOM Size, Request Count, Gzip/Brotli, Prefetching, Unused CSS, External Library Bloat, Redirects, Lazy Loading, Meta Tag Efficiency, and Media Queries.*

## ⚙️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/alvaromontoro/eco-web-auditor.git
   ```
2. **Get an API Key:**
   Visit Google AI Studio to generate a free API key.
3. **Configure the App:**
   Open index.php and replace the placeholder with your key:
   ```php
   PHP$apiKey = "YOUR_GEMINI_API_KEY_HERE";
   ```
4. **Deploy:**
   Upload the file to any server with PHP and cURL enabled.

## 🌍 Why Digital Sustainability?
Every byte transferred over the internet requires electricity. Data centers, cooling systems, and network infrastructure contribute significantly to global $CO_2$ emissions. By optimizing our websites to be more efficient, we directly reduce the energy required for every page view.

----

Created by [Alvaro Montoro](https://alvaromontoro.com) for the [DEV Weekend Challenge](https://dev.to/challenges/weekend-2026-04-16).
