#!/usr/bin/env python3
from pathlib import Path


view = Path("frontend/views/store/view.php")
source = view.read_text()

raw_anchor = "return '<a  href=\"' . $data->instagram_url"
required_fragments = [
    "$url = trim((string) $data->instagram_url);",
    "$label = Html::encode($url);",
    "$scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));",
    "in_array($scheme, ['http', 'https'], true)",
    "return Html::a($label, $url, [",
    "'rel' => 'noopener noreferrer'",
]

if raw_anchor in source:
    raise SystemExit("frontend store view still concatenates instagram_url into a raw anchor")

missing = [fragment for fragment in required_fragments if fragment not in source]
if missing:
    raise SystemExit("missing frontend store instagram link safety fragments: " + ", ".join(missing))

print("Frontend store Instagram link safety guard passed.")
