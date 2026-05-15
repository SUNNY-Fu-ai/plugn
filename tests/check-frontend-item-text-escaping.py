#!/usr/bin/env python3
import re
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]


def read(relative_path: str) -> str:
    return (ROOT / relative_path).read_text()


def require(condition: bool, message: str) -> None:
    if not condition:
        raise AssertionError(message)


def matches(pattern: str, source: str) -> bool:
    return re.search(pattern, source, re.DOTALL) is not None


index = read("frontend/views/item/index.php")
inventory = read("frontend/views/item/inventory.php")
view = read("frontend/views/item/view.php")

require(
    matches(
        r"'attribute'\s*=>\s*'item_name'\s*,\s*'label'\s*=>\s*'Item name'\s*,\s*'format'\s*=>\s*'text'",
        index,
    ),
    "item index must render item_name as escaped text",
)
require(
    matches(
        r"'attribute'\s*=>\s*'sku'\s*,\s*'label'\s*=>\s*'SKU'\s*,\s*'format'\s*=>\s*'text'",
        index,
    ),
    "item index must render sku as escaped text",
)
require(
    matches(
        r"'label'\s*=>\s*'Category name'.*?'format'\s*=>\s*'text'",
        index,
    ),
    "item index category names must render as escaped text",
)
require(
    matches(
        r"'label'\s*=>\s*'Item name'\s*,\s*'format'\s*=>\s*'text'\s*,\s*'attribute'\s*=>\s*'item_name'",
        inventory,
    ),
    "inventory item_name must render as escaped text",
)
require(
    matches(
        r"'format'\s*=>\s*'text'\s*,\s*'attribute'\s*=>\s*'sku'",
        inventory,
    ),
    "inventory sku must render as escaped text",
)

category_start = view.find("'attribute' => 'category_item'")
category_end = view.find("'attribute' => 'item_price'", category_start)
require(
    category_start != -1,
    "item detail category_item marker must exist",
)
require(
    category_end != -1,
    "item detail item_price marker must exist",
)
category_block = view[category_start:category_end]

require(
    matches(
        r"'attribute'\s*=>\s*'category_item'.*?'format'\s*=>\s*'text'",
        category_block,
    ),
    "item detail category names must render as escaped text",
)

for disallowed_pattern, source, message in [
    (
        r"'attribute'\s*=>\s*'item_name'\s*,\s*'label'\s*=>\s*'Item name'\s*,\s*'format'\s*=>\s*'html'",
        index,
        "item index item_name must not render as html",
    ),
    (
        r"'attribute'\s*=>\s*'sku'\s*,\s*'label'\s*=>\s*'SKU'\s*,\s*'format'\s*=>\s*'raw'",
        index,
        "item index sku must not render as raw",
    ),
    (
        r"'label'\s*=>\s*'Item name'\s*,\s*'format'\s*=>\s*'raw'\s*,\s*'attribute'\s*=>\s*'item_name'",
        inventory,
        "inventory item_name must not render as raw",
    ),
    (
        r"'format'\s*=>\s*'raw'\s*,\s*'attribute'\s*=>\s*'sku'",
        inventory,
        "inventory sku must not render as raw",
    ),
    (
        r"'format'\s*=>\s*'raw'",
        category_block,
        "item detail category names must not render as raw",
    ),
]:
    require(not matches(disallowed_pattern, source), message)

print("frontend item text escaping checks passed")
