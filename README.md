- Workings deployment : https://zddole.xo.je
- Configuration file : config.ini.php
- Language file for translations : language.ini.php (all strings stored) and help.html (manual)
- 2 directories with full read/write premissions named "data" (the uploaded quiz databases) and "results" (the results stored), as defined in the config.ini.php file
- Ready to use, just copy-paste to a PHP-ready server, full project under 100kb

<h1>
    zDoodle Manual
</h1>
<h3>
    1. Overview &amp; Application Architecture
</h3>
<p>
    zExam Engine (zDoodle) is a flexible web application for managing and conducting electronic meeting polls (Doodle-style scheduling). It is built on a PHP architecture and utilizes a flat-file database system using custom INI files, eliminating the need to configure or connect to an external SQL server.
</p>
<ul>
    <li><strong>Flat-File Architecture:</strong> Ensures standalone storage of meeting data and voting results within individual flat files.</li>
    <li><strong>File Protection:</strong> Each data file is protected against direct unauthorized reading or downloading via an embedded PHP execution wrapper (<code>die();</code>).</li>
    <li><strong>Anonymous Multi-Selection:</strong> Users with access to the meeting PIN can select multiple dates without storing any personal identifiable data.</li>
</ul>
<h3>
    2. Features &amp; Key Principles
</h3>
<ul>
    <li><strong>Simple Identification:</strong> Participants authenticate on the landing page using only the unique poll PIN and an optional display name. No other personal data is collected or stored.</li>
    <li><strong>Multiple Submissions:</strong> The system supports multiple entries/attempts under the same PIN while maintaining separate records for each submission.</li>
    <li><strong>Real-time Ordered Results:</strong> Poll results present dates sorted dynamically from the highest vote count to the lowest.</li>
    <li><strong>Audit Logging:</strong> Upon submission, the engine records selected votes, submission timestamp, and the participant's IP address.</li>
</ul>
<h3>
    3. User Workflow
</h3>
<p>
    The participation workflow consists of the following sequential steps:
</p>
<ol>
    <li><strong>Access:</strong> The user accesses the application and enters the unique PIN.</li>
    <li><strong>Verification:</strong> Upon successful PIN validation, the user gains voting access.</li>
    <li><strong>Instructions:</strong> The application displays the poll title and instructions.</li>
    <li><strong>Selection:</strong> The participant selects one or more preferred date/time slots from the list.</li>
    <li><strong>Submission:</strong> The participant submits their entry.</li>
    <li><strong>Results Display:</strong> The aggregate results are calculated and presented, showing all available date slots ordered from most popular to least popular.</li>
</ol>
<h3>
    4. Admin Operations
</h3>
<p>
    Administrative operations are secured behind an <strong>ADMIN PIN</strong> to prevent unauthorized access. The control panel provides the following management features:
</p>
<ul>
    <li><strong>Create:</strong> Upload a pre-formatted poll file (<code>.ini.php</code>) directly to the server.</li>
    <li><strong>Generate:</strong> Create a new poll file using a web form interface without manually editing files.</li>
    <li><strong>Update:</strong> Overwrite an existing poll configuration with an updated version (requires Quiz PIN and Admin PIN).</li>
    <li><strong>Download:</strong> Download active configuration and poll files directly from the server.</li>
    <li><strong>Results:</strong> Export aggregate voting and submission logs to a spreadsheet-compatible CSV file.</li>
    <li><strong>Delete:</strong> Permanently remove the poll configuration and its corresponding results file.</li>
    <li><strong>Template:</strong> Download the base PHP template file for local manual editing.</li>
</ul>
<h3>
    5. File Structure &amp; Security Wrapper
</h3>
<p>
    Poll configurations are stored as PHP files named after their PIN (e.g., <code>2000.ini.php</code>):
</p>
<pre>PIN.ini.php</pre>
<p>
    To prevent unauthorized access or reading via direct web access, every file <strong>must strictly</strong> begin with a PHP execution wrapper:
</p>
<pre>;&lt;?php
;die();
;/*</pre>
<p>
    And must strictly end with:
</p>
<pre>*/
;&gt;?</pre>
<p>
    The content inside contains a <code>[DATABASE]</code> section and a <code>[DOODLES]</code> section containing <code>D_x</code> entries.
