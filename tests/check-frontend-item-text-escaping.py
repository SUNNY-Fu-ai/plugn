#!/usr/bin/env python3
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]


def read(relative_path: str) -> str:
    return (ROOT / relative_path).read_text()


def require(condition: bool, message: str) -> None:
    if not condition:
        raise AssertionError(message)


index = read("frontend/views/item/index.php")
inventory = read("frontend/views/item/inventory.php")
view = read("frontend/views/item/view.php")

require(
    "'attribute' => 'item_name',\n                           'label' => 'Item name',\n                           'format' => 'text'"
    in index,
    "item index must render item_name as escaped text",
)
require(
    "'attribute' => 'sku',\n                    'label' => 'SKU',\n                    'format' => 'text'"
    in index,
    "item index must render sku as escaped text",
)
require(
    "'label' => 'Category name'" in index and "'format' => 'text'" in index,
    "item index category names must render as escaped text",
)
require(
    "'label' => 'Item name',\n                    'format' => 'text',\n                    'attribute' => 'item_name'"
    in inventory,
    "inventory item_name must render as escaped text",
)
require(
    "'format' => 'text',\n                    'attribute' => 'sku'" in inventory,
    "inventory sku must render as escaped text",
)
require(
    "'attribute' => 'category_item'" in view and "'format' => 'text'" in view,
    "item detail category names must render as escaped text",
)

for disallowed, source, message in [
    ("'attribute' => 'item_name',\n                           'label' => 'Item name',\n                           'format' => 'html'", index, "item index item_name must not render as html"),
    ("'attribute' => 'sku',\n                    'label' => 'SKU',\n                    'format' => 'raw'", index, "item index sku must not render as raw"),
    ("'label' => 'Item name',\n                    'format' => 'raw',\n                    'attribute' => 'item_name'", inventory, "inventory item_name must not render as raw"),
    ("'format' => 'raw',\n                    'attribute' => 'sku'", inventory, "inventory sku must not render as raw"),
    ("'attribute' => 'category_item'", view[view.find("'attribute' => 'category_item'") : view.find("'attribute' => 'item_price'")], "item detail category block must exist"),
    ("'format' => 'raw'", view[view.find("'attribute' => 'category_item'") : view.find("'attribute' => 'item_price'")], "item detail category names must not render as raw"),
]:
    if message.endswith("must exist"):
        require(disallowed in source, message)
    else:
        require(disallowed not in source, message)

print("frontend item text escaping checks passed")
