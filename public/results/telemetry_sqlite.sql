CREATE TABLE IF NOT EXISTS `speedtest_users` (
    `id`    INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `ispinfo`    text,
    `extra`    text,
    `timestamp`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ip`    text NOT NULL,
    `ua`    text NOT NULL,
    `lang`  text NOT NULL,
    `dl`    text,
    `ul`    text,
    `ping`  text,
    `jitter`        text,
    `log`   longtext
);