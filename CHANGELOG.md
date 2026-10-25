# Changelog

This changelog documents **only the two most recent published GitHub releases** of `mod_percentage`. Each entry is based on the actual Git diff between its published tag and the immediately preceding **published release** tag. Later commits on `master` are excluded.

## [1.1.6] - 2026-10-15

Published: 2026-10-15 21:10:57 UTC.

Comparison: [1.1.5...1.1.6](https://github.com/EduardoKrausME/moodle-mod_percentage/compare/1.1.5...1.1.6)

### Continuous integration

- Updated the GitHub Actions CI PostgreSQL service from `postgres:16` to **`postgres:17`**.
- Updated the MariaDB test service from `mariadb:10` to **`mariadb:11.4`**.
- Switched MariaDB's empty-root-password setting to `MARIADB_ALLOW_EMPTY_ROOT_PASSWORD=1` and replaced the `mysqladmin ping` readiness check with `healthcheck.sh --connect --innodb_initialized`, with adjusted retry settings.

### Plugin icon assets

- Added `pix/icon.png` as a PNG plugin icon.
- Renamed the existing `pix/icon.svg` to `pix/monologo.svg` with no content changes to that SVG in the tag comparison.

### Release metadata and scope

- Updated `version.php` from plugin version `2026101200` / release `1.1.5` to `2026101500` / release `1.1.6`.
- The published-tag comparison consists of five commits and changes only `.github/workflows/ci.yml`, `pix/icon.png`, the renamed SVG, and `version.php`. No activity runtime code or calculation behavior changed; the minimum Moodle build remained `2024100700`. Updating CI database services is not, by itself, a change in production database requirements.

## [1.1.5] - 2026-10-12

Published: 2026-10-12 11:33:40 UTC.

Comparison: [1.1.4...1.1.5](https://github.com/EduardoKrausME/moodle-mod_percentage/compare/1.1.4...1.1.5)

### Continuous integration

- Updated the PHP 8.3 CI matrix entry from `MOODLE_502_STABLE` to `MOODLE_503_STABLE` (Moodle 5.3).

### Release automation and packaging

- Changed `.github/workflows/release.yml` to read the head commit message through a `RELEASE_MESSAGE` environment variable rather than inserting commit text directly into shell code, reducing the risk of shell injection during version parsing.
- Removed the workflow steps that generated an installable `percentage.zip` archive, checked its `percentage/` root directory, and attached that archive to the GitHub Release.
- Removed the installable-ZIP instructions from the auto-generated release description. **The published-tag diff does not provide evidence that the updated release workflow still attaches an installable plugin ZIP.** The preceding release `1.1.4` had explicitly described its `percentage.zip` attachment.
- Retained annotated tag creation and GitHub Release publication.

### Release metadata and scope

- Updated `version.php` from `2026100501` / release `1.1.4` to `2026101200` / release `1.1.5`.
- The comparison contains three commits and modifies **only** `.github/workflows/ci.yml`, `.github/workflows/release.yml`, and `version.php`. The minimum Moodle build remained `2024100700`; no functional activity source code changed between these tags.

---

These entries are based solely on published release-tag comparisons. They exclude subsequent `master` commits and do not claim that tests were run or releases were modified while preparing this changelog.
