# TechSpring sign-in system
Developed by Devon Endicott and Alejandro Colon

Purpose: Link visitors to easily identifiable information

## Adjustable Files

- **token.txt** - contains the API token obtained from Nation Builder
- **printer.txt** - contains the full name of the printer *according to the host device*
- **welcome_message.txt** - the message to be displayed after the user has successfully signed in

## Cronological Automated Tasks

To cut load times, the program automatically runs `update_database.php` *hourly* (TODO: update this?) to pull new NationBuilder user information and store it in a local SQLite3 database. If the database is being built from nothing, this can be a VERY time consuming process, especially on a lower powered controller such as the Raspberry Pi. However, merely appending new information is much less intensive task and takes a much shorter amount of time.

## Server Setup
The web server (as of now) is being hosted on a retired Dell laptop, reimaged with Ubuntu 14.10 (?) LTS (?). The last `sudo apt-get dist-upgrade` was run on 6/22/2015. To enable wifi, the follow was added to `/etc/network/interfaces`:

```
auto wlan0
iface wlan0 inet dhcp
  wpa-ssid TechSpring
  wpa-psk <password>
```

During the Ubuntu set-up, the following packages were select: `LAMP Server`, `Mail Server`, and `SSH Server`.

The following packages were installed additionally: `sudo apt-get install php5-sqlite php5-curl php5-gd git libcups2-dev libcupsimage2-dev cups cups-client g++`

The DYMO Linux driver was downloaded from their page, configured, and installed using the following:
```
wget <link to driver>.tar.gz
tar -xzf *.tar.gz
cd <directory made>
sudo ./configure
sudo make
sudo make install
```

To prevent the shutdown of the laptop when the lid is shut, `/etc/systemd/logind.conf` was modified, adding the line `HandleLidSwitch=ignore`

The CUPS config file was changed, replacing `localhost` with `0.0.0.0` to allow remote access to the configuration. The directory permissions were appended the line `Allow @LOCAL`.

Connecting to `<server>:631`, the DYMO LabelWriter 450 Twin Turbo was added. The slug generated for the printer was placed in `/var/www/html/printer.txt`

We intend to leave a flashdrive with an image of the server on it incase anything happens to the original host device. The above instructions are only to duplicate our steps in the case that something else happens. The instructions are majorly from memory, and there may be intermittent restarts required.
