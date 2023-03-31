#include <stdio.h>
#include <stdlib.h>
#include <avr/io.h>
#define F_CPU 8000000UL
#include <util/delay.h>
#include <avr/interrupt.h>

//Define constants
#define BAUDRATE 8			//57.6k bps at 8MHz
#define water_p1 OCR1A
#define water_p2 OCR1B
#define h_resis OCR0
#define electro_v PD7

#define CS_ADC_PRESSURE PINC7
#define RD_ADC PINC6
#define WR_ADC PINC5
#define CS_ADC_TEMPERATURE PINC4

#define cap_sen_t1_low PE0
#define cap_sen_t1_high PE1
#define cap_sen_t2_low PC3
#define cap_sen_t2_high PC2
#define cap_sen_pt_high PC1

#define com0 0x5B	//Water pump 1 speed percentage 
#define com1 0x4A	//Liquid level tank 1
#define com3 0x3D	//Water pump 2 speed percentage
#define com4 0x2C	//Liquid level tank 2
#define com5 0x1F	//Liquid temperature
#define com6 0x0E	//Waiting time
#define START 0x0A	//Start indication 
#define STOP 0x5F

//Function prototypes
void usart_init(void);
void uart_tx(unsigned char);
unsigned char uart_rx(void);
int ascii_to_numeric(char input[], int);
int numeric_to_percentage_wps(int);
int numeric_to_percentage_h_res(int);
int ascii_input(void);
void pwm_init_p(void);
void pwm_init_r(void);
void electro_v_state(int);

void adc_init(void);
void adc_write_pressure (void);
void adc_write_temperature (void);
void INT_init(void);
char adc_to_temperature(void);
int adc_to_volume(void);
void ISR_INT0_vect(void);
void ISR_INT1_vect(void);
void USART_RX_vect(void);

void cap_sensors_init(void);
void uart_transmit(void);
int USART_printCHAR(char character, FILE *stream);
void stop_actuators(void);



//Global variable
unsigned int step = 0;	
int adc_data_pressure;
char adc_data_temperature;
char RxBuffer[2];
volatile uint8_t RxCounter = 0;
int wp1_speed;
int wp2_speed;
int volume_t1;
int volume_t2;
int temperature;
int waiting_time;
int start = 0;
int stop = 0;


static FILE mystdout = FDEV_SETUP_STREAM(USART_printCHAR, NULL, _FDEV_SETUP_WRITE);


