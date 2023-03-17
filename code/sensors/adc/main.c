#include <xc.h>
#include <stdio.h>
#include <stdlib.h>
#include <avr/interrupt.h>
#define F_CPU 8000000L
#include <util/delay.h>

#define BAUDRATE 8
#define CS_ADC (1 << PINE0);
#define RD_ADC (1 << PINE1);
#define WR_ADC (1 << PINE2);

//volatile uint8_t int_flag = 0;
char adc_data; 


void usart_init(void);
void usart_tx(unsigned char);
void adc_init(void);
void adc_write (void);
void INT0_init(void);

int main (void)
{
	//char adc_data;
	
	
	//DDRC |= (1 << PC0);
	
	usart_init();
	adc_init();
	INT0_init();
	
	adc_write();
	
	while(1){	
		usart_tx(adc_data);
		_delay_ms(100);
	}

	return 0;
}

//--------------------------------------------------------

//Initializes USART
void usart_init (void){
	UBRRL=BAUDRATE;								//sets baud rate
	UCSRB|=(1<<TXEN)|(1<<RXEN);					//enables receiver and transmitter
	UCSRC|=(1<<URSEL)|(1<<UCSZ0)|(1<<UCSZ1);	// 8bit data format
}

//Transmits data to USART
void usart_tx (unsigned char data){
	while (!( UCSRA & (1<<UDRE)));            // bit mask to check if UDRE bit is set (set = while (0), not set = while (1))
	UDR = data;                             // loads data to UDR register
}

//Initialized ADC
void adc_init(void){
	DDRE |= CS_ADC;  // Set pin 0 as an output
	DDRE |= RD_ADC;  // Set pin 1 as an output
	DDRE |= WR_ADC;  // Set pin 2 as an output
	DDRA = 0x0;		// Set PORTA as input
	
	PORTE |= CS_ADC;  // Output a high signal on pin 0
	PORTE |= RD_ADC;  // Output a high signal on pin 1
	PORTE |= WR_ADC;  // Output a high signal on pin 2
}

//Reads value from ADC
void adc_write(void){
	//char adc_out;
	
	//Writing Cycle
	PORTE &= ~CS_ADC;  // Output a low signal on pin 0
	PORTE &= ~WR_ADC;  // Output a low signal on pin 2
	PORTE |= WR_ADC;  // Output a high signal on pin 2
	PORTE |= CS_ADC;  // Output a high signal on pin 0
	
	//_delay_ms(1);
	
	/*//Reading Cycle
	PORTE &= ~CS_ADC;  // Output a low signal on pin 0
	PORTE &= ~RD_ADC;  // Output a low signal on pin 1
	adc_out = PINA;
	PORTE |= RD_ADC;  // Output a high signal on pin 1
	PORTE |= CS_ADC;  // Output a high signal on pin 0*/
	
	//return adc_out;
}

void INT0_init(void){
	
	// Sets up INT0 to trigger on falling edge
	MCUCR |= (1 << ISC01);
	MCUCR &= ~(1 << ISC00);
	
	GICR |= (1 << INT0);	// Enable INT0 interrupt
	sei();					// Enable global interrupts
}

ISR(INT0_vect)
{
	//Reading Cycle
	PORTE &= ~CS_ADC;  // Output a low signal on pin 0
	PORTE &= ~RD_ADC;  // Output a low signal on pin 1
	adc_data = PINA;
	PORTE |= RD_ADC;  // Output a high signal on pin 1
	PORTE |= CS_ADC;  // Output a high signal on pin 0

	//adc_write();
	// Set the interrupt flag to indicate that an interrupt has occurred
	//int_flag = 1;
	
	//Writing Cycle
	PORTE &= ~CS_ADC;  // Output a low signal on pin 0
	PORTE &= ~WR_ADC;  // Output a low signal on pin 2
	PORTE |= WR_ADC;  // Output a high signal on pin 2
	PORTE |= CS_ADC;  // Output a high signal on pin 0
}