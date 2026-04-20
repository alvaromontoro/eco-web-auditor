<?php
/**
 * Eco-Web Auditor - Advanced 20-Metric Edition
 */

$apiKey = "My Gemini API key was here, but I deleted it before uploading it to GitHub :)";
$results = null;
$targetUrl = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['url'])) {
    $targetUrl = filter_var($_POST['url'], FILTER_SANITIZE_URL);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $targetUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 12); // Slightly longer for more metrics
    $htmlContent = curl_exec($ch);
    curl_close($ch);

    if ($htmlContent) {
        $htmlSnippet = substr(strip_tags($htmlContent, '<img><link><script><html><head><a>'), 0, 8000);
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $apiKey;
        
        // Updated Prompt with 20 metrics and weighting instructions
        $prompt = "Act as a Green Web Auditor. Analyze this HTML for 20 metrics.
        PRIMARY (Weight 80%): Image Optimization, Dark Mode, Code Minification, Green Hosting, CDN, Caching, Fonts, Static vs Dynamic, UX Simplicity, Zombie Data.
        SECONDARY (Weight 20%): DOM Size, Request Count, Gzip/Brotli, Prefetching, Unused CSS, External Library Bloat, Redirects, Lazy Loading, Meta Tag Efficiency, Media Queries.
        
        Return ONLY a JSON object: {
            \"score\": 0-100, 
            \"results\": [{\"metric\": \"Name\", \"status\": \"Excellent|Pass|Fail|N/A\", \"reason\": \"short\"}],
            \"secondary_fails\": [\"Metric Name 1\", \"Metric Name 2\"]
        }. 
        Include only the 10 PRIMARY metrics in the 'results' array. For the 10 SECONDARY metrics, only list their names in 'secondary_fails' if they are a 'Fail'.
        HTML: " . $htmlSnippet;

        $payload = json_encode(["contents" => [["parts" => [["text" => $prompt]]]]]);

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        $response = curl_exec($ch);
        curl_close($ch);

        $jsonRes = json_decode($response, true);
        if (isset($jsonRes['candidates'][0]['content']['parts'][0]['text'])) {
            $rawText = $jsonRes['candidates'][0]['content']['parts'][0]['text'];
            $cleanJson = str_replace(['```json', '```'], '', $rawText);
            $results = json_decode($cleanJson, true);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A sustainability-focused web auditor to measure digital carbon footprints.">
    <meta name="color-scheme" content="light dark">
    <title>Eco-Web Auditor | DEV Weekend Challenge</title>
    <link rel="dns-prefetch" href="https://generativelanguage.googleapis.com">
    <link rel="preconnect" href="https://generativelanguage.googleapis.com">
    <style>
        :root { --bg: #f4f7f6; --card: #fff; --primary: #1b5e20; --text: #333; }
        @media (prefers-color-scheme: dark) {
            :root { --bg: #121212; --card: #1e1e1e; --text: #e0e0e0; --primary: #81c784; }
        }
        body { font-family: system-ui, -apple-system, sans-serif; background: var(--bg); color: var(--text); display: flex; flex-direction: column; align-items: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box; }
        main { width: 100%; max-width: 600px; background: var(--card); padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); box-sizing: border-box; }
        h1 { text-align: center; margin: 0; font-size: 1.8rem; color: var(--primary); }
        .subtitle { text-align: center; opacity: 0.8; font-size: 0.95rem; margin: 8px 0 25px 0; }
        .progress-container { position: relative; display: flex; flex-direction: column; align-items: center; margin-bottom: 30px; }
        .circle-svg { transform: rotate(-90deg); width: 120px; height: 120px; }
        .circle-bg { fill: none; stroke: #444; stroke-width: 8; }
        .circle-bar { fill: none; stroke: var(--primary); stroke-width: 8; stroke-linecap: round; transition: stroke-dasharray 1s ease-out; }
        .score-text { position: absolute; top: 42px; font-size: 1.6rem; font-weight: 800; color: var(--primary); }
        form { display: flex; flex-direction: column; width: 100%; }
        input { width: 100%; padding: 14px; margin-bottom: 12px; border: 2px solid #444; border-radius: 8px; font-size: 1rem; background: var(--card); color: var(--text); box-sizing: border-box; }
        button { width: 100%; padding: 14px; background: var(--primary); color: var(--bg); border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 1rem; display: flex; justify-content: center; align-items: center; gap: 10px; }
        button:disabled { opacity: 0.6; cursor: not-allowed; }
        .spinner { width: 20px; height: 20px; border: 3px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: #fff; animation: spin 1s linear infinite; display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .result-item { border-bottom: 1px solid #444; padding: 15px 0; display: flex; justify-content: space-between; align-items: center; }
        .status-Excellent { color: #1b5e20; background: #c8e6c9; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
        .status-Pass { color: #0d47a1; background: #bbdefb; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
        .status-Fail { color: #b71c1c; background: #ffcdd2; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
        .other-section { margin-top: 25px; padding-top: 15px; border-top: 2px solid #444; }
        .other-list { color: #ef5350; font-size: 0.9rem; }
        footer { margin-top: auto; padding: 24px; font-size: 0.85rem; opacity: 0.8; text-align: center; }
        footer a { color: var(--primary); font-weight: 700; text-decoration: underline; }
    </style>
</head>
<body>

<main>
    <h1>ðŸŒ± Eco-Web Auditor</h1>
    <p class="subtitle">Advanced 20-point digital efficiency check.</p>

    <?php if ($results): ?>
        <div class="progress-container">
            <div class="score-text"><?php echo $results['score']; ?>%</div>
            <svg class="circle-svg" aria-hidden="true">
                <circle class="circle-bg" cx="60" cy="60" r="50"></circle>
                <circle class="circle-bar" cx="60" cy="60" r="50" 
                        stroke-dasharray="<?php echo ($results['score'] / 100) * 314; ?> 314"></circle>
            </svg>
        </div>
    <?php endif; ?>

    <form id="auditForm" method="POST">
        <label for="urlInput" style="display: none;">Enter Website URL</label>
        <input type="url" id="urlInput" name="url" placeholder="https://example.com" aria-label="Enter website URL to audit" value="<?php echo htmlspecialchars($targetUrl); ?>" required>
        <button type="submit" id="submitBtn">
            <span id="btnText">Analyze Performance</span>
            <div id="btnSpinner" class="spinner"></div>
        </button>
    </form>

    <div id="results">
        <?php if ($results): ?>
            <?php foreach ($results['results'] as $item): ?>
                <div class="result-item">
                    <div style="max-width: 75%;">
                        <strong><?php echo htmlspecialchars($item['metric']); ?></strong><br>
                        <small style="color:#666;"><?php echo htmlspecialchars($item['reason']); ?></small>
                    </div>
                    <span class="status-<?php echo str_replace('/', '', $item['status']); ?>">
                        <?php echo htmlspecialchars($item['status']); ?>
                    </span>
                </div>
            <?php endforeach; ?>

            <?php if (!empty($results['secondary_fails'])): ?>
                <div class="other-section">
                    <h2>Other Optimization Opportunities</h2>
                    <ul class="other-list">
                        <?php foreach ($results['secondary_fails'] as $fail): ?>
                            <li>Failed: <?php echo htmlspecialchars($fail); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<footer>
    Part of the <a href="https://dev.to/devteam/join-our-dev-weekend-challenge-1000-in-prizes-across-ten-winners-submissions-due-april-20-at-47i1?bb=263082" target="_blank">DEV Weekend Challenge</a> &bull; April 2026
</footer>

<script>
    document.getElementById('auditForm').onsubmit = function() {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('btnText');
        const spinner = document.getElementById('btnSpinner');
        btn.disabled = true;
        text.innerText = "Deep Auditing...";
        spinner.style.display = "block";
    };
</script>

</body>
</html>
