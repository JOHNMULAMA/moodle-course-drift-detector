# Course Drift Detector - File Upload Functionality

## Overview

This document describes the file upload functionality implemented in the Course Drift Detector Moodle admin tool.

## Components

### 1. Upload Form (`classes/form/upload_form.php`)

The upload form extends Moodle's `moodleform` class and provides:

- **File picker element**: Accepts `.mbz`, `.zip`, and `.xml` file types
- **Client-side validation**: Ensures a file is selected before submission
- **Help button**: Provides context-sensitive help for users
- **Server-side validation**: Additional validation on form submission

### 2. Main Entry Point (`index.php`)

The main page handles:

- **Permission checks**: Verifies user has `tool/coursedriftdetector:upload` capability
- **Form rendering**: Displays the upload form to authorized users
- **File processing**: Handles uploaded files when form is submitted
- **Temporary storage**: Saves uploaded files to Moodle's temp directory
- **User notifications**: Provides feedback on upload success/failure

### 3. File Processing Logic

When a file is uploaded:

1. Form data is validated
2. File content is extracted using `get_file_content()`
3. File is saved to a temporary directory
4. Success notification is displayed
5. Temporary file is cleaned up after processing

## Security Features

- **Capability-based access control**: Only users with appropriate permissions can upload files
- **File type restrictions**: Only specific file types are accepted
- **Temporary file storage**: Files are stored in Moodle's secure temp directory
- **File cleanup**: Temporary files are removed after processing

## Usage Flow

1. User navigates to **Site administration > Server > Course Drift Detector**
2. User sees the upload form with file picker
3. User selects a course data file (`.mbz`, `.zip`, or `.xml`)
4. User clicks "Upload and analyze"
5. System processes the file and displays success/error messages
6. System returns to the upload page for additional uploads

## Future Enhancements

The current implementation provides the basic file upload infrastructure. Future enhancements may include:

- Parsing and analyzing course backup files
- Comparing course structures across different versions
- Storing analysis results in the database
- Generating drift detection reports
- Scheduling automated drift detection tasks

## Moodle Integration

The plugin integrates with Moodle through:

- **Admin menu**: Added to Site administration under Server section
- **Capabilities system**: Two capabilities for view and upload access
- **Language strings**: Full internationalization support
- **Moodle forms API**: Native form handling with validation
- **File API**: Standard Moodle file handling methods

## Testing

To test the file upload functionality:

1. Install the plugin in a Moodle instance
2. Log in as an administrator
3. Navigate to the Course Drift Detector page
4. Attempt to upload a test file
5. Verify success notifications appear
6. Check that invalid file types are rejected
7. Verify capability restrictions work correctly
