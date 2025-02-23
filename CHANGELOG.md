# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.2]

### Fixes

* Automatic upload checkbox on upload form #1

### Added 

* Sign in directly after signing up #2
* "Today So Far" summary on the success page 
* API for registering and login
* API for fetching meals, summary, and posting meal
* JWT tokens for API requests
* Date, type, and context as columns to the meal
* Terms and privacy

### Changed

* User view text to be more consistent
* User meal entry form to allow input of additional metadata and context clues
* Sleekplan moved to login

### Removed

* Form fields not needed
* File name requirement from Meal model. Isn't necessary to have a file to record a meal

## [0.1.1] - 2025-02-19

### Added

* SleekPlan javascript for user feedback

## [0.1.0] - 2025-02-10

This marks the first release!

### Added

* User is able to signup, reset password, and also receives a welcome email
* Able to upload from phone with the rear camera initially
* Confetti on success :)

[Unreleased]: https://github.com/cgsmith/calorie/compare/0.1.2...HEAD
[0.1.2]: https://github.com/cgsmith/calorie/releases/tag/0.1.2
[0.1.1]: https://github.com/cgsmith/calorie/releases/tag/0.1.1
[0.1.0]: https://github.com/cgsmith/calorie/releases/tag/0.1.0