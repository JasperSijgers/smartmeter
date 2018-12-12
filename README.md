# SmartMeter Logging
Logs the current energy and gas consumption readings from the smartmeter installed in your home. Not meant for professional use, may contain bugs and/or insecure connections.

## Functionality
The <i>logcurrent.php</i> script in <i>pi_files</i> executes the python script that reads the smart meter (if connected and the appropriate libraries are installed), then retrieves the data from the output and uploads it to a MySQL database using the php_mysqli functionality.

The web_files includes a simple overview of both consumed and produced electricity in hourly, daily, monthly and yearly views. It makes use of both PHP (for MySQL connections) and JavaScript/jQuery for getting the data and displaying it in graphs (using the Chart.js library).

## Requirements
- Raspberry Pi (any version really)
- Serial to USB Cable (available here: https://www.sossolutions.nl/slimme-meter-kabel)
- MySQL Database

## Setup
Connect the Raspberry Pi to the smart meter using the cable, download the pi_files and upload them to the raspberry pi. Install php, python, php-mysql and python-serial. Then set up a cronjob for the php script to run on the desired interval.
Note: I have found 1 minute to work perfectly fine as an interval, depending on the time it takes for the Pi to retrieve and upload the data, you may need to adjust. A minimum of 20s and a maximum of 5 minutes provides the most accurate and usable data.
