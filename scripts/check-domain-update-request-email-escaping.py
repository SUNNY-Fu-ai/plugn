#!/usr/bin/env python3
from pathlib import Path

template = Path("common/mail/domain-update-request.php").read_text()

required = [
    "use yii\\helpers\\Html;",
    "Html::encode(strtoupper($store_name))",
    "Html::encode($old_domain)",
    "Html::encode($new_domain)",
]

for snippet in required:
    if snippet not in template:
        raise SystemExit(f"missing required escaping snippet: {snippet}")

for raw in [
    "<?= strtoupper($store_name) ?>",
    "<?= $old_domain ?>",
    "<?= $new_domain ?>",
]:
    if raw in template:
        raise SystemExit(f"raw dynamic output still present: {raw}")

print("domain update request email escaping guard passed")
