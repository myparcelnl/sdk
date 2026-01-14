#!/bin/bash
set -e

# Fix PHP 7.4 compatibility issues in generated OpenAPI code
# Removes 'mixed' type hints which are only available in PHP 8.0+

GENERATED_DIR="src/Services/CoreApi/Generated/Shipments"

echo "Fixing PHP 7.4 compatibility in generated code..."

# Remove 'mixed' type hints from ArrayAccess methods
find "$GENERATED_DIR" -type f -name "*.php" -exec sed -i '' \
  -e 's/public function offsetExists(mixed \$offset): bool/public function offsetExists($offset)/' \
  -e 's/public function offsetGet(mixed \$offset)/public function offsetGet($offset)/' \
  -e 's/public function offsetSet(mixed \$offset, mixed \$value): void/public function offsetSet($offset, $value)/' \
  -e 's/public function offsetSet(\$offset, \$value): void/public function offsetSet($offset, $value)/' \
  -e 's/public function offsetUnset(mixed \$offset): void/public function offsetUnset($offset)/' \
  {} \;

echo "✓ Fixed PHP 7.4 compatibility issues"
