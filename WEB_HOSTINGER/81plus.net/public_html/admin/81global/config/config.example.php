<?php
/**
 * 81PLUS Global OS — Configuration Example
 * =========================================
 * This is the EXAMPLE configuration file. It contains placeholder values only.
 *
 * HOW TO USE:
 *   1. Copy this file:  cp config.example.php config.php
 *   2. Fill in every value marked with a comment or placeholder string.
 *   3. Never commit config.php to version control — it contains secrets.
 *
 * Project  : 81PLUS Global OS
 * Owner    : Mirco — Sicurissimo OS
 * Agent    : Nicolas (Co-Fondatore Artificiale)
 * Site     : https://81plus.net
 */

// ---------------------------------------------------------------------------
// DATABASE
// ---------------------------------------------------------------------------

define('DB_HOST',    'localhost');
define('DB_NAME',    'your_db_name');
define('DB_USER',    'your_db_user');
define('DB_PASS',    'your_db_password');
define('DB_CHARSET', 'utf8mb4');

// ---------------------------------------------------------------------------
// SECURITY
// ---------------------------------------------------------------------------

/** Change this to a random 64-character string before going live. */
define('ADMIN_SECRET', 'change_this_to_a_random_64char_secret');

// ---------------------------------------------------------------------------
// URLs
// ---------------------------------------------------------------------------

define('BASE_URL', 'https://81plus.net/admin/81global');
define('XMAS_URL', 'https://81plus.christmas');

// ---------------------------------------------------------------------------
// EMAIL / SENDER IDENTITY
// ---------------------------------------------------------------------------

define('FROM_NAME',  'Nicolas - Sicurissimo OS');
define('FROM_EMAIL', 'nicolas@81plus.net');

// ---------------------------------------------------------------------------
// APPLICATION
// ---------------------------------------------------------------------------

define('GLOBAL_OS_VERSION', '1.0.0');

// ---------------------------------------------------------------------------
// CORS & REDIRECT SECURITY
// ---------------------------------------------------------------------------

/**
 * ALLOWED_ORIGINS
 * Origins permitted to call the API (used in CORS checks).
 * Value is a JSON-encoded array of full origin strings.
 */
define('ALLOWED_ORIGINS', '["https://81plus.net","https://81plus.christmas","https://www.sicurissimo.online"]');

/**
 * WHITELIST_REDIRECT_DOMAINS
 * Domains permitted as redirect targets (used in open-redirect protection).
 * Value is a JSON-encoded array of bare domain strings.
 */
define('WHITELIST_REDIRECT_DOMAINS', '["81plus.net","81plus.christmas","sicurissimo.online"]');

// ---------------------------------------------------------------------------
// TRACKING
// ---------------------------------------------------------------------------

/**
 * TRACKING_PIXEL_GIF
 * Base64-encoded 1x1 transparent GIF.
 * Served directly — no external dependency required.
 */
define('TRACKING_PIXEL_GIF', 'R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
