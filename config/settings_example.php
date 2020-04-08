<?php

// uncomment to use an API Key with IPInfo (https://ipinfo.io/)
//const IPINFO_API_KEY = '';

// password to login to stats.php. Change this!!!
const STATS_PASSWORD = 'PASSWORD';

// if set to true, test IDs will be obfuscated to prevent users from guessing URLs of other tests
const OBFUSCATE_IDS = true;

// if set to true, IP addresses will be redacted from IP and ISP info fields, as well as the log
const REDACT_IP_ADDRESSES = false;

// database settings
const SQL_TYPE = 'mysql'; // type of database: "mysql", "sqlite" or "postgresql"

const SQL_HOSTNAME = 'DB_HOSTNAME';
const SQL_USERNAME = 'USERNAME';
const SQL_PASSWORD = 'PASSWORD';
const SQL_DATABASE = 'DB_NAME';

// Sqlite3 settings
const SQLITE_DB_FILE = '../../speedtest_telemetry.sql';