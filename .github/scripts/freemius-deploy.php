<?php
/**
 * Freemius steps of the release workflow, on the Freemius PHP SDK.
 *
 * upload <zip> <version> <free_zip_out>
 *     Uploads the zip as a pending version, unless the version is already on Freemius, and
 *     downloads the free build Freemius generates from it (premium-only code stripped).
 *
 * release <version>
 *     Releases the version to customers.
 *
 * Env: FREEMIUS_SDK_DIR, FREEMIUS_DEV_ID, FREEMIUS_PUBLIC_KEY, FREEMIUS_SECRET_KEY, FREEMIUS_PLUGIN_ID
 */

$usage = "Usage: php freemius-deploy.php upload <zip> <version> <free_zip_out>\n       php freemius-deploy.php release <version>\n";
$cmd   = isset( $argv[1] ) ? $argv[1] : '';

if ( ! ( 'upload' === $cmd && 5 === $argc ) && ! ( 'release' === $cmd && 3 === $argc ) ) {
	fwrite( STDERR, $usage );
	exit( 1 );
}

foreach ( array( 'FREEMIUS_SDK_DIR', 'FREEMIUS_DEV_ID', 'FREEMIUS_PUBLIC_KEY', 'FREEMIUS_SECRET_KEY', 'FREEMIUS_PLUGIN_ID' ) as $name ) {
	if ( '' === (string) getenv( $name ) ) {
		fail( "$name is not set." );
	}
}

require_once getenv( 'FREEMIUS_SDK_DIR' ) . '/freemius/Freemius.php';

$api       = new Freemius_Api( 'developer', getenv( 'FREEMIUS_DEV_ID' ), getenv( 'FREEMIUS_PUBLIC_KEY' ), getenv( 'FREEMIUS_SECRET_KEY' ) );
$tags_path = 'plugins/' . getenv( 'FREEMIUS_PLUGIN_ID' ) . '/tags';

if ( 'release' === $cmd ) {
	$version = $argv[2];
	$tag     = find_tag( $api, $tags_path, $version );

	if ( null === $tag ) {
		fail( "Version $version is not on Freemius." );
	}

	request( $api, "$tags_path/$tag->id.json", 'PUT', array( 'release_mode' => 'released' ) );
	echo "Released $version on Freemius (tag $tag->id).\n";
	exit( 0 );
}

list( , , $zip, $version, $free_zip ) = $argv;

// Re-running the workflow must not upload the same version twice.
$tag = find_tag( $api, $tags_path, $version );

if ( null !== $tag ) {
	// A re-run finds its own upload. A version uploaded before this commit is a different build.
	$commit_time = (int) getenv( 'COMMIT_TIME' );
	if ( $commit_time && strtotime( $tag->created . ' UTC' ) < $commit_time ) {
		fail( "Version $version was uploaded to Freemius on $tag->created UTC, before this commit. Delete that version in Freemius (or bump the version), then re-run." );
	}

	echo "Version $version is already on Freemius (tag $tag->id, release mode $tag->release_mode), skipping the upload.\n";
} else {
	// The SDK only sends the multipart body when there is at least one regular parameter.
	$tag = request( $api, "$tags_path.json", 'POST', array( 'add_contributor' => false ), array( 'file' => $zip ) );

	// Freemius takes the version from the plugin header, not from us.
	if ( $tag->version !== $version ) {
		fail( "Freemius read version $tag->version from the zip, expected $version." );
	}

	request( $api, "$tags_path/$tag->id.json", 'PUT', array( 'release_mode' => 'pending' ) );
	echo "Uploaded $version to Freemius as pending (tag $tag->id).\n";
}

// Freemius strips the premium-only code into the free build when the tag is processed.
for ( $attempt = 1; $attempt <= 6; $attempt++ ) {
	$body = @file_get_contents( $api->GetSignedUrl( "$tags_path/$tag->id.zip" ) );

	if ( false !== $body && 'PK' === substr( $body, 0, 2 ) ) {
		file_put_contents( $free_zip, $body );
		echo "Downloaded the free build to $free_zip.\n";
		exit( 0 );
	}

	echo "Free build not available yet (attempt $attempt), retrying in 15s.\n";
	sleep( 15 );
}

fail( "Could not download the free build for tag $tag->id." );

/**
 * Finds the Freemius tag of a version, or null.
 */
function find_tag( Freemius_Api $api, $tags_path, $version ) {
	$tags = request( $api, "$tags_path.json", 'GET', array( 'count' => 50 ) );

	foreach ( $tags->tags as $tag ) {
		if ( $tag->version === $version ) {
			return $tag;
		}
	}

	return null;
}

/**
 * Calls the Freemius API and fails the job on an error response.
 */
function request( Freemius_Api $api, $path, $method, $params = array(), $files = array() ) {
	$result = $api->Api( $path, $method, $params, $files );

	if ( ! is_object( $result ) || isset( $result->error ) ) {
		fail( "$method $path failed: " . json_encode( $result ) );
	}

	return $result;
}

function fail( $message ) {
	fwrite( STDERR, "::error::$message\n" );
	exit( 1 );
}
