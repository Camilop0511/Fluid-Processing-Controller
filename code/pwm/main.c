#include <xc.h>
#include <stdio.h>
#include <stdlib.h>
#include <avr/io.h>
#define F_CPU 8000000UL
#include <util/delay.h>

#include <math.h>

#define BAUDRATE 8			//57.6k bps at 8MHz
#define water_p1 OCR1A


void usart_init(void);
void uart_tx(unsigned char);
unsigned char uart_rx(void);
int ascii_to_numeric(char input[], int);
void pwm_init(void);
int numeric_to_percentage(int);

int output[4];

int main(void)
{
	char input[3];
	int numeric_value = 0;
	uint16_t percentage = 0;

	pwm_init();
	usart_init();

	/*uint8_t upper_byte = 0;
	uint8_t lower_byte = 0;*/


	while (1)
	{
		int length = 0;
		do{
			input[length] = uart_rx();
			uart_tx(input[length]);
			length++;
		} while ((length < 4) && (input[length-1] != '\r'));
		
		
		numeric_value = ascii_to_numeric(input, length-1);
		
/*upper_byte = (numeric_value >> 8) & 0xFF;
lower_byte = numeric_value & 0xFF;

uart_tx(upper_byte);
uart_tx(lower_byte);*/
		
		percentage = numeric_to_percentage(numeric_value);
	
/*upper_byte = (percentage >> 8) & 0xFF;
lower_byte = percentage & 0xFF;		
		
uart_tx(upper_byte);
uart_tx(lower_byte);*/

		water_p1 = percentage;
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

void pwm_init(void){
	// Set PD5 (OC1A) as output pin
	DDRD |= (1 << PD5);

	// Set Timer/Counter1 in Fast PWM mode, with non-inverted PWM output on OC1A
	TCCR1A |= (1 << COM1A1) | (1 << COM1A0) | (1 << WGM11) | (1 << WGM10);		//COM1A0 Inverts the signal
	TCCR1B |= /*(1 << WGM13) |*/ (1 << WGM12) /*| (1 << CS11)*/;

	// Set the prescaler value to divide the clock by 2 (producing a 1 MHz timer clock)
	TCCR1B |= (1 << CS10);
}


int ascii_to_numeric(char input[], int length){
	int value = 0;
	for (int i = 0; i < length; i++) {
		value = value * 10 + (input[i] - '0');
	}
	return value;
}

int numeric_to_percentage(int numeric_value ){
	int percentage = 0;
	
	//percentage = 1023 * (numeric_value/100);
//percentage = (unint16_t) (1023 * numeric_value;
	//percentage = ((float)numeric_value / 100.0 * 1023.0);
	percentage = (uint16_t)((uint32_t)numeric_value * 1023 / 100) & 0xFFFF;
	
	return percentage;
}