#include <xc.h>
#include <stdio.h>
#include <stdlib.h>
#include <avr/interrupt.h>
#define F_CPU 8000000L
#include <util/delay.h>

//Define ADC control pins
#define BAUDRATE 8
#define CS_ADC_PRESSURE PINC7
#define RD_ADC PINC6
#define WR_ADC PINC5
#define CS_ADC_TEMPERATURE PINC4

//Function prototypes
void usart_init(void);
void usart_tx(unsigned char);
void adc_init(void);

void adc_write_pressure (void);
void INT0_init(void);

void adc_write_temperature (void);
void INT0_init(void);


void INT_init(void);
char adc_to_temperature(char);

//Global variable
char adc_data_pressure;
char adc_data_temperature;

int main (void)
{
	//Initializes USAR, ADC, and interrupt
	usart_init();
	adc_init();
	INT_init();
	
	char temperature;
	
	//Continuously read and transmit ADC data
	while(1){
		/*adc_write_pressure();		//Trigger ADC conversion
		_delay_ms(100);
		usart_tx(adc_data_pressure);
		_delay_ms(100);*/
		
		adc_write_temperature();
		_delay_ms(100);
		temperature = adc_to_temperature(adc_data_temperature);
		usart_tx(temperature);			//Can also be used inside the interrupt function
		//usart_tx(adc_data_temperature);
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
	PORTC &= ~(1 << RD_ADC);
	adc_data_pressure = PINA;
	PORTC |= (1 << RD_ADC);
	PORTC |= (1 << CS_ADC_PRESSURE);
	
	//usart_tx(adc_data_pressure);
}

//-----------------------------------------
//-----------------------------------------

void adc_write_temperature(void){
	//Writing Cycle
	PORTC &= ~(1 << CS_ADC_TEMPERATURE);
	PORTC &= ~(1 << WR_ADC);
	PORTC |= (1 <<  WR_ADC);
	PORTC |= (1 << CS_ADC_TEMPERATURE);
}

void INT1_init(void){
	// Sets up INT0 to trigger on falling edge
	MCUCR |= (1 << ISC11);
	MCUCR &= ~(1 << ISC10);
	
	GICR |= (1 << INT1);	// Enable INT0 interrupt
	sei();					// Enable global interrupts
}

ISR(INT1_vect) {
	//Reading Cycle
	PORTC &= ~(1 << CS_ADC_TEMPERATURE);
	PORTC &= ~(1 << RD_ADC);
	adc_data_temperature = PINA;
	PORTC |= (1 << RD_ADC);
	PORTC |= (1 << CS_ADC_TEMPERATURE);
	
	//usart_tx(adc_data_temperature);
}

void INT_init(void){
	// Sets up INT0 and INT1 to trigger on falling edge
	MCUCR |= (1 << ISC01) | (1 << ISC11);
	MCUCR &= ~(1 << ISC00) & ~(1 << ISC10);
	
	GICR |= (1 << INT0) | (1 << INT1);	// Enable INT0 and INT1 interrupts
	sei();								// Enable global interrupts
}

//-----------------------------------------
//-----------------------------------------

char adc_to_temperature(char adc_temperature){
	int temperature;
	temperature = ((int)adc_temperature * 100) / 255;
	return (char)temperature;
	
}
