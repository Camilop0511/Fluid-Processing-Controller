#include <xc.h>
#include <stdio.h>
#include <stdlib.h>
#include <avr/io.h>
#define F_CPU 8000000UL
#include <util/delay.h>

#define BAUDRATE 8			//57.6k bps at 8MHz
#define water_p1 OCR1A
#define water_p2 OCR1B
#define h_resis OCR0

void usart_init(void);
void uart_tx(unsigned char);
unsigned char uart_rx(void);
int ascii_to_numeric(char input[], int);
int numeric_to_percentage_wps(int);
int numeric_to_percentage_h_res(int);
int ascii_input(void);
void pwm_init_p(void);
void pwm_init_r(void);

int main(void)
{
	int numeric_value = 0;
	uint16_t percentage = 0;
	
	pwm_init_p();
	pwm_init_r();
	usart_init();

	while (1)
	{
		numeric_value = ascii_input();
		percentage = numeric_to_percentage_wps(numeric_value);		
		water_p1 = percentage;
		
		numeric_value = ascii_input();
		percentage = numeric_to_percentage_wps(numeric_value);
		water_p2 = percentage;
		
		numeric_value = ascii_input();
		percentage = numeric_to_percentage_h_res(numeric_value);
		h_resis = percentage;
		
	}
	return 0;
}

//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------

//Initializes USART
void usart_init (void){
	UBRRL=BAUDRATE;								//sets baud rate
	UCSRB|=(1<<TXEN)|(1<<RXEN);					//enables receiver and transmitter
	UCSRC|=(1<<URSEL)|(1<<UCSZ0)|(1<<UCSZ1);	// 8bit data format
}

//Transmits data to USART
void uart_tx (unsigned char data){
	while (!(UCSRA & (1<<UDRE)));           // bit mask to check if UDRE bit is set (set = while (0), not set = while (1))
	UDR = data;                             // loads data to UDR register
}

unsigned char uart_rx(void){
	while (!(UCSRA & (1<<RXC)));            // bit mask to check if RXC bit is set (set = while (0), not set = while (1))
	return UDR;                             // returns the received character from UDR register
}

void pwm_init_p(void){
	DDRD |= (1 << PD5);  // Set PD5 (OC1A) as output pin
	DDRE |= (1 << PE2);  //Set PE2 (OC1B) as output pin

	// Set Timer/Counter1 in Fast PWM mode, with non-inverted PWM output on OC1A
	TCCR1A |= (1 << COM1A1) | (1 << COM1A0) | (1 << COM1B1) | (1 << COM1B0) | (1 << WGM11) | (1 << WGM10);		//COM1A0 Inverts the signal
	TCCR1B |= (1 << WGM12) | (1 << CS10); // Set the prescaler value to no prescaling
}

void pwm_init_r(){
	DDRB |= (1 << PB0);  // Set PB0 (OC0) as output pin
	
	TCCR0 |= (1 << COM01) | (1 << COM00) | (1 << WGM01) | (1 << WGM00); //COM00 inverts the signal
	TCCR0 |= (1 << CS00); // Set the prescaler value to no prescaling
}

int ascii_to_numeric(char input[], int length){
	int value = 0;
	for (int i = 0; i < length; i++) {
		value = value * 10 + (input[i] - '0');
	}
	return value;
}

int numeric_to_percentage_wps(int numeric_value ){
	int percentage = 0;
	percentage = (uint16_t)((uint32_t)numeric_value * 1023 / 100) & 0xFFFF;
	return percentage;
}

int numeric_to_percentage_h_res(int numeric_value ){
	int percentage = 0;
	percentage = (numeric_value * 256) / 100;
	return percentage;
}

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