int main(void)
{
	DDRD |= (1<< electro_v);
	PORTD |= (1 << PD7);			//Initializes valve as OFF.
	
	//Declare variables
	int numeric_value = 0;
	uint16_t percentage = 0;
	//char temperature;
	int volume;
	
	//Initialize PWM and USART communication
	cli();
	pwm_init_p();
	pwm_init_r();
	usart_init();
	adc_init();
	INT_init();
	cap_sensors_init();
	
	char buffer[2];
	int i;
	int a = 0, b = 0, c = 0;
	
	int wp1_speed_process;
	int wp2_speed_process;
	int volume_t1_process;
	int volume_t2_process;
	int temperature_process;
	int waiting_time_process;

	
	
	while (1)
	{
		/*numeric_value = ascii_input();
		percentage = numeric_to_percentage_wps(numeric_value);
		water_p1 = percentage;
		
		numeric_value = ascii_input();
		percentage = numeric_to_percentage_wps(numeric_value);
		water_p2 = percentage;
		
		numeric_value = ascii_input();
		percentage = numeric_to_percentage_h_res(numeric_value);
		h_resis = percentage;
		
		numeric_value = ascii_input();
		electro_v_state(numeric_value);*/
		
		
		
		/*adc_write_pressure();		//Trigger ADC conversion
		_delay_ms(100);
		//volume = adc_to_volume();
		//usart_tx(volume);
		usart_tx(adc_data_pressure);
		_delay_ms(100);
		
		adc_write_temperature();
		_delay_ms(100);
		temperature = adc_to_temperature();
		usart_tx(temperature);			//Can also be used inside the interrupt function
		//usart_tx(adc_data_temperature);
		_delay_ms(100);*/
		
		
		/*cap_sen_t1_low_val = (PINE & (1 << cap_sen_t1_low)) >> cap_sen_t1_low;
		_delay_ms(100);
		usart_tx(cap_sen_t1_low_val);
		
		/*_delay_ms(1000);
		
		cap_sen_t1_high_val = (PINE & (1 << cap_sen_t1_high)) >> cap_sen_t1_high;
		_delay_ms(100);
		usart_tx(cap_sen_t1_high_val);
		
		_delay_ms(1000);
		
		cap_sen_t2_low_val = (PINC & (1 << cap_sen_t2_low)) >> cap_sen_t2_low;
		_delay_ms(100);
		usart_tx(cap_sen_t2_low_val);
		
		_delay_ms(1000);
		
		cap_sen_t2_high_val = (PINC & (1 << cap_sen_t2_high)) >> cap_sen_t2_high;
		_delay_ms(100);
		usart_tx(cap_sen_t2_high_val);
		
		_delay_ms(1000);
		
		cap_sen_pt_high_val = (PINC & (1 << cap_sen_pt_high)) >> cap_sen_pt_high;
		_delay_ms(100);
		usart_tx(cap_sen_pt_high_val);
		
		_delay_ms(1000);*/
		
		//uart_transmit();
		
		
		//Step 0
		if(step == 0){
			printf("Step: %d\n\r", step);
				_delay_ms(1000);
			}

		
		if(step == 0 && start == 1 && stop == 0){ 
			
			step = 1;
			printf("Step: %d\n\r", step);
			
			//Transfer communication variables to process variables
			wp1_speed_process = wp1_speed;
			printf("WP1 speed: %d\n\r", wp1_speed_process);
			
			wp2_speed_process = wp2_speed;
			printf("WP2 speed: %d\n\r", wp2_speed_process);
			
			volume_t1_process = volume_t1;
			printf("volume product 1: %d\n\r", volume_t1_process);
			
			volume_t2_process = volume_t2;
			printf("volume product 2: %d\n\r", volume_t2_process);
			
			temperature_process = temperature;
			printf("temperature: %d\n\r", temperature_process);
			
			waiting_time_process = waiting_time;
			printf("waiting time: %d\n\r", waiting_time_process);
			
			adc_write_pressure();		//Trigger ADC conversion
			_delay_ms(100);
			volume = adc_to_volume();
			printf("volume process tank: %d\n\r", volume);
			
			_delay_ms(1000);
		}
		
		adc_write_pressure();		//Trigger ADC conversion
		_delay_ms(100);
		volume = adc_to_volume();
		printf("volume process tank: %d\n\r", volume);
		
		if(step == 1 && start == 1 && stop == 0 && cap_sen_t1_low == 0 && volume <= 100){
			
			step = 2;
			printf("Step: %d\n\r", step);
			_delay_ms(1000);
			percentage = numeric_to_percentage_wps(wp1_speed_process);
			printf("percentage value: %d", percentage);
			water_p1_start(percentage);
		
			
	
		}
		
	}
	return 0;
}

//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------
/*void uart_transmit (void){
	char message[] = "Hello, World\n\r";
	int i;
	for (i = 0; i < sizeof(message) - 1; i++) {
		uart_tx(message[i]);
	}
}*/





//Initializes USART
void usart_init (void){
	UBRRL=BAUDRATE;								//sets baud rate
	UCSRB|=(1<<TXEN)|(1<<RXEN)|(1<<RXCIE);					//enables receiver and transmitter
	UCSRC|=(1<<URSEL)|(1<<UCSZ0)|(1<<UCSZ1);	// 8bit data format
	//UCSRB |= (1 << TXCIE);						//Enables Tx interrupt
	 stdout = &mystdout;
}

//Transmits data to USART
void uart_tx (unsigned char data){
	while (!(UCSRA & (1<<UDRE)));           // bit mask to check if UDRE bit is set
	UDR = data;
}

int USART_printCHAR(char character, FILE *stream)
{
	uart_tx(character);
	return 0;
}

//Receive data from USART
unsigned char uart_rx(void){
	while (!(UCSRA & (1<<RXC)));            // bit mask to check if RXC bit is set
	return UDR;
}

