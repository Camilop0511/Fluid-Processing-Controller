# Fluid Processing Controller 

## Overview 
This project is a Fluid Processing Controller designed to emulate a small-scale industrial process. The system involves acquiring and controlling process variables related to fluids. It uses electronic components for fluid level and temperature measurements, including an MPX2010 pressure sensor for precise liquid level detection and an LM35DZ temperature sensor for accurate temperature monitoring.

The system features two storage tanks and a processing tank, managed by two water pumps and a heating resistor. It has both a physical control panel and a web-based interface for control. An ATmega8515 serves as the main controller, facilitating bidirectional communication with a Raspberry Pi. This setup allows users to input parameters and monitor the process through a web page hosted by the Raspberry Pi. 

Operationally, the system functions sequentially, ensuring meticulous control over each stage of fluid processing. This includes tasks such as filling, heating, and final delivery of the processed fluid. 

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
- `docs/`: Documentation files such as circuit designs, PCB designs, PCB illustrations, and a technical manual.


