# Generated Code – Do Not Edit

This directory contains auto-generated client code for the **MyParcel Core API** (Capabilities v2), generated via **Swagger Codegen v3** in a Docker container.

---

## Important Warning

**DO NOT modify these files manually!**  
Any manual changes will be overwritten when the client is regenerated.

---

## Regenerating the Client

To update the generated client code:

1. Make sure you have Docker installed
2. Regenerate the client using Composer (this runs Swagger Codegen in Docker):
   ```
   composer generate:capabilities
   ```
3. Re-dump the autoloader if needed:
   ```
   composer dump-autoload -o
   ```

## What's Generated

- API client classes (`lib/Api/`)
- Model and enum classes (`lib/Model/`)
- Configuration and helper classes (`lib/Configuration.php`, etc.)

## Implementation Notes

- **Generator**: swaggerapi/swagger-codegen-cli-v3:3.0.57
- **Specification source**: https://api.myparcel.nl/openapi.yaml
- **Output path**: `src/Services/CoreApi/Generated/Capabilities/lib/`

## CI/CD

In future releases, generated code may be produced automatically in GitHub Actions and distributed only through published Composer packages. The main branch should remain clean of regenerated artifacts.

For contribution guidelines, see `CONTRIBUTING.md`.