//Initializes PWM for controlling water pump speed
void pwm_init_p(void){
	DDRD |= (1 << PD5);  		// Set PD5 (OC1A) as output pin
	DDRE |= (1 << PE2);  		//Set PE2 (OC1B) as output pin

	// Set Timer/Counter1 in Fast PWM mode, with non-inverted PWM output on OC1A
	TCCR1A |= (1 << COM1A1) | (1 << COM1A0) | (1 << COM1B1) | (1 << COM1B0) | (1 << WGM11) | (1 << WGM10);		//COM1A0 Inverts the signal
	TCCR1B |= (1 << WGM12) | (1 << CS10); 	// Set the prescaler value to no prescaling
}

//Initializes PWM for controlling heater resistor
void pwm_init_r(){
	DDRB |= (1 << PB0);  // Set PB0 (OC0) as output pin
	
	TCCR0 |= (1 << COM01) | (1 << COM00) | (1 << WGM01) | (1 << WGM00);
	TCCR0 |= (1 << CS00);
}

//Convert ASCII input to numeric value
int ascii_to_numeric(char input[], int length){
	int value = 0;
	for (int i = 0; i < length; i++) {
		value = value * 10 + (input[i] - '0');
	}
	return value;
}

// Convert numeric value to percentage for water pumps
int numeric_to_percentage_wps(int numeric_value ){
	int percentage = 0;
	percentage = (uint16_t)((uint32_t)numeric_value * 1023 / 100) & 0xFFFF;
	return percentage;
}

// Convert numeric value to PWM duty cycle percentage for heater resistor
int numeric_to_percentage_h_res(int numeric_value ){
	int percentage = 0;
	percentage = (numeric_value * 255) / 100;
	return percentage;
}

//Sets the state of valve based on the received numeric value
void electro_v_state(int numeric_value){
	if(numeric_value == 1)
	PORTD &= ~(1 << PD7);
	else
	PORTD |= (1 << PD7);
}

// Read input from USART and return numeric value
int ascii_input(void){
	char input[3];
	int numeric_value = 0;
	uint16_t percentage = 0;
	int length = 0;
	
	do{
		input[length] = uart_rx();
		uart_tx(input[length]);
		length++;
	} while ((length < 4) && (input[length-1] != '\r'));
	
	numeric_value = ascii_to_numeric(input, length-1);

	return numeric_value;
}


void water_p1_start(int max){
	int m;
	for(m = 0; m <= max; m++){
		water_p1 = m;
		_delay_ms(2);				//Ensures that the loop completes within 3 seconds.
					
	}
}

void water_p1_stop(int max){
	int s;
	for(s = max; s = 0; s--){
		water_p1 = s;
		_delay_ms(40);				//Ensures that the loop completes within 3 seconds.
	}
	
}

//-------------------------------------------------------------------
//-------------------------------------------------------------------

//Initializes ADC control pins and set input/output pins
void adc_init(void){
	DDRC |= (1 << CS_ADC_PRESSURE);
	DDRC |= (1 << CS_ADC_TEMPERATURE);
	DDRC |= (1 << RD_ADC);
	DDRC |= (1 << WR_ADC);
	DDRA = 0x0;
	
	PORTC |= (1 << CS_ADC_PRESSURE);
	PORTC |= (1 << CS_ADC_TEMPERATURE);
	PORTC |= (1 << RD_ADC);
	PORTC |= (1 <<  WR_ADC);
}

//Triggers ADC conversion
void adc_write_pressure(void){
	//Writing Cycle
	PORTC &= ~(1 << CS_ADC_PRESSURE);
	PORTC &= ~(1 << WR_ADC);
	PORTC |= (1 <<  WR_ADC);
	PORTC |= (1 << CS_ADC_PRESSURE);
}

//Interrupt service routine for reading ADC data
ISR(INT0_vect)
{
	PORTC |= (1 << CS_ADC_TEMPERATURE);
	
	//Reading Cycle
	PORTC &= ~(1 << CS_ADC_PRESSURE);
	_delay_us(1);
	PORTC &= ~(1 << RD_ADC);
	_delay_us(1);
	adc_data_pressure = PINA;
	PORTC |= (1 << RD_ADC);
	_delay_us(1);
	PORTC |= (1 << CS_ADC_PRESSURE);
	_delay_us(1);
	
	//usart_tx(adc_data_pressure);
}

