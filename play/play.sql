CREATE DATABASE IF NOT EXISTS play_integrity;

CREATE TABLE IF NOT EXISTS play_integrity.play_integrity (
    BRAND varchar(255),
    MANUFACTURER varchar(255),
    MODEL varchar(255),
    PRODUCT varchar(255),
    DEVICE varchar(255),
    ID varchar(255),
    FINGERPRINT varchar(255),
    `VERSION:SECURITY_PATCH` varchar(255)
);
