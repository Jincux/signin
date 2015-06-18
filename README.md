# TechSpring sign-in system
Developed by Devon Endicott and Alejandro Colon

Purpose: Link visitors to easily identifiable information

## Adjustable Files

- **token.txt** - contains the API token obtained from Nation Builder
- **printer.txt** - contains the full name of the printer *according to the host device*
- **welcome_message.txt** - the message to be displayed after the user has successfully signed in

## Cronological Automated Tasks

To cut load times, the program automatically runs `update_database.php` *hourly* (TODO: update this?) to pull new NationBuilder user information and store it in a local SQLite3 database. If the database is being built from nothing, this can be a VERY time consuming process, especially on a lower powered controller such as the Raspberry Pi. However, merely appending new information is much less intensive task and takes a much shorter amount of time.