void adc_write_temperature(void){
	//Writing Cycle
	PORTC &= ~(1 << CS_ADC_TEMPERATURE);
	PORTC &= ~(1 << WR_ADC);
	PORTC |= (1 <<  WR_ADC);
	PORTC |= (1 << CS_ADC_TEMPERATURE);
}

ISR(INT1_vect) {
	PORTC |= (1 << CS_ADC_PRESSURE);
	
	//Reading Cycle
	PORTC &= ~(1 << CS_ADC_TEMPERATURE);
	_delay_us(1);
	PORTC &= ~(1 << RD_ADC);
	_delay_us(1);
	adc_data_temperature = PINA;
	PORTC |= (1 << RD_ADC);
	_delay_us(1);
	PORTC |= (1 << CS_ADC_TEMPERATURE);
	_delay_us(1);
	
	//usart_tx(adc_data_temperature);
}

void INT_init(void){
	// Sets up INT0 and INT1 to trigger on falling edge
	MCUCR |= (1 << ISC01) | (1 << ISC11);
	MCUCR &= ~(1 << ISC00) & ~(1 << ISC10);
	
	GICR |= (1 << INT0) | (1 << INT1);	// Enable INT0 and INT1 interrupts
	sei();								// Enable global interrupts
}

char adc_to_temperature(void){
	int temperature_adc;
	temperature_adc = ((int)adc_data_temperature * 100) / 255;
	return (char)temperature_adc;
}

int adc_to_volume(void){
	int volume;
	volume = ((float)adc_data_pressure - 1) * 7.86;
	return (int)volume;
}

//-------------------------------------------------------------------
//-------------------------------------------------------------------

void cap_sensors_init(void){
	DDRE &= ~(1 << cap_sen_t1_low) & ~(1 << cap_sen_t1_high);
	DDRC &= ~(1 << cap_sen_t2_low) & ~(1 << cap_sen_t2_high) & ~(1 << cap_sen_pt_high);
	
	return 0;
}


ISR(USART_RX_vect){
	//Read incoming byte and store it in buffer
	
	while (RxCounter < 2){
		if (UCSRA & (1 << RXC)) { // Check if there is data available
			RxBuffer[RxCounter] = UDR;
			RxCounter++;
		}
	}
	
		if (RxBuffer[0] == 0x5B){				//[
			wp1_speed = RxBuffer[1];
			printf("WP1 Speed: %d\n\r", wp1_speed);
			
		}
		
		else if (RxBuffer[0] == 0x4A){			//J
			//printf("liquid level tank 1: ");
			//printf("%x\n\r", RxBuffer[1]);
			volume_t1 = RxBuffer[1];
			printf("Tank 1 Volume: %d\n\r", volume_t1);
		}
	
		else if (RxBuffer[0] == 0x3D){			//=
			wp2_speed = RxBuffer[1];
			printf("WP2 Speed: %d\n\r", wp2_speed);
		}
	
		else if (RxBuffer[0] == 0x2C){			//,
			//printf("liquid level tank 2: ");
			//printf("%x\n\r", RxBuffer[1]);
			volume_t2 = RxBuffer[1];
			printf("Tank 2 Volume: %d\n\r", volume_t2);
		}
			
		else if (RxBuffer[0] == 0x1F){			//unit separator alt+31
			//printf("%x\n\r", RxBuffer[1]);
			temperature = RxBuffer[1];
			printf("Liquid's Temperature: %d\n\r", temperature);
		}
		
		else if (RxBuffer[0] == 0x0E){			//shiftout alt+14
			//printf("%x\n\r", RxBuffer[1]);
			waiting_time = RxBuffer[1];
			printf("Waiting time: %d\n\r", waiting_time);
		}
		
		else if (RxBuffer[0] == START){			//newline alt+10 alt+1
			
			//printf("%x\n\r", RxBuffer[1]);
			start = RxBuffer[1];
			printf("Indication: %d\n\r", start);
		}
		
		else if (RxBuffer[0] == STOP){
			
			stop = RxBuffer[1];
			printf("Indication: %d\n\r", stop);
			
		}
			
		
		RxCounter = 0;
		memset(RxBuffer, 0, sizeof(RxBuffer));
}

void stop_actuators(void){
			water_p1 = 0;	
			water_p2 = 0;
			h_resis = 0;
			PORTD |= (1 << electro_v);	
}