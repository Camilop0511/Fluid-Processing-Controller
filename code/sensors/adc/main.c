#include <xc.h>
#include <stdio.h>
#include <stdlib.h>
#include <avr/interrupt.h>
#define F_CPU 8000000L
#include <util/delay.h>

//Define ADC control pins
#define BAUDRATE 8
#define CS_ADC_PRESSURE PINC7
#define RD_ADC_PRESSURE PINC6
#define WR_ADC_PRESSURE PINC5

//Function prototypes
void usart_init(void);
void usart_tx(unsigned char);
void adc_init_pressure(void);
void adc_write_pressure (void);
void INT0_init(void);

//Global variable
char adc_data_pressure; 

int main (void)
{
	//Initializes USAR, ADC, and interrupt
	usart_init();
	adc_init_pressure();
	INT0_init();
	
	adc_write_pressure();		//Trigger ADC conversion
	
	//Continuously read and transmit ADC data
	while(1){	
		usart_tx(adc_data_pressure);
		_delay_ms(100);
	}

	return 0;
}

//--------------------------------------------------------

//Initializes USART
void usart_init (void){
	UBRRL=BAUDRATE;								
	UCSRB|=(1<<TXEN)|(1<<RXEN);					
	UCSRC|=(1<<URSEL)|(1<<UCSZ0)|(1<<UCSZ1);	
}

//Transmits data to USART
void usart_tx (unsigned char data){
	while (!( UCSRA & (1<<UDRE)));            
	UDR = data;                             
}

//Initializes ADC control pins and set input/output pins 
void adc_init_pressure(void){
	DDRC |= (1 << CS_ADC_PRESSURE);  
	DDRC |= (1 << RD_ADC_PRESSURE);  
	DDRC |= (1 << WR_ADC_PRESSURE); 
	DDRA = 0x0;		
	
	PORTC |= (1 << CS_ADC_PRESSURE);  
	PORTC |= (1 << RD_ADC_PRESSURE);  
	PORTC |= (1 <<  WR_ADC_PRESSURE);  
}

//Triggers ADC conversion
void adc_write_pressure(void){	
	//Writing Cycle
	PORTC &= ~(1 << CS_ADC_PRESSURE);  
	PORTC &= ~(1 << WR_ADC_PRESSURE);  
	PORTC |= (1 <<  WR_ADC_PRESSURE);  
	PORTC |= (1 << CS_ADC_PRESSURE); 
}

//Initializes external interrupt 0 for reading ADC data
void INT0_init(void){
	// Sets up INT0 to trigger on falling edge
	MCUCR |= (1 << ISC01);
	MCUCR &= ~(1 << ISC00);
	
	GICR |= (1 << INT0);	// Enable INT0 interrupt
	sei();					// Enable global interrupts
}

//Interrupt service routine for reading ADC data
ISR(INT0_vect)
{
	//Reading Cycle
	PORTC &= ~(1 << CS_ADC_PRESSURE);  
	PORTC &= ~(1 << RD_ADC_PRESSURE);  
	adc_data_pressure = PINA;
	PORTC |= (1 << RD_ADC_PRESSURE);  
	PORTC |= (1 << CS_ADC_PRESSURE);  
	
	//Writing Cycle
	PORTC &= ~(1 << CS_ADC_PRESSURE);  
	PORTC &= ~(1 << WR_ADC_PRESSURE);  
	PORTC |= (1 <<  WR_ADC_PRESSURE);  
	PORTC |= (1 << CS_ADC_PRESSURE);  
}