</p>
<h3>
    6. The [DATABASE] Section Configuration
</h3>
<p>
    The <code>[DATABASE]</code> section sets core poll parameters:
</p>
<ul>
    <li><strong>ADMIN:</strong> Secret PIN for administrator operations (keep confidential).</li>
    <li><strong>AUTHOR:</strong> Name or identity of the poll creator.</li>
    <li><strong>ENTRIES:</strong> Total number of date options defined in the file.</li>
    <li><strong>DURATION:</strong> Duration of each meeting slot in minutes.</li>
    <li><strong>INSTRUCTIONS:</strong> Instructions displayed to participants prior to voting.</li>
    <li><strong>LANGUAGE:</strong> Language code for the poll interface (e.g., <code>el</code> or <code>GR</code>).</li>
    <li><strong>PIN:</strong> Unique public PIN shared with participants for access.</li>
    <li><strong>TITLE:</strong> Official title of the poll.</li>
    <li><strong>VERSION:</strong> Author's personal revision/version string.</li>
</ul>
<p>
    Example Section:
</p>
<pre>[DATABASE]
ADMIN        = "1001"
AUTHOR       = "John Doe"
ENTRIES      = "6"
DURATION     = "30"
INSTRUCTIONS = "Select your preferred presentation dates."
LANGUAGE     = "en"
PIN          = "2000"
TITLE        = "Thesis Defense Scheduling"
VERSION      = "1.0"</pre>
<h3>
    7. Date Entries ([DOODLES])
</h3>
<p>
    Dates are defined sequentially inside the <code>[DOODLES]</code> section using <code>D_x</code> keys, where <code>x</code> is an incremental integer starting from 1.
</p>
<pre>[DOODLES]
D_1 = "2026-02-28 11:00"
D_2 = "2026-02-28 15:37"
D_3 = "2026-04-10 12:30"</pre>
<p>
    Date Rules &amp; Formatting Requirements:
</p>
<ul>
    <li><strong>Order:</strong> Keys must be sequentially numbered in strict ascending order (<code>D_1</code>, <code>D_2</code>, <code>D_3</code>...).</li>
    <li><strong>No Gaps:</strong> Empty or skipped entry keys are not allowed.</li>
    <li><strong>Date Format:</strong> Dates must follow the strict format <code>YYYY-MM-DD HH:MM</code> in 24-hour time.</li>
    <li><strong>Total Match:</strong> The maximum number of <code>D_x</code> entries must match the integer defined in <code>ENTRIES</code>.</li>
</ul>
<h3>
    8. File Syntax Rules &amp; Safety Guidelines
</h3>
<ul>
    <li>Do not alter section names or field keys, as doing so will cause system errors during voting.</li>
    <li>Do not leave required configuration fields empty.</li>
    <li>Ensure <code>ENTRIES</code> matches the exact count of defined <code>D_x</code> entries.</li>
    <li>Wrap all string parameters in double quotes (<code>"..."</code>).</li>
    <li>Do not use quotes (<code>"</code> or <code>'</code>) inside text values.</li>
    <li>Avoid backslash escaping (<code>\</code>) prior to quotes.</li>
    <li>Keep the PIN static after creating the poll.</li>
</ul>
<h3>
    9. Full INI Configuration Template Example
</h3>
<pre>;&lt;?php
;die();
;/*
[DATABASE]
ADMIN        = "1001"
AUTHOR       = "John Doe"
ENTRIES      = 6
DURATION     = 30
INSTRUCTIONS = "Please choose available presentation slots."
LANGUAGE     = "en"
PIN          = "1000"
TITLE        = "Thesis Presentation Schedule"
VERSION      = "v1.0"

[DOODLES]
D_1          = "2026-02-28 11:00"
D_2          = "2026-02-28 15:37"
D_3          = "2026-04-10 12:30"
D_4          = "2026-05-15 15:20"
D_5          = "2026-06-20 15:25"
D_6          = "2026-07-01 18:00"
*/
;?&gt;</pre>
</div>
