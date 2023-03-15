#include <xc.h>
#include <stdio.h>
#include <stdlib.h>
#include <avr/io.h>
#define F_CPU 8000000UL
#include <util/delay.h>

#define BAUDRATE 8			//57.6k bps at 8MHz

void usart_init(void);
void uart_tx(unsigned char);
unsigned char uart_rx(void);
int ascii_to_numeric(char input[]);

int output[4];

int main(void)
{
	// Set PD5 (OC1A) as output pin
	DDRD |= (1 << PD5);

	// Set Timer/Counter1 in Fast PWM mode, with non-inverted PWM output on OC1A
	TCCR1A |= (1 << COM1A1) | (1 << COM1A0) | (1 << WGM11) | (1 << WGM10);		//COM1A0 Inverts the signal
	TCCR1B |= /*(1 << WGM13) |*/ (1 << WGM12) /*| (1 << CS11)*/;

	// Set the prescaler value to divide the clock by 2 (producing a 1 MHz timer clock)
	TCCR1B |= (1 << CS10);

	// Set the output compare register (OCR1A) to produce a 70% duty cycle
	
	usart_init();
	char input[4];
	uint16_t numeric_value = 0;
	

	while (1)
	{
		// Wait for 4 characters input
		for (int i = 0; i < 4; i++) {
			input[i] = uart_rx();
			uart_tx(input[i]);
		}

		numeric_value = ascii_to_numeric(input);
		uart_tx(numeric_value);

		//OCR1A = 1023;
		OCR1A = numeric_value;

		//OCR1A = data;

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

int ascii_to_numeric(char input[]){
	uint16_t value = 0;
	for (int i = 0; i < 4; i++) {
		value = value * 10 + (input[i] - '0');
	}
	return value;
}