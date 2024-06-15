# Fluid Processing Controller 

## Overview 
This project is a Fluid Processing Controller designed to emulate a small-scale industrial process. The system involves acquiring and controlling process variables with fluids, utilizing electronic components for fluid level and temperature measurements, such as a MPX2010 pressure sensor for precise liquid level detection and a LM35DZ temperature sensor for accurate temperature monitoring. 

## Repository Structure
- `src/`: Final version of the code.
- `test/`: Contains development history.
  - `microcontroller/`: Experimental and development code with test folders for various functionalities.
    - `actuators/`: Test code related to actuators.
    - `pwm/`: Test code for Pulse Width Modulation.
    - `sensors/`: Test code for sensors.
    - `sequential_control/`: Sequential control tests.
    - `serial_communication/`: Code test for serial communication between the microcontroller and Raspberry Pi.
  - `web_server/`: Development history of the web server code.
- `docs/`: Documentation files.


