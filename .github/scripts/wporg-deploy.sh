#!/usr/bin/env bash
#
# Deploy the Freemius free build to wordpress.org SVN: syncs trunk and creates tags/<version>.
#
# Usage: .github/scripts/wporg-deploy.sh [path/to/free.zip] [--dry-run] [--yes]
#
# Without a zip path, the newest ~/Downloads/wp-frontend-delete-account-free*.zip is used.
# --dry-run  stops before committing and resets the SVN checkout.
# --yes      commits without asking (CI).
#
# Env:
#   SVN_DIR       SVN checkout to use. Defaults to ~/wp-frontend-delete-account; if it doesn't exist, a
#                 fresh checkout of trunk is made there.
#   SVN_USERNAME  wordpress.org username (default sanzeeb3).
#   SVN_PASSWORD  SVN password from the wordpress.org profile. Without it, svn prompts.

set -euo pipefail

SLUG="wp-frontend-delete-account"
SVN_URL="https://plugins.svn.wordpress.org/$SLUG"
SVN_DIR="${SVN_DIR:-$HOME/$SLUG}"
SVN_USERNAME="${SVN_USERNAME:-sanzeeb3}"

ZIP=""
DRY_RUN=0
YES=0
for arg in "$@"; do
	case "$arg" in
		--dry-run) DRY_RUN=1 ;;
		--yes) YES=1 ;;
		*) ZIP="$arg" ;;
	esac
done

fail() { echo "✗ $*" >&2; exit 1; }
ok() { echo "✓ $*"; }

if [ -z "$ZIP" ]; then
	ZIP="$(ls -t "$HOME"/Downloads/${SLUG}-free*.zip 2>/dev/null | head -1 || true)"
fi

[ -f "$ZIP" ] || fail "Free zip not found. Download it from Freemius, or pass its path."
ok "Using $ZIP"

# Unzip. Freemius zips have a single top-level folder.
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT
unzip -q "$ZIP" -d "$TMP"
MAIN="$(find "$TMP" -maxdepth 2 -name "$SLUG.php" | head -1)"
[ -n "$MAIN" ] || fail "Zip doesn't contain $SLUG.php"
SRC="$(dirname "$MAIN")"

# Check it is the free build.
VERSION="$(sed -n 's/^ \* Version: *//p' "$SRC/$SLUG.php" | tr -d '[:space:]')"
STABLE="$(sed -n 's/^Stable tag: *//p' "$SRC/readme.txt" | tr -d '[:space:]')"
[ -n "$VERSION" ] || fail "Couldn't read Version from $SLUG.php"
[ "$VERSION" = "$STABLE" ] || fail "Version $VERSION doesn't match readme Stable tag $STABLE"
grep -q "'is_premium' *=> *false" "$SRC/$SLUG.php" || fail "This looks like the PRO zip, not the free one"
for path in src/Pro; do
	[ ! -e "$SRC/$path" ] || fail "Free zip contains premium path $path"
done
! grep -rq "__premium_only" "$SRC/src" "$SRC/$SLUG.php" || fail "Free zip still contains __premium_only code"
ok "Free build $VERSION looks right"

# Get the SVN checkout.
SVN_AUTH=(--username "$SVN_USERNAME")
if [ -n "${SVN_PASSWORD:-}" ]; then
	SVN_AUTH+=(--password "$SVN_PASSWORD" --non-interactive --no-auth-cache)
fi

if [ ! -d "$SVN_DIR/.svn" ]; then
	echo "Checking out $SVN_URL to $SVN_DIR"
	svn checkout -q --depth immediates "$SVN_URL" "$SVN_DIR"
	svn update -q --set-depth infinity "$SVN_DIR/trunk"
fi

cd "$SVN_DIR"
[ -z "$(svn status)" ] || fail "SVN checkout has uncommitted changes. Run: cd $SVN_DIR && svn revert -R . && svn status"
svn update -q
# Lets a re-run of the workflow get past this step to the Freemius release.
if svn ls "$SVN_URL/tags/$VERSION" >/dev/null 2>&1; then
	ok "tags/$VERSION is already on wordpress.org, nothing to do"
	exit 0
fi

# Sync trunk and tag it. assets/ (icon, screenshots) is left alone.
rsync -a --delete --exclude=.svn "$SRC/" trunk/
svn status trunk | awk '$1=="!"{print $2}' | xargs -r svn rm -q
svn add -q --force trunk
svn cp -q trunk "tags/$VERSION"
ok "Synced trunk and created tags/$VERSION"

echo
echo "Changes (freemius/ files collapsed):"
svn status | grep -v '/freemius/.' || true
echo

if [ "$DRY_RUN" = 1 ]; then
	svn revert -R -q .
	rm -rf "tags/$VERSION"
	svn status --no-ignore | awk '$1=="?"{print $2}' | xargs -r rm -rf
	ok "Dry run: SVN checkout reset, nothing committed"
	exit 0
fi

if [ "$YES" != 1 ]; then
	read -r -p "Publish $SLUG $VERSION to wordpress.org? [y/N] " answer
	if [ "$answer" != "y" ] && [ "$answer" != "Y" ]; then
		echo "Not published. Reset the checkout with: cd $SVN_DIR && svn revert -R . && rm -rf tags/$VERSION"
		exit 1
	fi
fi

svn ci -m "Version $VERSION" "${SVN_AUTH[@]}"
ok "Published. Check https://wordpress.org/plugins/$SLUG/ in a few minutes."
