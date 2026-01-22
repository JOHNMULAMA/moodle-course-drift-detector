# Changelog

All notable changes to the Course Drift Detector plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-01-22

### Added
- Initial release of Course Drift Detector plugin
- Course monitoring functionality to track modifications within configurable period
- Risk assessment system with Low, Medium, and High levels
- Admin interface for viewing course drift reports
- Configurable settings for thresholds and monitoring period
- Language support (English)
- Capability definitions for view and manage permissions
- GDPR compliance through privacy provider implementation
- Custom styling for report interface
- Library functions for drift detection and risk calculation

### Security
- Proper capability checks for admin access
- SQL injection protection through Moodle DML
- XSS protection through proper output escaping
